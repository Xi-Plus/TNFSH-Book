<?php
function checklogin(){
	if(!isset($_COOKIE["TNFSH_Book"]))return false;
	$query = new query;
	$query->table="usersession";
	$query->where = array(
		array("cookie",$_COOKIE["TNFSH_Book"])
	);
	$row = fetchone(SELECT($query));
	if($row!==null){
		$query = new query;
		$query->table="userlist";
		$query->where = array(
			array("account",$row["account"]),
		);
		$temp = fetchone(SELECT($query));
		$temp["admin"]=false;
		return $temp;
	}
	$query = new query;
	$query->table="adminsession";
	$query->where = array(
		array("cookie",$_COOKIE["TNFSH_Book"])
	);
	$row = fetchone(SELECT($query));
	if($row!==null){
		$query = new query;
		$query->table="adminlist";
		$query->where = array(
			array("account",$row["account"]),
		);
		$temp = fetchone(SELECT($query));
		$temp["admin"]=true;
		return $temp;
	}
	return false;
}
?>