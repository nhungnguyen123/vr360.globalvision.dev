<?php
class adminDBObj
{
    public function __construct($_db)
    {
        $this->db = new PDO('mysql:host=localhost;dbname='.$_db['db'].';charset=utf8', $_db['user'], $_db['pass']);
        $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        $this->cQuery = '';
    }
    public function query($returndata = true)
    {
    	$row = Array();
    	$resultHander=$this->db->query($this->cQuery);
    	
    	if($returndata)
    	{
    		while($trow = $resultHander->fetch(PDO::FETCH_ASSOC))
    			$row[] = $trow;
    			 
    			$this->cQuery = '';
    			 
    			$JSONrow=json_encode($row);
    			return $JSONrow;
    	}
    	
    	else return true;
    }
    public function getAllUser()
    {
    	return $this->select('user', 'loginName, userEmail, userFullName, userId')->query();
    }
    public function getAllPano()
    {
    	return $this->select('tbl_vtour', '*')->query();
    }
    public function getPanoByUser($userId)
    {
    	return $this->select('tbl_vtour', '*')->where('user_id', '=',  $userId)->query();
    }    
    public function changePass($userId, $newPass)
    {
    	return $this->update('user', 'userPass', $newPass)->where('userID', '=', $userId)->query(false);
    }
    public function ChangeUserEmail($userId, $newEmail)
    {
    	return $this->update('user', ' 	userEmail', $newEmail)->where('userID', '=', $userId)->query(false);
    }
    public function ChangeUserFullName($userId, $newUserFullname)
    {
    	return $this->update('user', ' 	userFullName', $newUserFullname)->where('userID', '=', $userId)->query(false);
    }
    public function addNewUser($name, $pass, $email, $fullname)
    {
    	return $this->insertInto('user', 'loginName, userPass, userEmail, userFullName', "'$name', '$pass', '$email', '$fullname'")->query(false);
    }
    public function insertInto($table, $colsStr, $valueStr)
    {
    	$this->cQuery = "INSERT INTO $table ($colsStr) VALUES ($valueStr)";
    	return $this;
    }
    public function update($table, $col, $value)
    {
    	$this->cQuery = "UPDATE $table SET $col = '$value' ";
    	return $this;
    }
    public function select($table, $col)
    {
    	$this->cQuery = "SELECT $col FROM $table ";
    	return $this;
    }
    public function where($col, $operate, $value)
    {
    	$this->cQuery .= " WHERE $col $operate '$value'";
    	return $this;
    }
    //limit
    
    //oeder by
        
}
?>