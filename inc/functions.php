<?php
	session_start();
	
	function debug($variable){
		echo '<pre>'.print_r($variable, true).'</pre>';
	}
	
	function logged_only(){
		
		if (session_status() == PHP_SESSION_NONE){
	
	
		}
		
		if(!isset($_SESSION['auth'])){
		
		$_SESSION['flash']['danger']="Vous n'avez pas le droit d'accéder à cette page";
		
		header('Location:login.php');
		
		exit();
		}
	}
	
	
	function lireDonneePost($nomDonnee, $valDefaut="") {
    if ( isset($_POST[$nomDonnee]) ) {
        $val = $_POST[$nomDonnee];
    }
    else {
        $val = $valDefaut;
    }
    return $val;
}

	
?>	