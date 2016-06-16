<!DOCTYPE html>
<?php
require("../function/common.php");
function loginsuccess($row){
	global $noshow;
	$cookie = getrandommd5();
	setcookie("TNFSH_Book", $cookie, time()+86400*7, "/");
	$query = new query;
	if (isset($_GET["admin"])) {
		$query->table = "adminsession";
	} else {
		$query->table = "usersession";
	}
	$query->value = array(
		array("account", $row["account"]),
		array("cookie", $cookie)
	);
	INSERT($query);
	addmsgbox("success", "登入成功", false);
	$noshow = false;
	?><script>setTimeout(function(){location="../<?php 
		if (isset($_GET["from"])) {
			echo $_GET["from"];
		} else if (isset($_GET["admin"])) {
			echo "admin";
		} else{
			echo "home";
		}
	?>";}, 3000)</script><?php
}
$noshow = true;
$nosignup = true;
if(checklogin()){
	addmsgbox("info", "你已經登入了", false);
	$noshow = false;
	?><script>setTimeout(function(){location="../home";}, 1000)</script><?php
} else if(isset($_POST['user'])){
	$query = new query;
	if (isset($_GET["admin"])) {
		$query->table = "adminlist";
	} else {
		$query->table = "userlist";
	}
	$query->where = array(
		array("account", $_POST['user'])
	);
	$row = fetchone(SELECT($query));
	if($row===null){
		addmsgbox("danger", "無此帳號");
	} else if(password_verify($_POST['pwd'],$row["password"])){
		loginsuccess($row);
	} else{
		addmsgbox("danger", "密碼錯誤");
	}
}
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/comhead.php");
?>
<title><?php echo (isset($_GET["admin"])?"管理員":"學生"); ?>登入-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	if (isset($_GET["admin"])) {
		require("../res/header_admin.php");
	} else {
		require("../res/header_user.php");
	}
	if($noshow){
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
		<h2><?php echo (isset($_GET["admin"])?"管理員":"學生"); ?>登入</h2>
			<form method="post">
				<div class="input-group">
					<span class="input-group-addon">帳號</span>
					<input class="form-control" name="user" type="text" value="<?php echo @$_POST['user'];?>" maxlength="32" required>
					<span class="input-group-addon glyphicon glyphicon-user"></span>
				</div>
				<div class="input-group">
					<span class="input-group-addon">密碼</span>
					<input class="form-control" name="pwd" type="password" required>
					<span class="input-group-addon glyphicon glyphicon-asterisk"></span>
				</div>
				<button type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon-hand-right"></span>
					登入 
				</button>
			</form>
<?php
	}
	include("../res/footer.php");
?>
</body>
</html>