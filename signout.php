<?php
session_start();

require_once('config.php');

session_destroy();

echo '<meta http-equiv="refresh" content="0; url='.$site_url.'">';
?>