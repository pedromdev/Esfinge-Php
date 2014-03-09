<?php
session_start(); 
require_once('../../config.php');

if(isset($_SESSION['id'])){
require($diretorio_root.'/DAO/perfildao.php');
require($diretorio_root.'/DAO/amizadedao.php');
require($diretorio_root.'/DAO/PerfilAmizade.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $titulo ?></title>
<?php
	
	the_style();
	the_favicon();
	
?>
</head>
<body>
	<div class="caixa" id="caixa-home">
    	<?php
			
			if(isset($_REQUEST['user']) && $_REQUEST['user'] != $_SESSION['id']){
				include('other.php');
			} else {
				include('current.php');
			}
			
		?>
    </div>
</body>
</html>

<?php } else {

$url = '?url='.montar_url_retorno();
echo '<meta http-equiv="refresh" content="0; url=http://localhost/esfinge/login.php'.$url.'">';
} ?>