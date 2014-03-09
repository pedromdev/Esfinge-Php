<?php

if((isset($_REQUEST['ac']) && $_REQUEST['ac'] = 'exc') && isset($_REQUEST['id'])){
	$amizade = AmizadeDAO::buscar_por_id($_REQUEST['id']);
	
	if($amizade['id_convidador'] == $_SESSION['id'] || $amizade['id_convidado'] == $_SESSION['id']){
		AmizadeDAO::excluir($_REQUEST['id']);
	
		echo '<center><span class="info-msg">Convite excluído</span></center><br />';
	} else {
		echo '<meta http-equiv="refresh" content="0; url='.get_url_home().'">';
		exit;
	}
	
}

$enviados = AmizadeDAO::listar_convites_enviados($_SESSION['id']);

?>
<div align="right">
   	<a href="<?php echo $this_url_path?>">Amigos</a> - <a href="<?php echo $this_url_path ?>?conv=env">Convites enviados</a> - <a href="<?php echo $this_url_path ?>?conv=rec">Convites recebidos</a>
</div><br />
<table cellpadding="5" cellspacing="0" border="0" class="tabela">
	<tr class="header">
    	<td class="first" width="50%">Nome</td>
        <td class="last">Ação</td>
    </tr>
    <?php if(!$enviados){ ?>
    	<tr>
    		<td class="last" width="50%">Nenhum convite enviado</td>
        	<td class="last"></td>
    	</tr>
    <?php } else {?>
	<?php foreach($enviados as $enviado){ ?>
    	<?php $perfil = PerfilDAO::buscar_por_id_usuario($enviado['id_convidado']); ?>
    	<tr>
        	<td class="first"><?php echo $perfil['nome'] ?></td>
            <td class="last">
            	<a href="<?php echo $this_url_path ?>?conv=env&ac=exc&id=<?php echo $enviado['id'] ?>" class="botao-secundario padding3">Excluir convite</a>
            </td>
        </tr>
    <?php } ?>
    <?php } ?>
</table>
<br />
<br />
<a href="<?php echo URL_HOME ?>">Voltar</a>