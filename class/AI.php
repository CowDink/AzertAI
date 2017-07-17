<?php

class AI{
	protected $bot;
	protected $platform;
	protected $version = 0.2;
	
	
	
	//dipanggil ketika pertama kali dibuat
	function __construct($p){
		if($p){
			$this->platform = $p;
		}else{
			die("you don't choose the platform!");
		}
		
		switch ($this->platform){
			case "WebBot":
				$this->bot = new WebBot;
				break;
			case "TelegramBot":
				$this->bot = new TelegramBot;
				break;
			case "FacebookBot":
				$this->bot = new FacebookBot;
				break;
			default:
				die("AI error, chose the right platform!. \nWebBot \nTelegramBot \nFacebookBot");
		}
	}
	
	public function ai(){
		return $this->bot;
	}
	
	public function getPlatform(){
		return $this->platform;
	}
	
	public function getVersion(){
		return $this->version;
	}
}

?>