<?php
require_once '/inc/functions.php';
//connexion à la base de donnée
require '/inc/db.php';
//verification si données postées
if (!empty($_POST)){

	$errors = array();

	//Verification des données entrées
	// login:Soit la variable est vide
	//soit elle ne correspond pas à l'expression réguliere
	//que des carac compris entre a et z et 0 et 9, avec underscore autorisée
	if(empty($_POST['login'])|| !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['login'])){

		$errors['login'] = "Votre pseudo n'est pas valide";

	} else {
		$req = $dbh->prepare('SELECT idUtil FROM users WHERE login=?');

		$req->execute([$_POST['login']]);

		$log = $req-> fetch();
		if($log){
			$errors['login'] = "Ce pseudo est déjà pris";
		}


	}
	//test de conformiter du mail
	if(empty($_POST['mail']) || !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){

		$errors['mail'] = "Votre email n'est pas valide";

	} else {
		//require_once '/inc/db.php';
		//vérificationde son existance dans ma bdd
		$req = $dbh->prepare('SELECT idUtil FROM users WHERE mail=?');

		$req->execute([$_POST['mail']]);

		$log = $req-> fetch();
		if($log){
			$errors['mail'] = "Cet email est déjà utilisé";
		}
	}

	//test de conformiter du mot de passe
	if(!empty($_POST['mdp']) && !empty($_POST['ConfMdp']) ){
		
		if($_POST['mdp']!= $_POST['ConfMdp'] ){
		 $errors['password']= "Les mots de passe ne correspondent pas ";
		}
		
	}else {
		$errors['password']= " Mot de passe non renseigner";
	}
		
	
	//test de confirmité du choix des bouton radio (organisme ou artiste)
	if (empty($_POST['choix'])){
		$errors['choix']="Veuillez cocher votre statut";
	}
	else if($_POST['choix']== "orga"){
		if(empty($_POST['nomOrga']) || empty($_POST['adressOrga']) || empty($_POST['activiteOrga']) ){
			$errors['champsOrga']= " Veuillez renseigner tous les champs de l'organisation";
		}
	}
	else
	{
		if(empty($_POST['nomArt'])|| empty($_POST['prenomArt'])){
			$errors['champsArtiste']="Veuillez renseigner tous les champs d'artiste";
		}
		
		//test de la catégorie 
		if(empty($_POST['choixCat'])){		
		$errors['checkliste']= " Cocher au moins une catégorie";
		}	
	}
	
	
	//vérifications des erreurs constatées
	if (empty($errors)){

		//pour éviter la casse, utilisation de requete preparer
		$req = $dbh-> prepare( "INSERT INTO users SET login= ?, password= ?, mail= ?");

		$req -> execute([$_POST['login'],$_POST['mdp'], $_POST['mail']]);
		
		//récupération des l'id de l'utilisateur créé
		$req2 = $dbh->prepare('SELECT idUtil FROM users WHERE login=? ');

		$req2-> execute([$_POST['login']]);

		$id = $req2-> fetch();

			
		//insertion des champs en fonction du statut choisi
		if($_POST['choix']== "orga")
		{
			$req=$dbh-> prepare( "INSERT INTO organisation SET idUtilOrg= ?, nomOrga= ?, activiteOrga= ?, adresseOrga= ?");
			$req -> execute([$id["idUtil"],$_POST['nomOrga'],$_POST['activiteOrga'], $_POST['adressOrga']]);
			
		}else{
			$req=$dbh-> prepare( "INSERT INTO artiste SET idUtilArt=?, nomArt= ?, prenomArt= ?");
			$req -> execute([$id["idUtil"],$_POST['nomArt'],$_POST['prenomArt']]);
			
			//insertion daans la base de donnéee des catégories checked
			$idsCat = $_POST['choixCat'];
			foreach($idsCat as $catChecked ){
				$req2=$dbh-> prepare( "INSERT INTO appartenir SET idUtilArt= ?, idCat= ?");
				$req2 -> execute([$id["idUtil"],$catChecked]);
			}
			
		}
		
		//création du répertoir de l'utilisateur
		//bool mkdir ( string $pathname [, int $mode = 0777 [, bool $recursive = false [, resource $context ]]] )
		//pathname = chemin du dossier ; Le mode par défaut est le mode 0777, ce qui correspond au maximum de droits possible
		mkdir("./profils/".$_POST['login']."/album", 0700,true);
		mkdir("./profils/".$_POST['login']."/album/images" ,0700,true);
		mkdir("./profils/".$_POST['login']."/album/videos", 0700,true);
		//die('Votre compte à bien été crée');
		$_SESSION['flash']['inscription']= 'Votre compte à bien été crée';
		header('Location:login.php');
	}
		
	//exit();
	//fermeture de la connexion a la base de donnée 
	//unset($dbh);
}
?>


<?php require '/inc/header.php';?>

<h1> S'inscrire </h1>



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

	<form action= "" method="POST">

		<div class="form-group">

			<label for="">Pseudo:</label></br>
			<input  type="text" name="login" class="form-control" /></br>

		</div>

		<div class="form-group">

			<label for="">Mail:</label></br>
			<input type="text" name="mail" class="form-control"/ ></br>

		</div>

		<div class="form-group">

			<label for="">Mot de Passe:</label></br>
			<input type="password" name="mdp" class="form-control"/></br>

		</div>
		
		<div class="form-group">

			<label for="">Confirmer mot de Passe:</label></br>
			<input type="password" name="ConfMdp" class="form-control"/></br>

		</div>
			
		
		<div class="form-group">
			<label for="">Type d'utilisateur:</label></br>
			<input id="radioOrga" type="radio" name="choix" value="orga"  onClick="afficher_cacher();" /> <label for="orga">Organasition</label>
			<input id="radioArt" type="radio" name="choix" value="art"  onClick="afficher_cacher();" /> <label for="art">Artiste</label>
		</div>
		
		<div id="champsOrga">
			<div class="form-group">

				<label for="">Nom organisation:</label></br>
				<input  type="text" name="nomOrga" class="form-control" /></br>

			</div>
			
			<div class="form-group">

				<label for="">Activité de l'oraganisation:</label></br>
				<input  type="text" name="activiteOrga" class="form-control" /></br>

			</div>
			
			<div class="form-group">

				<label for="">Adresse organisation:</label></br>
				<input  type="text" name="adressOrga" class="form-control" /></br>

			</div>
		</div>
		
		<div id="champsArt">
			<div class="form-group">

				<label for="">Nom artiste:</label></br>
				<input  type="text" name="nomArt" class="form-control" /></br>

			</div>
			
			<div class="form-group">

				<label for="">Prénom artiste:</label></br>
				<input  type="text" name="prenomArt" class="form-control" /></br>

			</div>
			
			<div class="form-group">
						
			<!--liste de catégorie d'artiste-->
				<h3 class="text-center">Vos domaines</h3>
					<div class="well" style="max-height: 300px;overflow: auto;">
						<ul class="list-group">
						<?php 
						$listCatA = $dbh->query('SELECT * FROM categorie_art');
						
						While($attribut=$listCatA->fetch()){					
						?>		
						
						<li class="list-group-item">
						  <label><input type="checkbox" class="checkbox-inline" name='choixCat[]' value="<?php echo $attribut['idCat'];?>" > <?php echo " ".$attribut['libelleCat'];?></label>
						</li>
						
						<?php } $listCatA->closecursor();?>
						
						</ul>
					</div>
			
			</div>	

		</div>
		
			
		
	</br><input type = "submit" class="btn btn-primary"></input>

	</form>
	
<script type="text/javascript">
	function afficher_cacher()
	{
		if(document.getElementById('radioOrga').checked){
			
			document.getElementById('champsOrga').style.display='block';
			document.getElementById('champsArt').style.display='none';
		}
	    else
		{
			document.getElementById('champsArt').style.display='block';
			document.getElementById('champsOrga').style.display='none';
		}	

	}


</script>
	
	
	
<?php require'/inc/footer.php'; ?>
