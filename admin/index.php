<!DOCTYPE html>
<?php
require("../function/common.php");
if($login == false)header("Location: ../login/?from=admin");
else if($login["grade"] != "admin"){
	addmsgbox("danger", "你沒有權限");
	?><script>setTimeout(function(){location="../home/";}, 1000);</script><?php
}
?>
<html lang="zh-Hant-TW">
<head>
<?php
include_once("../res/comhead.php");
?>
<title>管理首頁-臺南一中教科書訂購系統</title>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php
require("../res/header_admin.php");
if ($login["grade"] == "admin") {
?>
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		<h2>教科書訂購系統 管理首頁</h2>
	</div>
</div>
<?php
	include("../res/footer.php");
}
?>
</body>
</html>