<?php

	/* Connexion à une base ODBC avec l'invocation de pilote */
	$dsn = 'mysql:dbname=man;host=localhost';
	$user = 'root';
	$password = '';

	try {
		$dbh = new PDO($dsn, $user, $password);
	} catch (PDOException $e) {
		echo 'Connexion échouée : ' . $e->getMessage();
	}