<?php

if(isset($_REQUEST['ac']) && isset($_REQUEST['id'])){
	if($_REQUEST['ac'] == 'exc'){
		$amizade = AmizadeDAO::buscar_por_id($_REQUEST['id']);
	
		if($amizade['id_convidador'] == $_SESSION['id'] || $amizade['id_convidado'] == $_SESSION['id']){
			AmizadeDAO::excluir($_REQUEST['id']);
		
			echo '<center><span class="info-msg">Convite excluído</span></center><br />';
		} else {
			echo '<meta http-equiv="refresh" content="0; url='.get_url_home().'">';
			exit;
		}
	} else if($_REQUEST['ac'] == 'acc'){
		$amizade = AmizadeDAO::buscar_por_id($_REQUEST['id']);
	
		if($amizade['id_convidador'] == $_SESSION['id'] || $amizade['id_convidado'] == $_SESSION['id']){
			AmizadeDAO::atualizar($_REQUEST['id']);
		
			echo '<center><span class="info-msg">Convite aceito com sucesso</span></center><br />';
		} else {
			echo '<meta http-equiv="refresh" content="0; url='.get_url_home().'">';
			exit;
		}
	}
}

$recebidos = AmizadeDAO::listar_convites_recebidos($_SESSION['id']);

?>
<div align="right">
   	<a href="<?php echo $this_url_path ?>">Amigos</a> - <a href="<?php echo $this_url_path ?>?conv=env">Convites enviados</a> - <a href="<?php echo $this_url_path ?>?conv=rec">Convites recebidos</a>
</div><br />
<table cellpadding="5" cellspacing="0" border="0" class="tabela">
	<tr class="header">
    	<td class="first" width="50%">Nome</td>
        <td class="last">Ação</td>
    </tr>
    <?php if(!$recebidos){ ?>
    	<tr>
    		<td class="last" width="50%">Nenhum convite recebido</td>
        	<td class="last"></td>
    	</tr>
    <?php } else {?>
	<?php foreach($recebidos as $recebido){ ?>
    	<?php $perfil = PerfilDAO::buscar_por_id_usuario($recebido['id_convidador']); ?>
    	<tr>
        	<td class="first"><?php echo $perfil['nome'] ?></td>
            <td class="last">
            	<a href="<?php echo $this_url_path ?>?conv=env&ac=acc&id=<?php echo $recebido['id'] ?>" class="botao-primario padding3">Aceitar</a>  <a href="<?php echo $this_url_path ?>?conv=env&ac=exc&id=<?php echo $recebido['id'] ?>" class="botao-secundario padding3">Recusar</a>
            </td>
        </tr>
    <?php } ?>
    <?php } ?>
</table>
<br />
<br />
<a href="<?php echo URL_HOME ?>">Voltar</a>