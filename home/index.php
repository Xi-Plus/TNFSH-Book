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
			<th>開放狀態</th>
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
			if (time() < strtotime($bookgroup["starttime"])) {
				echo "未開放";
			} else if (time() > strtotime($bookgroup["endtime"])) {
				echo "已結束";
			} else {
				echo "開放中";
			}
			?></td>
			<td><?php
				if (!$login) {
					echo "請先登入";
				} else if ($bookgroup["grade"] == $login["grade"]) {
					if (time() < strtotime($bookgroup["starttime"])) {
					?>
						<button type="button" class="btn btn-default disabled">
						<span class="glyphicon glyphicon-shopping-cart"></span>
						尚未開放 
						</button>
					<?php
					} else if (in_array($bookgroup["groupid"], $orderlist)) {
					?>
						已訂購
						<a href="../order/?id=<?php echo $bookgroup["groupid"] ?>" class="btn btn-info" role="button">
						<span class="glyphicon glyphicon-shopping-cart"></span>
						修改訂購
						</a>
					<?php
					} else {
					?>
						未訂購
						<a href="../order/?id=<?php echo $bookgroup["groupid"] ?>" class="btn btn-success" role="button">
						<span class="glyphicon glyphicon-shopping-cart"></span>
						立即訂購
						</a>
					<?php
					}
				} else {
					echo "不屬於你的訂購";
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