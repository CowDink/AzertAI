<?php
class Brain{
	protected $message;
	protected $db;
	protected $aiResult;
	protected $memoryData;
	protected $memoryId;
	protected $memoryBadword;
	protected $memorySid;
	protected $memoryTemp;
	protected $memoryFlow;
	
	/**
	protected $dbHost = "mysql.idhostinger.com";
	protected $dbUname = "u135667435_azert";
	protected $dbPass = "zxBM94NEgxFv";
	protected $dbTable = "u135667435_ai";
	*/
	
	protected $dbHost = "localhost";
	protected $dbUname = "root";
	protected $dbPass = "";
	protected $dbDatabase = "azert";
	
	
	
	function load(){
		$this->db = new DB($this->dbHost, $this->dbUname, $this->dbPass, $this->dbDatabase);
	}
	
	function setMemory($memory){
		$this->memoryData = $this->db->get_data("conversation", "*", "sid='{$memory}'");
		if(sizeof($this->memoryData) === 0){
			$newMemory = [
				"sid"=> $memory
			];
			$this->db->post_data("conversation", $newMemory);
			$this->memoryData = $this->db->get_data("conversation", "*", "sid='{$memory}'");
		}
		
		$data = $this->memoryData[0];
		
		$this->memoryId = $data["id"];
		$this->memoryBadword = $data["bad_word"];
		$this->memorySid = $data["sid"];
		$this->memoryTemp = $data["temp"];
		$this->memoryFlow = $data["flow"];
		print_r($this->memoryId);
	}
	
	function processCommand(){
		$command = $this->message;
		//mendapatkan data command dari database
		$data = $this->db->get_data("command", "response", "command='{$command}'");
		if(sizeof($data) !== 0){
			$this->aiResult = $data[0]["response"];
		}else{
			$this->aiResult = "Maaf, kamu salah memasukan perintah";
		}
	}
	
	function processText(){
		$text = strtolower(trim($this->message));
		$maintenanceReport = "System error... \nplease don't chat me while I'm repairs my system!";
		$this->aiResult = $text;
	}
}
?>