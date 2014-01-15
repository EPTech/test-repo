<?php

function getcheckdetails($user,$password) {
	//echo 'country code : '.$countrycode;
	$desencrypt = new DESEncryption();
	$key = $user; //"mantraa360";
	$cipher_password = $desencrypt->des($key, $password, 1, 0, null,null);
	$str_cipher_password = $desencrypt->stringToHex ($cipher_password);
	
	$label = "";
	$table_filter = " where username='".$user."' and password='".$str_cipher_password."'";
	
	$query = "select * from userdata".$table_filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_affected_rows();
	//echo ' num rows :'.$numrows;
	$dbobject = new dbobject();
	$no_of_pin_misses = $dbobject->getitemlabel('parameter','parameter_name','no_of_pin_misses','parameter_value');
	$pin_missed = $dbobject->getitemlabel('userdata','username',$user,'pin_missed');
	$override_wh = $dbobject->getitemlabel('userdata','username',$user,'override_wh');
	$extend_wh = $dbobject->getitemlabel('userdata','username',$user,'extend_wh');
	
	if($numrows > 0){
		@ $ddate = date('w');
		$row = mysql_fetch_array($result);
		
		@ $dhrmin = date('Hi');
		$worktime = $dbobject->getitemlabel('parameter','parameter_name','working_hours','parameter_value');
		//echo $dhrmin;
		if($override_wh=='1'){
		$worktime = $extend_wh;
		}
		$worktimesplit = explode("-",$worktime);
		$lowertime = str_replace(":","",$worktimesplit[0]);
		$uppertime = str_replace(":","",$worktimesplit[1]);
		
		$lowerstatus = ($lowertime < $dhrmin)==''?"0":"1";
		$upperstatus = ($dhrmin < $uppertime)==''?"0":"1";

                
		$pass_dateexpire = $row['pass_dateexpire'];
		@$expiration_date = strtotime($pass_dateexpire);
		@$today = date('Y-m-d');
		@$today_date = strtotime($today);
		
		//echo 'exp date: '.$pass_dateexpire.'   -  today date: '.$today;
		//echo 'Change on Logon : '.$row['passchg_logon'];
		
		if($row['user_disabled']=='1'){
			$label = "2";
		}
		else if($row['user_locked']=='1'){
			$label = "3";
		}
		else if($row['day_1']=='0' && $ddate=='0'){
			//You are not allowed to login on Sunday
			$label = "4";
		}
		else if($row['day_2']=='0' && $ddate=='1'){
			//You are not allowed to login on Monday
			$label = "5";
		}
		else if($row['day_3']=='0' && $ddate=='2'){
			//You are not allowed to login on Tuesday
			$label = "6";
		}
		else if($row['day_4']=='0' && $ddate=='3'){
			//You are not allowed to login on Wednesday
			$label = "7";
		}
		else if($row['day_5']=='0' && $ddate=='4'){
			//You are not allowed to login on Thursday
			$label = "8";
		}
		else if($row['day_6']=='0' && $ddate=='5'){
			//You are not allowed to login on Friday
			$label = "9";
		}
		else if($row['day_7']=='0' && $ddate=='6'){
			//You are not allowed to login on Saturday
			$label = "10";
		}
		else if(!(($lowerstatus==1) && ($upperstatus==1))){
			//You are not allowed to login due to working hours violation
			$label = "11";
		}
		else if($expiration_date <=$today_date){
			$label = "13";
		}
		else if($row['passchg_logon']=='1'){
			$label = "14";
		}
		else {
			$label = "1";
			$_SESSION[username_sess] = $user;
			$_SESSION[role_id_sess] = $row['role_id'];
			$_SESSION[role_name_sess] = $row['role_name'];
			$_SESSION[branch_code_sess] = $row['branch_code'];
			$_SESSION[firstname_sess] = $row['firstname'];
			$_SESSION[lastname_sess] = $row['lastname'];
			$_SESSION[acct_no_sess] = $row['acct_no'];
			
			$_SESSION['agent_login'] = "OK";
			$_SESSION['last_page_load'] = time();
			$_SESSION['processor'] = $user;
			$oper="IN";
			//$audit = $dbobject->doAuditTrail($oper);
			$dbobject->resetpinmissed($user);
		}
		//$label = $user.'|'.$row['role_id'].'|'.$row['role_name'].'|'.$row['branch_code'].'|'.$row['firstname'].'|'.$row['lastname'];
	}else{
		if($no_of_pin_misses==$pin_missed){
			$label = "12";
			$dbobject->updateuserlock($user,'1');
		}else{
		$label = "0";
		$dbobject->updatepinmissed($user);
		}		
	}
	return $label;
	}

?>
