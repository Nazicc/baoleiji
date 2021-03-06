<?php
if(!defined('CAN_RUN')) {
	exit('Access Denied');
}

abstract class base_set {

	function __construct() {
	}

	function get_table_name() {
		return $this->table_name;
	}
	
	function set_table_name($table_name) {
		$this->table_name = $table_name;
	}

	function base_add($table_name, $data) {
		$sql1 = $sql2 = '';
		//跳过第一个,即xxx_id;
		each($data);
		while (list($key, $val) = each($data)) {
			$sql1 = $sql1 . '`' . $key . '`,';
			$sql2 = $sql2. '\'' . $val . '\'' . ',';
		}
		$sql1 = substr($sql1, 0, strlen($sql1) - 1);
		$sql2 = substr($sql2, 0, strlen($sql2) - 1);
		$query = "INSERT INTO `$table_name`($sql1) VALUES($sql2)";
		$this->query($query);
	}

	function base2_add($table_name, $data) {
		$sql1 = $sql2 = '';
		while (list($key, $val) = each($data)) {
			$sql1 = $sql1 . '`' . $key . '`,';
			$sql2 = $sql2. '\'' . $val . '\'' . ',';
		}
		$sql1 = substr($sql1, 0, strlen($sql1) - 1);
		$sql2 = substr($sql2, 0, strlen($sql2) - 1);
		$query = "INSERT INTO `$table_name`($sql1) VALUES($sql2)";
		$this->query($query);
	}
	
	function base_delete($table_name, $id_name, $id) {
			$query = "DELETE FROM `$table_name` WHERE $id_name = '$id'";
			$this->query($query);
	}

	function base_delete_all($table_name, $id_name, $id_arr) {
		$query = "DELETE FROM `$table_name` WHERE $id_name IN(". "'" . implode("', '", $id_arr) . "')";
		$this->query($query);
	}

	function base_edit($table_name, $data, $flag) {
		//var_dump($flag);
		$sql1 = $sql2 = '';
		if(list($key, $val) = each($data)) {
			$sql2 = "$key = '$val'";
		}

		while (list($key, $val) = each($data)) {
			if(isset($flag[$key]) && $flag[$key] == true) {
				$sql1 = $sql1 . "`$key` = '$val',";
			}
			else {
				//echo "@$key@";
			}
		}
		$sql1 = substr($sql1, 0, strlen($sql1) - 1);
		$query = "UPDATE `$table_name` SET $sql1 WHERE $sql2";
		//print($query);
		$this->query($query);
	}


	function base_select($query) {
		$result = $this->query($query);
		if (!$result) {
			return NULL;
		}
		else if(mysql_num_rows($result) == 0) {
			return NULL;
		}
		else {
			while($row = mysql_fetch_assoc($result)) {
				$data[] = $row;
			}
			return $data;
		}
	}

	function base_select_count($table_name, $where) {
		$query = "SELECT COUNT(*) AS row_num FROM `$table_name` $where";
		$result = $this->query($query);
		if (!$result) {
			return NULL;
		}
		else if(mysql_num_rows($result) == 0) {
			return NULL;
		}
		else {
			$row = mysql_fetch_assoc($result);
			
			return $row['row_num'];
		}
	}

	//通用add,edit,delete
	public function query($query) {
		global $_CONFIG;
		if($_CONFIG['DB_DEBUG']){
			echo $query . "<br>";
		}
		//echo $query;
		$result =  mysql_query($query);
		if($result === false) {
//			echo "SQL:" . $query . "<br>";
			if($_CONFIG['DB_DEBUG']){
				echo "Error:" . mysql_error() . "<br>";
			}else{
				echo "Error: database error<br>";
			}
		}
		return $result;
	}

	public function add($data) {
		$this->base_add($this->table_name, $data->get_all());
	}

	
	public function add2($data) {
		$this->base2_add($this->table_name, $data->get_all());
	}

	public function delete($data_id, $id_name = '') {
		if($id_name == '') {
			$id_name = $this->id_name;		
		}
		if(is_array($data_id)) {
			
			$this->base_delete_all($this->table_name, $id_name, $data_id);
		}
		else {
			$this->base_delete($this->table_name, $id_name, $data_id);
		}
	}

	public function delete2($where) {
		$query = "DELETE FROM `".$this->table_name."` WHERE $where";
		$this->query($query);
	}

	public function delete_all($where = '1=1') {
		$table_name = $this->table_name;
		$query = "DELETE FROM `$table_name` WHERE $where";
		$this->query($query);
	}

	public function edit($data) {
		$this->base_edit($this->table_name, $data->get_all(), $data->get_flag());
	}

	public function select_all($where = '1=1', $orderby1 = '', $orderby2 = 'DESC') {
		if($orderby1 == '') {
			$orderby1 = $this->id_name;
		}
		return $result = $this->base_select("SELECT * FROM `$this->table_name` WHERE $where ORDER BY $orderby1 $orderby2");
	
	}

	public function select_in($id_arr) {
		$query = "SELECT * FROM `$this->table_name` WHERE $this->id_name IN(". "'" . implode("', '", $id_arr) . "')";
		return $this->base_select($query);
	}

	public function select_limit($begin, $end, $where = '1=1', $orderby1 = '', $orderby2 = 'DESC', $orderby3='', $orderby4='DESC') {
		if($orderby1 == '') {
			$orderby1 = $this->id_name;
		}
		if($orderby2 == '') {
			$orderby2 = 'DESC';
		}
		return $result = $this->base_select("SELECT * FROM `$this->table_name` WHERE $where ORDER BY `$orderby1` $orderby2 ".(!empty($orderby3) ? $orderby3." ".$orderby4 : '')." LIMIT $begin, $end");
	}
	
	public function select_count($where = '1=1') {
		return $this->base_select_count($this->table_name, "WHERE $where");
	}


	public function select_by_id($id, $where = '1=1') {
		if(is_numeric($id)) {
			$result =  $this->base_select("SELECT * FROM `$this->table_name` WHERE $this->id_name = '$id' AND $where");
			if($result) return $result[0];
		}
	}

	public function update_single($data_index, $data_data, $id) {
		if(is_numeric($id)) {
			$query = "UPDATE $this->table_name SET `$data_index` = '$data_data' WHERE $this->id_name = '$id'";
			return $this->query($query);
		}
	}

	public function update($data_index, $data_data, $where = '1=1') {
		$query = "UPDATE $this->table_name SET `$data_index` = '$data_data' WHERE $where";
		return $this->query($query);
		
	}

	public function update_add($data_index, $id) {
		if(is_numeric($id)) {
			$query = "UPDATE $this->table_name SET `$data_index` = `$data_index` + 1 WHERE $this->id_name = '$id'";
			return $this->query($query);
		}
	}

	public function udf_encrypt($password){///return $password;
		$p = $this->base_select("SELECT udf_encrypt('".($password)."') as pass");
		return $p[0]['pass'];
	}

	public function udf_decrypt($password){//return $password;
		$p = $this->base_select("SELECT udf_decrypt('".($password)."') as pass");
		return $p[0]['pass'];
	}
}

?>
