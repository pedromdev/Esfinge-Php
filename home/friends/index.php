<?php
session_start(); 
require_once('../../config.php');

if(isset($_SESSION['id'])){
require_once($diretorio_root.'/DAO/perfildao.php');
require_once($diretorio_root.'/DAO/amizadedao.php');
require_once($diretorio_root.'/DAO/PerfilAmizade.php');

$this_url_path = this_url_path(__FILE__);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo ?></title>
<?php
	
	the_style();
	the_favicon();
	
	$amigos = PerfilAmizade::listar_perfil_amigos($_SESSION['id']);
?>
</head>
<body>
	<div class="caixa" id="caixa-home">    	
	<?php
		if(isset($_REQUEST['add'])){
			$valores = array('id_convidador' => $_SESSION['id'],
							 'id_convidado' => $_REQUEST['add']);
			
			$amizade = AmizadeDAO::buscar_amizade($_SESSION['id'], $_REQUEST['add']);
			if(!$amizade){
				AmizadeDAO::salvar($valores);
				
			} else {
				echo '<meta http-equiv="refresh" content="0; url='.URL_HOME.'">';
				exit;
			}
			
			echo '<h2 class="texto-azul">Convite enviado</h2>';
		} else if(isset($_REQUEST['conv'])){ 
			if($_REQUEST['conv'] == 'env'){
				include('enviados.php');
				exit;
			} else if($_REQUEST['conv'] == 'rec'){
				include('recebidos.php');
				exit;
			}
		} else if(isset($_REQUEST['exc'])){
			$amizade = AmizadeDAO::buscar_amizade($_SESSION['id'], $_REQUEST['exc']);
			
			if(!$amizade){
				echo '<meta http-equiv="refresh" content="0; url='.URL_HOME.'">';
				exit;
			} else {
				AmizadeDAO::excluir($amizade['id']);
			}
		} else {
		?>
        <div align="right">
        	<a href="<?php echo $this_url_path ?>">Amigos</a> - <a href="<?php echo $this_url_path ?>?conv=env">Convites enviados</a> - <a href="<?php echo $this_url_path ?>?conv=rec">Convites recebidos</a>
    	</div><br />
        <table class="tabela" cellpadding="5" cellspacing="0" border="0">
        	<tr class="header">
            	<td class="first" width="50%">Nome</td>
                <td class="last">Data da amizade</td>
            </tr>
            <?php if(!$amigos){ ?>
            	<tr>
                	<td class="first"></td>
                    <td class="last"></td>
                </tr>
            <?php } else { 
					foreach($amigos as $amigo){
						$amizade = AmizadeDAO::buscar_amizade($_SESSION['id'], $amigo['id_usuario_perfil']);
			?>
            	<tr>
                	<td><a href="<?php echo URL_USUARIO.'?user='.$amigo['id_usuario_perfil'] ?>"><?php echo $amigo['nome'] ?></a></td>
                    <td><?php echo get_date('d F Y',$amizade['data_amizade']) ?></td>
                </tr>
            <?php } 
				}
			?>
        </table>
	<?php } ?>
	<br />
	<br />
	<a href="<?php echo URL_HOME ?>">Voltar</a>
	</div>
</body>
</html>

<?php } else {

$url = '?url='.montar_url_retorno();
echo '<meta http-equiv="refresh" content="0; url=http://localhost/esfinge/login.php'.$url.'">';
} ?>