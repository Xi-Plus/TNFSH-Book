<!DOCTYPE html>
<?php
require("../function/common.php");
if($login == false)header("Location: ../login/?from=manageroom");
else if(!$login["admin"]){
	addmsgbox("danger", "你沒有權限");
	?><script>setTimeout(function(){location="../home";}, 1000);</script><?php
}
else if(isset($_POST["add"])){
	$query = new query;
	$query->table ="adminlist";
	$query->value = array(
		array("account", $_POST["account"]),
		array("password", password_hash($_POST["password"], PASSWORD_DEFAULT)),
		array("name", $_POST["name"])
	);
	INSERT($query);
	addmsgbox("success", "已增加 ".$_POST["account"]." ".$_POST["name"]);
}
else if(isset($_POST["del"])){
	$query = new query;
	$query->table = "adminlist";
	$query->where = array(
		array("account", $_POST["account"])
	);
	DELETE($query);
	addmsgbox("success", "已刪除 ".$_POST["account"]);
}
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
include_once("../res/comhead.php");
?>
<title>管理員管理-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
include_once("../res/header_admin.php");
if($login["admin"]){
?>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<h2>管理員管理</h2>
		<div class="row">
			<div class="col-sm-12">
				<form method="post">
					<h2>新增</h2>
					<input type="hidden" name="add">
					<div class="input-group">
						<span class="input-group-addon">帳號</span>
						<input class="form-control" name="account" type="text" required>
						<span class="input-group-addon glyphicon glyphicon-pencil"></span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">密碼</span>
						<input class="form-control" name="password" type="password" required>
						<span class="input-group-addon glyphicon glyphicon-pencil"></span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">姓名</span>
						<input class="form-control" name="name" type="text" required>
						<span class="input-group-addon glyphicon glyphicon-pencil"></span>
					</div>
					<button name="input" type="submit" class="btn btn-success">
						<span class="glyphicon glyphicon-plus"></span>
						新增 
					</button>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
				<script>
					function checkdeladmin(id){
						if(!confirm('確認刪除?'))return false;
						accountdel.value = id;
						admindel.submit();
					}
				</script>
				<table cellspacing="0" cellpadding="2" class="table table-hover table-condensed">
				<div style="display:none">
					<form method="post" id="admindel">
						<input name="del" type="hidden">
						<input name="account" type="hidden" id="accountdel">
					</form>
				</div>
				<tr>
					<th>帳戶</th>
					<th>姓名</th>
					<th>操作</th>
				</tr>
				<?php
				$query = new query;
				$query->table = "adminlist";
				$query->order = array(
					array("account", "ASC")
				);
				$row = SELECT($query);
				foreach($row as $admin){
				?>
				<tr>
					<td><?php echo $admin["account"]; ?></td>
					<td><?php echo $admin["name"]; ?></td>
					<td>
						<button name="input" type="button" class="btn btn-danger" onClick="checkdeladmin('<?php echo $admin["account"]; ?>');" >
						<span class="glyphicon glyphicon-trash"></span>
						刪除 
						</button>
					</td>
				</tr>
				<?php
				}
				?>
				</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-1"></div>
</div>
<?php
	}
	include("../res/footer.php");
?>
</body>
</html>