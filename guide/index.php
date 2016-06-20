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
		<a href="tnfsh-book-user-guide.pdf" target="_blank" role="button" class="btn btn-success">下載學生說明書</a>
		<a href="tnfsh-book-admin-guide.pdf" target="_blank" role="button" class="btn btn-success">下載管理員說明書</a>
	</div>
</div>
<?php
	include("../res/footer.php");
?>
</body>
</html>