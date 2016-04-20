<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$Controller = new ApplicationCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	/*
	set_error_handler("warning_handler", E_WARNING);
	function warning_handler($errno, $errstr) { 
		echo 0;
		exit();
	}
	*/
	
	if(!isset($_SESSION['logged_in_id'])){
		//NOT LOGGED IN
		$error['status'] = 'Log in to add a link';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	} else {
		$link_data = array();
		$link = trim($_POST['add_link']);
		if(isset($_POST['post_forum'])){
			$postForum = true;
		} else {
			$postForum = false;
		}
		$info = parse_url($link);
		$iframe = "";
		if(!$info['host'] || $info['host'] == ''){
			$frameDoc = new DOMDocument();
			$frameDoc->loadHTML($link);
			@$src = $frameDoc->getElementsByTagName('iframe')->item(0)->getAttribute('src');
			if($src && $src != ''){
				$fileCheck = @get_headers($src);
				if($fileCheck[0] == 'HTTP/1.0 200 OK' || $fileCheck[0] == 'HTTP/1.1 200 OK'){
					$link_data['iframe'] = '<iframe class="videoFrame" src="'.$src.'" frameborder="0" allowfullscreen style="width:100%; height:400px;"></iframe>';
					$link_data['meta_description'] = "";
					$link_data['site_title'] = "";
					$link_data['ogtitle'] = "";
					$link_data['ogtype'] = "";
					$link_data['ogimage'] = "";
					$link_data['ogurl'] = "";
					$link_data['description'] = "";
					$link_data['sitename'] = "";
					$link_data['username'] = $_SESSION['logged_in_user'];
					echo json_encode($link_data);
					exit();
				} else {
					//BAD HEADERS ERROR
					$error['status'] = 'Unsuccessful server response';
					$error['code'] = 500;
					echo json_encode($error);
					exit();
				}
			} else {
				//INVALID SRC LINK
				$error['status'] = 'Bad link provided';
				$error['code'] = 500;
				echo json_encode($error);
				exit();
			}
		} else {
			/*TEST FOR YOUTUBE,VIMEO, OR VINE NORMAL LINK*/
			if($info['host'] == 'www.youtube.com' || $info['host'] == 'youtube.com'){
				//YOUTUBE LINK
				$iframe = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i", 
						  "<iframe class='videoFrame' src=\"https://www.youtube.com/embed/$2\" allowfullscreen frameborder='0' width='100%' height='400'></iframe>", $link);
			} else if($info['host'] == 'www.vimeo.com' || $info['host'] == 'vimeo.com'){
		     	//VIMEO LINK
				$iframe = preg_replace('#https?://(www\.)?vimeo\.com/(\d+)#',
						 '<iframe class="videoFrame" src="https://player.vimeo.com/video/$2" frameborder="0" allowfullscreen style="width:100%; height:400px;"></iframe>',
						 $link);
			} else if($info['host'] == 'www.vine.co' || $info['host'] == 'vine.co'){
		     	//VINE LINK
				$iframe = "<iframe class='videoFrame' src='".$link."/embed/simple' frameborder='0' allowfullscreen style='width:100%; height:400px;'></iframe>";
			}
			
			/*NOMRAL NON-VIDEO LINKS*/
			$html = $Helper->fileGetContentscUrl($link);
			$doc = new DOMDocument();
			@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
			/*TITLE*/
			$nodes = $doc->getElementsByTagName('title');
			$title = $nodes->item(0)->nodeValue;
			$link_data['site_title'] = $title;
			/*METAS*/
			$description = '';
			$og_title = '';
			$og_type = '';
			$og_image = '';
			$og_url = '';
			$og_description = '';
			$og_sitename = '';
			$metas = $doc->getElementsByTagName('meta');
			for($i=0; $i<$metas->length; $i++){
				$meta = $metas->item($i);
				//META DESCRIPTION NON-OG
				if($meta->getAttribute('name') == 'description'){
					$description = $meta->getAttribute('content');
					$link_data['meta_description'] = $description;
				}
				//META OG:TITLE
				if($meta->getAttribute('property') == 'og:title'){
					$og_title = $meta->getAttribute('content');
					$link_data['ogtitle'] = $og_title;
				}
				//META OG:TYPE
				if($meta->getAttribute('property') == 'og:type'){
					$og_type = $meta->getAttribute('content');
					$link_data['ogtype'] = $og_type;
				}
				//META OG:IMAGE
				if($meta->getAttribute('property') == 'og:image' && $og_image == ''){
					$tempPic = $Controller->getTempPic($_SESSION['logged_in_id']);
					if(!$postForum){
						//UNLINK PREVIOUS PHOTO
						$userdir = '../assets/user-images/'.$_SESSION['logged_in_id'].'/';
						if(!empty($tempPic)){
							if(file_exists($userdir.$tempPic) ){
								unlink($userdir.$tempPic);
							}
						}
					}
					
					//DELETE TEMP POST PHOTO
					$Controller->removeTempPhoto($_SESSION['logged_in_id']);
					
					$og_image = $meta->getAttribute('content');
						
					$extension = strtolower(strrchr($og_image,'.'));
					
					if(preg_match('/:/', $extension)){
						$hashPos = strrpos($extension, ':');
						$extension = substr($extension, 0, $hashPos);
					}
					if(preg_match('/\?/', $extension)){
						$quesPos = strrpos($extension, '?');
						$extension = substr($extension, 0, $quesPos);
					}
					
					$bytes = openssl_random_pseudo_bytes(4,$cstrong);
					$hex = bin2hex($bytes);
					
					$userdir = '../assets/user-images/'.$_SESSION['logged_in_id'].'/';
					
					$linkName = 'budvibes-'.$hex.$extension;
	
					if(copy($og_image, $userdir.$linkName)){
						if(file_exists($userdir.$linkName)){
							//RESIZE PHOTO
							$linkPhoto = new \bv\resize($userdir.$linkName);
							$linkPhoto->resizeImage(516,516);
							$linkPhoto->saveImage($userdir.$linkName,80);
							$link_data['ogimage'] = __LOCATION__ . '/assets/user-images/'.$_SESSION['logged_in_id'].'/'.$linkName;
						}
					}
					
				}
				//META OG:URL
				if($meta->getAttribute('property') == 'og:url'){
					$og_url = $meta->getAttribute('content');
					$link_data['ogurl'] = $og_url;
				}
				//META OG:DESCRIPTION
				if($meta->getAttribute('property') == 'og:description'){
					$og_description = $meta->getAttribute('content');
					$link_data['ogdescription'] = $og_description; 
				}
				//META OG:SITENAME
				if($meta->getAttribute('property') == 'og:site_name'){
					$og_sitename = $meta->getAttribute('content');
					$link_data['sitename'] = $og_sitename;
				}
			}
			//USE HOST IF NO URL GIVEN
			if(!$link_data['sitename']){
				$link_data['sitename'] = $info['host'];
			}
			$link_data['iframe'] = $iframe; //IFRAME EITHER "" OR <iframe src="site.com"></iframe>
			$link_data['username'] = $_SESSION['logged_in_user'];
			if(!empty($link_data)){
				$link_data['code'] = 200;
				echo json_encode($link_data);
				exit();
			} else {
				//DATASET IS EMPTY
				$error['status'] = 'No data provided from site';
				$error['code'] = 500;
				echo json_encode($error);
				exit();
			}
			
		}//END ELSE FOR NORMAL LINK
		
	}//END IF FOR NO SESSION SET