<?php

require($diretorio_root.'/DAO/resumodao.php');
require($diretorio_root.'/DAO/usuariodao.php');

$amizade = AmizadeDAO::buscar_amizade($_SESSION['id'], $_REQUEST['user']);

$usuario = UsuarioDAO::buscar_por_id($_REQUEST['user']);

$qtd_resumos = ResumoDAO::qtd_resumos($_REQUEST['user']);

$perfil = PerfilDAO::buscar_por_id_usuario($_REQUEST['user']);

echo '<img src="'.get_gravatar_img($usuario['email']).'" /><br /><br>';
echo '<strong>'.$perfil['nome'].'</strong><br>'; 
echo '<strong>Idade</strong>: '.$perfil['idade'].'<br>';
echo '<strong>Grau de estudo</strong>: '.$perfil['grau_estudo'].'<br>';
echo '<a href="'.URL_HOME.'resumos.php?user='.$_REQUEST['user'].'">Ver resumos ('.$qtd_resumos.')</a><br>';
if(!$amizade){
	echo '<a href="'.URL_AMIGOS.'?add='.$_REQUEST['user'].'">Adicionar</a><br>';
} else {
	echo '<a href="'.URL_AMIGOS.'?exc='.$_REQUEST['user'].'">Desfazer amizade</a><br>';
}
echo the_link_home();
exit;
?>