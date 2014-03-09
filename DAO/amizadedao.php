<?php

class AmizadeDAO{
	
	private static $connection = false;
	
	private static function db_connect(){
		$connection = mysql_connect('localhost', 'root', '');
		
		if(!$connection){
			die('Não foi possível connectar: '.mysql_error());
			return false;
		}
		
		$db = mysql_select_db('esfinge', $connection);
		
		if(!$db){
			die('Não foi possível conectar ao banco: '.mysql_error());
			return false;
		}
		
		AmizadeDAO::$connection = true;
		
		return $connection;
	}
	
	private static function db_disconnect(){
		if(AmizadeDAO::$connection){
			AmizadeDAO::$connection = false;
			mysql_close();
		}
	}
	
	private static function get_var($sql){		
		AmizadeDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			AmizadeDAO::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		AmizadeDAO::db_disconnect();
		
		if(!$result) return false;
		
		list($select, $key, $str) = split(' ', $sql, 3);
		
		if($key == '*'){
			return false;
		} else {
			return $result[$key];
		}
	}
	
	private static function get_row($sql){		
		AmizadeDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			AmizadeDAO::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		AmizadeDAO::db_disconnect();
		
		if(!$result) return false;
		
		return $result;
	}
	
	private static function get_list($sql){		
		AmizadeDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			AmizadeDAO::db_disconnect();
			return false;
		}
		
		$results = array();
		
		while($array = mysql_fetch_array($query)){
			$results[] = $array;
		}
		
		AmizadeDAO::db_disconnect();
		
		if(!$results) return false;
		
		return $results;
	}
	
	private static function query($sql){
		AmizadeDAO::db_connect();
		
		$query = mysql_query($sql);
		
		AmizadeDAO::db_disconnect();
	}
	
	static function excluir_amigos($id_usuario){
		AmizadeDAO::query(sprintf("DELETE FROM amizade WHERE id_convidador = %d OR id_convidado = %d", $id_usuario, $id_usuario));
	}
	
	function qtd_convites_recebidos($id_usuario){
		$result = AmizadeDAO::get_var(sprintf("SELECT COUNT(*) FROM amizade WHERE status_amizade = 1 AND id_convidado = %d", $id_usuario));
		
		if(!$result) return false;
		
		return $result;
	}
	
	static function salvar($valores = array()){
		$sql = sprintf("INSERT INTO amizade(status_amizade, id_convidador, id_convidado) VALUES(1, %d, %d)",$valores['id_convidador'], $valores['id_convidado']);
		
		AmizadeDAO::query($sql);
	}
	
	static function atualizar($id){
		$sql = sprintf("UPDATE amizade SET status_amizade = 0 WHERE id = %d",$id);
		
		AmizadeDAO::query($sql);
		
		$sql = sprintf("UPDATE amizade SET data_amizade = '%s' WHERE id = %d", date('Y-m-d'), $id);
		
		AmizadeDAO::query($sql);
	}
	
	static function buscar_por_id($id){
		$result = AmizadeDAO::get_row(sprintf("SELECT * FROM amizade WHERE id = %d",$id));
		
		if(!$result) return false;
		
		return $result;
	}
	
	static function excluir($id){
		AmizadeDAO::query(sprintf("DELETE FROM amizade WHERE id = %d", $id));
	}
	
	static function listar_convites_enviados($id_convidador){
		return AmizadeDAO::get_list(sprintf("SELECT * FROM amizade WHERE id_convidador = %d and status_amizade = 1", $id_convidador));
	}
	
	static function listar_convites_recebidos($id_convidado){
		return AmizadeDAO::get_list(sprintf("SELECT * FROM amizade WHERE id_convidado = %d and status_amizade = 1", $id_convidado));
	}
	
	static function listar_amigos($id_usuario){
		$results = AmizadeDAO::get_list(sprintf("SELECT * FROM amizade WHERE status_amizade = 0 and (id_convidado = %d or id_convidador = %d)", $id_usuario, $id_usuario));
		
		if(!$results) return false;
		
		return $results;
	}
	
	static function buscar_amizade($id_usuario1, $id_usuario2){
		$sql = sprintf("select * from amizade where (id_convidado = %d and id_convidador = %d) or (id_convidado = %d and id_convidador = %d)", $id_usuario1, $id_usuario2, $id_usuario2, $id_usuario1);
		
		$result = AmizadeDAO::get_row($sql);
		
		if(!$result) return false;
		
		return $result;
	}
	
	static function verificar_amizade($id_convidador, $id_convidado){
		$result = AmizadeDAO::get_var(sprintf("SELECT id FROM amizade WHERE id_convidador = %d and id_convidado = %d", $id_convidador, $id_convidado));
		
		if(!$result){
			return false;
		} else {
			return true;
		}
	}
}
?>