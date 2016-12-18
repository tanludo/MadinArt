<?php 
if (session_status() == PHP_SESSION_NONE){
	
	session_start();
}
 ?>
 
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Madin'Art Network</title>

    <!-- Bootstrap core CSS -->
    <link href="CSS/app.css" rel="stylesheet">
	<link href="CSS/style.css" rel="stylesheet">
	
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>

  <body>

    <nav class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Madin'Art Network</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
		  <?php if (isset($_SESSION['auth'])): ?>
			<li><a href="logout.php"> Se déconnecter</a></li>
			<li><a href="#"><span class="glyphicon glyphicon-user">Mon profil</span></a></li>
		  <?php else: ?>
            <li><a href="register.php">S'inscrire</a></li>
            <li><a href="login.php">Se connecter</a></li>		
			<?php endif; ?>
			<li><a href="support.php">Support</a></li>
          </ul>
		  
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
	
    <div class="container">

	
	
    
