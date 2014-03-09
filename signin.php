<?php

session_start();

require_once('config.php');
require_once($diretorio_root.'/DAO/usuariodao.php');

$user = UsuarioDAO::buscar_por_email($_POST['email']);

if(!$user){
	session_destroy();
	echo '<meta http-equiv="refresh" content="0; url='.URL_SITE.'/login.php?msg=erro">';
	exit;
} else {
	$senhainfo = hash('sha256', $_POST['senha']);
	$senhabanco = $user['senha'];

	if($senhainfo != $senhabanco){
		session_destroy();
		echo '<meta http-equiv="refresh" content="0; url='.URL_SITE.'/login.php?msg=erro">';
		exit;
	}
}

$_SESSION['id'] = $user['id'];
$_SESSION['email'] = $user['email'];

if(isset($_REQUEST['url'])){
	echo '<meta http-equiv="refresh" content="0; url='.montar_url_direcionamento($_REQUEST['url']).'">';
} else {
	echo '<meta http-equiv="refresh" content="0; url='.URL_HOME.'">';
}


?>