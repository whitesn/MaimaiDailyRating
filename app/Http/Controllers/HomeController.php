<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Http\Requests;
use App\Player;
use App\Arcade;
use Config;

class HomeController extends Controller
{
    public function dashboard()
    {
        $top_raters_count = [];
        $top_players = Player::orderBy('rating', 'desc')->take( 300 )->get();
		$players = [];
		
		$rank = 1;
		$rank_hold = 0;
		$last_rating = -1;
		
		foreach( $top_players as $top_player )
		{
			if( $last_rating == -1)
			{
				$last_rating = $top_player->rating;
				array_push( $players, ['rank' => $rank, 'player' => $top_player] );
			}
			else
			{
				if ( $last_rating != $top_player->rating )
				{
					$rank += $rank_hold + 1;
					$last_rating = $top_player->rating;
					$rank_hold = 0;
				}
				else
				{
					$rank_hold++;
				}
				
				array_push( $players, ['rank' => $rank, 'player' => $top_player] );
			}
		}
		
        $arcades = array();
        $arcades_data = Arcade::all();
        foreach( $arcades_data as $arcade )
        {
            $arcades[$arcade->id] = $arcade->name;
            $top_raters_count[$arcade->id] = 0;
        }

        foreach( $top_players as $player )
        {
            if( $player->rating >= Config::get('mdr.top_raters_threshold') )
            {
                $top_raters_count[$player->arcade_id]++;
            }
        }

        $data['players'] = $players;
        $data['arcades'] = $arcades;
        $data['top_raters_count'] = $top_raters_count;
        $data['last_update_date'] = DB::table('meta')->where('name', '=', 'LAST_UPDATE_TIME')->first()->value;

        return view( 'home', $data );
    }
}
