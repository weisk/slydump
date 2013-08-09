<?php
	require_once("const.php");

	function getFavoriteShows() {		
		$sql = "SELECT * FROM `favshows` WHERE 1 ORDER BY ID";
		return getJSON($sql);
	}

	function getJSON($query) {
		$host = DBHOST;
		$ddbb = DBNAME;
		$user = DBUSER;
		$pass = DBPASS;

		$conn = mysql_connect($host,$user,$pass);
		mysql_select_db($ddbb, $conn);
		$result = mysql_query($query,$conn);
		$rows = array();
		while($data = mysql_fetch_array($result)) { $rows[] = $data; }
		mysql_close($conn);
		return json_encode($rows);
	}

	$op = $_GET['action'];
	switch ($op) {		
		case 'favoriteShows':
			$r = getFavoriteShows();
			echo $r;
			break;
		default: 
			break;
	}
?>