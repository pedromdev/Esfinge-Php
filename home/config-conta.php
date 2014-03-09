<?php
session_start(); 
require('../config.php');

$this_url_path = this_url_path(__FILE__);

if(isset($_SESSION['id'])){	

require($diretorio_root.'/DAO/usuariodao.php');
require($diretorio_root.'/DAO/perfildao.php');
require($diretorio_root.'/DAO/amizadedao.php');
require($diretorio_root.'/DAO/resumodao.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo ?></title>
<?php
	the_style();
	the_favicon();
	
	if(isset($_REQUEST['excluir'])){
		$id = $_SESSION['id'];
		ResumoDAO::excluir_resumos($id);
		AmizadeDAO::excluir_amigos($id);
		PerfilDAO::excluir_perfil($id);
		UsuarioDAO::excluir($id);
		echo '<meta http-equiv="refresh" content="0; url='.URL_SITE.'">';
		session_destroy();
		exit;
	} else if(isset($_REQUEST['atualizar']) && isset($_POST['email'])){
		$valores['email'] = $_POST['email'];
		
		if(isset($_POST['nova_senha'])) $valores['senha'] = $_POST['nova_senha'];
		
		UsuarioDAO::atualizar($_SESSION['id'],$valores);
		
		echo '<h2 class="texto-azul">Configurações de conta atualizadas</h2><br />';
		echo '<a href="'.URL_HOME.'">Voltar</a>';
		exit;
	}
	
	$user = UsuarioDAO::buscar_por_email($_SESSION['email']);
?>
</head>
<body>
<div class="caixa" id="caixa-home">
	<form name="config_conta" action="<?php echo $this_url_path ?>config-conta.php?atualizar" method="post" onsubmit="return validaForm(this);">
    	<h2>Configurações avançadas</h2>
        <input class="fields campo-largo" type="text" id="email" value="<?php echo $_SESSION['email'] ?>" placeholder="E-mail"/><br />
        <input class="fields campo-largo" type="password" id="senha_antiga" name="senha_antiga" placeholder="Senha antiga"/><br />
        <input class="fields campo-largo" type="password" id="nova_senha" name="nova_senha" placeholder="Nova senha"/><br />
        <input class="fields campo-largo" type="password" id="confirma_nova" name="confirma_nova" placeholder="Confirmar senha" /><input id="mensagem" disabled="disabled"/><br />
        <input type="submit" class="botao-primario largo" value="Atualizar"  />
    </form>
    <script type="text/javascript">
		function confirmar_senha(){
			var nova_senha = document.forms["config_conta"]["nova_senha"].value;
			var field = document.forms["config_conta"]["confirma_nova"];
			
			var styleMsg = "border-radius:4px; font-weight:bold; padding:4px; margin-left:10px; height:20px; text-align:center; width:100px;";
			var erroBorder = "border:1px solid #F00; color:#F00; background-color:#FCC;";
			var sucessBorder = "border:1px solid #0F0; color:#0F0; background-color:#CFC;";
			
			if(field.value.length < nova_senha.length && field.value.length > 0){
				document.getElementById("mensagem").setAttribute("style", styleMsg+erroBorder);
				document.getElementById("mensagem").setAttribute("value", "Incompatível");
			} else if(field.value != nova_senha && field.value.length > 0){
				document.getElementById("mensagem").setAttribute("style", styleMsg+erroBorder);
				document.getElementById("mensagem").setAttribute("value", "Incompatível");
			} else if(field.value == nova_senha && field.value.length != 0){
				document.getElementById("mensagem").setAttribute("style", styleMsg+sucessBorder);
				document.getElementById("mensagem").setAttribute("value", "Compatível");
			} else {
				document.getElementById("mensagem").setAttribute("style", "width:0; border:0;");
				document.getElementById("mensagem").setAttribute("value", "");
			}
		}
		
		var field = document.getElementById("confirma_nova");
		var field2 = document.getElementById("nova_senha");
		
		field.setAttribute("onkeypress", "confirmar_senha()");
		field.setAttribute("onkeyup", "confirmar_senha()");
		field.setAttribute("onkeydown", "confirmar_senha()");
		
		field2.setAttribute("onkeypress", "confirmar_senha()");
		field2.setAttribute("onkeyup", "confirmar_senha()");
		field2.setAttribute("onkeydown", "confirmar_senha()");
		
		confirmar_senha();
    </script>
	<script src="../js/sha256.js"></script>
    <script type="text/javascript">
		function validaForm(form){
			var senha = CryptoJS.SHA256(form.senha_antiga.value);
			var nova = form.nova_senha.value;
			var confirma = form.confirma_nova.value;
			
			var erros = 0;
			var msg = '';
			
			if(senha != "<?php echo $user['senha'] ?>"){
				form.senha_antiga.style.border = "2px solid #F00";
				msg += '- Senha inválida\n';
				erros++;
			}
			
			if(nova != confirma){
				msg += '- Senha incompatível\n';
				erros++;
			}
			
			if(erros > 0){
				alert(msg);
				return false;
			}
			
			return true;
		}
    </script>
    <a href="<?php echo URL_HOME ?>config-conta.php?excluir" onclick="return confirm('Você tem certeza que deseja excluir esta conta? Você excluíra todas as amizades e todos seus resumos. Após a exclusão não há como recuperar seus dados.\n\nDeseja continuar?');">Excluir conta</a><br />
	<a href="<?php echo URL_HOME ?>">Voltar</a>
</div>
</body>
</html>

<?php } else {

$url = '?url='.montar_url_retorno();
echo '<meta http-equiv="refresh" content="0; url=http://localhost/esfinge/login.php'.$url.'">';
} ?>