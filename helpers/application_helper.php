<?php
	session_start();
	

	class ApplicationHelper{
		
		private $user_id;
		private $username;
		
		public function log_in($user){
			#SET SESSIONS
			$_SESSION['logged_in_id'] = $user['id'];
			$_SESSION['logged_in_user'] = $user['username'];
			$_SESSION['logged_in_photo'] = $user['profile_pic'];
			$_SESSION['store_id'] = $user['store_id'];
			$_SESSION['store_reg'] = $user['store_reg'];
			$_SESSION['store_state'] = $user['store_state'];
			$_SESSION['user_verified'] = $user['verified'];
			if($user['type'] == 'store'){
					$_SESSION['store'] = true;
				} else {
					$_SESSION['store'] = false;
			}
			
			#SET COOKIES - 30Day Expiration
			$cook_id = $this->obfuscate_link($row['user_id']);
			$cook_store_id = $this->obfuscate_link($row['store_id']);
		
			setcookie('logged_in_id', $cook_id, time() + (60 * 60 * 24 * 30));
			setcookie('logged_in_user', $user['username'], time() + (60 * 60 * 24 * 30));
			setcookie('logged_in_photo', $user['profile_pic'], time() + (60 * 60 * 24 * 30));
			setcookie('store_id', $cook_store_id, time() + (60 * 60 * 24 * 30));
			setcookie('store_reg', $user['store_reg'], time() + (60 * 60 * 24 * 30));
			setcookie('store_state', $user['store_state'], time() + (60 * 60 * 24 * 30));
			setcookie('user_verified', $user['verified'], time() + (60 * 60 * 24 * 30));
			setcookie('store', $_SESSION['store'], time() + (60 * 60 * 24 * 30));
			
		}
		
		public function check_session(){
			if(!isset($_SESSION['logged_in_id'])){
				if(isset($_COOKIE['logged_in_id']) && isset($_COOKIE['logged_in_user']) && isset($_COOKIE['user_verified']) 
				&& isset($_COOKIE['logged_in_photo']) && isset($_COOKIE['store']) && isset($_COOKIE['store_id']) && isset($_COOKIE['store_reg']) && isset($_COOKIE['store_state'])){
					$cookie_id = unobfuscate_link($_COOKIE['logged_in_id']);
					$cookie_id = $cookie_id[1];
					$_SESSION['logged_in_id'] = $cookie_id;
					$cookie_store_id = unobfuscate_link($_COOKIE['store_id']);
					$cookie_store_id = $cookie_store_id[1];
					$_SESSION['store_id'] = $cookie_store_id;
					$_SESSION['store_reg'] = $_COOKIE['store_reg'];
					$_SESSION['store_state'] = $_COOKIE['store_state'];
					$_SESSION['logged_in_user'] = $_COOKIE['logged_in_user'];
					$_SESSION['user_verified'] = $_COOKIE['user_verified'];
					$_SESSION['logged_in_photo'] = $_COOKIE['logged_in_photo'];
					$_SESSION['store'] = $_COOKIE['store'];
				}
			}
		}
		
	}
	
?>