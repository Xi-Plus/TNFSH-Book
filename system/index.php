<!DOCTYPE html>
<?php
require("../function/common.php");
if($login == false)header("Location: ../login/?from=system");
else if($login["grade"] != "admin"){
	addmsgbox("danger", "你沒有權限");
	?><script>setTimeout(function(){location="../home";}, 1000);</script><?php
	insertlog($login["account"], "system", "access_denied");
}
else if (isset($_POST["id"])) {
	switch ($_POST["type"]) {
		case 'textarea':
		case 'text':
			$query = new query;
			$query->table = "system";
			$query->value = array("value", $_POST["value"]);
			$query->where = array("id", $_POST["id"]);
			UPDATE($query);
			break;
		case 'checkbox':
			$query = new query;
			$query->table = "system";
			$query->value = array("value", (isset($_POST["value"])?"1":"0"));
			$query->where = array("id", $_POST["id"]);
			UPDATE($query);
			break;
	}
	addmsgbox("success", "已修改 ".$_POST["id"]);
	insertlog($login["account"], "system", "edit", $_POST["id"]." ".$_POST["value"]);
} else {
	insertlog($login["account"], "system", "view");
}
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/comhead.php");
?>
<title>系統設定-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/header_admin.php");
if($login["grade"] == "admin"){
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
		<h2>系統設定</h2>
		<table width="0" border="0" cellspacing="10" cellpadding="0" class="table">
		<tr>
			<th>ID</th>
			<th>值</th>
			<th>註解</th>
			<th>操作</th>
		</tr>
		<?php
		$query = new query;
		$query->table = "system";
		$row = SELECT($query);
		foreach ($row as $temp) {
		?>
		<form method="post">
		<input type="hidden" name="id" value="<?php echo $temp["id"]; ?>">
		<input type="hidden" name="type" value="<?php echo $temp["type"]; ?>">
		<tr>
			<td><?php echo $temp["id"]; ?></td>
			<td><?php
			switch ($temp["type"]) {
				case 'textarea':
					?><textarea name="value" cols="50" rows="3"><?php echo htmlspecialchars($temp["value"]); ?></textarea><?php
					break;
				case 'text':
					?><input name="value" type="text" size="50" value="<?php echo $temp["value"]; ?>"><?php
					break;
				case 'checkbox':
					?><input name="value" type="checkbox" <?php echo ($temp["value"]?"checked":"");?>><?php
					break;
			}
			?></td>
			<td><?php echo $temp["comment"]; ?></td>
			<td>
				<button type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon-pencil"></span>
					修改
				</button>
			</td>
		</tr>
		</form>
		<?php
		}
		?>
		</table>
<?php
	}
	include("../res/footer.php");
?>
</body>
</html>