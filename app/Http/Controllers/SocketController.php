<?php

namespace App\Http\Controllers;

use App\Models\SocketIO;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Providers\UtilityServiceProvider as u;

class SocketController extends Controller
{
    public function __construct()
    {
        $this->baseUri = "127.0.0.1";
        $this->port = 3000;
    }
    public static function pushData($user_id,$event,$data){
        $arr=[
            'user_id'=>$user_id,
            'event'=>$event,
            'data'=>$data
        ];
        
        $socketio = new SocketIO();
        if ($socketio->send($this->baseUri, $this->port , 'pushData', json_encode($arr))){
            u::insertSimpleRow( array(
                'user_id'=>$user_id,
                'event'=>$event,
                'created_at'=>date('Y-m-d H:i:s'),
                'data'=>json_encode($data)
            ),'log_socket');
            echo 'we sent the message and disconnected: '.json_encode($data);
        } else {
            echo 'Sorry, we have a mistake :\'(';
        }
    }
}
