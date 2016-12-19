<?php
ini_set("display_errors",0);error_reporting(0);
require_once '/inc/functions.php';
//connexion à la base de donnée
require '/inc/db.php';
//verification si données postées
if (!empty($_POST)){
	$errors = array();
	
	
	if(empty($_POST['mail']) || !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){

		$errors['mail'] = "Votre email n'est pas valide";

	} 
	if(empty($_POST['titre'])){
		$errors['titre'] = "Le champs titre doit etre renseigner";
	}
	if(empty($_POST['description'])){
		$errors['descrip'] = "Le champs description n'est pas renseigner";
	}
	if(empty($errors)) {
		$mailUser= $_POST['mail'];
		$objet = $_POST['titre'];
		$msg = $_POST['description'];
		$dmd = $_POST['lstDmd'];
		
		//constitution des élélments de l'email
		//=====mail de destination (to)
		$mailDest = "tanasiludovic@gmail.com"; // Déclaration de l'adresse de destination(ex:MadinArtSupport@gmail.com).
		
		//filtre des serveur qui rencontrent des bogues
		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mailUser))
			{
				$passage_ligne = "\r\n";
			}
			else
			{
				$passage_ligne = "\n";
			}
		
		//==clé aléatoire de limite Boundary :  permet de séparer les différentes parties de notre e-mail
		$boundary = "-----=".md5(rand());
		
		//=====Création du header de l'e-mail.
		
		$header = "From: \"User\"<$mailUser>".$passage_ligne;
		$header .= "To: technicien <$mailDest> ".$passage_ligne;
		$header.= "Reply-to: \"RETOUR\" <$mailUser>".$passage_ligne; 
		$header.= "MIME-Version: 1.0".$passage_ligne; 
		$header.= "Content-Type: multipart/mixed; boundary=\"$boundary\"".$passage_ligne;
		$header.= $passage_ligne;
		
		
		//=====Déclaration des messages au format texte et au format HTML.
		// $message_txt = "Origine demande : Users".$passage_ligne." Type de demande : ".$dmd."\n Descriptif : ".$msg."".$passage_ligne;
		$message_html ="<!DOCTYPE html>
							<html lang='fr'>
							  <head>
								<meta charset='utf-8'>
							  </head>
							  <body>
								<h2>Signalment d'un incident</h2>
								<p>Réception de l'incident avec les éléments suivants :</p>
								<ul>
								  <li><strong>Origine demande</strong> : User </li>
								  <li><strong>Type de probleme</strong> : $dmd </li>
								  <li><strong>Descriptif</strong> : $msg </li>
								</ul>
							  </body>
						</html>";
		
		//=====Création du message(corp).
		//séparation effectuer avec le boudary(ouverture)
		$message = $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout du message au format texte.
		$message .= "Content-Type: text/html; charset='utf-8'".$passage_ligne;
		$message .= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_html.$passage_ligne;
		//fermeture du boudary(frontière)
		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			

		
			//=====Envoi de l'e-mail.
			//REMARQUE IMPORTANTE : j'utilise le serveur smtp du FAI déclarer dans le fichier php.ini de wamps
			if(mail($mailDest,$objet,$message,$header)){
			//==========
				$_POST['EnvoiMail']['succes']= "Votre mail à été envoyé";
				//throw new Exception('Le mail n\'as pas pu être envoyé');
			}
			else
			{
				$_POST['EnvoiMail']['echec']= "Votre mail n'as pas pu être envoyé";
			}
	}
}
		

 require '/inc/header.php';
 
 ?>

<h1> ENVOYER UNE DEMANDE </h1>



<?php if (!empty($errors)):?>
	<div class="alert alert-danger">
		<p>Vous n'avez pas rempli le formulaire correctement</p>
		<ul>
		<?php foreach ($errors as $error):?>
			<!--liste de erreur trouver-->
			<li><?=$error; ?></li>

		<?php endforeach;?>
		</ul>
	</div>

<?php endif;?>

<!-- Message de confirmation d'envoi du mail -->
	<?php if (!empty($_POST['EnvoiMail'])):?>
		<?php if (!empty($_POST['EnvoiMail']['succes'])):?>
			<div class="alert alert-success">
			
				<?php echo "<p>".$_POST['EnvoiMail']['succes']."</p>";
					 unset($_POST['EnvoiMail']['succes']); 
				?> 
				
			</div>
		<?php else:?>
			<div class="alert alert-danger">
				<?php echo "<p>".$_POST['EnvoiMail']['echec']."</p>";?>
			</div>
	
	<?php endif; 
		endif;// fin message résultat de l'envoi du mail ?>
	
	
	<form action= "" method="POST">

		<div class="form-group">

			<label for="">Ma demande concerne :</label></br>
			<select id="lsDmd" name="lstDmd" title="Sélectionnez le type de demande">
			<option value='Connexion' > Un problème de connexion</option>
			<option value='Bug' > Un bug</option>
			<option value='Autre' > Un autre probleme</option>
			</select>
		</div>

		<div class="form-group">

			<label for="">Titre :</label></br>
			<input type="text" name="titre" class="form-control"/ ></br>

		</div>

		<div class="form-group">

			<label for="">Adresse mail:</label></br>
			<h6>remarque: veillez à saisir une adresse valide<h6>
			<input type="text" name="mail" class="form-control" value ="<?php if (isset($_SESSION['auth'])): echo $_SESSION ['auth']['mail'] ; else: echo ""; endif;?>"/></br>

		</div>
		
		<div class="form-group">

			<label for="">Description:</label></br>
			<textarea type="textarea" name="description" class="form-control"></textarea></br>

		</div>
			
					
		
	</br><input type = "submit" class="btn btn-primary" value='Envoyer'></input>

	</form>
		
	
<?php require'/inc/footer.php'; ?>

