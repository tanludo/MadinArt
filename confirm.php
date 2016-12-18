<?php

	session_start();
	$_SESSION['auth'] = $log;
	header('Location:login.php');