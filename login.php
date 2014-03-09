<?php
session_start();

require_once('config.php');
if(!isset($_SESSION['id'])){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo ?></title>
<?php the_favicon(); ?>
<?php the_style(); ?>
<link rel="stylesheet" href="estilo-logo.css"  />
</head>
	<center>
    	<div class="limpa40"></div>
    	<span class="esfinge-azul">Esfinge</span>
        <div class="limpa40"></div>
    	<form name="login" id="login" class="caixa" method="post" action="signin.php<?php echo isset($_REQUEST['url'])? '?url='.$_REQUEST['url'] : '' ?>">
        	<?php
				if(isset($_REQUEST['msg'])){
					if($_REQUEST['msg'] == 'erro'){
						echo '<span class="erro-msg">Usuário/Senha incorretos</span>';
						echo '<div class="limpa20"></div>';
					}
				}
			?>
        	<input class="fields campo-largo" type="text" name="email" placeholder="E-mail"  /><br />
        	<input class="fields campo-largo" type="password" name="senha" placeholder="Senha"  /><br />
			<input class="botao-primario largo" type="submit" value="Logar"  />
        </form>
    </center>
<body>
</body>
</html>
<?php } else {
		echo '<meta http-equiv="refresh" content="0; url='.URL_HOME.'">';
	}
?>