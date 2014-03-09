<?php

class PerfilDAO{
	private static $connection = false;
	
	private static function db_connect(){
		$connection = mysql_connect('127.0.0.1', 'root', '');
		
		if(!$connection){
			die('Não foi possível connectar: '.mysql_error());
			return false;
		}
		
		$db = mysql_select_db('esfinge', $connection);
		
		if(!$db){
			die('Não foi possível conectar ao banco: '.mysql_error());
			return false;
		}
		
		PerfilDAO::$connection = true;
		
		return $connection;
	}
	
	private static function db_disconnect(){
		if(PerfilDAO::$connection){
			PerfilDAO::$connection = true;
			mysql_close();
		}
	}
	
	private static function get_var($sql){		
		PerfilDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			PerfilDAO::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		PerfilDAO::db_disconnect();
		
		if(!$result) return false;
		
		list($select, $key, $str) = split(' ', $sql, 3);
		
		if($key == '*'){
			return false;
		} else {
			return $result[$key];
		}
	}
	
	private static function get_row($sql){		
		PerfilDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			PerfilDAO::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		PerfilDAO::db_disconnect();
		
		if(!$result) return false;
		
		return $result;
	}
	
	private static function get_list($sql){		
		PerfilDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			PerfilDAO::db_disconnect();
			return false;
		}
		
		$results = array();
		
		while($array = mysql_fetch_array($query)){
			$results[] = $array;
		}
		
		PerfilDAO::db_disconnect();
		
		if(!$results) return false;
		
		return $results;
	}
	
	private static function query($sql){
		PerfilDAO::db_connect();
		
		$query = mysql_query($sql);
		
		PerfilDAO::db_disconnect();
	}
	
	static function excluir_perfil($id_usuario){
		PerfilDAO::query(sprintf("DELETE FROM perfil WHERE id_usuario_perfil = %d", $id_usuario));
	}
	
	static function salvar($valores = array(), $id){
		$sql = sprintf("INSERT INTO perfil(nome, grau_estudo, date_cadastro, id_usuario_perfil) VALUES('%s', '%s', '%s', %d)",$valores['nome'], $valores['grau_estudo'], date('Y-m-d'), $id);
		
		PerfilDAO::query($sql);
	}
	
	static function atualizar($id, $valores = array()){
		$conexao = PerfilDAO::db_connect();
		
		foreach($valores as $chave=>$valor){
			if(is_string($valor)){
				$sql = sprintf("UPDATE perfil SET %s = '%s' WHERE id = %d", $chave, $valor, $id);
			} else if(is_int($valor)){
				$sql = sprintf("UPDATE perfil SET %s = %d WHERE id = %d", $chave, $valor, $id);
			}
			
			mysql_query($sql);
		}
		
		PerfilDAO::db_disconnect();
	}
	
	static function excluir($id){
		$sql = sprintf("DELETE FROM perfil WHERE id = %d", $id);
		
		PerfilDAO::query($sql);
	}
	
	static function listar_todos(){
		$valores = PerfilDAO::get_list("SELECT * FROM perfil");
		
		return $valores;
	}
	
	static function buscar_por_id_usuario($id){
		$sql = sprintf("SELECT * FROM perfil WHERE id_usuario_perfil = %d", $id);
		
		$result = PerfilDAO::get_row($sql);
		
		return $result;
	}
	
	static function listar_por_nome($id_usuario, $query){
		$results = PerfilDAO::get_list("SELECT * FROM perfil WHERE (id_usuario_perfil <> ".$id_usuario.") and (".$query.")");
		
		if(!$results) return false;
		
		return $results;
	}
}

?>