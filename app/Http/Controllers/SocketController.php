<?php

namespace App\Http\Controllers;

use App\Models\SocketIO;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Providers\UtilityServiceProvider as u;

class SocketController extends Controller
{
    public static function pushData($user_id,$event,$data){
        $baseUri = "127.0.0.1";
        $port = 3000;
        $socketio = new SocketIO();
        if ($socketio->send($baseUri, $port , $event, json_encode($data))){
            u::insertSimpleRow( array(
                'user_id'=>$user_id,
                'event'=>$event,
                'created_at'=>date('Y-m-d H:i:s'),
                'data'=>json_encode($data)
            ),'log_socket');
            // echo 'we sent the message and disconnected: '.json_encode($data);
        } else {
            // echo 'Sorry, we have a mistake :\'(';
        }
    }
}
