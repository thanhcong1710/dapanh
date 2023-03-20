<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Providers\UtilityServiceProvider as u;

class RoomsController extends Controller
{
    public function create(Request $request)
    {
        $code = u::generateRandomString(6);
        $room_id =  u::insertSimpleRow([
            'code' => $code,
            'game_id' => $request->game_id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->user()->id,
            'status' => 1
        ],'data_rooms');
        return response()->json( ['room_id'=>$room_id,'room_code'=>$code, 'status'=>1, 'type_game'=>$request->game_id]);
    }
    public function detail(Request $request, $room_code){
        $room_info = u::first("SELECT *, id AS room_id,  
                (SELECT num_img FROM users WHERE id= ".auth()->user()->id.") AS user_num_img
            FROM data_rooms WHERE code = '$room_code'");
        return response()->json( $room_info);
    }
    public function approveRound(Request $request){
        $user_id = auth()->user()->id;
        $room_info = u::first("SELECT id, curr_round FROM data_rooms WHERE id = $request->room_id ");
        $curr_round = $room_info && $room_info->curr_round ? $room_info->curr_round : 0;
        $round_info = u::first("SELECT id, num_img FROM data_rounds WHERE id = $curr_round AND status=0");
        if(!$round_info){
            $round_id= u::insertSimpleRow([
                'room_id' => $request->room_id,
                'num_img' => $request->num_img,
                'created_at' => date('Y-m-d H:i:s'),
                'status'=>0
            ],'data_rounds');
            u::updateSimpleRow(['curr_round' => $round_id],['id'=>$request->room_id], 'data_rooms');
        }else{
            $round_id = $round_info->id; 
            if($round_info->num_img != $request->num_img){
                u::updateSimpleRow([
                    'num_img' => $request->num_img
                ],['id'=>$round_id], 'data_rounds');
            }
        }
        $map_user_info = u::first("SELECT id FROM data_user_round WHERE round_id = $round_id AND user_id= $user_id");
        if($map_user_info){
            u::updateSimpleRow([
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => $request->user_ready
            ],['id'=>$map_user_info->id],'data_user_round');
        }else{
            u::insertSimpleRow([
                'user_id' => $user_id,
                'room_id' => $request->room_id,
                'num_img' => $request->num_img,
                'round_id' => $round_id,
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => $request->user_ready
            ],'data_user_round');
        }
        $data_push = array(
            'room_id' => $request->room_id,
            'action' => 'approveRound',
            'user_id' => $user_id,
            'num_img' => $request->num_img,
            'user_ready'  => $request->user_ready,
        );
        SocketController::pushData($user_id,'dataRoom', $data_push);
        return response()->json( ['status'=>1]);
    }
    public function changeConfigNum(Request $request){
        $room_info = u::first("SELECT id, curr_round, created_by FROM data_rooms WHERE id = $request->room_id ");
        $curr_round = $room_info && $room_info->curr_round ? $room_info->curr_round : 0;
        $round_info = u::first("SELECT id, num_img FROM data_rounds WHERE id = $curr_round AND status=0");
        if($round_info){
            u::query("UPDATE data_user_round SET `status`=0 WHERE round_id= $round_info->id AND user_id!= $room_info->created_by");
        }
        $data_push = array(
            'room_id' => $request->room_id,
            'action' => 'changeConfigNum',
            'num_img' => $request->num_img
        );
        SocketController::pushData(auth()->user()->id,'dataRoom', $data_push);
        return response()->json( ['status'=>1]);
    }

    public function endRound(Request $request){
        $room_info = u::first("SELECT id, curr_round FROM data_rooms WHERE id = $request->room_id ");
        $list_user = u::query("SELECT user_id FROM data_user_round WHERE round_id = $room_info->curr_round AND status=1");
        $arr_data = array();
        foreach($list_user AS $user){
            if($request->is_only){
                $arr_data[] = [
                    'user_id'=>$user->user_id,
                    'response_game' => u::getResponseGameIsOnly(1)
                ];
            }else{
                $arr_data[] = [
                    'user_id'=>$user->user_id,
                    'response_game' => u::getResponseGame()
                ];
            }
        }
    
        if($request->is_only){
            array_push($arr_data,[
                'user_id' => 999999,
                'response_game' => u::getResponseGameIsOnly(0)
            ]);
        }
        $arr_data_res = u::processResult($arr_data, $request->num_img);
        foreach($arr_data_res AS  $row){
            if($row['user_id'] != 999999){
                if($row['response_game']==1 && $row['img_add']>0){
                    u::addImgRand($row['img_add'],$row['user_id']);
                }elseif($row['response_game']==0 && $row['img_minus']>0){
                    u::minusImg($row['img_minus'], $row['user_id']);
                }
            }
        }
        $data_push = array(
            'room_id' => $request->room_id,
            'action' => 'endGame',
            'list_user' => $arr_data_res
        );
        SocketController::pushData(auth()->user()->id,'dataRoom',$data_push);
        u::updateSimpleRow(['status'=>1],['id'=>$room_info->curr_round],'data_rounds');
        return response()->json( ['status'=>1]);
    }

    //Game búa bao kéo
    public function approveRound2(Request $request){
        $user_id = auth()->user()->id;
        $room_info = u::first("SELECT id, curr_round FROM data_rooms WHERE id = $request->room_id ");
        $round_info = u::first("SELECT id, is_only, num_img FROM data_rounds WHERE id = $room_info->curr_round AND status=0");
        if(!$round_info){
            $round_id= u::insertSimpleRow([
                'room_id' => $request->room_id,
                'num_img' => $request->num_img,
                'created_at' => date('Y-m-d H:i:s'),
                'is_only' => $request->is_only,
                'status' => 0,
                'select_option'=>1 //mặc định là chọn búa
            ],'data_rounds');
            u::updateSimpleRow(['curr_round' => $round_id],['id'=>$request->room_id], 'data_rooms');
        }else{
            $round_id = $round_info->id; 
            if($round_info->is_only != $request->is_only || $round_info->num_img != $request->num_img){
                u::updateSimpleRow(['is_only' => $request->is_only,'num_img' => $request->num_img],['id'=>$round_id], 'data_rounds');
            }
        }
        $map_user_info = u::first("SELECT id FROM data_user_round WHERE round_id = $round_id AND user_id= $user_id");
        if($map_user_info){
            u::updateSimpleRow([
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => 1
            ],['id'=>$map_user_info->id],'data_user_round');
        }else{
            u::insertSimpleRow([
                'user_id' => $user_id,
                'room_id' => $request->room_id,
                'num_img' => $request->num_img,
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => 1
            ],'data_user_round');
        }
        return response()->json( ['status'=>1]);
    }

    public function selectOption2(Request $request){
        $room_info = u::first("SELECT id, curr_round FROM data_rooms WHERE id = $request->room_id ");
        $round_info = u::first("SELECT id, is_only, num_img FROM data_rounds WHERE id = $room_info->curr_round AND status=0");
        if($round_info){
            u::updateSimpleRow(['select_option' => $request->select_option],['id'=>$request->room_id], 'data_rooms');
        }
        return response()->json( ['status'=>1]);
    }

    public function endRound2(Request $request){
        $room_info = u::first("SELECT id, curr_round FROM data_rooms WHERE id = $request->room_id ");
        $list_user = u::query("SELECT user_id,select_option FROM data_user_round WHERE round_id = $room_info->curr_round AND status=1");
        if($request->is_only){
            $arr_data = array(
                '0' => [
                    'user_id' => $list_user[0]->user_id,
                    'select_option'=> $list_user[0]->select_option,
                ],
                '1' => [
                    'user_id' => 0,
                    'select_option'=> u::genSelectOptionGame2($list_user[0]->select_option),
                ]
            );
        }else{
            $arr_data = array();
            foreach($list_user AS $user){
                $arr_data[] = [
                    'user_id'=>$user->user_id,
                    'select_option'=> $list_user[0]->select_option,
                ];
            }
        }
        $arr_data_res = u::processResultGame2($arr_data, $request->num_img);
        foreach($arr_data_res AS  $row){
            if($row['user_id'] > 0){
                if($row['response_game']==1 && $row['img_add']>0){
                    u::addImgRand($row['img_add'],$row['user_id']);
                }elseif($row['response_game']==0 && $row['img_minus']>0){
                    u::minusImg($row['img_minus'], $row['user_id']);
                }
            }
        }
        SocketController::pushData($row['user_id'],'endGame',$arr_data_res);
        u::updateSimpleRow(['status'=>1],['id'=>$room_info->curr_round],'data_rounds');
        return response()->json( ['status'=>1]);
    }
}
