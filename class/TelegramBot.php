<?php
class TelegramBot extends Brain{
	protected $token;
	protected $chatId;
	protected $message;
	protected $type;
	protected $result;
	protected $rawData;
	protected $rawDataArr;
	protected $botName;
	protected $brain;
	protected $web = "https://api.telegram.org/bot";
	
	function __construct(){
		Brain::load();
		$this->mail = new MailSender("test@gmail.com", "mail.cowdink@gmail.com", "debug TelegramBot azert", $this->aiResult);
	}
	
	public function token($t){
		$this->token = $t;
	}
	
	public function setChatId($c){
		$this->chatId = $c;
		$this->setBrainId();
	}
	
	public function method($m){
		if($m == "wh"){
			$this->rawData = file_get_contents("php://input");
			$this->rawDataArr = json_decode($this->rawData, true);
			return true;
		}else if($m == "gu"){
			$this->rawData = file_get_contents($this->web.$this->token."/getupdates");
			$this->rawDataArr = json_decode($this->rawData, true);
			return true;
		}else{
			die("just use wh or gu");
			return false;
		}
	}
	
	public function autoData(){
		if($this->rawDataArr){
			$this->chatId = $this->rawDataArr["message"]["chat"]["id"];
			$this->message = (string)$this->rawDataArr["message"]["text"];
			$this->message = str_replace("@AzertBot", "", $this->message);
			file_get_contents($this->web.$this->token."/sendmessage?chat_id={$this->chatId}&text={$this->message}");
		}
		$this->setBrainId();
	}
	
	public function setBrainId(){
		Brain::setMemory($this->chatId);
	}
	
	//set pesan
	public function setMessage($m){
		$m = $this->db->filter($m);
		$this->message = $m;
	}
	
	//get pesan
	public function getMessage(){
		return $this->message;
	}
	
	//proses
	public function process(){
		$type = $this->getType();
		
		//memproses menurut tipe
		if($type == "command"){
			Brain::processCommand();
		}else{
			Brain::processText();
		}
	}
	
	//mendapat tipe pesan
	public function getType(){
		if($this->message[0] == "/"){
			return "command";
		}
		return "text";
	}
	
	//menampilkan jawaban
	public function getResponse(){
		if($this->aiResult){
			return $this->aiResult;
		}
		return "you don't process the message";
	}
	
	public function echoAll(){
		//print_r($this->token."<br/>");
	    print_r($this->chatId."<br/>");
		print_r($this->message."<br/>");
		print_r($this->getType()."<br/>");
		print_r($this->aiResult."<br/>");
		print_r($this->result."<br/>");
		print_r($this->rawDataArr."<br/>");
	}
	
	//mengirimkan jawaban
	public function send(){
		if($this->aiResult){
			$this->aiResult = urlencode($this->aiResult);
			$this->result = file_get_contents($this->web.$this->token."/sendmessage?chat_id={$this->chatId}&text={$this->aiResult}");
			file_get_contents($this->web.$this->token."/sendmessage?chat_id={$this->chatId}&text={$this->message}");
			echo $this->result;
			return $this->result;
		}
		return "you don't process the message";
	}
}
?>