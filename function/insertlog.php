<?php
function insertlog($account, $page, $action, $detail=""){
	if ($account === null) {
		$account = "";
	}
	$query = new query;
	$query->table="log";
	$query->value=array(
		array("account", $account),
		array("page", $page),
		array("action", $action),
		array("detail", $detail)
	);
	INSERT($query);
}
?>