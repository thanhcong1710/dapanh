<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtilityServiceProvider extends ServiceProvider
{
    public static function query($query, $print = false)
    {
        $resp = null;
        $query = trim($query);
        $upperQuery = strtoupper(substr($query, 0, 6));
        if ($print) {
            dd('\n-------------------------------------------------------------\n', $query, '\n-------------------------------------------------------------\n');
        } else {
            if ($upperQuery == ('SELECT')) {
                $resp = DB::select(DB::raw($query));
            } elseif ($upperQuery == ('INSERT')) {
                $resp = DB::insert(DB::raw($query));
            } elseif ($upperQuery == ('UPDATE')) {
                $resp = DB::update(DB::raw($query));
            } elseif ($upperQuery == ('DELETE')) {
                $resp = DB::delete(DB::raw($query));
            } else {
                $resp = DB::statement(DB::raw($query));
            }
        }
        return $resp;
    }
	public static function first($query, $print = false)
	{
		$resp = self::query($query, $print);
		return $resp && is_array($resp) && count($resp) >= 1 ? $resp[0] : $resp;
	}
    public static function getOne($query){
	    $finalQuery = $query. " LIMIT 1";
        $resp = DB::select(DB::raw($finalQuery));
        return $resp && is_array($resp) && count($resp) >= 1 ? $resp[0] : $resp;
    }
    public static function getObject($array_input, $table, $order_by_key='', $order_by_desc=false) {
		$sub_sql = '1 ';
		$sub_order = '';
		foreach ( $array_input as $key => $value ) {
			$sub_sql .= " AND " . $key . "= :" . $key;
		}
		if($order_by_key!=''){
			if($order_by_desc){
				$sub_order = " ORDER BY $order_by_key DESC";
			}else{
				$sub_order = " ORDER BY $order_by_key ASC";
			}
		}
		$query = "SELECT * FROM " . $table . " WHERE " . $sub_sql . $sub_order . " LIMIT 1";
		$resp = DB::select(DB::raw($query),$array_input);
		return $resp && is_array($resp) && count($resp) == 1 ? $resp[0] : $resp;
    }

	public static function getMultiObject($array_input, $table, $limit=0, $order_by_key='', $order_by_desc=false) {
		$sub_sql = '1 ';
		$sub_order = '';
		$sub_limit = '';
		foreach ( $array_input as $key => $value ) {
			$sub_sql .= " AND " . $key . "= :" . $key;
		}
		if($order_by_key!=''){
			if($order_by_desc){
				$sub_order = " ORDER BY $order_by_key DESC";
			}else{
				$sub_order = " ORDER BY $order_by_key ASC";
			}
		}
		if($limit){
			$sub_limit = " LIMIT $limit";
		}
		$query = "SELECT * FROM " . $table . " WHERE " . $sub_sql . $sub_order . $sub_limit;
		$resp = DB::select(DB::raw($query),$array_input);
		return $resp ;
    }

	public static function insertSimpleRow($arr_params, $table) {
		$field = "";
		$field_value = "";
		foreach ( $arr_params as $key => $value ) {
			$field .= $key . ",";
			$field_value .= ":" . $key . ",";
		}
		$field = rtrim ( $field, "," );
		$field_value = rtrim ( $field_value, "," );
		$sql = "INSERT IGNORE INTO " . $table . "(" . $field . ") VALUES (" . $field_value . ")";
		$resp = DB::insert(DB::raw($sql),$arr_params);
		return $resp ? DB::getPdo()->lastInsertId() : $resp;
    }

	public static function updateSimpleRow($arr_params, $arr_key, $table) {
		$set_clause = "";
		$arr_binding = array();
		foreach ( $arr_params as $key => $value ) {
			$set_clause .= $key . "= :value_" . $key . ",";
			$arr_binding['value_'.$key] = $value;
		}
		$set_clause = rtrim ( $set_clause, "," );

		$sql_cond = '1=1';
		foreach ( $arr_key as $key => $value ) {
			$sql_cond .= ' AND ' . $key . "= :key_" . $key;
			$arr_binding['key_'.$key] = $value;
		}
		if ($set_clause != '') {
			$sql = 'UPDATE ' . $table . ' SET ' . $set_clause . ' WHERE ' . $sql_cond;
			$resp = DB::update(DB::raw($sql),$arr_binding);
			return $resp;
		}
	}

	public static function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public static function getResponseGameIsOnly($is_user){
		if($is_user){
			$arr_status = [
				'0'=>0,
				'1'=>0,
				'2'=>0,
				'3'=>0,
				'4'=>0,
				'5'=>0,
				'6'=>1,
				'7'=>1,
				'8'=>1,
				'9'=>1,
			];
		}else {
			$arr_status = [
				'0'=>0,
				'1'=>0,
				'2'=>0,
				'3'=>0,
				'4'=>1,
				'5'=>1,
				'6'=>1,
				'7'=>1,
				'8'=>1,
				'9'=>1,
			];
		}
		return $arr_status[rand(0,9)];
	}
	public static function getResponseGame(){
		$arr_status = [
			'0'=>0,
			'1'=>1
		];
		return $arr_status[rand(0,1)];
	}
	public static function processResult($arr_data, $num_img){
		$i=0;
		foreach($arr_data AS $row){
			if($row['response_game']==1){
				$i++;
			}
		}
		if(count($arr_data) == $i || $i==0){
			$total_charge = 0;
		}else{
			$total_charge = $i*$num_img;
		}
		$tb_charge = (count($arr_data)-$i) ? ceil($total_charge/(count($arr_data)-$i)):0;
		foreach($arr_data AS $k=>$row){
			if($row['response_game']==1){
				$arr_data[$k]['img_add'] = $total_charge > 0 ? $num_img : 0;
				$arr_data[$k]['img_minus'] = 0; 	
			}else{
				$arr_data[$k]['img_add'] = 0;
				$arr_data[$k]['img_minus'] = $tb_charge;
			}
		}
		return $arr_data;
	}
	public static function addImgRand($num_img, $user_id, $num_type =5){
		$list_img = self::query("SELECT id FROM data_images WHERE status=1");
		$arr_img = array();
		$num_type = $num_type > $num_img ? $num_img : $num_type;
		$randIndex =  array_rand($list_img,$num_type);
		if($num_type==1){
			$randIndex=array(
				'0'=>$randIndex
			);
		}
		$list_img_id = '';
		for($i=0;$i<$num_type;$i++){
			if($i==$num_type-1){
				$arr_img[$list_img[$randIndex[$i]]->id]= [
					'img_id'=>$list_img[$randIndex[$i]]->id,
					'img_num'=>$num_img - (ceil($num_img/$num_type) * ($num_type-1)),
					'user_id' => $user_id
				];
			}else{
				$arr_img[$list_img[$randIndex[$i]]->id]= [
					'img_id'=>$list_img[$randIndex[$i]]->id,
					'img_num'=>ceil($num_img/$num_type),
					'user_id' => $user_id
				];
			}
			$list_img_id.= $list_img_id? ','.$list_img[$randIndex[$i]]->id : $list_img[$randIndex[$i]]->id;
		}
		$user_img = self::query("SELECT id,user_id,img_id,num FROM data_user_image WHERE user_id=$user_id AND img_id IN($list_img_id) AND status=1");
		$arr_update =array();
		$sql_udpate = "INSERT INTO data_user_image (id, num) VALUES "; 
		$sql_insert = "INSERT INTO data_user_image (user_id, img_id, num, `status`) VALUES "; 
		$check_insert = false;
		foreach($user_img AS $us){
			$tmp_num = $us->num+$arr_img[$us->img_id]['img_num'];
			$sql_udpate.="($us->id,$tmp_num),";
			array_push($arr_update,$us->img_id);
		}
		if(count($user_img)>0){
			$sql_udpate = substr($sql_udpate, 0, -1);
			$sql_udpate = $sql_udpate." ON DUPLICATE KEY UPDATE num = VALUES(num)";
			self::query($sql_udpate);
		}
		foreach($arr_img AS $ai){
			if(!in_array($ai['img_id'],$arr_update)){
				$sql_insert .= "($user_id,'".$ai['img_id']."','".$ai['img_num']."', 1),";
				$check_insert = true;
			}
		}
		if($check_insert){
			$sql_insert = substr($sql_insert, 0, -1);
			self::query($sql_insert);
		}
		
		self::query("UPDATE users SET num_img = (SELECT SUM(num) FROM data_user_image WHERE user_id=$user_id AND status=1) WHERE id=$user_id");
		return $arr_img;
	}
	public static  function minusImg($num_img, $user_id){
		$list_img = self::query("SELECT * FROM data_user_image WHERE status=1 ORDER BY num DESC");
		$i=0;
		while($num_img>0 && $i < count($list_img)){
			if($list_img[$i]->num < $num_img){
				self::query("DELETE FROM data_user_image WHERE id = ".$list_img[$i]->id);
				$num_img = $num_img - $list_img[$i]->num;
			}else{
				$tmp = $list_img[$i]->num - $num_img;
				self::query("UPDATE data_user_image SET num = $tmp  WHERE id=".$list_img[$i]->id);
				$num_img =0;
			}
			$i++;
		}
		self::query("UPDATE users SET num_img = (SELECT SUM(num) FROM data_user_image WHERE user_id=$user_id AND status=1) WHERE id=$user_id");
		return true;
	}
	public static  function genSelectOptionGame2($user_option){
		$arr_status = [
			'0'=>1,//búa
			'1'=>1,
			'2'=>1,
			'3'=>2,//bao
			'4'=>2,
			'5'=>2,
			'6'=>3,//kéo
			'7'=>3,
			'8'=>3,
		];
		if($user_option == 1){
			unset($arr_status[6]);
		}elseif($user_option == 2){
			unset($arr_status[1]);
		}else{
			unset($arr_status[3]);
		}
		$randIndex =  array_rand($arr_status);
		return $arr_status[$randIndex];
	}
	public static function processResultGame2($arr_data, $num_img){
		if($arr_data[0]['select_option']==1){
			if($arr_data[1]['select_option']==1){
				$arr_data[0]['response_game']=2;//Hòa
				$arr_data[1]['response_game']=2; 
			}elseif($arr_data[1]['select_option']==2){
				$arr_data[0]['response_game']=0;//Thua
				$arr_data[1]['response_game']=1; 
			}else{
				$arr_data[0]['response_game']=1;//Thắng
				$arr_data[1]['response_game']=0; 
			}
		}elseif($arr_data[0]['select_option']==2){
			if($arr_data[1]['select_option']==2){
				$arr_data[0]['response_game']=2;
				$arr_data[1]['response_game']=2; 
			}elseif($arr_data[1]['select_option']==1){
				$arr_data[0]['response_game']=0;
				$arr_data[1]['response_game']=1; 
			}else{
				$arr_data[0]['response_game']=1;
				$arr_data[1]['response_game']=0; 
			}
		}else{
			if($arr_data[1]['select_option']==3){
				$arr_data[0]['response_game']=2;
				$arr_data[1]['response_game']=2; 
			}elseif($arr_data[1]['select_option']==1){
				$arr_data[0]['response_game']=0;
				$arr_data[1]['response_game']=1; //Thắng
			}else{
				$arr_data[0]['response_game']=1;
				$arr_data[1]['response_game']=0; 
			}
		}
		$i=0;
		foreach($arr_data AS $row){
			if($row['response_game']==1){
				$i++;
			}
		}
		if(count($arr_data) == $i || $i==0){
			$total_charge = 0;
		}else{
			$total_charge = $i*$num_img;
		}
		$tb_charge = ceil($total_charge/(count($arr_data)-$i));
		foreach($arr_data AS $k=>$row){
			if($row['response_game']==1){
				$arr_data[$k]['img_add'] = $total_charge > 0 ? $num_img : 0;
				$arr_data[$k]['img_minus'] = 0; 	
			}else{
				$arr_data[$k]['img_add'] = 0;
				$arr_data[$k]['img_minus'] = $tb_charge;
			}
		}
		return $arr_data;
	}
}
