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
			$username = $statement->fetchColumn(0);
			return $username;
		}
		
		public function findStrainName($id){
			$strainName = "SELECT name AS username FROM products WHERE id=:id";
			$statement = $this->pdo->prepare($strainName);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$strain = $statement->fetchColumn(0);
			return $strain;
		}
		
		public function findSecondUsername($id){
			$secondUser = "SELECT username, type, store_reg, store_state FROM users WHERE id=:id";
			$statement = $this->pdo->prepare($secondUser);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$user = $statement->fetch(PDO::FETCH_ASSOC);
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
			$title = $statement->fetchColumn(0);
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
			$message = $statement->fetchColumn(0);
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
		
		public function deleteTempPhoto($id){
			$deletePhoto = "DELETE FROM temp_postphotos 
			WHERE user_id=:id";
			$statement = $this->pdo->prepare($deletePhoto);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false; 
		}
		
		public function findTempPic($id){
			$tempPhoto = "SELECT pic FROM temp_postphotos 
			WHERE user_id=:id";
			$statement = $this->pdo->prepare($tempPhoto);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$tempPic = $statement->fetchColumn(0);
			return $tempPic ? $tempPic : false;
		}
		
		public function insertTempPic($id,$photo,$type){
			$tempPic = "INSERT INTO temp_postphotos(user_id,pic,type)
			VALUES(:id,:photo,:type)";
			$statement = $this->pdo->prepare($tempPic);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->bindValue(':photo',$photo);
			$statement->bindValue(':type',$type);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function findTempVideo($id){
			$tempVideo = "SELECT video FROM temp_postvideos 
			WHERE user_id=:id";
			$statement = $this->pdo->prepare($tempVideo);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$tempVideo = $statement->fetchColumn(0);
			return $tempVideo ? $tempVideo : false;
		}
		
		public function deleteTempVideo($id){
			$deleteVideo = "DELETE FROM temp_postvideos 
			WHERE user_id=:id";
			$statement = $this->pdo->prepare($deleteVideo);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function getStoreId($id){
			$storeId = "SELECT store_id FROM users WHERE id=:id";
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$id = $statement->fetchColumn(0);
			return $id ? $id : false;
		}
		
		public function insertProdPhotoFull($curWallId,$rating,
											$type,$userId,
											$userText,$newPhoto,
											$newVideo,$tagString){
			$newProdComment = "INSERT INTO prod_comments 
			VALUES('NULL', :curWallId, :rating, :userId, :type, :userId, 
			:userText, :newPhoto, 'NULL', :tagString, NOW())";
			$statement->bindValue(':curWallId',$curWallId,PDO::PARAM_INT);
			$statement->bindValue(':rating',$rating,PDO::PARAM_INT);
			$statement->bindValue(':type',$type);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':userText',$userText);
			$statement->bindValue(':newPhoto',$newPhoto);
			$statement->bindValue(':newVideo',$newVideo);
			$statement->bindValue(':tagString',$tagString);
			$statement->execute();
			$insertId = $this->pdo->lastInsertId();
			return $insertId ? $insertId : false;
		}
		
		public function insertUserPhotoFull($curWallId,$rating,
											$type,$userId,
											$userText,$newPhoto,
											$newVideo,$tagString){
			$newUserComment = "INSERT INTO user_comments 
			VALUES('NULL', :curWallId, :rating, :userId, :type, :userId,
			:userText, :newPhoto, :newVideo, :tagString, NOW())";
			$statement = $this->pdo->prepare($newUserComment);
			$statement->bindValue(':curWallId',$curWallId,PDO::PARAM_INT);
			$statement->bindValue(':rating',$rating,PDO::PARAM_INT);
			$statement->bindValue(':type',$type);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':userText',$userText);
			$statement->bindValue(':newPhoto',$newPhoto);
			$statement->bindValue(':newVideo',$newVideo);
			$statement->bindValue(':tagString',$tagString);
			$statement->execute();
			$insertId = $this->pdo->lastInsertId();
			return $insertId ? $insertId : false;
		}
		
		public function insertUserVideoOnly($curWallId,$rating,
								            $type,$userId,
										    $userText,$newPhoto,
						                    $newVideo,$tagString){
			$newVideoComment = "INSERT INTO user_comments 
			VALUES('NULL', :curWallId, :rating, :userId, 
			:type, :userId, :userText, 'vid-placeholder.png', :newVideo, 
			:tagString, NOW())";
			$statement = $this->pdo->prepare($newVideoComment);
			$statement->bindValue(':curWallId',$curWallId, PDO::PARAM_INT);
			$statement->bindValue(':rating',$rating,PDO::PARAM_INT);
			$statement->bindValue(':type',$type);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':newVideo',$newVideo);
			$statement->bindValue(':newPhoto',$newPhoto);
			$statement->bindValue(':tagString',$tagString);
			$statement->bindValue(':userText',$userText);
			$statement->execute();
			return $statement->rowCount() ? $this->pdo->lastInsertId() : false;
		}
		
		public function insertProdVideoOnly($curWallId,$rating,
								            $type,$userId,
										    $userText,$newPhoto,
						                    $newVideo,$tagString){
			$newVideo = "INSERT INTO prod_comments 
			VALUES('NULL', :curWallId, :rating, :userId, 
			:type, :userId, :userText, :newPhoto, :newVideo, 
			:tagString, NOW())";
			$statement = $this->pdo->prepare($newVideo);
			$statement->bindValue(':curWallId',$curWallId, PDO::PARAM_INT);
			$statement->bindValue(':rating',$rating,PDO::PARAM_INT);
			$statement->bindValue(':type',$type);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':newVideo',$newVideo);
			$statement->bindValue(':tagString',$tagString);
			$statement->bindValue(':newPhoto',$newPhoto);
			$statement->execute();
			$statement->rowCount() ? $this->pdo->lastInsertId() : false;
		}
		
		public function insertTempVideo($userId,$video,$type){
			$tempVideo = "INSERT INTO temp_postvideos (user_id,video,type) 
			VALUES(:userId,:video,:type)";
			$statement = $this->pdo->prepare($tempVideo);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':video',$video);
			$statement->bindValue(':type',$type);
			$statement->execute();
			return $statement->rowCount() ? $this->pdo->lastInsertId() : false;
		}
		
		public function insertProdVideoPic($newVideoPic,$newCommentId){
			$updateProdComment = "UPDATE prod_comments 
			SET pic=:newVideoPic WHERE id=:newCommentId"; 
			$statement = $this->pdo->prepare($updateProdComment);
			$statement->bindValue(':newVideoPic',$newVideoPic);
			$statement->bindValue(':newCommentId',$newCommentId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		public function insertUserVideoPic($newVideoPic,$newCommentId){
			$updateUserComment = "UPDATE user_comments 
			SET pic=:newVideoPic WHERE id=:newCommentId";
			$statement = $this->pdo->prepare($updateUserComment);
			$statement->bindValue(':newVideoPic',$newVideoPic);
			$statement->bindValue(':newCommentId',$newCommentId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function findNewProdComment($id){
			$newProdComment = "SELECT c.id, c.user_id AS user_comm_id, 
			c.rating, c.comm_id, c.comm_type, c.orig_id, c.comment, 
			c.pic, c.vid,  c.tags, c.created_at, u.id AS user_id, 
			u.username, u.profile_pic, u.type, u.store_id, 
			u.store_reg, u.store_state
			FROM prod_comments c
			LEFT JOIN users u ON c.comm_id = u.id
			WHERE c.id=:id";
			$statement = $this->pdo->prepare($newProdComment);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$comment = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $comment ? $comment : false;
		}
		
		public function findNewUserComment($id){
			$newUserComment = "SELECT c.id, c.user_id AS user_comm_id, 
			c.rating, c.comm_id, c.comm_type, c.orig_id,  
			c.comment, c.pic, c.vid,  c.tags, c.created_at, 
			u.id AS user_id, u.username, u.profile_pic, 
			u.type, u.store_id, u.store_reg, u.store_state
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.id
			WHERE c.id=:id";
			$statement = $this->pdo->prepare($newUserComment);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			$comment = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $comment ? $comment : false;
		}
		
		public function findTempProfilePic($userId){
			$tempProfilePic = "SELECT photo FROM temp_profilepic 
			WHERE user_id=:userId";
			$statement = $this->pdo->prepare($tempProfilePic);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}
		
		public function deleteTempProfilePic($userId,$tempPic,$userdir){
			$deleteTempPic = "DELETE FROM temp_profielpic WHERE user_id=:userId";
			$statement = $this->pdo->prepare($deleteTempPic);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			$num = $statement->rowCount();
			if($num){
				$imgPath = $userdir.$tempPic;
				if(file_exists($imgPath)){
					unlink($imgPath);
				}
				return true;
			} else {
				return false;
			}
		}
		
		public function findUserProfilePic($userId){
			$profilePic = "SELECT profile_pic FROM users 
			WHERE id=:userId";
			$statement = $this->pdo->prepare($profilePic);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : false;
		}
		
		public function insertProfilePic($userId,$photo){
			$newProfilePic = "UPDATE users 
			SET profile_pic=:photo 
			WHERE id=:userId";
			$statement = $this->pdo->prepare($newProfilePic);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':photo',$photo);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
	} //END APPLICATION MODELS CLASS
?>