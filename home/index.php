<?php
session_start(); 
require('../config.php');

$this_url_path = this_url_path(__FILE__);

if(isset($_SESSION['id'])){	
	if(isset($_POST['busca']))
		echo '<meta http-equiv="refresh" content="0; url='.$this_url_path.url_busca().'">';

require($diretorio_root.'/DAO/amizadedao.php');
require($diretorio_root.'/DAO/perfildao.php');
require($diretorio_root.'/DAO/PerfilAmizade.php');
require($diretorio_root.'/DAO/ResumoAmizade.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo ?></title>
<?php
	
	the_style();
	the_favicon();
		
	$perfil = PerfilDAO::buscar_por_id_usuario($_SESSION['id']);
	$convites_recebidos = AmizadeDAO::qtd_convites_recebidos($_SESSION['id']);
	
	if(!$convites_recebidos){
		$convites_recebidos = '';
	} else {
		$convites_recebidos = ' ('.$convites_recebidos.')';
	}
?>
</head>
<body>
<div class="caixa" id="caixa-home">
<?php

	if($_REQUEST){
		if(isset($_REQUEST['acao'])){
			include('editar-perfil.php');  
 		} else if (isset($_REQUEST['resumo'])){
			include('resumo.php');
		} else if(isset($_REQUEST['busca'])){
			include('usuarios.php');
		}
	} else {
		echo '<a href="https://pt.gravatar.com/site/login" target="_blank"><img src="'.get_gravatar_img($_SESSION['email']).'" /></a><br />';
		echo '<strong>'.$perfil['nome'].'</strong>'; 
		
		?>
        
        <br /><a href="<?php echo $this_url_path ?>?acao=editarperfil">Editar perfil</a>
        <br /><a href="<?php echo $this_url_path ?>?resumo=novo">Novo resumo</a>
        <br /><a href="<?php echo $this_url_path ?>resumos.php">Ver resumos</a>
        <br /><a href="<?php echo $this_url_path ?>friends/">Meus amigos</a>
    	<br /><a href="../signout.php">Sair</a>
        
        <a href="<?php echo URL_AMIGOS.'?conv=rec' ?>" style="position:absolute; top:20px; left:10%;">Convites recebidos<?php echo $convites_recebidos?></a>
        
        <form name="buscar" id="buscar" action="<?php echo $this_url_path ?>" method="post">
        	<input type="text" name="busca" class="fields campo-largo" />
            <input type="submit" value="Buscar" class="botao-primario padding5"/>
        </form>
        
        <table id="tabela-amigos" cellpadding="5" cellspacing="0" border="0">
        	<tr class="header">
            	<td>Amigos</td>
            </tr>
            <?php
				$amigos = PerfilAmizade::listar_perfil_amigos($_SESSION['id']);
				if(!$amigos){ ?>
            	<tr>
                	<td>Nenhum amigo</td>
                </tr>
            <?php } else {
					$i = 0;
					foreach($amigos as $amigo){
			?>
            	<tr>
                	<td><a href="<?php echo URL_USUARIO.'?user='.$amigo['id_usuario_perfil'] ?>"><?php echo $amigo['nome'] ?></a></td>
                </tr>
            <?php }
					if($i == 4){
						break;
					}
					$i++;
				}
			?>
        </table>
        <table id="resumos-recentes" class="tabela" cellpadding="5" cellspacing="0" border="0">
        	<tr class="header">
            	<td class="first">Título</td>
                <td>Autor</td>
                <td class="last">Data de publicação</td>
            </tr>
        	<?php
				$resumos_recentes = ResumoAmizade::listar_resumos_recentes($_SESSION['id']);
				if(!$resumos_recentes){
					echo '<tr><td class="last" width="33%"></td><td class="last" width="33%">Nenhum resumo publicado</td><td class="last" width="33%"></td></tr>';
				} else { ?>
            <?php 
				foreach($resumos_recentes as $recente){ 
					//$perfil = $perfildao->buscar_por_id_usuario($recente['id_usuario_resumo']);
			?>
            	<tr>
            		<td width="33%" class="first"><a href="<?php echo $this_url_path.'resumos.php?res='.$recente['id'] ?>"><?php echo $recente['titulo'] ?></a></td>
                	<td width="33%"><?php echo $recente['nome'] ?></td>
                	<td width="33%" class="last"><?php echo get_date('d F Y H:i:s',$recente['data_publicacao']) ?></td>
            	</tr>
            <?php } ?>
        <?php } ?>
        </table>
	<?php }
?>
</div>
</body>
</html>

<?php } else {

$url = '?url='.montar_url_retorno();
echo '<meta http-equiv="refresh" content="0; url=http://localhost/esfinge/login.php'.$url.'">';
} ?>