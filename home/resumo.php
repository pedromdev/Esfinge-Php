<?php

require_once($diretorio_root.'/DAO/resumodao.php');

if($_REQUEST['resumo'] == 'excluir'){
	if(isset($_REQUEST['id_r'])){
		$valores = ResumoDAO::buscar_por_id($_REQUEST['id_r']);
		
		if($valores['id_usuario_resumo'] != $_SESSION['id']){
			echo '<meta http-equiv="refresh" content="0; url='.URL_HOME.'">';
			exit;
		}
		
		ResumoDAO::excluir($_REQUEST['id_r']);
		echo '<h2 class="texto-azul">Resumo excluído com sucesso</h2>';
		echo '<a href="'.URL_HOME.'">Voltar</a>';
	}
	exit;
}

$valores = array('titulo' => '',
				 'texto' => '',
				 'area' => '',
				 'referencia' => '',
				 'id_usuario_resumo' => '',
				 'status_visibilidade' => '',
				 'status_publicacao' => '');
$parametros = '?resumo='.$_REQUEST['resumo'];
$acao = '';
$botao = '';
$msg = '';
if($_REQUEST['resumo'] == 'editar'){
	if(isset($_REQUEST['id_r'])){
		$parametros .= '&id_r='.$_REQUEST['id_r'];
		$acao = '&acao_resumo=atualizar';
		$botao = 'Atualizar';
		
		$valores = ResumoDAO::buscar_por_id($_REQUEST['id_r']);
		
		if($valores['id_usuario_resumo'] != $_SESSION['id']){
			echo '<meta http-equiv="refresh" content="0; url='.URL_HOME.'">';
			exit;
		}
		
		if(isset($_REQUEST['acao_resumo']) && $_REQUEST['acao_resumo'] == 'atualizar'){
			if(isset($_POST['titulo'])){
				$valores['titulo'] = $_POST['titulo'];
				$valores['texto'] = $_POST['texto'];
				$valores['area'] = $_POST['area'];
				$valores['referencia'] = $_POST['referencia'];
				$valores['status_visibilidade'] = $_POST['status_visibilidade'];
				$valores['status_publicacao'] = $_POST['status_publicacao'];
				
				if($valores['id_usuario_resumo'] != $_SESSION['id']){
					echo '<meta http-equiv="refresh" content="0; url='.URL_HOME.'">';
					exit;
				}
				
				ResumoDAO::atualizar($_REQUEST['id_r'], $valores);
				$msg = '<span class="info-msg" style="float: right; margin-right: 450px; padding-top: 0;">Resumo atualizado com sucesso</span>';
			}
		}
	} else {
		echo '<meta http-equiv="refresh" content="0; url='.URL_HOME.'?resumo=novo">';
	}
} else if($_REQUEST['resumo'] == 'novo'){
	$acao = '&acao_resumo=salvar';
	$botao = 'Salvar';
	if(isset($_REQUEST['acao_resumo']) && $_REQUEST['acao_resumo'] == 'salvar'){
		if(isset($_REQUEST['status']) && isset($_POST['titulo'])){
			$valores['titulo'] = $_POST['titulo'];
			$valores['texto'] = $_POST['texto'];
			$valores['area'] = $_POST['area'];
			$valores['referencia'] = $_POST['referencia'];
			$valores['status_visibilidade'] = $_POST['status_visibilidade'];
			$valores['status_publicacao'] = $_POST['status_publicacao'];
			$valores['id_usuario_resumo'] = $_POST['iur'];
			
			$id = ResumoDAO::salvar($valores);
			$acao = '&acao_resumo=atualizar';
			$parametros = '?resumo=editar&id_r='.$id;
			$botao = 'Atualizar';
			$msg = '<span class="info-msg" style="float: right; margin-right: 450px; padding-top: 0;">Resumo salvo com sucesso</span>';
		}
	}
}

$action = URL_HOME.$parametros.$acao;
?>
<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinyMCE.init({
        mode : "textareas"
});
</script>
<div style="float:right;">
<a href="<?php echo URL_HOME ?>">Voltar</a> - <a href="<?php echo URL_HOME.'?resumo=novo' ?>">Novo</a>
</div>
<?php echo $msg ?>
<form id="resumo" method="post" name="resumo" onsubmit="validaResumo(this); return false;">
	<input type="hidden" name="iur" value="<?php echo $_SESSION['id'] ?>"  />
	<input placeholder="Título" id="titulo" name="titulo" class="fields padding5" value="<?php echo $valores['titulo'] ?>"  />
    <textarea name="texto" style="width: 100%; height:380px;" ><?php echo $valores['texto'] ?></textarea>
    <div id="rodape">
    	<input placeholder="Área" name="area" class="fields padding5" value="<?php echo $valores['area'] ?>"  />
    	<input placeholder="Referência" name="referencia" class="fields padding5" value="<?php echo $valores['referencia'] ?>"  />
    	<select name="status_visibilidade" id="status_visibilidade">
    		<option value="0" <?php echo $valores['status_visibilidade'] == 0? 'selected="selected"' : ''?>>Público</option>
    	    <option value="1" <?php echo $valores['status_visibilidade'] == 1? 'selected="selected"' : ''?>>Somente amigos</option>
        	<option value="2" <?php echo $valores['status_visibilidade'] == 2? 'selected="selected"' : ''?>>Privado</option>
    	</select>
        <select name="status_publicacao" id="status_publicacao">
    		<option value="0" <?php echo $valores['status_publicacao'] == 0? 'selected="selected"' : ''?>>Publicado</option>
    	    <option value="1" <?php echo $valores['status_publicacao'] == 1? 'selected="selected"' : ''?>>Rascunho</option>
    	</select>
        <div id="botoes">
        	
            <?php if(get_url() == URL_HOME.'?resumo=novo'){ ?>
            	<input type="submit" class="botao-primario" value="<?php echo $botao ?>" formaction="<?php echo $action ?>"  />
            <?php } else {?>
            	<input type="submit" class="botao-primario" value="<?php echo $botao ?>" formaction="<?php echo $action ?>"  />
            <?php } ?>
        </div>
    </div>
</form>
<script type="text/javascript" src="../js/validacao.js"></script>
<?php exit; ?>