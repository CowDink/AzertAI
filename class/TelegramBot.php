<?php
class TelegramBot extends Brain{
	protected $token;
	protected $chatId;
	protected $type;
	protected $result;
	protected $rawData;
	protected $rawDataArr;
	protected $botName;
	protected $brain;
	protected $web = "https://api.telegram.org/bot";
	
	//dipanggil ketika pertama kali dibuat
	function __construct(){
		//load brain
		Brain::load();
		//membuat mail sender
		$this->mail = new MailSender("test@gmail.com", "mail.cowdink@gmail.com", "debug TelegramBot azert", $this->aiResult);
	}
	
	/**
	* mengatur nama bot
	* penting jika memasukan bot
	* kedalam group dan melakukan
	* command atau chat
	* dengan cara tag si bot
	* contoh
	* input asli: /help
	* input: /help@NamaBot (jika di group)
	* nama digunakan untuk di replace
	* input yang dikirim ke otak:
	* /help
	*/
	public function name($n){
		$this->botName = $n;
	}
	
	//set token
	public function token($t){
		$this->token = $t;
	}
	
	/**
	* set chat id untuk
	* dijadikan ingatan atau
	* mengingat task brain
	* dan mengirim ke telegram
	*/
	public function setChatId($c){
		$this->chatId = $c;
		//set brain
		$this->setBrainId();
	}
	
	/**
	* set method
	* jika menggunakan webhook
	* maka  $bot->ai()->method("wh");
	* jika masih menggunakan
	* getupdates
	* $bot->ai()->method("gu");
	* untuk saat ini getupdates 
	* masih belum jadi
	*/
	public function method($m){
		if($m == "wh"){
			$this->rawData = file_get_contents("php://input");
			$this->rawDataArr = json_decode($this->rawData, true);
			return true;
		}else if($m == "gu"){
			//dont use this
			$this->rawData = file_get_contents($this->web.$this->token."/getupdates");
			$this->rawDataArr = json_decode($this->rawData, true);
			return true;
		}else{
			die("just use wh or gu");
			return false;
		}
	}
	
	/**
	* auto data
	* otomatis mengatur
	* data kedalam variabel
	* gunakan fungsi ini
	* untuk mengatur semua data
	* secara otomatis
	*
	* jika menggunakan auto data
	* pastikan jika
	* sudah set method
	* $bot->ai()->method();
	*/
	public function autoData(){
		if($this->rawDataArr){
			$this->setChatId($this->rawDataArr["message"]["chat"]["id"]);
			$this->setMessage($this->rawDataArr["message"]["text"]);
		}
	}
	
	/**
	* setBrainId
	* menjadikan chat id
	* sebagai brain id
	* atau ingatan
	*/
	public function setBrainId(){
		Brain::setMemory($this->chatId);
	}
	
	//set pesan
	public function setMessage($m){
		$m = str_replace($this->botName, "", $m);
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
			return $this->result;
		}
		return "you don't process the message";
	}
}
?>