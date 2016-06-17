<!DOCTYPE html>
<?php
require("../function/common.php");
$login = false;
$query = new query;
$query->table = "session";
$query->where = array(
	array("cookie", @$_COOKIE["TNFSH_Book"])
);
DELETE($query);
setcookie("TNFSH_Book", "", time(), "/");
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/comhead.php");
?>
<title>登出-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	addmsgbox("success", "已登出", false);
	if ($login["grade"] == "admin") {
		require("../res/header_admin.php");
	} else {
		require("../res/header_user.php");
	}
	require("../res/footer.php");
?>
<script>setTimeout(function(){location="../home/";},3000)</script>
</body>
</html>