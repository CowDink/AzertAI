<?php
class MailSender{
	function __construct($from, $to, $subject, $message){
		$this->to = $to;
		$this->subject = $subject;
		$this->message = $message;
		$this->headers = "From:{$from}";
	}
	
	public function setMessage($m){
		$this->message = $m;
		return true;
	}
	
	public function send(){
		mail($this->to ,$this->subject ,$this->message , $this->headers);
	}
}
?>