<?php

class ResumoAmizade{
	private static $connection = false;
	
	private static function db_connect(){
		$connection = mysql_connect(DBHOST, DBUSER, DBPW);
		
		date_default_timezone_set('America/Sao_Paulo');
		
		if(!$connection){
			die('Não foi possível connectar: '.mysql_error());
			return false;
		}
		
		$db = mysql_select_db(DBNAME, $connection);
		
		if(!$db){
			die('Não foi possível conectar ao banco: '.mysql_error());
			return false;
		}
		
		return $connection;
	}
	
	private static function db_disconnect(){
		if(ResumoAmizade::$connection){
			ResumoAmizade::$connection = false;
			mysql_close();
		}
	}
	
	private static function get_var($sql){		
		ResumoAmizade::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			ResumoAmizade::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		ResumoAmizade::db_disconnect();
		
		if(!$result) return false;
		
		list($select, $key, $str) = split(' ', $sql, 3);
		
		if($key == '*'){
			return false;
		} else {
			return $result[$key];
		}
	}
	
	private static function get_row($sql){		
		ResumoAmizade::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			ResumoAmizade::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		ResumoAmizade::db_disconnect();
		
		if(!$result) return false;
		
		return $result;
	}
	
	private static function get_list($sql){		
		ResumoAmizade::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			ResumoAmizade::db_disconnect();
			return false;
		}
		
		$results = array();
		
		while($array = mysql_fetch_array($query)){
			$results[] = $array;
		}
		
		ResumoAmizade::db_disconnect();
		
		if(!$results) return false;
		
		return $results;
	}
	
	private static function query($sql){
		ResumoAmizade::db_connect();
		
		$query = mysql_query($sql);
		
		ResumoAmizade::db_disconnect();
	}
	
	function listar_resumos_recentes($id_usuario){		
		$sql = sprintf("call resumos_recentes(%d)", $id_usuario);
		
		$results = ResumoAmizade::get_list($sql);
		
		if(!$results) return false;
		
		return $results;
	}
}

?>