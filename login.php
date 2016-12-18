<?php
	 require '/inc/functions.php';
	
	if (!empty($_POST) && !empty($_POST['login']) && !empty($_POST['mdp'])){

		require'/inc/db.php';
		
		$errors = array();
				
		//préparation de la requete
		$req = $dbh-> prepare('SELECT * FROM users WHERE login = :login OR mail = :login');
		$req->bindParam(':login', $_POST['login']);
		
		$req-> execute();
		
		$log = $req-> fetch();
		
		//si le login ou mail est correcte
		if($log){
			//vérifier la conformité du mot de passe
			if($log['password'] != $_POST['mdp']){
				$errors['mdp']="Le mot de passe saisi est incorrecte";
			}
		}else{
			$errors['login']="Le pseudo saisi est incorrecte";
		}
		
		//acces au compte de l'utilisateur
		if(empty($errors)){
			$_SESSION ['auth']['login']= $log['login'];
			$_SESSION ['auth']['idUser']=$log['idUtil'];
			$_SESSION ['auth']['bio']=$log['bio'];
			$_SESSION ['auth']['mail']=$log['mail'];
			$_SESSION['flash']['success'] = "Vous êtes maintenant connecté";
			header('Location:account.php');
		}
		/*if (password_verify($_POST['mdp'], $log->password)){
			
			$_SESSION ['auth']= $log['login'];
			$_SESSION['flash']['success'] = "Vous êtes maintenant connecté";
			header('Location:account.php');
			exit();
		} else {
			$_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrecte';
		}*/
		
	}

 require '/inc/header.php';
 ?>

	<h1> Se connecter </h1>
	
	<?php if (!empty($errors)):?>
	<div class="alert alert-danger">
		<p>Erreurs </p>
		<ul>
		<?php foreach ($errors as $error):?>
			<!--liste de erreur trouver-->
			<li><?=$error; ?></li>

		<?php endforeach;?>
		</ul>
	</div>

	 <?php endif;//fin message erreur?>
	
	<!-- Message de confirmation d'inscription -->
	<?php if (!empty($_SESSION['flash']['inscription'])):?>
	
	<div class="alert alert-success">
	
		<?php echo "<p>".$_SESSION['flash']['inscription']."</p>";
			 unset($_SESSION['flash']['inscription']); 
		?> 
		
	</div>

	<?php endif;// fin message d'inscription?>

	
	<!-- Formulaire de connection -->
	<form action= "" method="POST">
	
		<div class="form-group">
		
			<label for="">Pseudo ou email:</label></br>
			<input type="text" name="login" class="form-control" required></br>
			
			
		</div>
			
		<div class="form-group">
			
			<label for="">Mot de Passe:</label></br>
			<input type="password" name="mdp" class="form-control" required></br>
			
		</div>
		
		<button type = "submit" class="btn btn-primary">Connexion</button>
	</form>		
	
<?php require '/inc/footer.php';?>