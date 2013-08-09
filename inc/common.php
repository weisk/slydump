<?php

	function getat() {
		$url = "http://api.series.ly/v2/auth_token/?id_api=".IDAPI."&secret=".SECRET;
		$at = json_decode(geturl($url,1),TRUE);
		$_SESSION['at'] = $at["auth_token"];
		$_SESSION['atexp'] = $at["auth_expires_date"];
		return $at;
	}

	function postut($at) {
		$url = "http://api.series.ly/v2/user/user_login";
		$parms = "auth_token=".$at."&redirect_url=xxx&username=".SLYUSER."&password=".SLYPASS."&remember=0";			
		$ut = getredir($url."?".$parms,1);
		$s2 = preg_split('<\?\s*?>',$ut);
		$s3 = preg_split('<&\s*?>',$s2[1]);
		$s4 = preg_split('<=\s*?>',$s3[0]);
		$s5 = preg_split('<=\s*?>',$s3[1]);
		$_SESSION['ut'] = $s4[1];
		$_SESSION['utexp'] = $s5[1];
		return $ut;
	}

	function expired_at() { return !( isset($_SESSION['at']) && ($_SESSION['atexp']>time()) ) ; }
	function expired_ut() { return !( isset($_SESSION['ut']) && ($_SESSION['utexp']>time()) ) ; }

	function getut($at,$link) {
		$redir = "http://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"];
		$url = "http://api.series.ly/v2/user/user_login?auth_token=".$at."&redirect_url=".$redir;
		
		if(isset($link))	$url .= "?link=".$link;
		else 				$url .= "?action=getut";
		header('Location: '.$url);
	}

	function geturl ($url,$tries) {
		$i = 0;
		while($i<$tries) {
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_HEADER, false);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($c, CURLOPT_FOLLOWLOCATION, false);
			//curl_setopt($c, CURLINFO_HEADER_OUT, true);
			//$log = fopen("httplog.log","w");
			//curl_setopt($c, CURLOPT_VERBOSE, true);
	 		//curl_setopt($c, CURLOPT_STDERR, $log);
			//fclose($log);
			$r = curl_exec($c);
			//$info = curl_getinfo($c);
			//return $info;			
			curl_close($c);
			if(strlen($r)>2) return $r;
			$i++;
		}
		return "E1";
	}
	
	function posturl ($url,$parms) {
  		$c = curl_init($url);
 		curl_setopt($c, CURLOPT_POST, true);
 		curl_setopt($c, CURLOPT_POSTFIELDS, $parms);
 		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
 		$r = curl_exec($c);
		curl_close($c);
		return $r;
 		if(strlen($r)>2)	return $r;
 		else 				return "E1";
	}

	function getredir($url,$tries) {
		$i = 0;
		while($i<$tries) {
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_HEADER, true);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($c, CURLOPT_FOLLOWLOCATION, false);
			$r = curl_exec($c);
			curl_close($c);
			if(preg_match('#Location: (.*)#', $r, $a)) {
				$loc = trim($a[1]);
				return $loc;
			}
			$i++;
		}
		return "E1";
	}

	function GetIP() { 
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 										$ip = getenv("HTTP_CLIENT_IP"); 
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))						$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 										$ip = getenv("REMOTE_ADDR"); 
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))	$ip = $_SERVER['REMOTE_ADDR']; 
		else 																													$ip = "unknown";
		return($ip); 
	}

	function NewConn() {		
		$register_globals = (bool) ini_get('register_gobals'); 
		if ($register_globals) $ip = getenv('REMOTE_ADDR'); 
		else $ip = GetIP(); 
		$rem_port = $_SERVER['REMOTE_PORT']; 
		$rem_host = $_SERVER['REMOTE_HOST'];
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$rqst_method = $_SERVER['METHOD']; 	
		$referer = $_SERVER['HTTP_REFERER']; 
		$lang=$_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$query=$_SERVER['QUERY_STRING'];
		$user=$_SESSION['user'];
		$date=date("YmdHis");
		$parms = array($ip,$rem_port,$date,$user,$lang,$user_agent,$rem_host,$rqst_method,$referer,$query);
		return $parms;		
	}



?>