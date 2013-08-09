<?php
	session_start();
	require_once("const.php");
	require_once("login.php");
	
	if(isset($_POST['login'])&&isset($_POST['user'])&&isset($_POST['pass'])) {
		$log = new logmein();
		$log->encrypt = true;
		$log->login("logon", $_POST['user'], $_POST['pass']);
	}

	if(isset($_POST['logout'])) { 
		$log = new logmein();
		$log->logout();
	}

	if(isset($_SESSION['logged'])&&isset($_SESSION['user'])) {
		$log = new logmein();
		$log->increment_visits($_SESSION['user']);
	}
	

?>