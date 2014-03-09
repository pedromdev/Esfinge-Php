<?php

require_once('amizadedao.php');
require_once('perfildao.php');

class PerfilAmizade{
	
	static function listar_perfil_amigos($id_usuario){
		
		$amizades = AmizadeDAO::listar_amigos($id_usuario);
		
		if(!$amizades) return false;
		
		$perfis_amigos = array();
		
		foreach($amizades as $amizade){
			if($amizade['id_convidador'] == $id_usuario){
				$perfis_amigos[] = PerfilDAO::buscar_por_id_usuario($amizade['id_convidado']);
			} else {
				$perfis_amigos[] = PerfilDAO::buscar_por_id_usuario($amizade['id_convidador']);
			}
		}
		
		return $perfis_amigos;
	}
	
	static function listar_usuarios($id_usuario, $busca){
		$nomes = spliti(' ',str_replace('+', ' ', $busca));
		
		$query = '';
		$i = 0;
		
		foreach($nomes as $nome){
			if($i == 0){
				$query .= "nome like '%".$nome."%'";
			} else {
				$query .= " or nome like '%".$nome."%'";
			}
			$i++;
		}
		
		$results = PerfilDAO::listar_por_nome($id_usuario, $query);
		
		if(!$results) return false;
		
		return $results;
	}
}

?>