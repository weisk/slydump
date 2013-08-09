<?php

	session_start();
	require_once("inc/const.php");
	require_once("inc/common.php");

	$op = $_GET['a'];
	switch ($op) {
		case 'echo':
			echo "WE ARE IN TEST.php";
			echo "<br/><br/><br/>";
			break;
		case 'test1':
			if(strpos($_SERVER["HTTP_HOST"],"atspace")>-1) echo "WE ARE ATSPACE";
			else if (strpos($_SERVER["HTTP_HOST"],"192.168.1.62:86")>-1) echo "WE ARE LOCAL";
			break;
		case 'test2':
			echo "CONSTANT DBNAME: ".DBNAME."<br/>";
			echo "CONSTANT DBHOST: ".DBHOST."<br/>";
			echo "CONSTANT DBUSER: ".DBUSER."<br/>";
			echo "CONSTANT DBPASS: ".DBPASS."<br/>";
			echo "CONSTANT EXPTIME: ".EXPTIME;
			break;
		case 'test3':
			echo "<table><tr><td>TIME:</td><td>".time()."</td></tr>";
			echo "<tr><td>ATEXP:</td><td>".$_SESSION["atexp"]."</td></tr>";
			echo "<tr><td>UTEXP:</td><td>".$_SESSION["utexp"]."</td></tr></table>";
			if(expired_at()) echo "<br/>AT HAS EXPIRED!";
			if(expired_ut()) echo "<br/>UT HAS EXPIRED!";
			break;
		case 'gethost':
			echo gethostname();
			break;
		case 'unsetall':
			session_unset();
			session_destroy();
			echo "OK";
			break;
		case 'printall':
			echo "SESSION: <br/>";
			var_dump($_SESSION);
			echo "<br/><br/><br/>GET: <br/>";
			var_dump($_GET);
			echo "<br/><br/><br/>POST: <br/>";
			var_dump($_POST);
			echo "<br/><br/><br/>COOKIE: <br/>";
			var_dump($_COOKIE);
			echo "<br/><br/><br/>session_id(): <br/>";
			echo session_id();
			echo "<br/><br/><br/>time(): <br/>";
			echo time();
			echo "<br/><br/><br/>SERVER: <br/>";
			var_dump($_SERVER);
			break;
		case 'getat':
			if(expired_at()) getat();
			var_dump($_SESSION['at']);
			break;
		case 'postut':
			if(expired_at()) getat();
			if(expired_ut()) postut($_SESSION['at']);
			var_dump($_SESSION['ut']);
			break;
		case 'geturl':
			$r = geturl($_GET['url'],1);
			echo $r;
			break;
		case 'expired':
			echo "<br/>AT EXPIRED? ".expired_at();
			echo "<br/>UT EXPIRED? ".expired_ut();
			break;
		default:
			echo "<h3>ACTIONS ( _GET [ 'a' ] ) :</h3><br/>";
			echo "<ul><li>echo</li><li>gethost</li><li>printall</li><li>unsetall</li><li>getat</li><li>getut</li><li>postut</li><li>geturl ( _GET [ 'url' ] )</li></ul>";
			break;
	}
?>