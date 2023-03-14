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
    public function getImgByUser(Request $request, $user_id){
        $list_img = u::query("SELECT i.* FROM data_user_image AS u LEFT JOIN  data_images AS i ON i.id=u.img_id WHERE u.status=1 AND i.status=1 AND u.user_id=$user_id");
        return  response()->json( $list_img);
    }
    public function changeImageShow(Request $request){
        u::query("UPDATE  users AS u SET img_show = (SELECT img_key FROM data_images WHERE id=$request->img_id) WHERE u.id=$request->user_id");
        return  response()->json( ['status'=>1]);
    }
}
