<?php

$usuarios = PerfilAmizade::listar_usuarios($_SESSION['id'], $_REQUEST['busca']);?>

<table cellpadding="8" cellspacing="0" border="0" class="tabela">
    <tr class="header">
       	<td class="first">Nome</td>
        <td>Grau de estudo</td>
        <td>Data de cadastro</td>
        <td class="last"></td>
    </tr>
	<?php if(!$usuarios){?>
			<tr>
                <td class="last" width="33%"></td>
                <td class="last" width="33%">Não outros usuários</td>
                <td class="last" width="33%"></td>
            </tr>
    <?php } else { ?>
	<?php foreach($usuarios as $usuario){?>
		<tr>
        	<td class="first"><a href="<?php echo URL_USUARIO.'?user='.$usuario['id_usuario_perfil'] ?>"><?php echo $usuario['nome'] ?></a></td>
            <td><?php echo $usuario['grau_estudo'] ?></td>
            <td><?php echo get_date('d F Y', $usuario['date_cadastro']) ?></td>
            <?php if(($amizade = AmizadeDAO::buscar_amizade($_SESSION['id'], $usuario['id_usuario_perfil'])) == false){ ?>
            	<td class="last"><a href="<?php echo URL_AMIGOS.'?add='.$usuario['id_usuario_perfil']?>" class="botao-primario largo">Adicionar</a></td>
            <?php } else { 
					if($amizade['status_amizade'] == 1){
						echo '<td class="last">Convite pendente</td>';
					} else {
						echo '<td class="last">Amigo</td>';
					}	
            } ?>
        </tr>
	<?php } ?>
<?php } ?>
</table>
<br />
<a href="<?php echo URL_HOME ?>">Voltar</a>
<?php exit; ?>