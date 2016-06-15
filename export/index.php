<?php
require("../function/common.php");
$ok = true;
$timeout = false;
$groupid = $_GET["id"];
if($login == false) header("Location: ../login/");
else if(!$login["admin"]){
	exit("你沒有權限");
}
else if (!isset($groupid)) {
	exit("沒有給ID");
}
$query = new query;
$query->table ="bookgroup";
$query->where = array(
	array("groupid", $groupid)
);
$group = fetchone(SELECT($query));
if ($ok && $group == null) {
	exit("找不到ID");
}
$query = new query;
$query->table ="orderlist";
$query->order = array(
	array("account","ASC")
);
$query->where = array(
	array("groupid",$groupid)
);
$orderlist = SELECT($query);
$query = new query;
$query->table ="booklist";
$query->order = array(
	array("bookid","ASC")
);
$query->where = array(
	array("bookgroup",$groupid)
);
$booklist = SELECT($query);
$output="account";
foreach ($booklist as $key => $book) {
	$output.=",".$book["name"];
	$booklist[$key]["count"]=0;
}
$output.=",money\n";
foreach ($orderlist as $order) {
	$output. = $order["account"];
	$money = 0;
	$books = json_decode($order["books"]);
	foreach ($booklist as $key => $book) {
		$output.=",";
		if (in_array($book["bookid"], $books)) {
			$output.="1";
			$money+ = $book["money"];
			$booklist[$key]["count"]++;
		}
	}
	$output.=",".$money."\n";
}
$output.="total";
foreach ($booklist as $key => $book) {
	$output.=",".$booklist[$key]["count"];
}
$output.=",\n";
header('Content-type:application/force-download');
header('Content-Transfer-Encoding: UTF-8');
header('Content-Disposition:attachment;filename = export.csv');
echo $output;
?>
