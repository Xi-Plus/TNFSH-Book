<?php
function checklogin(){
	if(!isset($_COOKIE["TNFSH_Book"]))return false;
	$query = new query;
	$query->table="session";
	$query->where = array(
		array("cookie",$_COOKIE["TNFSH_Book"])
	);
	$row = fetchone(SELECT($query));
	if($row!==null){
		$query = new query;
		$query->table="account";
		$query->where = array(
			array("account",$row["account"]),
		);
		$temp = fetchone(SELECT($query));
		return $temp;
	}
	return false;
}
?>