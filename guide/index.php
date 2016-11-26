<!DOCTYPE html>
<?php
require("../function/common.php");
insertlog($login["account"], "guide", "view");
?>
<html lang="zh-Hant-TW">
<head>
<?php
include_once("../res/comhead.php");
?>
<title>使用說明-臺南一中教科書訂購系統</title>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php
require("../res/header_user.php");
?>
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		<h2>使用說明</h2>
		<?php 
		$query = new query;
		$query->table = "system";
		$query->where = array(
			array("id", "guidebook_user")
		);
		$guidebook_user = fetchone(SELECT($query))["value"];
		$query = new query;
		$query->table = "system";
		$query->where = array(
			array("id", "guidebook_admin")
		);
		$guidebook_admin = fetchone(SELECT($query))["value"];
		?>
		<a href="<?php echo $guidebook_user; ?>" target="_blank" role="button" class="btn btn-success">學生說明書</a>
		<a href="<?php echo $guidebook_admin; ?>" target="_blank" role="button" class="btn btn-success">管理員說明書</a>
	</div>
</div>
<?php
	include("../res/footer.php");
?>
</body>
</html>