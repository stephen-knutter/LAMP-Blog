<?php
	include('application_controller.php');
	include('../../models/User.php');
	
	class UsersCtrl extends ApplicationCtrl{
		
		private $username;
		private $email;
		private $password;
		private $confirmation;
		private $UserModel;
		private $errors = array();
		
		public function __construct(){
			$this->UserModel = new User;
		}
		
		public function validate_user($username,$email,$password,$confirmation){
			$this->username = $username;
			$this->email = $email;
			$this->password = $password;
			$this->confirmation = $confirmation;
			
			$name_check = $this->validate_username();
			$name_success = $name_check['success'];
			if(!$name_success){
				$this->errors['username'] = $name_check['username'];
			}
			
			$email_check = $this->validate_email();
			$email_success = $email_check['success'];
			if(!$email_success){
				$this->errors['email'] = $email_check['email'];
			}
			
			$pass_check = $this->validate_password();
			$pass_success = $pass_check['success'];
			if(!$pass_success){
				$this->errors['password'] = $pass_check['password'];
			}
			
			if(empty($this->errors)){
				$this->UserModel->add_user($this->username, $this->email, $this->password);
			} else {
				return $this->errors;
			}
		}
		
		private function validate_username(){
			$valid_user = $this->UserModel->validate_username($this->username);
		}
		private function validate_email(){
			$valid_email = $this->UserModel->validate_email($this->email);
		}
		private function validate_password(){
			$valid_pass = $this->UserModel->validate_password($this->password, $this->confirmation);
		}
		
	}
?>