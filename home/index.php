<!DOCTYPE html>
<?php
require("../function/common.php");
insertlog($login["account"], "home", "view");
?>
<html lang="zh-Hant-TW">
<head>
<?php
include_once("../res/comhead.php");
?>
<title>首頁-臺南一中教科書訂購系統</title>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php
require("../res/header_user.php");
?>
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		<?php
		$query = new query;
		$query->table = "system";
		$query->where = array(
			array("id", "announcement_user")
		);
		echo fetchone(SELECT($query))["value"];
		?>
	</div>
</div>
<?php
	include("../res/footer.php");
?>
</body>
</html>