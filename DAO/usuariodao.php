<?php

class UsuarioDAO{
	
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
		
		return $connection;
	}
	
	private static function db_disconnect(){
		if(UsuarioDAO::$connection){
			UsuarioDAO::$connection = false;
			mysql_close();
		}
	}
	
	private static function get_var($sql){		
		UsuarioDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			UsuarioDAO::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		UsuarioDAO::db_disconnect();
		
		if(!$result) return false;
		
		list($select, $key, $str) = split(' ', $sql, 3);
		
		if($key == '*'){
			return false;
		} else {
			return $result[$key];
		}
	}
	
	private static function get_row($sql){		
		UsuarioDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			UsuarioDAO::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		UsuarioDAO::db_disconnect();
		
		if(!$result) return false;
		
		return $result;
	}
	
	private static function get_list($sql){		
		UsuarioDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			UsuarioDAO::db_disconnect();
			return false;
		}
		
		$results = array();
		
		while($array = mysql_fetch_array($query)){
			$results[] = $array;
		}
		
		UsuarioDAO::db_disconnect();
		
		if(!$results) return false;
		
		return $results;
	}
	
	private static function query($sql){
		UsuarioDAO::db_connect();
		
		$query = mysql_query($sql);
		
		UsuarioDAO::db_disconnect();
	}
	
	static function salvar($valores = array()){
		$valores['senha'] = hash('sha256',$valores['senha']);
		$sql = sprintf("INSERT INTO usuario(email, senha) VALUES('%s', '%s')", $valores['email'], $valores['senha']);
		
		UsuarioDAO::query($sql);
		
		$sql = sprintf("SELECT id FROM usuario WHERE email = '%s'",$valores['email']);
		
		return UsuarioDAO::get_var($sql);
	}
	
	static function atualizar($id, $valores = array()){
		if($valores['senha'] != ''){
			$valores['senha'] = hash('sha256',$valores['senha']);
		}
		
		foreach($valores as $chave=>$valor){
			if($chave == 'senha' && $valor != ''){
				$sql = sprintf("UPDATE usuario SET %s = '%s' WHERE id = %d", $chave, $valor, $id);
			} else {
				$sql = sprintf("UPDATE usuario SET %s = '%s' WHERE id = %d", $chave, $valor, $id);
			}
			
			UsuarioDAO::query($sql);
		}
		
	}
	
	static function buscar_por_email($email){
		$sql = sprintf("SELECT * FROM usuario WHERE email = '%s'", $email);
		
		$result = UsuarioDAO::get_row($sql);
		
		return $result;
	}
	
	static function excluir($id){
		$sql = sprintf("DELETE FROM usuario WHERE id = %d", $id);
		
		UsuarioDAO::query($sql);
	}
	
	static function buscar_por_id($id){
		$sql = sprintf("SELECT * FROM usuario WHERE id = %d", $id);
		
		$result = UsuarioDAO::get_row($sql);
		
		return $result;
	}
	
	static function email_existente($email){
		$sql = sprintf("SELECT * FROM usuario WHERE email = '%s'", $email);
		
		$result = UsuarioDAO::get_row($sql);
		
		if(!$result){
			return false;
		} else {
			return true;
		}
		
	}
}

?>