<?php
	session_start();
	require_once("const.php");
	require_once("common.php");

	function searchserie($at,$str) {
		$url = "http://api.series.ly/v2/search?auth_token=".$at."&q=".$str."&filter=1";
		return geturl($url,1);
	}

	function loadserie($at,$ids) {
		$url = "http://api.series.ly/v2/media/full_info?auth_token=".$at."&idm=".$ids."&mediaType=1";
		return geturl($url,1);
	}

	function getlinks($at,$idm,$type) {
		$url = "http://api.series.ly/v2/media/episode/links?auth_token=".$at."&idm=".$idm."&mediaType=".$type;
		return geturl($url,1);
	}

	function getlink($at,$ut,$link) {
		$url = $link."?auth_token=".$at."&user_token=".$ut;
		return getredir($url,1);
	}

	$op = $_GET['action'];
	switch ($op) {
		case 'search':
			switch($_GET['type']) {
				case 'serie':
					if(expired_at()) getat();
					$r = searchserie($_SESSION['at'],urlencode($_GET['str']));
					echo $r;
					break;
				default:
					break;
			}
			break;
		case 'load':
			switch($_GET['type']) {
				case 'serie':
					if(expired_at()) getat();
					$r = loadserie($_SESSION['at'],$_GET['ids']);
					echo $r;
					break;
				default:
					break;
			}
			break;
		case 'getlinks':			
			if(expired_at()) getat();
			$r = getlinks($_SESSION['at'],$_GET['idm'],$_GET['type']);
			echo $r;
			break;
		case 'getlink':
			if(isset($_SESSION['logged'])) {
				if(expired_at()) getat();
				if(expired_ut()) postut($_SESSION['at'],$_GET['link']);
				$r = getlink($_SESSION['at'],$_SESSION['ut'],$_GET['link']);
				echo $r;
			} else {
				echo "E2";
			}			
			break;
		default: 
			break;
	}
	

	
	

?>
