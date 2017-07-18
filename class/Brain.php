<?php
class Brain{
	/**
	* hasil dari proses akan disimpan
	* di variabel $aiResult
	*/
	protected $message;
	protected $db;
	protected $aiResult;
	protected $memoryData;
	protected $memoryId;
	protected $memoryBadword;
	protected $memorySid;
	protected $memoryTemp;
	protected $memoryFlow;
	protected $text;
	
	
	protected $dbHost = "mysql.idhostinger.com";
	protected $dbUname = "u135667435_azert";
	protected $dbPass = "rahasia";
	protected $dbDatabase = "u135667435_ai";
	
	/**
	protected $dbHost = "localhost";
	protected $dbUname = "root";
	protected $dbPass = "";
	protected $dbDatabase = "azert";
	*/
	
	//meload brain agar bekerja
	function load(){
		$this->db = new DB($this->dbHost, $this->dbUname, $this->dbPass, $this->dbDatabase);
	}
	
	//mengatur memory, atau ingatan
	function setMemory($memory){
		//mendapat data ingatan
		$this->memoryData = $this->db->get_data("conversation", "*", "sid='{$memory}'");
		
		//jika tidak ada ingatan, maka buat ingatan baru
		if(sizeof($this->memoryData) === 0){
			$newMemory = [
				"sid"=> $memory
			];
			
			$this->db->post_data("conversation", $newMemory);
			$this->memoryData = $this->db->get_data("conversation", "*", "sid='{$memory}'");
		}
		
		$data = $this->memoryData[0];
		
		//memasukan data ingatan kedalam variabel
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
		$this->text = strtolower(trim($this->message));
		//filter kata" kasar
		if($this->filterBadWord($this->text)){
			$match = $this->brainMatch();
			if($match){
				//print_r($this->chatBrainId);
				$this->responseList = $this->db->get_data("response", "id_response", "id_chat='{$match}'");
				//mengubahnya menjadi array
				$this->responseArray = explode(" ", $this->responseList[0]["id_response"]);
				print_r($this->responseArray);
				//mendapatkan random response
				$this->responseNum = $this->responseArray[rand(0, sizeof($this->responseArray)-1)];
				//mendapat text response
				$this->response = $this->db->get_data("response_text", "response", "id='{$this->responseNum}'")[0]["response"];
				$maintenanceReport = "System error... \nplease don't chat me while I'm repairs my system!";
				$this->aiResult = $this->response;
			}else{
				$this->aiResult = "Maaf saya tidak mengerti";
			}
		}else{
			$this->chatBrainId = "100";//100 adalah ingatan khusus untuk merespon perkataan kasar
			//mendapatkan list response
			$this->responseList = $this->db->get_data("response", "id_response", "id_chat='{$this->chatBrainId}'");
			//mengubahnya menjadi array
			$this->responseArray = explode(" ", $this->responseList[0]["id_response"]);
			//mendapatkan random response
			$this->responseNum = $this->responseArray[rand(0, sizeof($this->responseArray)-1)];
			//mendapat text response
			$this->response = $this->db->get_data("response_text", "response", "id='{$this->responseNum}'")[0]["response"];
			//output
			$this->aiResult = $this->response;
		}
		
	}
	
	function filterBadWord($text){
		$textArr = explode(" ", $text);
		$badWordList = $this->db->get_data("badword", "word");
		for($i = 0;$i < sizeof($textArr);$i++){
			for($j = 0;$j < sizeof($badWordList);$j++){
				similar_text($textArr[$i], $badWordList[$j]["word"], $percentage);
				//jika kemiripan kata dengan salah satu kata" kasar lebih dari 80% maka dianggap kata kasar
				if($percentage >= 80){
					echo $percentage;
					echo $textArr[$i];
					echo $badWordList[$j]["word"];
					//jika terdapat kata-kata kasar
					return false;
				}
			}
		}
		//jika tidak ada kata kasar
		return true;
	}
	
	function brainMatch(){
		$textArr = explode(" ", $this->text);
		$tempData = array();
		for($i = 0;$i < sizeof($textArr);$i++){
			$temp = $this->db->get_data("chat_input", "id", "chat LIKE '{$textArr[$i]}%' OR chat LIKE '%{$textArr[$i]}%' OR chat REGEXP '{$textArr[$i]}'");
			$this->tempData[$i] = sizeof($temp) !== 0? $temp[0]["id"]: "";
		}
		$result = array_count_values($this->tempData);
		asort($result);
		end($result);
		$result = key($result);
		echo $result;
		return $result;
	}
}
?>