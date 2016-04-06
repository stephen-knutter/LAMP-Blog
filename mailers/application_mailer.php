<?php
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	class ApplicationMailer{
		private $mail;
		private $subject;
		private $to;
		private $message;
		private $alt;
		private $type;
		
		public function __construct(){
			$this->mail = new PHPMailer;
		}
		
		public function setMessage($subject,$to,$alt,$type=''){
			$this->subject = $subject;
			$this->to = $to;
			$this->alt = $alt;
			$this->type = $type;
			
			switch($this->type){
				case 'store':
					$this->message = '<p>Registration is complete and your new password is&nbsp;<b>'.$this->alt.'</b>&nbsp; log in with your email and click the edit button to change</p><br/>';
				break;
				case 'reset':
					$this->message = '<p>New store to be set up for billing with a store id of <b>'.$this->alt.'</b></p>';
				break;
				default:
					$this->message = '<p>Click or Copy and Paste the link to complete registration</p><br/>&nbsp;<a href='.$this->alt.'>Complete Registration</a>&nbsp;';
				break;
			}
		}
		
		public function sendMail(){
			$this->mail->IsSMTP();
			$this->mail->Host = __MAILHOST__;
			$this->mail->SMTPAuth = true;
			$this->mail->SMTPSecure = "ssl";
			$this->mail->Port = __MAILPORT__;
			$this->mail->Username = __MAILFROM__;
			$this->mail->Password = __MAILPASS__;
			if(__MODE__ == 'DEVELOPMENT'){
				$this->mail->SMTPDebug= 2;
			}
			$this->mail->SetFrom(__MAILFROM__,"Vibes");
			$this->mail->Subject = $this->subject;
			$this->mail->AltBody = "To view message use HTML";
			$this->mail->MsgHTML($this->message);
			$this->mail->AddAddress($this->to,"");
			if($this->mail->Send()){
				return true;
			} else {
				return false;
			}
		}
		
	}//END APPLICATION MAILER CLASS
	