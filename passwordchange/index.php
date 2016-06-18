<!DOCTYPE html>
<?php
require("../function/common.php");
if($login == false)header("Location: ../login/?from=passwordchange");
else if(isset($_POST['pwd'])){
	$account = $login["account"];
	if (isset($_POST["user"])) {
		$account = $_POST["user"];
	}
	$query = new query;
	$query->table = "account";
	$query->where = array(
		array("account", $account)
	);
	$temp = fetchone(SELECT($query));
	if ($temp === null) {
		addmsgbox("danger", "無此帳號");
		insertlog($login["account"], "passwordchange", "error", $account." not found");
	} else if($login["account"] == $account && !password_verify($_POST['oldpwd'], $temp["password"])){
		addmsgbox("danger", "舊密碼錯誤");
		insertlog($login["account"], "passwordchange", "error", $account." wrong password");
	} else if ($_POST["pwd"] != $_POST["pwd2"]) {
		addmsgbox("danger", "新密碼不相符");
		insertlog($login["account"], "passwordchange", "error", $account." password different");
	} else {
		$query = new query;
		$query->table ="account";
		$query->value = array(
			array("password", password_hash($_POST["pwd"], PASSWORD_DEFAULT))
		);
		$query->where = array(
			array("account", $account)
		);
		UPDATE($query);
		addmsgbox("success", "密碼已修改");
		insertlog($login["account"], "passwordchange", "ok", $account);
	}
} else {
	insertlog($login["account"], "passwordchange", "view");
}
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/comhead.php");
if ($config["reCAPTCHA"]["on"]) {
	?><script src='https://www.google.com/recaptcha/api.js'></script><?php
}
?>
<title>變更密碼-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	require("../res/header_user.php");
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
		<h2>變更密碼</h2>
		<form method="post">
			<?php
			if ($login["grade"] == "admin") {
			?>
			<div class="input-group">
				<span class="input-group-addon">帳號</span>
				<input class="form-control" name="user" type="text" maxlength="32" required autofocus>
				<span class="input-group-addon glyphicon glyphicon-user"></span>
			</div>
			<?php
			}
			?>
			<div class="input-group">
				<span class="input-group-addon">舊密碼</span>
				<input class="form-control" name="oldpwd" type="password" <?php echo ($login["grade"] == "admin"?'placeholder="變更他人密碼不需填寫"':""); ?>>
				<span class="input-group-addon glyphicon glyphicon-asterisk"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">新密碼</span>
				<input class="form-control" name="pwd" type="password" required>
				<span class="input-group-addon glyphicon glyphicon-asterisk"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">確認密碼</span>
				<input class="form-control" name="pwd2" type="password" required>
				<span class="input-group-addon glyphicon glyphicon-asterisk"></span>
			</div>
			<button type="submit" class="btn btn-success">
				<span class="glyphicon glyphicon-hand-right"></span>
				變更 
			</button>
		</form>
<?php
	include("../res/footer.php");
?>
</body>
</html>