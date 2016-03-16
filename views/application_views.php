<?php
	
	class ApplicationViews{
		
		private $Controller;
		private $server_loc;
		
		public function __construct(){
			$this->Controller = new ApplicationCtrl;
			$this->server_loc = $_SERVER['LOCATION'];
		}
		
		#DISPLAY HEADER
		function add_head($title='Blog',$meta_desc="",$alt="",$url=""){
			$html = '<!DOCTYPE html>';
			$html .= '<html>';
			$html .= '<head>';
			$html .= 	'<title>'.$title.'</title>';
			$html .= 	'<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />';
			$html .= 	'<meta name="description" content="'.$meta_desc.'">';
			$html .= 	'<link rel="alternate" media="only screen and (max-width: 640px)" href="'.$alt.'">';
			$html .=	'<link rel="canonical" href="'.$url.'">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'.$this->server_loc.'/assets/css/mac-front.css">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'.$this->server_loc.'/assets/css/search.css">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'.$this->server_loc.'/assets/css/profile.css">';
			$html .=	'<link rel="stylesheet" type="text/css" href="'.$this->server_loc.'/assets/css/sign.css">';
			$html .= 	'<link rel="icon" href="https://www.budvibes.com/images/tab-pic.png">';
			$html .= 	'<script src="https://maps.googleapis.com/maps/api/js?v=3.14&sensor=false"></script>';
			$html .= 	'<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';
			$html .= 	'<script type="text/javascript" src="'.$this->server_loc.'/assets/javascripts/infobubble.js"></script>';
			$html .= 	'<script type="text/javascript" src="'.$this->server_loc.'/assets/javascripts/search.js"></script>';
			$html .= 	'<script type="text/javascript" src="'.$this->server_loc.'/assets/javascripts/front-script.js"></script>';
			$html .= 	'<script type="text/javascript" src="'.$this->server_loc.'/assets/javascripts/global-fns.js"></script>';
			$html .= 	'<script type="text/javascript" src="'.$this->server_loc.'/assets/javascripts/relation.js"></script>';
			$html .= 	'<script type="text/javascript" src="'.$this->server_loc.'/assets/javascripts/endless-scroll.js"></script>';
			$html .= 	'<script type="text/javascript" src="'.$this->server_loc.'/assets/javascripts/tooltip.js"></script>';
			$html .= 	'<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
			$html .= '</head>';
			$html .= '<body>';
			
			echo $html;
		}
		
		#DISPLAY FOOTER
		function do_footer(){
			$html = '</body>';
			$html .= '</html>';
			
			echo $html;
		}
		
		#HEAD BANNER
		function do_header($type='',$link=''){
			$html = '<div id="header" class="clearfix">';
			$html .= 	'<div id="headLogo">';
			$html .= 		'<a href="/"><img id="headLogo" src="http://localhost/bv_mvc/LAMP-Blog/assets/images/plant.png" alt="budvibes logo"></a>';
			$html .= 	'</div>';
			$html .= 	'<div id="searchBar">';
			$html .=		'<input type="text" name="search" id="search" autocomplete="off">';
			$html .= 	'</div>';
						if(isset($_SESSION['logged_in_id'])){
							$username = $this->Controller->remove_whitespace($_SESSION['logged_in_user']);
							if($_SESSION['store']){
								$url = 'https://www.budvibes.com/'.$_SESSION['store_state'].'/'.$_SESSION['store_reg'].'/'.$username;
							} else {
								$url = 'https://www.budvibes.com/'.$username;
							}
							
							$html .= '<div id="headMenu">';
							$html .=	'<li><a href="'.$url.'"><img src="'.$this->server_loc.'/assets/images/home-icon.png" alt="home"></a></li>';
							$html .=	'<li><a id="forumIcon"><img src="'.$this->server_loc.'/assets/images/forum-icon.png" alt="budvibes forum topics"></a></li>';
							$html .= 	'<li><a id="libIcon"><img src="'.$this->server_loc.'/assets/images/leaf-head-icon.png" alt="budvibes marijuana strains"></a></li>';
							$html .= 	'<li><a id="locationIcon"><img src="'.$this->server_loc.'/assets/images/location-icon-small.png" alt="budvibes marijuana dispensary maps"></a></li>';
							$html .= 	'<li><a id="userIcon"><img src="'.$this->server_loc.'/assets/images/user-icon.png" alt="'.$_SESSION['logged_in_user'].'"></a></li>';
							
							if($type == 'message'){
								$html .= '<li><a id="msgIcon"><img src="'.$this->server_loc.'/assets/images/message-user-icon.png" alt="messages"></a></li>';
							} else {
								$html .= '<li><a id="menuIcon"><img src="'.$this->server_loc.'/assets/images/menu-icon.png" alt="marijuana dispensary listings"></a></li>';
							}
							
							$html .= '</div>';
						} else {
							$html .= '<div id="headMenu">';
							$html .=	'<li><a href="/"><img src="'.$this->server_loc.'/assets/images/home-icon.png" alt="home"></a></li>';
							$html .=	'<li><a id="forumIcon"><img src="'.$this->server_loc.'/assets/images/forum-icon.png" alt="budvibes forum topics"></a></li>';
							$html .= 	'<li><a id="libIcon"><img src="'.$this->server_loc.'/assets/images/leaf-head-icon.png" alt="budvibes marijuana strains"></a></li>';
							$html .= 	'<li><a id="locationIcon"><img src="'.$this->server_loc.'/assets/images/location-icon-small.png" alt="budvibes marijuana dispensary maps"></a></li>';
							$html .= 	'<li><a id="userIcon"><img src="'.$this->server_loc.'/assets/images/user-icon.png" alt="budvibes sign in"></a></li>';
							
							if($type == 'message'){
								$html .= '<li><a id="msgIcon"><img src="'.$this->server_loc.'/assets/images/message-user-icon.png" alt="budvibes sign up"></a></li>';
							} else {
								$html .= '<li><a id="menuIcon"><img src="'.$this->server_loc.'/assets/images/menu-icon.png" alt="marijuana dispensary listings"></a></li>';
							}
							
							$html .= '</div>';
						}
			$html .= '</div>';
			
			echo $html;
		}
		
	}
?>