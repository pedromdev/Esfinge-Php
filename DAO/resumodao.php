<?php

class ResumoDAO{
	
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
		if(ResumoDAO::$connection){
			ResumoDAO::$connection = false;
			mysql_close();
		}
	}
	
	private static function get_var($sql){		
		ResumoDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			ResumoDAO::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		ResumoDAO::db_disconnect();
		
		if(!$result) return false;
		
		list($select, $key, $str) = split(' ', $sql, 3);
		
		if($key == '*'){
			return false;
		} else {
			return $result[$key];
		}
	}
	
	private static function get_row($sql){		
		ResumoDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			ResumoDAO::db_disconnect();
			return false;
		}
		
		$result = mysql_fetch_array($query);
		
		ResumoDAO::db_disconnect();
		
		if(!$result) return false;
		
		return $result;
	}
	
	private static function get_list($sql){		
		ResumoDAO::db_connect();
		
		$query = mysql_query($sql);
		
		if(!$query){
			ResumoDAO::db_disconnect();
			return false;
		}
		
		$results = array();
		
		while($array = mysql_fetch_array($query)){
			$results[] = $array;
		}
		
		ResumoDAO::db_disconnect();
		
		if(!$results) return false;
		
		return $results;
	}
	
	private static function query($sql){
		ResumoDAO::db_connect();
		
		$query = mysql_query($sql);
		
		ResumoDAO::db_disconnect();
	}
	
	static function excluir_resumos($id_usuario){
		ResumoDAO::query(sprintf("DELETE FROM resumo WHERE id_usuario_resumo = %d", $id_usuario));
	}
	
	static function salvar($valores = array()){
		if($valores['status_publicacao'] == 0){
			
			$sql = sprintf("INSERT INTO resumo(titulo, texto, status_publicacao, status_visibilidade, area, referencia, data_publicacao, id_usuario_resumo) VALUES('%s', '%s', %d, %d, '%s', '%s', '%s', %d)", $valores['titulo'], $valores['texto'], $valores['status_publicacao'], $valores['status_visibilidade'], $valores['area'], $valores['referencia'], date('Y-m-d H:i:s', time()), $valores['id_usuario_resumo']);
			
		} else {
			
			$sql = sprintf("INSERT INTO resumo(titulo, texto, status_publicacao, status_visibilidade, area, referencia, id_usuario_resumo) VALUES('%s', '%s', %d, %d, '%s', '%s', %d)", $valores['titulo'], $valores['texto'], $valores['status_publicacao'], $valores['status_visibilidade'], $valores['area'], $valores['referencia'], $valores['id_usuario_resumo']);
			
		}
		
		ResumoDAO::query($sql);
		
		return ResumoDAO::get_var(sprintf("SELECT id FROM resumo WHERE id_usuario_resumo = %d ORDER BY id DESC LIMIT 1",$valores['id_usuario_resumo']));
	}
	
	static function atualizar($id, $valores = array()){
		foreach($valores as $chave=>$valor){
			if(is_int($valor)){
				$sql = sprintf("UPDATE resumo SET %s = %d WHERE id = %d", $chave, $valor, $id);
			} else if(is_string($valor)){
				$sql = sprintf("UPDATE resumo SET %s = '%s' WHERE id = %d", $chave, $valor, $id);
			}
			ResumoDAO::query($sql);
		}
	}
	
	static function buscar_por_id($id){
		return ResumoDAO::get_row(sprintf("SELECT * FROM resumo WHERE id = %d",$id));
	}
	
	static function listagem_paginada($id_usuario, $pagina, $maxitens){
		$index = ($maxitens*$pagina)-$maxitens;
		
		$sql = sprintf("SELECT * FROM resumo WHERE id_usuario_resumo = %d ORDER BY data_publicacao DESC LIMIT %d,%d", $id_usuario, $index, $maxitens);
		
		return ResumoDAO::get_list($sql);
	}
	
	static function listagem_paginada_amigos($id_usuario, $pagina, $maxitens){
		$index = ($maxitens*$pagina)-$maxitens;
		
		$sql = sprintf("SELECT * FROM resumo WHERE id_usuario_resumo = %d AND status_visibilidade <= 1 ORDER BY data_publicacao DESC LIMIT %d,%d", $id_usuario, $index, $maxitens);
		
		return ResumoDAO::get_list($sql);
	}
	
	static function listagem_paginada_outros_usuarios($id_usuario, $pagina, $maxitens){
		$index = ($maxitens*$pagina)-$maxitens;
		
		$sql = sprintf("SELECT * FROM resumo WHERE id_usuario_resumo = %d AND status_visibilidade < 1 ORDER BY data_publicacao DESC LIMIT %d,%d", $id_usuario, $index, $maxitens);
		
		return ResumoDAO::get_list($sql);
	}
	
	static function listar_por_autor($id_usuario){
		$sql = sprintf("SELECT * FROM resumo WHERE id_usuario_resumo = %d ORDER BY data_publicacao DESC", $id_usuario);
		
		return ResumoDAO::get_list($sql);
	}
	
	static function listar_recentes($id_usuario){
		$sql = sprintf("SELECT * FROM resumo WHERE id_usuario_resumo = %d ORDER BY data_publicacao DESC LIMIT 3", $id_usuario);
		
		return ResumoDAO::get_list($sql);
	}
	
	static function listar_por_amigos($id_usuario){
		$sql = sprintf("SELECT * FROM resumo WHERE id_usuario_resumo = %d AND status_visibilidade <= 1 AND status_publicacao = 0 ORDER BY data_publicacao DESC", $id_usuario);
		
		return ResumoDAO::get_list($sql);
	}
	
	static function listar_por_outros_usuarios($id_usuario){
		$sql = sprintf("SELECT * FROM resumo WHERE id_usuario_resumo = %d AND status_visibilidade = 0 AND status_publicacao = 0 ORDER BY data_publicacao DESC", $id_usuario);
		
		return ResumoDAO::get_list($sql);
	}
	
	static function excluir($id){
		$sql = sprintf("DELETE FROM resumo WHERE id = %d", $id);
		
		ResumoDAO::query($sql);
	}
	
	static function qtd_resumos($id_usuario){
		return ResumoDAO::get_var(sprintf("SELECT COUNT(*) FROM resumo WHERE id_usuario_resumo = %d", $id_usuario));
	}
	
	static function qtd_resumos_amigos($id_usuario){
		return ResumoDAO::get_var(sprintf("SELECT COUNT(*) FROM resumo WHERE id_usuario_resumo = %d AND status_visibilidade <= 1 AND status_publicacao = 0", $id_usuario));
	}
	
	static function qtd_resumos_outros_usuarios($id_usuario){
		return ResumoDAO::get_var(sprintf("SELECT COUNT(*) FROM resumo WHERE id_usuario_resumo = %d AND status_visibilidade = 0 AND status_publicacao = 0", $id_usuario));
	}
}