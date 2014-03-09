<?php

require_once($diretorio_root.'/DAO/resumodao.php');

$qtd_resumos = ResumoDAO::qtd_resumos($_SESSION['id']);

$perfil = PerfilDAO::buscar_por_id_usuario($_SESSION['id']);

echo '<img src="'.get_gravatar_img($_SESSION['email']).'" /><br /><br>';
echo '<strong>'.$perfil['nome'].'</strong><br>'; 
echo '<strong>Idade</strong>: '.$perfil['idade'].'<br>';
echo '<strong>Grau de estudo</strong>: '.$perfil['grau_estudo'].'<br>';
echo '<a href="'.URL_HOME.'resumos.php">Ver resumos ('.$qtd_resumos.')</a><br>';
echo the_link_home();
exit; 
?>