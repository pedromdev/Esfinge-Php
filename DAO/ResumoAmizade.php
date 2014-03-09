<?php

class ResumoAmizade{
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
		/*$this->init();
		
		$amigos = $this->amizadedao->listar_amigos($id_usuario);
		
		if(!$amigos) return false;
		
		$resumos = array();
		
		foreach($amigos as $amigo){
			if($amigo['id_convidador'] == $id_usuario){
				$lista = $this->resumodao->listar_recentes($amigo['id_convidado']);
			} else {
				$lista = $this->resumodao->listar_recentes($amigo['id_convidador']);
			}
			
			foreach($lista as $item){
				if($item['status_visibilidade'] <= 1){
					$resumos[] = $item;
				}
			}
		}
		
		$resumos = mergeSort($resumos);
		
		return array_slice($resumos, 0, 3);*/
		
		$sql = sprintf("select res.id, res.titulo, res.data_publicacao, per.nome from resumo as res inner join perfil as per, amizade as ami 
	where per.id_usuario_perfil = res.id_usuario_resumo and res.status_visibilidade <= 1
	and res.status_publicacao = 0 and
	((res.id_usuario_resumo = ami.id_convidado and ami.id_convidador = %d) or
	(res.id_usuario_resumo = ami.id_convidador and ami.id_convidado = %d)) 
	and ami.status_amizade = 0 order by res.data_publicacao desc limit 3", $id_usuario, $id_usuario);
		
		$results = ResumoAmizade::get_list($sql);
		
		if(!$results) return false;
		
		return $results;
	}
}

?>