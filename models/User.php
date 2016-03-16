<?php
	include('application_models.php');
	
	class User extends ApplicationModels{
		
		private $url;
		private $conn;
		private $errors = array();
		
		public function __construct(){
			$this->conn = $this->db_conn();
		}
		
		#VERIFY USERNAME
		function validate_username($username){
			if( (strlen($username) >= 4) && (strlen($username) <=29) ){
				
				#$reserved_words = array("sign-up", "sign-in", "privacy", "terms", "claim", "finish", "tags", "users", "weed", "stores", 
				#"forums", "profile", "posts", "photos", "videos", "buds", "followers", "following", "edit", "menu", "forgot");
				$reserved_words = array();
				if(in_array($username, $reserved_words)){
					$this->$errors['username'] = 'Invalid username';
					$this->$errors['success'] = false;
				} else {
					$regex = "/^[a-zA-Z0-9 \-\'\&]+$/";
					if(!preg_match($regex, $username)){
						$this->errors['username'] = 'Invalid username';
						$this->errors['success'] = false;
					} else {
						$this->url = $this->create_url($username);
						$query = "SELECT slug FROM users WHERE slug='".$url."'";
						$result = $this->conn->query($query);
						$num = $result->num_rows;
						if($num){
							$this->errors['username'] = 'Username already in use';
							$this->errors['success'] = false;
						} else {
							$this->errors['success'] = true;
						}
					}
				}
			} else {
				$this->errors['username'] = 'Username must be 4 to 29 characters';
				$this->errors['success'] = false;
			}
			return $this->errors;
		}
		#VERIFY EMAIL
		function validate_email($email){
			if(filter_var($email, FILTER_VALIDATE_EMAIL) ){
				$query = "SELECT email FROM users WHERE email='".$email."'";
				$result = $this->conn->query($query);
				$num = $result->num_rows;
				if($num){
					$this->errors['email'] = 'Email has been registered';
					$this->errors['success'] = false;
				} else {
					$this->errors['success'] = true;
				}
			} else {
				$this->errors['email'] = 'Invalid email';
				$this->errors['success'] = false;
			}
			return $this->errors;
		}
		#VERIFY PASSWORD
		function validate_password($password,$confirmation){
			if( (strlen($password) >= 5) && strlen($password) <= 50){
				if($password == $confirmation){
					$this->errors['success'] = true;
				} else {
					$this->errors['password'] = 'Passwords do not match';
					$this->errors['success'] = false;
				}
			} else {
				$this->errors['password'] = 'Invalid password';
				$this->errors['success'] = false;
			}
			return $this->errors;
		}
		#ADD NEW USER
		function add_user($username,$email,$password){
			$query = "INSERT INTO users (username,slug,email,password_digest,created_at,updated_at) VALUES('".$this->escape($username)."', 
			'".$this->escape($this->url)."', '".$this->escape($email)."', '".sha1($this->escape($password))."', NOW(), NOW()) LIMIT 1";
			if($this->conn->query($query)){
				$new_id = $this->conn->insert_id;
				$query = "SELECT username,slug,email FROM users WHERE id='".$new_id."'";
				$result = $this->conn->query($query);
				$user = $result->fetch_assoc();
				return $user || false;
			} else {
				return false;
			}
		}
		
	}
?>