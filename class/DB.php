<?php

//query builder
class DB{
	
	protected $db;
	
	//dipanggil ketika object pertama kali dibuat
	function __construct($host, $user, $pass, $database){
		$this->db = new Mysqli($host, $user, $pass, $database) or die(mysqli_errno());
		date_default_timezone_set("Asia/Jakarta");
	}

	
	public function clearStoredResults(){
		$db = $this->db;
		do {
        	if ($res = $db->store_result()) {
            	$res->free();
        	}
		} while ($db->more_results() && $db->next_result());
	}
	
	
	public function filter($data){
    	$db = $this->db;
    	$data = mysqli_real_escape_string($db,$data);
		return preg_replace('/[^A-Za-z ?-[] !]/s',' ',$data);
	}
	
	
	public function truncate($table){
		$db = $this->db;
		$db->query("TRUNCATE TABLE {$table}") or die($db->error);
		return true;
	}
	
	/**digunakan untuk mendapatkan data (type assoc untuk mendapatkan data bertipe array
	type object digunakan untuk mendapatkan data bertipe object*/
	public function get_data($table, $select="*", $where="", $type="assoc"){
    	$db = $this->db;
    	$query = "SELECT {$select} FROM {$table} ";
    	if($where){
    		$query .= "WHERE {$where}";
    	}
    	$query = $db->query($query) or die($db->error);
    	$i = 0;
    	$fetch = "fetch_{$type}";
    	$data = array();
    	while($row = $query->{$fetch}()){
        	$data[$i] = $row;
            $i++;
		}
		return $data;
	}
	
	//digunakan untuk set data
	public function post_data($table,$data,$where=""){
    	$db = $this->db;
    	$map = "";
    	if(is_array($data)){
    		//memecah value menjadi kalimat dengan pemisah koma
        	$val = "'".implode("','",array_map(array($db, 'real_escape_string'), $data))."'";
        	//memecah key menjadi kalimat dengan pemisah koma
        	$key = "".implode(",",array_map(array($db, 'real_escape_string'), array_keys($data)))."";
        	$map = "({$key})";
    	}
    	else {
        	$val = "'".array_map(array($db, 'real_escape_string'), $data)."'";
    	}
    	//memasukan data
    	$query = "INSERT INTO {$table} {$map} VALUES({$val}) ";
    	if($where){
        	$query .= "WHERE {$where}";
    	}
    	$query = $db->query($query) or die($db->error.$query);
    	return true;
	}
	
	//digunakan untuk mengupdate data ($data adalah array)
	public function update_data($table, $data, $to_update){
		$db = $this->db;
		$query = "UPDATE {$table} SET ";
		$i = 0;
		$length = count($data);
		foreach($data as $key => $value){
			if($i != $length-1){
				$query .= "{$key} = '{$value}',";
			}else{
				//jika data terakhir, tidak dikasih koma
				$query .= "{$key} = '{$value}' ";
			}
			$i++;
		}
		$query .= "WHERE {$to_update}";
		//echo $query;
		$query = $db->query($query) or die($db->error.$query);
		return true;
	}
	
	//digunakan untuk menghapus data
	public function delete_data($table,$where){
    	$db = $this->db;
    	$query = "DELETE FROM {$table} WHERE {$where}";
    echo $query;
    	$db->query($query) or die($db->error);
    	return true;
	}
	
	//custom query
	public function customQuery($query){
    	$db = $this->db;
    	$query = $db->query($query) or die($db->error);
    	return true;
	}
	
	
	private function child_result($data){
    	foreach($data as $inner) {
        	$result[key($inner)] = current($inner);
    	}
    	return $result;
	}
}

?>