<!DOCTYPE html>
<?php
require("../function/common.php");
if($login == false)header("Location: ../login/?from=adminlist");
else if($login["grade"] != "admin"){
	addmsgbox("danger", "你沒有權限");
	?><script>setTimeout(function(){location="../home";}, 1000);</script><?php
	insertlog($login["account"], "adminlist", "access_denied");
} else if(isset($_POST["add"])){
	$query = new query;
	$query->table ="account";
	$query->value = array(
		array("account", $_POST["account"]),
		array("password", password_hash($_POST["password"], PASSWORD_DEFAULT)),
		array("name", $_POST["name"]),
		array("grade", "admin")
	);
	INSERT($query);
	addmsgbox("success", "已增加 ".$_POST["account"]." ".$_POST["name"]);
	insertlog($login["account"], "adminlist", "add", $_POST["account"]." ".$_POST["name"]);
} else if(isset($_POST["edit"])){
	$query = new query;
	$query->table ="account";
	$query->value = array(
		array("account", $_POST["account"]),
		array("name", $_POST["name"])
	);
	$query->where = array(
		array("account", $_POST["oldaccount"])
	);
	UPDATE($query);
	addmsgbox("success", "已修改 ".$_POST["account"]." ".$_POST["name"]);
	insertlog($login["account"], "adminlist", "edit", $_POST["account"]." ".$_POST["name"]);
} else if(isset($_POST["del"])){
	$query = new query;
	$query->table = "account";
	$query->where = array(
		array("account", $_POST["account"])
	);
	DELETE($query);
	addmsgbox("success", "已刪除 ".$_POST["account"]);
	insertlog($login["account"], "adminlist", "del", $_POST["account"]);
} else {
	insertlog($login["account"], "adminlist", "view");
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
if($login["grade"] == "admin"){
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
						<span class="input-group-addon glyphicon glyphicon-user"></span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">密碼</span>
						<input class="form-control" name="password" type="password" required>
						<span class="input-group-addon glyphicon glyphicon-asterisk"></span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">姓名</span>
						<input class="form-control" name="name" type="text" required>
						<span class="input-group-addon glyphicon glyphicon-font"></span>
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
					<table cellspacing="0" cellpadding="2" class="table table-hover table-condensed">
					<tr>
						<th>帳號</th>
						<th>姓名</th>
						<th>操作</th>
					</tr>
					<?php
					$query = new query;
					$query->table = "account";
					$query->order = array(
						array("account", "ASC")
					);
					$query->where = array(
						array("grade", "admin")
					);
					$row = SELECT($query);
					foreach($row as $admin){
					?>
					<tr>
						<td><?php echo $admin["account"]; ?></td>
						<td><?php echo $admin["name"]; ?></td>
						<td>
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#editModal" onclick="editadmin('<?php echo $admin["account"]; ?>','<?php echo $admin["name"]; ?>')">
							<span class="glyphicon glyphicon-pencil"></span>
							修改
							</button>
							<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delModal" onclick="deladmin('<?php echo $admin["account"]; ?>','<?php echo $admin["name"]; ?>');" >
							<span class="glyphicon glyphicon-trash"></span>
							刪除 
							</button>
						</td>
					</tr>
					<?php
					}
					?>
					</table>
					<script>
						function editadmin(account, name){
							editoldaccount.value = account;
							editaccount.value = account;
							editname.value = name;
						}
						function deladmin(account, name){
							delaccount.value = account;
							delshowaccount.innerHTML = account;
							delshowname.innerHTML = name;
						}
					</script>
					<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method="post">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="editModalLabel">修改管理員</h4>
								</div>
								<div class="modal-body">
									<input type="hidden" name="edit">
									<input type="hidden" name="oldaccount" id="editoldaccount">
									<div class="input-group">
										<span class="input-group-addon">帳號</span>
										<input class="form-control" name="account" id="editaccount" type="text" required>
										<span class="input-group-addon glyphicon glyphicon-user"></span>
									</div>
									<div class="input-group">
										<span class="input-group-addon">姓名</span>
										<input class="form-control" name="name" id="editname" type="text" required>
										<span class="input-group-addon glyphicon glyphicon-font"></span>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
									<button type="submit" class="btn btn-success">
										<span class="glyphicon glyphicon-pencil"></span>
										修改
									</button>
								</div>
								</form>
							</div>
						</div>
					</div>
					<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method="post">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="delModalLabel">刪除管理員</h4>
								</div>
								<div class="modal-body">
									<input type="hidden" name="del">
									<input type="hidden" name="account" id="delaccount">
									確認刪除 <span id="delshowaccount"></span> / <span id="delshowname"></span>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
									<button type="submit" class="btn btn-danger">
										<span class="glyphicon glyphicon-trash"></span>
										刪除
									</button>
								</div>
								</form>
							</div>
						</div>
					</div>
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