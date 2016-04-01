<?php

	class ApplicationCtrl{
		
		private $Helper;
		private $Model;
		
		public function __construct(){
			$this->Helper = new ApplicationHelper;
			$this->Model = new ApplicationModels;
		}
		
		public function remove_whitespace($link){
			$link = strtolower($link);
			$link = preg_replace('/\s+/', '-', $link);
			$link = preg_replace('/\&amp\;/', 'and', $link);
			$link = preg_replace('/\&/', 'and', $link);
			$link = preg_replace('/\?/', '', $link);
			$link = preg_replace('/\'/', '', $link);
			$link = preg_replace('/\&39\;/', '', $link);
			return $link;
		}
		
		public function is_logged_in(){
			if(isset($_SESSION['logged_in_user'])){
				header('Location: '.$_SERVER['LOCATION'].'/'.$_SESSION['logged_in_user']);
				exit();
			}
		}
		
		/* !!! FOR PRODUCTION ONLY !!! */
		public function checkUrl(){
			if(__MODE__ == 'development'){
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
		}
		
		public function getSharedUsername($id){
			$username = $this->Model->findSharedUsername($id);
			return $username;
		}
		
		public function getStrainNameHead($id){
			$strain = $this->Model->findStrainName($id);
			return $strain;
		}
		
		public function getSecondUsernameHead($id){
			$user = $this->Model->findSecondUsername($id);
			return $user;
		}
		
		public function getForumThread($id){
			$thread = $this->Model->findForumThread($id);
			return $thread;
		}
		
		public function getForumBlock($id){
			$title = $this->Model->findBlockTitle($id);
			return $title;
		}
		
		public function getForumReply($id){
			$forumReply = $this->Model->findForumReply($id);
			return $forumReply;
		}
		
		public function getForumContent($id){
			$message = $this->Model->findForumContent($id);
			return $message;
		}
		
		public function getProdReplies($id){
			$replies = $this->Model->findProductReplies($id);
			return $replies;
		}
		
		public function getUserReplies($id){
			$replies = $this->Model->findUserReplies($id);
			return $replies;
		}
		
		public function generateProductShareCount($id){
			$shareCount = $this->Model->findProductShares($id);
			return $shareCount;
		}
		
		public function generateUserShareCount($id){
			$shareCount = $this->Model->findUserShares($id);
			return $shareCount;
		}
		
		public function getProductReplyCount($id){
			$replyCount = $this->Model->findProductReplyCount($id);
			return $replyCount;
		}
		
		public function getUserReplyCount($id){
			$replyCount = $this->Model->findUserReplyCount($id);
			return $replyCount;
		}
		
		public function generateFeedFake($feedType,$id,$alt=''){
		
		if($feed_type == 'feed'){
			
		} else if($feed_type == 'posts') {
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at, 
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state, 
			NULL,NULL 
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE c.comm_id IN(SELECT comment_id FROM replies WHERE user_id='".$id."') 
			OR c.user_id = '".$id."' 
			OR c.comm_id = '".$id."' 
			UNION ALL 
			SELECT pc.id, pc.user_id AS user_comm_id, pc.user_name, pc.rating, pc.comm_type, 
			pc.comm_id, pc.comm_name, pc.orig_id, pc.orig_name, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at, 
			pu.user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, pu.store_state, 
			pp.id AS prod_id, pp.pic AS prod_pic 
			FROM prod_comments pc 
			LEFT JOIN users pu ON pc.comm_id = pu.user_id 
			LEFT JOIN products pp ON pc.user_id = pp.id 
			WHERE 
			pc.comm_id IN(SELECT user_id FROM prod_replies WHERE user_id='".$id."') 
			OR 
			pc.comm_id = '".$id."' 
			ORDER BY created_at DESC 
			LIMIT 15";
		} else if($feed_type == 'ajax-posts'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at, 
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state, 
			NULL,NULL 
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE c.comm_id IN(SELECT comment_id FROM replies WHERE user_id='".$id."') 
			OR c.user_id = '".$id."' 
			OR c.comm_id = '".$id."' 
			UNION ALL 
			SELECT pc.id, pc.user_id AS user_comm_id, pc.user_name, pc.rating, pc.comm_type, 
			pc.comm_id, pc.comm_name, pc.orig_id, pc.orig_name, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at, 
			pu.user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, pu.store_state, 
			pp.id AS prod_id, pp.pic AS prod_pic 
			FROM prod_comments pc 
			LEFT JOIN users pu ON pc.comm_id = pu.user_id 
			LEFT JOIN products pp ON pc.user_id = pp.id 
			WHERE 
			pc.comm_id IN(SELECT user_id FROM prod_replies WHERE user_id='".$id."') 
			OR 
			pc.comm_id = '".$id."' 
			ORDER BY created_at DESC 
			LIMIT $alt, 15";
		} else if($feed_type == 'product'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			p.id AS prod_id, p.pic AS prod_pic 
			FROM prod_comments c 
			LEFT JOIN users u ON c.comm_id = u.user_id 
			LEFT JOIN products p ON c.user_id = p.id
			WHERE c.user_id = '".$id."'
			ORDER BY created_at DESC 
			LIMIT 15";
		} else if($feed_type == 'ajax-strainfeed'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			p.id AS prod_id, p.pic AS prod_pic 
			FROM prod_comments c 
			LEFT JOIN users u ON c.comm_id = u.user_id 
			LEFT JOIN products p ON c.user_id = p.id
			WHERE c.user_id = '".$id."'
			ORDER BY created_at DESC 
			LIMIT $alt, 15";
		} else if($feed_type == 'tags'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			NULL,NULL
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE 
			c.tags LIKE '%".$alt."%' 
			AND c.comm_type <> 'shpf' 
			AND c.comm_type <> 'shrf' 
			AND c.comm_type <> 'shpt' 
			AND c.comm_type <> 'shrt' 
			AND c.comm_type <> 'shpp' 
			AND c.comm_type <> 'shrp' 
			AND c.comm_type <> 'shpvf'  
			AND c.comm_type <> 'shrvf' 
			AND c.comm_type <> 'shpvv'  
			AND c.comm_type <> 'shrvv' 
			AND c.comm_type <> 'shpll' 
			AND c.comm_type <> 'shrll' 
			AND c.comm_type <> 'shplf' 
			AND c.comm_type <> 'shrlf' 
			AND c.comm_type <> 'shpllv'
			AND c.comm_type <> 'shplfv' 
			AND c.comm_type <> 'shsmk' 
			AND c.comm_type <> 'shfg' 
			UNION ALL
			SELECT pc.id, pc.user_id AS user_comm_id, pc.user_name, pc.rating, pc.comm_type,
			pc.comm_id, pc.comm_name, pc.orig_id, pc.orig_name, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at,
			pu.user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, pu.store_state,
			pp.id AS prod_id, pp.pic AS prod_pic
			FROM prod_comments pc
			LEFT JOIN users pu ON pc.comm_id = pu.user_id 
			LEFT JOIN products pp ON pc.user_id = pp.id
			WHERE
			pc.tags LIKE '%".$alt."%'  
			AND pc.comm_type <> 'shsf' 
			AND pc.comm_type <> 'shst' 
			AND pc.comm_type <> 'shsp'  
			AND pc.comm_type <> 'shsvf' 
			AND pc.comm_type <> 'shsvv' 
			AND pc.comm_type <> 'shsll' 
			AND pc.comm_type <> 'shslf' 
			AND pc.comm_type <> 'shsllv' 
			AND pc.comm_type <> 'shslfv' 
			AND pc.comm_type <> 'shsmk' 
			ORDER BY created_at DESC 
			LIMIT 15";
		} else if($feed_type == 'ajax-search'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			NULL,NULL
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE 
			c.tags LIKE '%".$alt."%' 
			AND c.comm_type <> 'shpf' 
			AND c.comm_type <> 'shrf' 
			AND c.comm_type <> 'shpt' 
			AND c.comm_type <> 'shrt' 
			AND c.comm_type <> 'shpp' 
			AND c.comm_type <> 'shrp' 
			AND c.comm_type <> 'shpvf' 
			AND c.comm_type <> 'shrvf' 
			AND c.comm_type <> 'shpvv'  
			AND c.comm_type <> 'shrvv' 
			AND c.comm_type <> 'shpll' 
			AND c.comm_type <> 'shrll' 
			AND c.comm_type <> 'shplf'  
			AND c.comm_type <> 'shrlf' 
			AND c.comm_type <> 'shpllv' 
			AND c.comm_type <> 'shplfv' 
			AND c.comm_type <> 'shsmk' 
			AND c.comm_type <> 'shfg' 
			UNION ALL
			SELECT pc.id, pc.user_id AS user_comm_id, pc.user_name, pc.rating, pc.comm_type,
			pc.comm_id, pc.comm_name, pc.orig_id, pc.orig_name, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at,
			pu.user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, pu.store_state,
			pp.id AS prod_id, pp.pic AS prod_pic
			FROM prod_comments pc
			LEFT JOIN users pu ON pc.comm_id = pu.user_id 
			LEFT JOIN products pp ON pc.user_id = pp.id
			WHERE
			pc.tags LIKE '%".$alt."%'  
			AND pc.comm_type <> 'shsf' 
			AND pc.comm_type <> 'shst' 
			AND pc.comm_type <> 'shsp'  
			AND pc.comm_type <> 'shsvf' 
			AND pc.comm_type <> 'shsvv' 
			AND pc.comm_type <> 'shsll' 
			AND pc.comm_type <> 'shslf' 
			AND pc.comm_type <> 'shsllv' 
			AND pc.comm_type <> 'shplfv' 
			AND pc.comm_type <> 'shsmk' 
			ORDER BY created_at DESC 
			LIMIT $id, 15";
		}else if($feed_type == 'forums'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			NULL,NULL
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE 
			c.comment LIKE '%".$alt."%' 
			AND c.comm_type = 'fg' 
			ORDER BY created_at DESC LIMIT 15";
		} else if($feed_type == 'ajax-forums'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			NULL,NULL
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE 
			c.comment LIKE '%".$alt."%' 
			AND c.comm_type = 'fg' 
			ORDER BY created_at DESC 
			LIMIT $id, 15";
		} else if($feed_type == 'ajax-feed'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			NULL,NULL 
			FROM user_comments c 
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE 
			c.comm_id IN(SELECT following_id FROM relationships WHERE follower_id='".$id."') 
			OR c.comm_id = '".$id."' 
			OR c.user_id = '".$id."' 
			UNION ALL 
			SELECT pc.id, pc.user_id AS user_comm_id, pc.user_name, pc.rating, pc.comm_type, 
			pc.comm_id, pc.comm_name, pc.orig_id, pc.orig_name, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at, 
			pu.user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, pu.store_state, 
			pp.id AS prod_id, pp.pic AS prod_pic 
			FROM prod_comments pc 
			LEFT JOIN users pu ON pc.comm_id = pu.user_id 
			LEFT JOIN products pp ON pc.user_id = pp.id 
			WHERE 
			pp.id IN(SELECT prod_id FROM prod_relationships WHERE user_id='".$id."') 
			OR pc.comm_id IN(SELECT following_id FROM relationships WHERE follower_id='".$id."') 
			OR pc.comm_id = '".$id."' 
			ORDER BY created_at DESC 
			LIMIT $alt, 15";
		} else if($feed_type == 'ajax-front'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			NULL,NULL
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE c.comm_type <> 'smk' 
			AND c.comm_type <> 'shpf' 
			AND c.comm_type <> 'shrf' 
			AND c.comm_type <> 'shpt' 
			AND c.comm_type <> 'shrt' 
			AND c.comm_type <> 'shpp' 
			AND c.comm_type <> 'shrp' 
			AND c.comm_type <> 'shpvf'  
			AND c.comm_type <> 'shrvf' 
			AND c.comm_type <> 'shpvv'  
			AND c.comm_type <> 'shrvv' 
			AND c.comm_type <> 'shpll' 
			AND c.comm_type <> 'shrll' 
			AND c.comm_type <> 'shplf'  
			AND c.comm_type <> 'shrlf' 
			AND c.comm_type <> 'shpllv' 
			AND c.comm_type <> 'shplfv' 
			AND c.comm_type <> 'shsmk' 
			AND c.comm_type <> 'shfg' 
			UNION ALL
			SELECT pc.id, pc.user_id AS user_comm_id, pc.user_name, pc.rating, pc.comm_type,
			pc.comm_id, pc.comm_name, pc.orig_id, pc.orig_name, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at,
			pu.user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, pu.store_state,
			pp.id AS prod_id, pp.pic AS prod_pic
			FROM prod_comments pc
			LEFT JOIN users pu ON pc.comm_id = pu.user_id 
			LEFT JOIN products pp ON pc.user_id = pp.id 
			WHERE pc.comm_type <> 'smk' 
			AND pc.comm_type <> 'shsf' 
			AND pc.comm_type <> 'shst' 
			AND pc.comm_type <> 'shsp'  
			AND pc.comm_type <> 'shsvf' 
			AND pc.comm_type <> 'shsvv' 
			AND pc.comm_type <> 'shsll' 
			AND pc.comm_type <> 'shslf' 
			AND pc.comm_type <> 'shsllv' 
			AND pc.comm_type <> 'shslfv' 
			AND pc.comm_type <> 'shsmk' 
			ORDER BY created_at DESC 
			LIMIT $alt, 15";
		} else if($feed_type == 'map'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			NULL,NULL
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE c.comm_type <> 'smk' 
			AND c.comm_type <> 'shpf' 
			AND c.comm_type <> 'shrf' 
			AND c.comm_type <> 'shpt' 
			AND c.comm_type <> 'shrt' 
			AND c.comm_type <> 'shpp' 
			AND c.comm_type <> 'shrp' 
			AND c.comm_type <> 'shpvf'  
			AND c.comm_type <> 'shrvf' 
			AND c.comm_type <> 'shpvv'  
			AND c.comm_type <> 'shrvv' 
			AND c.comm_type <> 'shpll' 
			AND c.comm_type <> 'shrll' 
			AND c.comm_type <> 'shplf'  
			AND c.comm_type <> 'shrlf' 
			AND c.comm_type <> 'shpllv' 
			AND c.comm_type <> 'shplfv' 
			AND c.comm_type <> 'shsmk' 
			AND c.comm_type <> 'shfg' 
			UNION ALL
			SELECT pc.id, pc.user_id AS user_comm_id, pc.user_name, pc.rating, pc.comm_type,
			pc.comm_id, pc.comm_name, pc.orig_id, pc.orig_name, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at,
			pu.user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, pu.store_state,
			pp.id AS prod_id, pp.pic AS prod_pic
			FROM prod_comments pc
			LEFT JOIN users pu ON pc.comm_id = pu.user_id 
			LEFT JOIN products pp ON pc.user_id = pp.id 
			WHERE pc.comm_type <> 'smk' 
			AND pc.comm_type <> 'shsf' 
			AND pc.comm_type <> 'shst' 
			AND pc.comm_type <> 'shsp'  
			AND pc.comm_type <> 'shsvf' 
			AND pc.comm_type <> 'shsvv' 
			AND pc.comm_type <> 'shsll' 
			AND pc.comm_type <> 'shslf' 
			AND pc.comm_type <> 'shsllv' 
			AND pc.comm_type <> 'shslfv' 
			AND pc.comm_type <> 'shsmk' 
			ORDER BY created_at DESC 
			LIMIT 15";
		} else if($feed_type == 'user-post'){
			$query_select = "SELECT c.id, c.user_id AS user_comm_id, c.user_name, c.rating, c.comm_type, 
			c.comm_id, c.comm_name, c.orig_id, c.orig_name, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			NULL,NULL
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.user_id 
			WHERE 
			c.id = '".$id."'  
			LIMIT 1";
		}else if($feed_type == 'strain-post'){
			$query_select = "SELECT pc.id, pc.user_id AS user_comm_id, pc.user_name, pc.rating, pc.comm_type,
			pc.comm_id, pc.comm_name, pc.orig_id, pc.orig_name, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at,
			pu.user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, pu.store_state,
			pp.id AS prod_id, pp.pic AS prod_pic
			FROM prod_comments pc
			LEFT JOIN users pu ON pc.comm_id = pu.user_id 
			LEFT JOIN products pp ON pc.user_id = pp.id
			WHERE
			pc.id = '".$id."' 
			LIMIT 1";
		}
	 } //END FAKE FUNCTION
	 
	}
?>


