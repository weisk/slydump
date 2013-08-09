<?php

class logmein {

	var $dbhost = DBHOST;
	var $dbname = DBNAME;
	var	$dbuser = DBUSER;
	var	$dbpass = DBPASS;

	var $user_table = 'logon';
	var $user_column = 'email';
	var $pass_column = 'pass';

	var $encrypt = false;
	var $secret = "Lafrasellargaquesiemfaradesecret";

	var $conn = '';

	function dbconnect() {
		$connections = mysql_connect($this->dbhost,$this->dbuser,$this->dbpass) or die ('Unable to connect to the database');
		mysql_select_db($this->dbname) or die('Unable to select database');
		return $connections;
	}

	function login($user_table,$user,$pass) {
		//if($this->encrypt==true) { $pass = hash_hmac('sha256',$pass,$this->secret); }
		$result = $this->qry("SELECT * FROM ".$this->user_table." WHERE ".$this->user_column."='?' AND ".$this->pass_column." = '?' AND enabled=1;", $user,$pass);		
		$row=mysql_fetch_assoc($result);
		if($row != "Error"){
			if($row[$this->user_column] !="" && $row[$this->pass_column] !=""){
				$_SESSION['logged'] = true;
				$_SESSION['user'] = $user;
				$this->increment_logins($user);
				mysql_close($this->conn);
				return true;
			}
		}
		mysql_close($this->conn);
		return false;
	}

	function increment_logins($user) {
		$result = $this->qry("UPDATE ".$this->user_table." SET totallogins=totallogins+1 WHERE ".$this->user_column."='?'", $user);
		return;
	}

	function increment_visits($user) {
		$result = $this->qry("UPDATE ".$this->user_table." SET totalvisits=totalvisits+1 WHERE ".$this->user_column."='?'", $user);
		return;
	}

	function log_connection($parms) {
		$sql = "INSERT INTO conn (ip,ts,user,lang,user_agent,rem_host,rqst_method,referer,query) VALUES ('?','?','?','?','?','?','?','?','?')";
		$ip = $parms[0].":".$parms[1];
		$result = $this->qry($sql,$ip,$parms[2],$parms[3],$parms[4],$parms[5],$parms[6],$parms[7],$parms[8],$parms[9]);
		return;
	}

	function qry($query) {
		$this->conn = $this->dbconnect();
		$args = func_get_args();
		$query = array_shift($args);
		$query = str_replace("?","%s",$query);
		$args = array_map('mysql_real_escape_string',$args);
		array_unshift($args,$query);
		$query = call_user_func_array('sprintf',$args);
		$result = mysql_query($query) or die(mysql_error());
		if($result) return $result;
		return "qry Escape Error";
	}

	function logout() { 
		session_unset();
		session_destroy();
		return;
	}

	function logincheck($logincode,$user_table,$pass_column,$user_column) {
		$this->dbconnect();

		if($this->pass_column=="") { $this->pass_column = $pass_column; }
		if($this->user_column=="") { $this->user_column = $user_column; }
		if($this->user_table=="") { $this->user_table = $user_table; }

		$result = $this->qry("SELECT * FROM ".$this->user_table." WHERE ".$this->pass_column." = '?';" , $logincode);
		$row = mysql_fetch_assoc($result);
		$rownum = mysql_num_rows($result);

		if($row != "Error") { if($rownum > 0) return true; }
		return false;
	}

	function passwordreset($username, $user_table, $pass_column, $user_column){
		$this->dbconnect();

		$newpassword = $this->createPassword();

		if($this->pass_column == "") { $this->pass_column = $pass_column; }
		if($this->user_column == "") { $this->user_column = $user_column; }
		if($this->user_table == "") { $this->user_table = $user_table; }

		if($this->encrypt == true) { $newpassword_db = md5($newpassword); }
		else { $newpassword_db = $newpassword; }
 

		$qry = "UPDATE ".$this->user_table." SET ".$this->pass_column."='".$newpassword_db."' WHERE ".$this->user_column."='".stripslashes($username)."'";
		$result = mysql_query($qry) or die(mysql_error());

		$to = stripslashes($username);		
		$illegals=array("%0A","%0D","%0a","%0d","bcc:","Content-Type","BCC:","Bcc:","Cc:","CC:","TO:","To:","cc:","to:");
		$to = str_replace($illegals,"",$to);
		$getemail = explode("@",$to);
		if(sizeof($getemail)==2) {
			$from = $_SERVER['SERVER_NAME'];
			$subject = "Password Reset: ".$_SERVER['SERVER_NAME'];
			$msg = "Your new password is: ".$newpassword."";
			$headers = "MIME-Version: 1.0 rn" ;
			$headers .= "Content-Type: text/html; \r\n" ;
			$headers .= "From: $from  \r\n";
			$sent = mail($to,$subject,$msg,$headers);
			if($sent) return true;
		}
		return false;
	}

	function createPassword() {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}

	function loginform($formname,$formclass,$formaction){
        echo '
			<form name="'.$formname.'" method="post" id="'.$formname.'" class="'.$formclass.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
				<div>
					<label for="username">Username</label>
					<input name="username" id="username" type="text">
				</div>
				<div>
					<label for="password">Password</label>
					<input name="password" id="password" type="password">
				</div>
				<input name="action" id="action" value="login" type="hidden">
				<div><input name="submit" id="submit" value="Login" type="submit"></div>
			</form>
		';
	}

	function resetform($formname,$formclass,$formaction){
		echo '
			<form name="'.$formname.'" method="post" id="'.$formname.'" class="'.$formclass.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
				<div>
					<label for="username">Username</label>
					<input name="username" id="username" type="text">
				</div>
				<input name="action" id="action" value="resetlogin" type="hidden">
				<div><input name="submit" id="submit" value="Reset Password" type="submit"></div>
			</form>
		';
	}
 

}
?>