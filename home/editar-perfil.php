
<h2>Editar perfil</h2>

<?php
if(isset($_REQUEST['atualizar']) && $_REQUEST['atualizar'] == 'true'){

	if(isset($_POST['nome'])){
		
		$valores = array('nome' => $_POST['nome'],
						 'idade' => $_POST['idade'],
						 'grau_estudo' => $_POST['grau_estudo']);
						 
		PerfilDAO::atualizar($_POST['id'], $valores);
		
	}
	
	echo '<h1>Perfil atualizado com sucesso</h1><br />';

}?>
<form name="editar-perfil" action="<?php echo URL_HOME ?>?acao=editarperfil&atualizar=true" method="post" onsubmit="return validaPerfil(this);">
	<input type="hidden" name="id" value="<?php echo $perfil['id'] ?>" />
    <input class="fields campo-largo" type="text" name="nome" value="<?php echo $perfil['nome'] ?>" placeholder="Nome"/><br>
    <input class="fields campo-largo" type="text" name="idade" value="<?php echo $perfil['idade'] ?>" placeholder="Idade"/><br>
    <input class="fields campo-largo" type="text" name="grau_estudo" value="<?php echo $perfil['grau_estudo'] ?>" placeholder="Grau de estudo"/><br>
    <input class="botao-primario largo" type="submit" value="Atualizar" /><br>
</form>
<a href="<?php echo URL_HOME ?>config-conta.php">Configurações avançadas</a><br />
<a href="<?php echo URL_HOME ?>">Voltar</a>
<script type="text/javascript" src="../js/validacao.js"></script>
<?php exit; ?>