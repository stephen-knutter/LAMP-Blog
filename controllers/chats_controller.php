<?php
	require dirname(__DIR__) . '/models/Chat.php';
	
	class ChatsCtrl extends ApplicationCtrl{
		
		private $ChatModel;
		private $Helper;
		private $Views;
		private $Mailer;
		
		public function __construct(){
			$this->ChatModel = new Chat;
			$this->Helper    = new ApplicationHelper;
			$this->Mailer    = new ApplicationMailer;
			$this->Views     = new ApplicationViews;
		}
		
		public function checkChatThread($sessionId,$chatWithId){
			$chatThread = $this->ChatModel->getChatStatus($sessionId,$chatWithId);
			return $chatThread;
		}
		
		public function getUserById($userId){
			$user = $this->ChatModel->getUserByIdFromChat($userId);
			return $user;
		}
		
		public function markChatToRead($sessionId,$parent){
			$markAsRead = $this->ChatModel->setChatToRead($sessionId,$parent);
			return $markAsRead;
		}
		
		public function getChatThread($parent){
			$chatThread = $this->ChatModel->findChatThread($parent);
			return $chatThread;
		}
		
		public function addNewThreadMsg($parent,
		                                $sessionId,
									    $chatWithId,
									    $msgType,
									    $msgPic,
									    $message){
			$threadId = $this->ChatModel->insertNewThreadMsg($parent,
														     $sessionId,
														     $chatWithId,
														     $msgType,
														     $msgPic,
														     $message);
			return $threadId;
		}
		
		public function getEmojiList(){
			$emojis = $this->ChatModel->findEmojis();
			return $emojis;
		}
		
		public function checkForNewMsg($chatWithId){
			$newMessage = $this->ChatModel->getNewMessage($chatWithId);
			return $newMessage;
		}
	}