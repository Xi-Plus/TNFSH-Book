<!DOCTYPE html>
<?php
require("../function/common.php");
$ok = true;
$timeout = false;
$groupid = $_GET["id"];
if($login == false)header("Location: ../login/");
else if (!isset($groupid)) {
	addmsgbox("danger","沒有給ID");
	$ok = false;
}
$query = new query;
$query->table ="bookgroup";
$query->where = array(
	array("groupid",$groupid)
);
$group = fetchone(SELECT($query));
if ($ok && $group === null) {
	addmsgbox("danger", "找不到ID");
	$ok = false;
} else if ($group["grade"] != $login["grade"]) {
	addmsgbox("danger", "不屬於你的訂購");
	$ok = false;
}else if (time() < strtotime($group["starttime"])) {
	addmsgbox("danger", "尚未達訂購時間");
	$ok = false;
} else if (time() > strtotime($group["endtime"])) {
	addmsgbox("warning", "已超過訂購時間，只允許檢視");
	$timeout = true;
}
$query = new query;
$query->table ="orderlist";
$query->where = array(
	array("account", $login["account"]),
	array("groupid", $groupid)
);
$temp = fetchone(SELECT($query));
$orderlist = array();
if ($temp !== null) {
	$orderlist = json_decode($temp["books"]);
}
if(isset($_POST["order"])){
	if ($timeout) {
		addmsgbox("danger", "timeout");
	}else {
		$neworder = array();
		if (isset($_POST["orderlist"])) {
			foreach ($_POST["orderlist"] as $key => $value) {
				$neworder[] = (int)$key;
			}
			if (count($orderlist) !== 0) {
				$query = new query;
				$query->table ="orderlist";
				$query->value = array(
					array("books", json_encode($neworder)),
					array("hash", md5(json_encode([$login["account"],$groupid,$neworder])))
				);
				$query->where = array(
					array("account", $login["account"]),
					array("groupid", $groupid)
				);
				UPDATE($query);
				addmsgbox("success", "已成功修改訂單");
			} else {
				$query = new query;
				$query->table ="orderlist";
				$query->value = array(
					array("account", $login["account"]),
					array("groupid", $groupid),
					array("books", json_encode($neworder)),
					array("hash", md5(json_encode([$login["account"],$groupid,$neworder])))
				);
				INSERT($query);
				addmsgbox("success", "已完成新訂購");
			}
			$orderlist = $neworder;
		} else {
			$query = new query;
			$query->table = "orderlist";
			$query->where = array(
				array("account", $login["account"]),
				array("groupid", $groupid)
			);
			DELETE($query);
			addmsgbox("success", "已刪除訂單");
			$orderlist = array();
		}
	}
}else if(isset($_POST["del"])){
	if ($timeout) {
		addmsgbox("danger", "timeout");
	}else {
		$query = new query;
		$query->table ="orderlist";
		$query->where = array(
			array("account", $login["account"]),
			array("groupid", $groupid)
		);
		DELETE($query);
		addmsgbox("success", "已刪除訂單");
		$orderlist = array();
	}
}
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/comhead.php");
?>
<title>訂購-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/header_user.php");
if($ok){
?>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<h2>訂購</h2>
		<div class="row">
			<div class="col-md-12">
				<form method="post">
				<input type="hidden" name="order">
				<div class="table-responsive">
				<table cellspacing="0" cellpadding="2" class="table table-hover table-condensed">
				<tr>
					<th>ID</th>
					<th>名稱</th>
					<th>金額</th>
					<th>訂購勾選</th>
				</tr>
				<?php
				$query = new query;
				$query->table = "booklist";
				$query->where = array(
					array("bookgroup",$groupid)
				);
				$row = SELECT($query);
				$money = 0;
				foreach($row as $book){
				?>
				<tr>
					<td><?php echo $book["bookid"]; ?></td>
					<td><?php echo $book["name"]; ?></td>
					<td><?php echo $book["money"]; ?></td>
					<td>
						<input type="checkbox" name="orderlist[<?php echo $book["bookid"]; ?>]" <?php
						if (in_array($book["bookid"], $orderlist)) {
							echo "checked";
							$money += $book["money"];
						}
						?>>
					</td>
				</tr>
				<?php
				}
				?>
				</table>
				</div>
				總金額: <?php echo $money; ?> 元<br>
				<?php
				if (!$timeout) {
				?>
				<button name="input" type="submit" class="btn btn-success">
					<?php
					if (count($orderlist) == 0) {
						?><span class="glyphicon glyphicon-check"></span>送出新訂單<?php
					} else {
						?><span class="glyphicon glyphicon-edit"></span>修改訂單<?php
					}
					?>
				</button>
				</form>
				<?php
					if (count($orderlist) != 0) {
				?>
					<form method="post">
					<input type="hidden" name="del">
					<button name="input" type="submit" class="btn btn-danger">
						<span class="glyphicon glyphicon-trash"></span>
						刪除這筆訂單
					</button>
					</form>
				<?php
					}
				}
				?>
			</div>
		</div>
	</div>
	<div class="col-md-1"></div>
</div>
<?php
	}
	require("../res/footer.php");
?>
</body>
</html>