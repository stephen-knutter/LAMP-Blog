<?php
	class ApplicationModels{
		
		private $mMysql;
		
		public function db_conn(){
			$this->mMysql = new mysqli('localhost', 'root', 'root', 'budvibes');
			if($this->mMysql){
				return $this->mMysql;
			} else {
				return false;
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
		
		function escape($item){
			return $this->mMysql->real_escape_string($item);
		}
	}
?>