<!DOCTYPE html>
<?php
require("../function/common.php");
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/comhead.php");
?>
<title>訂購列表-臺南一中教科書訂購系統</title>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php
require("../res/header_user.php");
?>
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		<h2>訂購列表</h2>
		<table width="0" border="0" cellspacing="10" cellpadding="0" class="table">
		<tr>
			<th>ID</th>
			<th>名稱</th>
			<th>開始時間</th>
			<th>結束時間</th>
			<th>狀態</th>
			<th>操作</th>
		</tr>
		<?php
		$query = new query;
		$query->table = "orderlist";
		$query->where = array(
			array("account",$login["account"])
		);
		$row = SELECT($query);
		$orderlist = array();
		foreach ($row as $temp) {
			$orderlist[] = $temp["groupid"];
		}
		$query = new query;
		$query->table = "bookgroup";
		$query->order = array(
			array("endtime","ASC")
		);
		$row = SELECT($query);
		$nobookgroup = true;
		foreach ($row as $bookgroup) {
			$nobookgroup = false;
		?>
		<tr>
			<td><?php echo $bookgroup["groupid"]; ?></td>
			<td><?php echo $bookgroup["name"]; ?></td>
			<td><?php echo $bookgroup["starttime"]; ?></td>
			<td><?php echo $bookgroup["endtime"]; ?></td>
			<td><?php
				if (in_array($bookgroup["groupid"], $orderlist)) {
					echo "已訂購";
				} else if ($bookgroup["grade"] == $login["grade"]) {
					echo "未訂購";
				} else if ($login) {
					echo "不屬於你的訂購";
				} else {
					echo "請先登入";
				}
			?></td>
			<td><?php 
				if ($bookgroup["grade"] == $login["grade"]) {
					?><a href="../order/?id=<?php echo $bookgroup["groupid"] ?>">訂購</a><?php
				}			
			?></td>
		</tr>
		<?php
		}
		if ($nobookgroup) {
		?>
		<tr>
			<td colspan="7" align="center">無任何訂購</td>
		</tr>
		<?php
		}
		?>
		</table>
	</div>
</div>
<?php
	require("../res/footer.php");
?>
</body>
</html>