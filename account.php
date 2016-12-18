<?php 

require 'inc/functions.php';
logged_only();
require 'inc/db.php';
$idUser = $_SESSION['auth']['idUser'];

if (!empty($_POST['bio'])){
	
	
	$req = $dbh-> prepare("UPDATE users SET bio= ? WHERE idUtil = ?");
	
	$req -> execute([$_POST['bio'],$idUser]);
	$_SESSION['flash']['success'] = "Votre biographie est enregistrée";
}  

require 'inc/header.php';?> 

	<div class="container text-center">  
	<h1> Votre compte </h1>
	
	<div class="row">
	
		<div class="col-sm-3 well">
			<div class="well">
				<p><a href="#">Mon Profil</a></p>
				<img src="images/blank-profile.png" class="img-circle" height="65" width="65" alt="Avatar">
			</div>
		</div>
		
		<div class="col-sm-9 ">
			
			<div class="well">
			<h1> Bonjour <?= $_SESSION['auth']['login']; ?> </h1>
			
			<form action= "" method="POST">
			
		
			<div class="form-group">
				<h3><label for="">Biographie</label></br></h3>
				<input type="text" name="bio" class="form-control" placeholder="Dites quelques mots" ></br>
				<button type = "submit" class="btn btn-primary">Sauvegarder</button>
			</div>
			</form>
            </div>
			

		</div>
	
	</div>	
	
    </div>  
      
 
	
<?php require 'inc/footer.php';?>