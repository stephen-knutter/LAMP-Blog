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
		
		public function calculateImageRatio($image){
			if(file_exists($image)){
				list($width,$height) = @getimagesize($image);
				if($width && $height){
					$ratio = $width/$height;
					return $ratio;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		public function createUrl($text){
			$text = strtolower($text);
			$text = preg_replace('/\&amp;/', 'and', $text);
			$text = preg_replace('/\&/', 'and', $text);
			$text = preg_replace('/\'/', '', $text);
			$text = preg_replace('/\&39;/', '', $text);
			$text = preg_replace('/\s+/','-',$text);
			return $text;
		}
		
		public function createToken(){
			$token = md5(uniqid(rand(), true));
			return $token;
		}
		
		public function checkWebsite($website){
			if(filter_var($website, FILTER_VALIDATE_URL)){
				$regex = "((https?|ftp)\:\/\/)?"; // SCHEME
				$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
				$regex .= "([a-z0-9-.]*)\.([a-z]{2,4})"; // Host or IP
				$regex .= "(\:[0-9]{2,5})?"; // Port
				$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
				$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
				$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
				
				if(preg_match("/^$regex$/",$website,$m)){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		public function checkPhoneNumber($phone){
			if(preg_match("/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/", $phone, $m)){
				return true;
			} else {
				return false;
			}
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
		
		public function getFileFromFilePath($path){
			$file = strrchr($path,'/');
			$file = substr($file,1);
			return $file;
		}
		
		public function getFileNameNoExtension($file){
			$dot = strpos($file,'.');
			$fileName = substr($file,0,$dot);
			return $fileName;
		}
		
		public function getExtension($item){
			return strtolower(strrchr($item,'.'));
		}
		
		public function addVideoPic($newVideo,$userId){
		  //$fileName = $this->getFileFromFilePath($newVideo);
	      $fileName = $this->getFileNameNoExtension($newVideo);
	      $newFileName = $fileName . '.jpg';
	      $vidPath = 'C:\\wamp\\www\\bv_mvc\\lamp-blog\\assets\\user-images\\'.$userId.'\\'.$fileName.'.mp4';
	      $picPath = 'C:\\wamp\\www\\bv_mvc\\lamp-blog\\assets\\user-images\\'.$userId.'\\'.$newFileName;
	      $output = array();
	      $cmd = "C:\\wamp\\ffmpeg\\ffmpeg64 -i $vidPath -an -ss 00:00:01 -r 1 -vframes 1 -y $picPath";
	      exec($cmd,$output,$retval);
	      if(file_exists($picPath)){
		    return $newFileName;
	      } else {
		     return false;
	      }
		}
		
		public function cropPreview($path){
			$previewPic = new \bv\resize($path);
			$previewPic->resizeImage(516,516,'landscape',0,0,0,0,false);
			$previewPic->saveImage($path,80);
		}
		
		public function cropReplyPhoto($path,$photo){
			$replyPhoto = new \bv\resize($path.$photo);
			$replyPhoto->resizeImage(300,300,'landscape',0,0,0,0,false);
			$replyPhoto->saveImage($path.'reply-'.$photo,80);
			if(file_exists($path.'reply-'.$photo)){
				return true;
			} else {
				return false;
			}
		}
		
		public function cropPhoto($path,$photo){
			$feedPhoto = new \bv\resize($path.$photo);
			$feedPhoto->resizeImage(516,516,'landscape',0,0,0,0,false);
			$feedPhoto->saveImage($path.'feed-'.$photo,80);
				
			$mobilePhoto = new \bv\resize($path.$photo);
			$mobilePhoto->resizeImage(320,320,'landscape',0,0,0,0,false);
			$mobilePhoto->saveImage($path.'mobile-'.$photo,80);
				
			$panePhoto = new \bv\resize($path.$photo);
			$panePhoto->resizeImage(280,280,'landscape',0,0,0,0,false);
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
		
		public function cropProfilePhoto($path){
			$profilePic = new \bv\resize($path);
			$profilePic->resizeImage(190,190);
			$profilePic->saveImage($path,100);
			if(file_exists($path)){
				return true;
			} else {
				return false;
			}
		}
		
		public function cropProfilePhotoExact($userdir,$tempPhoto,$x,$y,$w,$h){
			$curTempPath = $userdir.$tempPhoto;
			$newPicPath = $userdir.'budvibes-'.$tempPhoto;
			$profilePic = new \bv\resize($curTempPath);
			$profilePic->resizeImage(190,190,'exact',$x,$y,$w,$h,false);
			$profilePic->saveImage($newPicPath,100);
			if(file_exists($newPicPath)){
				return true;
			} else {
				return false;
			}
		}
		
		public function cropProfilePicThumbs($userdir,$photo){
			$curProfilePic = $userdir.$photo;
			$relationLink = $userdir.'relation-'.$photo;
			$relationPic = new \bv\resize($curProfilePic);
			$relationPic->resizeImage(100,100);
			$relationPic->saveImage($relationLink,100);
			
			$topLink = $userdir.'top-'.$photo;
			$topPic = new \bv\resize($curProfilePic);
			$topPic->resizeImage(110,110);
			$topPic->saveImage($topLink,100);
			
			$thumbLink = $userdir.'thumb-'.$photo;
			$thumbPic = new \bv\resize($curProfilePic);
			$thumbPic->resizeImage(60,60);
			$thumbPic->saveImage($thumbLink,100);
			
			$smallThumbLink = $userdir.'thumbsmall-'.$photo;
			$smallThumbPic = new \bv\resize($curProfilePic);
			$smallThumbPic->resizeImage(45,45);
			$smallThumbPic->saveImage($smallThumbLink,100);
			
			if(file_exists($relationLink) &&
			   file_exists($topLink) &&
			   file_exists($thumbLink) && 
			   file_exists($smallThumbLink)){
				   return true;
			   } else {
				   return false;
			   }
		}
		
		public function cropStoreSpecialPhoto($userdir,$photo){
			$curSpecialImg = $userdir.$photo;
			
			$largeSpecialLink = $userdir.'special-'.$photo;
			$largeSpecialImg = new \bv\resize($curSpecialImg);
			$largeSpecialImg->resizeImage(380,380);
			$largeSpecialImg->saveImage($largeSpecialLink,80);
			
			$smallSpecialLink = $userdir.'small-special-'.$photo;
			$smallSpecialImg = new \bv\resize($curSpecialImg);
			$smallSpecialImg->resizeImage(260,260);
			$smallSpecialImg->saveImage($smallSpecialLink,80);
			
			if(file_exists($largeSpecialLink) && 
			   file_exists($smallSpecialLink)){
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
				$_SESSION['store'] = $storeType = true;
			} else {
				$_SESSION['store'] = $storeType = false;
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
			setcookie('store', $storeType, time() + (60 * 60 * 24 * 30));
		}
		
		public function checkSession(){
			if(!isset($_SESSION['logged_in_id'])){
				if(isset($_COOKIE['logged_in_id']) && 
				   isset($_COOKIE['logged_in_user']) && 
				   isset($_COOKIE['user_verified']) && 
				   isset($_COOKIE['logged_in_photo']) && 
				   isset($_COOKIE['store']) && 
				   isset($_COOKIE['store_id']) && 
				   isset($_COOKIE['store_reg']) && 
				   isset($_COOKIE['store_state'])){
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
		
		public function formatMessages($chats,$sessionId){
			$i=0;
			$success = array();
			foreach($chats as $chat){
				$threadId = $chat['id'];
				$threadProfilePic = $chat['profile_pic'];
				$threadChatId = $chat['user_id'];
				$threadDate = $chat['created_at'];
				$threadMsgType = $chat['message_type'];
				$threadMsg = $chat['comm_text'];
				$threadMsgPic = $chat['pic'];
				$chatDate = strtotime($threadDate);
				$chatDate = date("M d, Y",$chatDate);
				if($threadProfilePic == 'no-profile.png'){
					$thumbLink = __LOCATION__ . '/assets/images/thumb-no-profile.png';
				} else {
					$thumbLink = __LOCATION__ . '/assets/user-images/'.$threadChatId.'/thumbsmall-'.$threadProfilePic;
				}
				if($sessionId == $threadChatId){
					$thumbClass = 'chatMsgThumbRight';
					$bodyClass = 'chatMsgBodyRight';
				} else {
					$thumbClass = 'chatMsgThumbLeft';
					$bodyClass = 'chatMsgBodyLeft';
				}
				if($threadMsgType == 'me' ){
					$imgClass = 'emojipost';
					$threadMsgPic = __LOCATION__ . '/assets/images/emoji/'.$threadMsgPic;
				} elseif($threadMsgType == 'mp'){
					$imgClass = 'picpost';
					$threadMsgPic = __LOCATION__ . '/assets/user-images/'.$threadChatId.'/'.$threadMsgPic;
				} else {
					$imgClass = '';
				}
				$success[$i]['profile_pic'] = $threadProfilePic;
				$success[$i]['user_id'] = $threadChatId;
				$success[$i]['date'] = $chatDate;
				$success[$i]['msg_type'] = $threadMsgType;
				$success[$i]['msg_text'] = $threadMsg;
				$success[$i]['pic'] = $threadMsgPic;
				$success[$i]['thumb'] = $thumbLink;
				$success[$i]['thumb_class'] = $thumbClass;
				$success[$i]['body_class'] = $bodyClass;
				$success[$i]['image_class'] = $imgClass;
				$i++;
			}
			return $success;
			exit();
		}
		
		public function formatStoreTime($time){
			$newTime = date("g:i a", strtotime($time));
			return $newTime;
		}
		
		public function getFormatHour($time){
			$newHour = date("g", strtotime($time));
			return $newHour;
		}
		
		public function getFormatMinute($time){
			$newMinute = date("i", strtotime($time));
			return $newMinute;
		}
		
		public function getFormatMeridian($time){
			$newMeridian = date("a", strtotime($time));
			return $newMeridian;
		}
	
	}//END APPLICATION HELPER CLASS
	
?>