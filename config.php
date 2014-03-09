<?php
	
	include('merge.php');
	
	define('URL_SITE', 'http://'.$_SERVER['SERVER_NAME'].'/esfinge/');
	define('URL_HOME', 'http://'.$_SERVER['SERVER_NAME'].'/esfinge/home/');
	define('URL_AMIGOS', 'http://'.$_SERVER['SERVER_NAME'].'/esfinge/home/friends/');
	define('URL_USUARIO', 'http://'.$_SERVER['SERVER_NAME'].'/esfinge/home/user/');

	$titulo = 'Esfinge';
	$diretorio_root = str_replace('\\', '/', dirname(__FILE__));
	$site_url = 'http://'.$_SERVER['SERVER_NAME'].str_replace('C:/xampp/htdocs', '', $diretorio_root).'/';
	$favicon = '<link rel="shortcut icon" href="images/favicon.png">';
	
	function this_url_path($file){
		global $diretorio_root, $site_url;
		
		$file = str_replace('\\', '/', dirname($file));
		
		$file = str_replace($diretorio_root, URL_SITE, $file);
		
		$http = substr($file, 0, 7);
		$path = substr($file, 7).'/';
		return $http.str_replace('//', '/', $path);
	}
	
	function get_url_home(){
		global $site_url;
		return $site_url.'home/';
	}
	
	function get_favicon($file){
		global $site_url;
		
		$file = this_url_path($file);
		$file = str_replace($site_url, '', $file);
		
		$count = substr_count($file, '/');
		$favicon = 'images/favicon.png';
		
		if($count > 0){
			for($i = 0; $i < $count; $i++){
				$favicon = '../'.$favicon;
			}
		}
		
		return '<link rel="shortcut icon" href="'.$favicon.'">';
	}
	
	function get_style($file){
		global $site_url;
		
		$file = this_url_path($file);
		$file = str_replace($site_url, '', $file);
		
		$count = substr_count($file, '/');
		$style = 'style.css';
		
		if($count > 0){
			for($i = 0; $i < $count; $i++){
				$style = '../'.$style;
			}
		}
		
		return '<link rel="stylesheet" href="'.$style.'">';
	}
	
	function get_gravatar_img( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    	$url = 'http://www.gravatar.com/avatar/';
    	$url .= md5( strtolower( trim( $email ) ) );
    	$url .= "?s=$s&d=$d&r=$r";
    	if ( $img ) {
    	    $url = '<img src="' . $url . '"';
    	    foreach ( $atts as $key => $val )
    	        $url .= ' ' . $key . '="' . $val . '"';
    	    $url .= ' />';
    	}
    	return $url;
	}
	
	function montar_url_retorno(){
		$server = $_SERVER['SERVER_NAME']; 
		$endereco = $_SERVER ['REQUEST_URI'];
		
		$url = 'http://'.$server.$endereco;
		
		$url = str_replace(':', '%3A', $url);
		$url = str_replace('/', '%2F', $url);
		$url = str_replace('?', '%3F', $url);
		$url = str_replace('=', '%3D', $url);
		
		return $url;
	}
	
	function montar_url_direcionamento($url){
		$url = str_replace('%3A', ':', $url);
		$url = str_replace('%2F', '/', $url);
		$url = str_replace('%3F', '?', $url);
		$url = str_replace('%3D', '=', $url);
		
		return $url;
	}
	
	function get_url(){
		return 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}
	
	function url_busca(){
		if(isset($_POST['busca'])){
			$url = str_replace(' ', '+', strtolower($_POST['busca']));
			return '?busca='.$url;
		}
		
		return false;
	}
	
	function get_date($pattern = 'd-m-Y H:i:s', $strdate = null){
		$count = substr_count($pattern, 'F');
		$count .= substr_count($pattern, 'M');
		
		if($count > 0){
			if(is_null($strdate)){
				return trazdus_data(date($pattern));
			} else {
				return trazdus_data(date($pattern, strtotime($strdate)));
			}
		} else {
			if(is_null($strdate)){
				return trazdus_data(date($pattern));
			} else {
				return trazdus_data(date($pattern, strtotime($strdate)));
			}
		}
	}
	
	function trazdus_data($data){
		$F = strpos($data, 'F');
		$M = strpos($data, 'M');
		
		if($M === false){
			return traduz_mes_F($data);
		} else {
			return traduz_mes_M($data);
		}
	}
	
	function traduz_mes_F($data){
		$meses = array('January' => 'Janeiro',
					   'February' => 'Fevereiro',
					   'March' => 'Março',
					   'April' => 'Abril',
					   'May' => 'Maio',
					   'June' => 'Junho',
					   'July' => 'Julho',
					   'August' => 'Agosto',
					   'September' => 'Setembro',
					   'October' => 'Outubro',
					   'November' => 'Novembro',
					   'December' => 'Dezembro');
		
		//DEPRECATED
		//$data = super_str_replace('(January|February|March|April|May|June|July|August|September|October|November|December)', '(Janeiro|Fevereiro|Março|Abril|Maio|Junho|Julho|Agosto|Setembro|Outubro|Novembro|Dezembro)', $data);
		
		$data = strtr($data, $meses);
		
		return $data;
	}
	
	function traduz_mes_M($data){
		$meses = array('Jan' => 'Jan',
					   'Feb' => 'Fev',
					   'Mar' => 'Mar',
					   'Apr' => 'Abr',
					   'May' => 'Mai',
					   'Jun' => 'Jun',
					   'Jul' => 'Jul',
					   'Aug' => 'Ago',
					   'Sep' => 'Set',
					   'Oct' => 'Out',
					   'Nov' => 'Nov',
					   'Dec' => 'Dez');
		
		//DEPRECATED
		//$data = super_str_replace('(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)', '(Jan|Fev|Mar|Abr|Mai|Jun|Jul|Ago|Set|Out|Nov|Dez)', $data);
		
		$data = strtr($data, $meses);
		
		return $data;
	}
	
	//DEPRECATED
	function super_str_replace($search, $replace, $str){
		$search = str_replace('(', '', $search);
		$search = str_replace(')', '', $search);
		
		$replace = str_replace('(', '', $replace);
		$replace = str_replace(')', '', $replace);
		
		$replaces_search = spliti(' ', str_replace('|', ' ', $search));
		$replaces_replace = spliti(' ', str_replace('|', ' ', $replace));
		
		$count_search = count($replaces_search);
		$count_replace = count($replaces_replace);
		
		if($count_replace == $count_search){
			for($i = 0; $i < $count_replace; $i++){
				$str = str_replace($replaces_search[$i], $replaces_replace[$i], $str);
			}
			
			return $str;
		} else {
			return false;
		}
	}
	
	function the_link_home(){
		echo '<a href="'.get_url_home().'">Voltar</a>';
	}
	
	function the_favicon(){
		global $site_url;
		echo '<link rel="shortcut icon" href="'.URL_SITE.'images/favicon.png">';
	}
	
	function the_style(){
		global $site_url;
		echo '<link rel="stylesheet" href="'.URL_SITE.'style.css">';
	}
	
?>