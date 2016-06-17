<!DOCTYPE html>
<?php
require("../function/common.php");
$show = true;
if($login){
	addmsgbox("info", "你已經登入了", false);
	$show = false;
	?><script>setTimeout(function(){location="../home/";}, 1000)</script><?php
	insertlog($login["account"], "login", "error", "already");
} else if(isset($_POST['user'])){
	$query = new query;
	$query->table = "account";
	$query->where = array(
		array("account", $_POST['user'])
	);
	$login = fetchone(SELECT($query));
	if ($config["reCAPTCHA"]["on"]) {
		require("../function/cURL-HTTP-function/curl.php");
		$res = cURL_HTTP_Request('https://www.google.com/recaptcha/api/siteverify',array('secret'=>$config['reCAPTCHA']['secret_key'],'response'=>$_POST['g-recaptcha-response']));
	}
	if ($login === null) {
		addmsgbox("danger", "無此帳號");
		insertlog($_POST['user'], "login", "error", "account not found");
	} else if ($config["reCAPTCHA"]["on"] && !json_decode($res->html)->success) {
		addmsgbox("danger", "驗證碼失敗");
		insertlog($_POST['user'], "login", "error", "reCAPTCHA");
	} else if(password_verify($_POST['pwd'],$login["password"])){
		$cookie = getrandommd5();
		setcookie("TNFSH_Book", $cookie, time()+86400*7, "/");
		$query = new query;
		$query->table = "session";
		$query->value = array(
			array("account", $login["account"]),
			array("cookie", $cookie)
		);
		INSERT($query);
		addmsgbox("success", "登入成功", false);
		$show = false;
		?><script>setTimeout(function(){location="../<?php 
			if (isset($_GET["from"])) {
				echo $_GET["from"];
			} else if ($login["grade"] == "admin") {
				echo "admin";
			} else {
				echo "home";
			}
		?>";}, 3000)</script><?php
		insertlog($_POST['user'], "login", "ok");
	} else {
		$login = false;
		addmsgbox("danger", "密碼錯誤");
		insertlog($_POST['user'], "login", "error", "wrong password");
	}
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
<title>登入-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	require("../res/header_user.php");
	if($show){
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
		<h2>登入</h2>
			<form method="post">
				<div class="input-group">
					<span class="input-group-addon">帳號</span>
					<input class="form-control" name="user" type="text" value="<?php echo @$_POST['user']; ?>" maxlength="32" required autofocus>
					<span class="input-group-addon glyphicon glyphicon-user"></span>
				</div>
				<div class="input-group">
					<span class="input-group-addon">密碼</span>
					<input class="form-control" name="pwd" type="password" required>
					<span class="input-group-addon glyphicon glyphicon-asterisk"></span>
				</div>
				<?php
				if ($config["reCAPTCHA"]["on"]) {
				?>
					<div class="g-recaptcha" data-sitekey="<?php echo $config["reCAPTCHA"]["site_key"]; ?>"></div>
				<?php
				}
				?>
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