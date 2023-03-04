<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Providers\UtilityServiceProvider as u;

class GamesController extends Controller
{
    public function listGame(){
        $list_game = u::query("SELECT * FROM data_games WHERE status=1");
        return  response()->json( $list_game);
    }
}
