<?php
	class Chat extends ApplicationModels{
		
		public function getChatStatus($sessionId,$chatWithId){
			$chatStatus = "SELECT id FROM messages 
			WHERE user_one IN(SELECT user_one FROM messages WHERE user_one=:sessionId AND user_two=:chatWithId) 
			OR user_one IN(SELECT user_one FROM messages WHERE user_one=:chatWithId AND user_two=:sessionId) 
			AND parent=0";
			$statement = $this->pdo->prepare($chatStatus);
			$statement->bindValue(':sessionId',$sessionId,PDO::PARAM_INT);
			$statement->bindValue(':chatWithId',$chatWithId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}
		public function getUserByIdFromChat($userId){
			$user = $this->getUserById($userId);
			return $user;
		}
		
		public function setChatToRead($sessionId,$parent){
			$setChatRead = "UPDATE messages SET status='r' 
			WHERE parent=:parent  
			OR id=:parent 
			AND user_two=:sessionId";
			$statement = $this->pdo->prepare($setChatRead);
			$statement->bindValue(':parent',$parent,PDO::PARAM_INT);
			$statement->bindValue(':sessionId',$sessionId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function findChatThread($parent){
			$chatThread = "SELECT m.id, m.parent, m.user_one, 
			m.user_two, m.message_type, m.comm_text, m.pic, m.created_at, 
			u.id AS user_id, u.username, u.profile_pic FROM messages m 
		    INNER JOIN users u ON u.id = m.user_one 
		    WHERE m.id=:parent 
		    OR m.parent=:parent 
			ORDER BY m.created_at DESC";
			$statement = $this->pdo->prepare($chatThread);
			$statement->bindValue(':parent',$parent,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function insertNewThreadMsg($parent,
										   $sessionId,
										   $chatWithId,
										   $msgType,
										   $msgPic,
										   $message){
			$newThread = "INSERT INTO messages 
			VALUES('NULL', :parent, 'u', :sessionId, 
			:chatWithId, :msgType, :message, :msgPic, NOW())";
			$statement = $this->pdo->prepare($newThread);
			$statement->bindValue(':parent',$parent,PDO::PARAM_INT);
			$statement->bindValue(':sessionId',$sessionId,PDO::PARAM_INT);
			$statement->bindValue(':chatWithId',$chatWithId,PDO::PARAM_INT);
			$statement->bindValue(':msgType',$msgType);
			$statement->bindValue(':msgPic',$msgPic);
			$statement->bindValue(':message',$message);
			$statement->execute();
			return $statement->rowCount() ? $this->pdo->lastInsertId() : false;
		}
		
		public function findEmojis(){
			$emojis = "SELECT id, pic, name FROM emojis";
			$statement = $this->pdo->prepare($emojis);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function getNewMessage($chatWithId){
			//USER_ONE FROM MESSAGES IS ALWAYS PERSON WHO MADE POST
			$messageUpdate = "SELECT m.id, m.status, m.user_one, 
			m.user_two, m.message_type, m.comm_text, m.pic, m.created_at,
		    u.id AS user_id, u.username, u.profile_pic 
		    FROM messages m 
		    INNER JOIN users u ON u.id = m.user_one 
		    WHERE m.status='u' 
			AND m.user_one=:chatWithId";
			$statement = $this->pdo->prepare($messageUpdate);
			$statement->bindValue(':chatWithId',$chatWithId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
	}