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
}
