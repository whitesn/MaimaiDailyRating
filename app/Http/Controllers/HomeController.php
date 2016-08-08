<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Http\Requests;
use App\Player;
use App\Arcade;

class HomeController extends Controller
{
    public function dashboard()
    {
        $rating_13_counts = array();
        $top_100 = Player::orderBy('rating', 'desc')->take( 100 )->get();

        $arcades = array();
        $arcades_data = Arcade::all();
        foreach( $arcades_data as $arcade )
        {
            $arcades[$arcade->id] = $arcade->name;
            $rating_13_counts[$arcade->id] = 0;
        }

        foreach( $top_100 as $player )
        {
            if( $player->rating >= 13 )
            {
                $rating_13_counts[$player->arcade_id]++;
            }
        }

        $data['players'] = $top_100;
        $data['arcades'] = $arcades;
        $data['rating_13_data'] = $rating_13_counts;
        $data['last_update_date'] = DB::table('meta')->where('name', '=', 'LAST_UPDATE_TIME')->first()->value;

        return view( 'top100', $data );
    }
}
