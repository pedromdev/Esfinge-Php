<?php
session_start(); 
require_once('../config.php');

if(isset($_SESSION['id'])){
require($diretorio_root.'/DAO/usuariodao.php');
require($diretorio_root.'/DAO/perfildao.php');
require($diretorio_root.'/DAO/resumodao.php');
require($diretorio_root.'/DAO/amizadedao.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo ?></title>
<?php
	
	the_style();
	the_favicon();
	
	$maxitens = 5;
	$page = '';
	if(isset($_REQUEST['user'])){
		$amizade = AmizadeDAO::buscar_amizade($_SESSION['id'], $_REQUEST['user']);
		if(!$amizade){
			if(isset($_REQUEST['page'])){
				$resumos = ResumoDAO::listagem_paginada_outros_usuarios($_REQUEST['user'], $_REQUEST['page'], $maxitens);
			} else {
				$resumos = ResumoDAO::listagem_paginada_outros_usuarios($_REQUEST['user'], 1, $maxitens);
			}
			
			$count = ResumoDAO::qtd_resumos_outros_usuarios($_REQUEST['user']);
			$page = '?user='.$_REQUEST['user'].'&page=';
		} else {
			if(isset($_REQUEST['page'])){
				$resumos = ResumoDAO::listagem_paginada_amigos($_REQUEST['user'], $_REQUEST['page'], $maxitens);
			} else {
				$resumos = ResumoDAO::listagem_paginada_amigos($_REQUEST['user'], 1, $maxitens);
			}
			
			$count = ResumoDAO::qtd_resumos_amigos($_REQUEST['user']);
			$page = '?user='.$_REQUEST['user'].'&page=';
		}
	} else {
		if(isset($_REQUEST['page'])){
			$resumos = ResumoDAO::listagem_paginada($_SESSION['id'], $_REQUEST['page'], $maxitens);
		} else {
			$resumos = ResumoDAO::listagem_paginada($_SESSION['id'], 1, $maxitens);
		}
		
		$count = ResumoDAO::qtd_resumos($_SESSION['id']);
		$page = '?page=';
	}
	
	$paginas = intval($count/$maxitens);
	
	
	
	if($paginas != 0 && ($paginas*$maxitens) < $count){
		$paginas++;
	}
	
?>
</head>
<body>
<div class="caixa" id="caixa-home">
<?php if(isset($_REQUEST['res'])){ 
	$res = ResumoDAO::buscar_por_id($_REQUEST['res']);
	$amizade = AmizadeDAO::buscar_amizade($_SESSION['id'], $res['id_usuario_resumo']);
	
	if($res['id_usuario_resumo'] != $_SESSION['id']){
		if((!$amizade && $res['status_visibilidade'] > 0)
			|| ($amizade['status_amizade'] == 1 && $res['status_visibilidade'] > 0)){
			echo '<meta http-equiv="refresh" content="0; url='.get_url_home().'">';
			exit;
		}
	}
?>
	<span class="letra-grande texto-azul"><?php echo $res['titulo'] ?></span>
    <?php if($res['id_usuario_resumo'] == $_SESSION['id']){ ?>
    	<div style="float:right">
    		<a href="<?php echo get_url_home().'?resumo=editar&id_r='.$res['id'] ?>">Editar</a> - <a href="<?php echo get_url_home().'?resumo=excluir&id_r='.$res['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir esse resumo?');">Excluir</a>
    	</div>
    <?php } ?>
    <div id="caixa-texto">
    	<?php echo $res['texto'] ?>
    </div>
<?php } else { ?>
	<table cellpadding="8" cellspacing="0" border="0" class="tabela">
    	<tr class="header">
        	<td class="first">Título</td>
            <td>Status de publicação</td>
            <td>Data de publicação</td>
            <td>Visibilidade</td>
            <td>Área</td>
            <td class="last">Referência</td>
        </tr>
    <?php if(!$resumos){ ?>
        	<tr>
            	<td class="last">Não há resumos aqui :(</td>
            </tr>
        <?php } else { ?>
        <?php foreach($resumos as $resumo){ ?>
        	<tr>
            	<td><a href="<?php echo get_url_home().'resumos.php?res='.$resumo['id'] ?>"><?php echo $resumo['titulo'] ?></a></td>
                <td><?php echo $resumo['status_publicacao'] == 0? 'Publicado' : 'Rascunho' ?></td>
                <td><?php echo $resumo['data_publicacao'] != ''? date('d/m/Y H:i:s', strtotime($resumo['data_publicacao'])) : '' ?></td>
                <td><?php
                	if($resumo['status_visibilidade'] == 0){
						echo 'Público';
					} else if($resumo['status_visibilidade'] == 1){
						echo 'Somente amigos';
					} else if($resumo['status_visibilidade'] == 2){
						echo 'Privado';
					}
				?></td>
                <td><?php echo $resumo['area'] ?></td>
                <td class="last"><?php echo $resumo['referencia'] ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
    </table>
<?php } ?>
<br />
<br />

<?php for($i = 1; $i <= $paginas; $i++){ ?>
	<a class="page" href="<?php echo get_url_home().'resumos.php'.$page.$i ?>"><?php echo $i ?></a>
<?php } ?>
<br />
<br />
<a href="<?php echo get_url_home() ?>">Voltar</a>
</div>
</body>
</html>

<?php } else {

$url = '?url='.montar_url_retorno();
echo '<meta http-equiv="refresh" content="0; url=http://localhost/esfinge/login.php'.$url.'">';
} ?>