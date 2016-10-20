<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;

use App\Http\Requests;
use App\Arcade;
use App\Grabber;
use App\Player;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJarInterface;

class RatingGrabber extends Controller
{
    private $jar;
    private $client;
    private $active_sid;

    private function login( $grabber )
    {
        $res = $this->client->get( 'https://maimai-net.com/maimai-mobile/login.html', [
            'cookies' => $this->jar,
            'allow_redirects' => true
        ]);

        $res = $this->client->post( 'https://maimai-net.com/maimai-mobile/login.html', [
            'form_params' => [
                'mode' => '1',
                'segaid' => $grabber->username,
                'passwd' => $grabber->password
            ],
            'cookies' => $this->jar,
            'allow_redirects' => true
        ]);

        $body = $res->getBody();
        $pos = strpos( $body, "sid=" );

        if( $pos === FALSE )
        {
            echo( "[ERROR]: Unable to find SID after login attempt, check if maimai-net is updated.\n");
            return false;
        }

        $this->active_sid = substr( $body, $pos + 4, 8 );

        if( strpos( $body, 'AIME_SELECT' ) !== FALSE )
        {
            echo "[DEBUG]: SegaID has multiple Aime accounts, choosing the first one...\n";

            // Handling account choosing
            $res = $this->client->post( 'https://maimai-net.com/maimai-mobile/aime.html?sid=' . $this->active_sid, [
                'form_params' => [
                    'AIME_SELECT' => '0'
                ],
                'cookies' => $this->jar,
                'allow_redirects' => true
            ]);
        }

        return true;
    }

    private function get_home_arcade( $grabber )
    {
        $res = $this->client->get( 'https://maimai-net.com/maimai-mobile/ranking.html?kind=20&sub=2&sid=' . $this->active_sid, [
            'cookies' => $this->jar,
            'allow_redirects' => true
        ]);

        $body = $res->getBody();
        $pos = strpos( $body, "<div class=\"data_midashi\">" ) + strlen( "<div class=\"data_midashi\">" );
        $pos2 = strpos( $body, "</div>", $pos );

        $store = substr( $body, $pos, $pos2 - $pos );
        return trim( $store );
    }

    private function clear_player_database()
    {
        Player::truncate();
    }

    private function add_player_database( $arcade_id )
    {
        $rating_data = array();

        $res = $this->client->get( 'https://maimai-net.com/maimai-mobile/ranking.html?kind=20&sub=2&sid=' . $this->active_sid, [
            'cookies' => $this->jar,
            'allow_redirects' => true
        ]);

        $body = $res->getBody();
        $pos1 = strpos( $body, "<table width=\"100%\" border=\"0\" class=\"ranking_2\">" );
        $pos2 = strpos( $body, "</table>", $pos1 );
        $table_data = substr( $body, $pos1, $pos2 - $pos1 );

        if( $pos1 === FALSE )
        {
            echo "[DEBUG]: Failed to get the proper page of rating, skipping...";
            return;
        }

        $records = explode( "<tr>", $table_data );
        array_shift( $records ); // Delete the first record, which is the table head

        foreach( $records as $record )
        {
            /* Get the second <td> for player name, first is the position number */
            $pos = strpos( $record, "</td>" );

            $str_delimiter = "<td>";
            $pos = strpos( $record, $str_delimiter, $pos );
            $start_pos = $pos + strlen($str_delimiter);
            $end_pos = strpos( $record, "</td>", $start_pos );

            $player_name = substr( $record, $start_pos, $end_pos - $start_pos );

            /* Get the rating */
            $str_delimiter = "<td class=\"score\" style=\"text-align:right; padding-right:50px\">";
            $pos = strpos( $record, $str_delimiter );
            $start_pos = $pos + strlen($str_delimiter);
            $end_pos = strpos( $record, "&nbsp;", $start_pos );

            $player_rating = substr( $record, $start_pos, $end_pos - $start_pos );
            $player_rating = preg_replace('/\s+/', '', $player_rating);

            $data = [
                'name' => $player_name,
                'rating' => $player_rating,
                'arcade_id' => $arcade_id
            ];

            array_push( $rating_data, $data );
        }

        DB::table('player')->insert( $rating_data );
    }

    public function main()
    {
        date_default_timezone_set('Asia/Jakarta');

        self::clear_player_database();

        $this->jar = new \GuzzleHttp\Cookie\CookieJar();
        $this->client = new \GuzzleHttp\Client([
            'verify' => false,
            'allow_redirects' => true,
            'debug' => false
        ]);

        $arcades = Arcade::all();
        $grabbers = Grabber::all();

        $grab_status = array();
        echo "[DEBUG]: == Arcade List == \n";
        foreach( $arcades as $arcade )
        {
            $grab_status[$arcade->id] = false;
            echo $arcade->name . "\n";
        }
        echo "=========================== \n";

        foreach( $grabbers as $grabber )
        {
            echo "[DEBUG]: Logging in with account : " . $grabber->username . "\n";

            if( self::login( $grabber ) )
            {
                $home_arcade = self::get_home_arcade( $grabber );
                echo "[DEBUG]: Checking arcade: [" . $home_arcade . "]...\n";
                $arcade = Arcade::where('name', '=', $home_arcade);

                if( $arcade->count() <= 0 )
                {
                    echo "[DEBUG]: This arcade is not registered in database, skipping...\n";
                    continue;
                }

                $arcade_id = $arcade->first()->id;

                if( $grab_status[$arcade_id] )
                {
                    echo "[DEBUG]: This arcade data is already grabbed, skipping...\n";
                }
                else
                {
                    echo "[DEBUG]: Adding player database from arcade: " . $home_arcade . "\n";
                    self::add_player_database( $arcade_id );
                    $grab_status[$arcade_id] = true;
                    $now = new DateTime('now');
                    DB::table('meta')->where('name', '=', 'LAST_UPDATE_TIME')
                                     ->update(['value' => $now->format( 'd-m-Y H:i:s')]);
                }
            }
            else
            {
                echo "[DEBUG]: Failed to login with account: " . $grabber->username . ", skipping...\n";
            }
        }
    }
}
