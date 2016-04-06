<?php
	
	class ApplicationModels{
		
		public	$pdo;
		private $Helper;
		
		public function __construct(){
			$this->Helper = new ApplicationHelper;
			$this->pdo = $this->pdo_conn();
		}
		
		/*****
			#MODEL FUNCTIONS LIST#
			 @pdo_conn();
			 @create_url();
			 @obfuscate_link();
			 @get_prod_buttons();
			 @get_user_buttons();
			 @getUsernameHead();
		*****/
		
		public function pdo_conn(){
			try{
				$pdo = new PDO(
					sprintf('mysql:host=%s;dbname=%s;port=%s;charset=%s',
							__DBHOST__,
							__DATABASE__,
							__DBPORT__,
							__DBCHARSET__
							),
							__DBUSER__,
							__DBPASS__
				);
				return $pdo;
			} catch(PDOException $e){
				return false;
				exit();
			}
		}
		
		public function create_url($username){
			//LEAVE(-)
			//STRIP(')
			//CONVERT (&) TO and
			$username = strtolower($username);
			$username = preg_replace('/\&amp\;/', 'and', $username);
			$username = preg_replace('/\&/', 'and', $username);
			$username = preg_replace('/\'/', '', $username);
			$username = preg_replace('/\&39\;/', '', $username);
			$username = preg_replace('/\s+/', '-', $username);
			return $username;
		}
		
		public function get_prod_buttons($id){
			$query_relation = "SELECT prod_id, user_id 
				FROM prod_relationships 
				WHERE prod_id='".$id."' AND user_id='".$_SESSION['logged_in_id']."'";
		}
		
		public function get_user_buttons($id){
			$query_relation = "SELECT follower_id, following_id 
				FROM relationships 
				WHERE follower_id='".$_SESSION['logged_in_id']."' AND following_id='".$id."'";
		}
		
		public function findSharedUsername($id){
			$otherUser = "SELECT username FROM users WHERE id=:id";
			$statement = $this->pdo_conn()->prepare($otherUser);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$username = $statement->fetchColumn(1);
			return $username;
		}
		
		public function findStrainName($id){
			$strainName = "SELECT name AS username FROM products WHERE id=:id";
			$statement = $this->pdo->prepare($strainName);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$strain = $statement->fetchColumn(1);
			return $strain;
		}
		
		public function findSecondUsername($id){
			$secondUser = "SELECT username, type, store_reg, store_state FROM users WHERE id=:id";
			$statement = $this->pdo->prepare($secondUser);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$user = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $user;
		}
		
		public function findForumThread($id){
			$forumThread = "SELECT title, postid, parent 
			FROM forum_general WHERE postid=:id";
			$statement = $this->pdo->prepare($forumThread);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$thread = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $thread;
		}
		
		public function findBlockTitle($id){
			$blockTitle = "SELECT title FROM forum_general WHERE postid=:id";
			$statement = $this->pdo->prepare($blockTitle);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$title = $statement->fetchColumn(1);
			return $title;
		}
		
		public function findForumReply($id){
			$forumReply = 'SELECT fr.id, fr.forum_id, fr.user_id, fr.comment, 
						   u.user_id, u.username, u.type, u.store_reg, u.store_state 
						   FROM forum_replies fr 
						   LEFT JOIN users u ON fr.user_id = u.user_id 
						   WHERE fr.forum_id=:id';
			$statement = $this->pdo->prepare($forumReply);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$reply = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $reply;
		}
		
		public function findForumContent($id){
			$forumComment = "SELECT message FROM forum_general WHERE postid=:id";
			$statement = $this->pdo->prepare($forumComment);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$message = $statement->fetchColumn(1);
			return $message;
		}
		
		public function findProductReplies($id){
			$replies = array();
			$productReplies = "SELECT r.id, r.prod_id, r.user_id AS user_reply_id, r.reply, r.pic, r.created_at, 
				u.id, u.username, u.profile_pic, u.type, u.store_reg, u.store_state  
				FROM prod_replies r 
				LEFT JOIN users u ON r.user_id = u.id 
				WHERE r.prod_id=:id 
				ORDER BY r.created_at DESC";
			$statement = $this->pdo->prepare($productReplies);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$replyNum = $statement->rowCount();
			$replyItems = $statement->fetchAll(PDO::FETCH_ASSOC);
			$replies['num'] = $replyNum;
			$replies['items'] = $replyItems;
			return $replies;
		}
		
		public function findUserReplies($id){
			$replies = array();
			$userReplies = "SELECT r.id, r.comment_id, r.user_id AS user_reply_id, r.reply, r.pic, r.created_at, 
				u.id AS user_id, u.username, u.profile_pic, u.type, u.store_reg, u.store_state 
				FROM user_replies r 
				LEFT JOIN users u ON r.user_id = u.id 
				WHERE r.comment_id=:id 
				ORDER BY r.created_at DESC";
			$statement = $this->pdo->prepare($userReplies);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$replyNum = $statement->rowCount();
			$replyItems = $statement->fetchAll(PDO::FETCH_ASSOC);
			$replies['num'] = $replyNum;
			$replies['items'] = $replyItems;
			return $replies;
		}
		
		public function findProductShares($id){
			$productShares = "SELECT COUNT(*) 
			FROM prod_comments 
			WHERE rating=:id 
			AND comm_type <> 'fg'";
			$statement = $this->pdo->prepare($productShares);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$count = $statement->fetchColumn(0);
			return $count;
		}
		
		public function findUserShares($id){
			$userShares = "SELECT COUNT(*) 
			FROM user_comments 
			WHERE rating=:id 
			AND comm_type <> 'fg'";
			$statement = $this->pdo->prepare($userShares);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$count = $statement->fetchColumn(0);
			return $count;
		}
		
		public function findProductReplyCount($id){
			$productReplyCount = "SELECT COUNT(*)
			FROM prod_replies r
			WHERE r.prod_id=:id";
			$statement = $this->pdo->prepare($productReplyCount);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$count = $statement->fetchColumn(0);
			return $count;
		}
		
		public function findUserReplyCount($id){
			$userReplyCount = "SELECT COUNT(*)
			FROM user_replies r
			WHERE r.comment_id=:id";
			$statement = $this->pdo->prepare($userReplyCount);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$count = $statement->fetchColumn(0);
			return $count;
		}
		
		public function findUserMessageCount($id){
			$msgCount = "SELECT COUNT(*) FROM messages 
			WHERE status='u' AND user_two=:id";
			$statement = $this->pdo->prepare($msgCount);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$count = $statement->fetchColumn(0);
			return $count;
		}
		
		public function findUserMessages($id){
			$messages = array();
			$userMessages = "SELECT chat_id, parent, user_one, user_two 
			FROM messages 
			WHERE parent = 0 
			AND (user_one=:id OR user_two=:id)";
			$statement = $this->pdo->prepare($userMessages);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$count = $statement->rowCount();
			$messageThread = $statement->fetchAll(PDO::FETCH_ASSOC);
			$messages['count'] = $count;
			$messages['thread'] = $messageThread;
			return $messages;
		}
		
		public function findUserMessageOne($id){
			$message = "SELECT m.chat_id, m.parent, m.status, m.user_one, m.user_two, m.message_type, m.created_at, 
			u.id AS user_id, u.username, u.profile_pic 
			FROM messages m 
			LEFT JOIN users u ON m.user_two = u.id 
			WHERE m.user_two=:id 
			ORDER BY m.created_at DESC 
			LIMIT 1";
			$statement = $this->pdo->prepare($message);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$msg = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $msg;
		}
		
		public function findUserMessageTwo($id){
			$message = "SELECT m.chat_id, m.parent, m.status, m.user_one, m.user_two, m.message_type,m.created_at, 
			u.user_id, u.username, u.profile_pic 
			FROM messages m 
			LEFT JOIN users u ON m.user_one = u.user_id 
			WHERE m.user_one=:id 
			ORDER BY m.created_at DESC 
			LIMIT 1";
			$statement = $this->pdo->prepare($message);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$msg = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $msg;
		}
		
	} //END APPLICATION MODELS CLASS
?>