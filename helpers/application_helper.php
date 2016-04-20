<?php
	session_start();
	require dirname(__DIR__) . '/vendor/autoload.php';
	

	class ApplicationHelper{
		
		private $user_id;
		private $username;
		
		public function __construct(){
			//$_SESSION['authenticity'] = $this->createToken();
		}
		
		public function obfuscateLink($file_id,$type='documents'){
			$temp = array(date("jmY"), $file_id, $type); // using date("jmY") ensures download links are specific to each day
			$temp = serialize($temp);
			$temp = base64_encode($temp);
			$link = rawurlencode($temp);
			return $link;
		}
		
		public function unobfuscateLink($link) {
			$temp = rawurldecode($link);
			$temp = base64_decode($temp);
			$download_array = unserialize($temp);
			return $download_array;
		}
		
		public function createToken(){
			$token = md5(uniqid(rand(), true));
			return $token;
		}
		
		public function fileGetContentscUrl($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
			$data = curl_exec($ch);
			curl_close($ch);
		
			return $data;
		}
		
		public function createnofollow($str){
			return preg_replace_callback(
				"#(<a.*?>)#i",
				create_function('$matches', 'return $this->adjustLink($matches[1]);'),
				$str);
		}
		
		public function adjustLink($input){
			//retrieve the whitelist from the config file
			//$whitelist = $GLOBALS['whitelist'];
			$whitelist = array(__LOCATION__);

			// if the link in $input already contains ref="nofollow", return it as it is
			if (preg_match('#rel\s*?=\s*?[\'"]?.*?nofollow.*?[\'"]?#i', $input)) {
				return $input;   
			}

			// extract the URL from $input
			preg_match('#href\s*?=\s*?[\'"]?([^\'"]*)[\'"]?#i', $input, $captures);

			// $href will contain the extracted URL, such as http://seophp.example.com
			$href = $captures[1];

			// if URL doesn't contain http://, assume it's a local link
			if (!preg_match('#^\s*http://#', $href)){
				return strip_tags($input);
			}

			// extract the host name of the URL, such as seophp.example.com
			$parsed = parse_url($href);
			$host = $parsed['host'];

			// if the URL is in the whitelist, send $input back as it is
			if (in_array($host, $whitelist)) {
				return $input;
			} 

			// assuming the URL already has a rel attribute, change its value to nofollow
			$x = preg_replace('#(rel\s*=\s*([\'"]?))((?(3)[^\'"]*|[^\'" ]*))([\'"]?)#i', 
	             '\\1\\3,nofollow\\4', $input);

			// if the string has been modified, it means it already had a rel attribute,
			// whose value has been changed to nofollow, so we return the new version
			if ($x != $input) {
				return $x;
			}  else {
				return preg_replace('#<a#i', '<a rel="nofollow" target="_blank"', $input);
			}
	
		}
		
		public function sanitizeInput($input, $type='', 
						$allowed_tags_1 = array('<h1>', '<h2>', '<b>', '<i>', '<a>', '<ul>', '<li>', '<pre>', '<div>', '<p>', '<hr>', '<blockquote>', '<img>'), 
						$allowed_tags_2 = array('<h1>', '<h2>', '<b>', '<i>', '<a>', '<ul>', '<li>', '<pre>', '<div>', '<p>', '<hr>', '<blockquote>', '<img>', '<iframe>')){
			if($type == 'forum'){
				$allowed_tags = $allowed_tags_2;
			} else {
				$allowed_tags = $allowed_tags_1;
			}
			$allowed_html = implode('', $allowed_tags);
			$input = strip_tags($input, $allowed_html);
		
			//IF FORUM POST; STRIP OUT NON-MATCHING IFRAMES ONE BY ONE
			if($type == 'forum'){
				preg_match_all("/<iframe src=\"([^\"]*)\"/", $input, $output_array);
			
				foreach($output_array[1] as $key=>$value){
					$cur_frame = '<iframe src="'.$value.'">';
					if(preg_match('/<iframe src="https:\/\/\www.youtube.com"/', $cur_frame)){
						//DO NOTHING
					}else if(preg_match('/<iframe src="https:\/\/\www.vimeo.com"/', $cur_frame)){
						//DO NOTHING
					}else if(preg_match('/<iframe src="https:\/\/\www.vine.co"/', $cur_frame)){
						//DO NOTHING
					} else {
						$input = preg_replace('"'.$cur_frame.'"', '', $input);
					}
				}
				
			}
			
			//return preg_replace('#<(.*?)>#ise', "'<' . removeAttributes('\\1') . '>'", $input);
			$bad_attr = 'onclick|onerror|onmousemove|onmouseout|onmouseover|onkeypress|onkeydown|onkeyup|javascript:';
			return preg_replace_callback('#<(.*?)>#is',
										 function($m){
											return "'<' . stripslashes(preg_replace(#($bad_attr)(\s*)(?==)#is, 'NOPE', $m[1])) . '>'";
										 },
										 $input);
		}
		
		public function removeAttributes($input){
			$bad_attr = 'onclick|onerror|onmousemove|onmouseout|onmouseover|onkeypress|onkeydown|onkeyup|javascript:';
			return stripslashes(preg_replace("#($bad_attr)(\s*)(?==)#is", 'NOPE', $input));
		}
		
		public function getPhoto($path){
			$photo = strrchr($path,'/');
			$photo = substr($photo,1);
			return $photo;
		}
		
		public function getExtension($item){
				return strtolower(strrchr($item,'.'));
		}
		
		public function cropPhoto($path,$photo){
			$feedPhoto = new \bv\resize($path.$photo);
			$feedPhoto->resizeImage(516,516);
			$feedPhoto->saveImage($path.'feed-'.$photo,80);
				
			$mobilePhoto = new \bv\resize($path.$photo);
			$mobilePhoto->resizeImage(320,320);
			$mobilePhoto->saveImage($path.'mobile-'.$photo,80);
				
			$panePhoto = new \bv\resize($path.$photo);
			$panePhoto->resizeImage(280,280);
			$panePhoto->saveImage($path.'pane-'.$photo,80);
				
			$smallPhoto = new \bv\resize($path.$photo);
			$smallPhoto->resizeImage(60,60,'exact');
			$smallPhoto->saveImage($path.'small-'.$photo,80);
			
			if(file_exists($path.'feed-'.$photo) &&
			   file_exists($path.'mobile-'.$photo) &&
			   file_exists($path.'pane-'.$photo) && 
			   file_exists($path.'small-'.$photo)){
					return true;
			} else {
					return false; 
			}
		}
		
		public function isLoggedIn(){
			return isset($_SESSION['logged_in_id']) ? true : false;
		}

		public function logIn($user){
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
			$cook_id = $this->obfuscateLink($user['id']);
			$cook_store_id = $this->obfuscateLink($user['store_id']);
		
			setcookie('logged_in_id', $cook_id, time() + (60 * 60 * 24 * 30));
			setcookie('logged_in_user', $user['username'], time() + (60 * 60 * 24 * 30));
			setcookie('logged_in_photo', $user['profile_pic'], time() + (60 * 60 * 24 * 30));
			setcookie('store_id', $cook_store_id, time() + (60 * 60 * 24 * 30));
			setcookie('store_reg', $user['store_reg'], time() + (60 * 60 * 24 * 30));
			setcookie('store_state', $user['store_state'], time() + (60 * 60 * 24 * 30));
			setcookie('user_verified', $user['verified'], time() + (60 * 60 * 24 * 30));
			setcookie('store', $_SESSION['store'], time() + (60 * 60 * 24 * 30));
		}
		
		public function checkSession(){
			if(!isset($_SESSION['logged_in_id'])){
				if(isset($_COOKIE['logged_in_id']) && isset($_COOKIE['logged_in_user']) && isset($_COOKIE['user_verified']) 
				&& isset($_COOKIE['logged_in_photo']) && isset($_COOKIE['store']) && isset($_COOKIE['store_id']) && isset($_COOKIE['store_reg']) && isset($_COOKIE['store_state'])){
					$cookie_id = unobfuscateLink($_COOKIE['logged_in_id']);
					$cookie_id = $cookie_id[1];
					$_SESSION['logged_in_id'] = $cookie_id;
					$cookie_store_id = unobfuscateLink($_COOKIE['store_id']);
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
	
	}//END APPLICATION HELPER CLASS
	
?>