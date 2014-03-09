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
<?php the_style(); ?>
<?php the_favicon(); ?>
<link rel="stylesheet" href="estilo-logo.css"  />
</head>
<body id="default">
	<center>
        <div class="limpa40"></div>
    	<span class="esfinge-branco">Esfinge</span>
        <div class="limpa40"></div>
    	<form name="cadastro" id="cadastro" class="caixa" action="<?php echo $site_url.'?acao=cadastrar' ?>"
        	method="post" onsubmit="validaCadastro(this); return false;">
            <h3>Cadastre-se</h3>
        	<?php
				
				if(isset($_REQUEST['acao']) && isset($_POST)){
					
					require($diretorio_root.'/DAO/usuariodao.php');
					require($diretorio_root.'/DAO/perfildao.php');
					
					if(UsuarioDAO::email_existente($_POST['email']) == true){
						echo '<span class="erro-msg">E-mail já existente</span>';
						echo '<div class="limpa20"></div>';
					} else {
						$u['email'] = $_POST['email'];
						$u['senha'] = $_POST['senha'];
					
						$p['nome'] = $_POST['nome'];
						$p['grau_estudo'] = $_POST['grau_estudo'];
					
						$id = UsuarioDAO::salvar($u);
						PerfilDAO::salvar($p, $id);
					
					
						echo '<span class="info-msg">Cadastro efetuado com sucesso</span>';
						echo '<div class="limpa20"></div>';
					}
				}
			?>
            <input class="fields campo-largo" type="text" name="email" id="email" placeholder="E-mail" /><br />
            <input class="fields campo-largo" type="text" name="nome" id="nome" placeholder="Nome"  /><br />
            <input class="fields campo-largo" type="text" name="grau_estudo" id="grau" placeholder="Grau de ensino"  /><br />
            <input class="fields campo-largo" type="password" name="senha" id="senha" placeholder="Senha"  /><br />
            <input class="fields campo-largo" type="password" name="confima" id="confirma" placeholder="Confirmar senha"  /><br />
            <input type="submit" class="botao-primario largo" value="Cadastrar"  /><br />
       		<b><p>Ou</p></b>
			<a href="login.php" class="botao-primario largo">Fazer Login</a>
        </form>
    </center>
    <script type="text/javascript" src="js/validacao.js"></script>
</body>
</html>
<?php } else {
		echo '<meta http-equiv="refresh" content="0; url='.URL_HOME.'">';
	}
?>