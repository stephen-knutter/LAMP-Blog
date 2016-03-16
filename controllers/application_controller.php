<?php
	class ApplicationCtrl{
		
		function remove_whitespace($link){
			$link = strtolower($link);
			$link = preg_replace('/\s+/', '-', $link);
			$link = preg_replace('/\&amp\;/', 'and', $link);
			$link = preg_replace('/\&/', 'and', $link);
			$link = preg_replace('/\?/', '', $link);
			$link = preg_replace('/\'/', '', $link);
			$link = preg_replace('/\&39\;/', '', $link);
			return $link;
		}
		
		function is_logged_in(){
			if(isset($_SESSION['logged_in_user'])){
				header('Location: https://www.budvibes.com/'.$_SESSION['logged_in_user']);
				exit();
			}
		}
		
		/* !!! FOR PRODUCTION ONLY !!! 
		function check_url(){
			$redirect_url = false;
			$uri = $_SERVER['REQUEST_URI'];
			$host = $_SERVER['HTTP_HOST'];
			if( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != off) || $_SERVER['SERVER_PORT'] == 443){
				//CHECK FOR WWW
				if(!preg_match('/www\./', $host)){
					$redirect_url = 'https://www.'.$host;
					if(!empty($uri)){
						$redirect_url .= $uri;
					}
				}
			} else {
				//REDIRECT TO HTTPS://WWW	
				if(preg_match('/www\./', $host)){
					$redirect_url = 'https://'.$host;
				} else {
					$redirect_url = 'https://www.'.$host;
				}
				if(!empty($uri)){
					$redirect_url .= $uri;
				}
			}
			
			if($redirect_url){
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: '.$redirect_url);
				exit();
			}
		}
		*/
	}
?>


