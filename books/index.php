<!DOCTYPE html>
<?php
require("../function/common.php");
$ok = true;
$groupid = $_GET["id"];
if($login == false)header("Location: ../login/?from=bookgroup");
else if($login["grade"] != "admin"){
	addmsgbox("danger", "你沒有權限");
	?><script>setTimeout(function(){location="../home";},1000);</script><?php
	$ok = false;
	insertlog($login["account"], "books", "access_denied");
}
else if (!isset($groupid)) {
	addmsgbox("danger", "沒有給ID");
	$ok = false;
	insertlog($login["account"], "books", "error", "no id");
}
$query = new query;
$query->table ="bookgroup";
$query->where = array(
	array("groupid",$groupid)
);
if ($ok&&fetchone(SELECT($query))===null) {
	addmsgbox("danger", "找不到ID");
	$ok = false;
	insertlog($login["account"], "books", "error", "id not found");
}
else if(isset($_POST["add"])){
	$query = new query;
	$query->table ="booklist";
	$query->value = array(
		array("name",$_POST["name"]),
		array("money",$_POST["money"]),
		array("bookgroup",$groupid)
	);
	INSERT($query);
	addmsgbox("success", "已增加 ".$_POST["name"]." ".$_POST["money"]);
	insertlog($login["account"], "books", "add", $_POST["name"]." ".$_POST["money"]);
}
else if(isset($_POST["del"])){
	$query = new query;
	$query->table = "booklist";
	$query->where = array(
		array("bookid",$_POST["bookid"])
	);
	DELETE($query);
	addmsgbox("success", "已刪除 ".$_POST["bookid"]);
	insertlog($login["account"], "books", "del", $_POST["bookid"]);
} else {
	insertlog($login["account"], "books", "view", $groupid);
}
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
include_once("../res/comhead.php");
?>
<title>訂購管理-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
include_once("../res/header_admin.php");
if($ok){
?>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<h2>訂購管理</h2>
		<div class="row">
			<div class="col-sm-12">
				<form method="post">
					<h2>新增</h2>
					<input type="hidden" name="add">
					<div class="input-group">
						<span class="input-group-addon">名稱</span>
						<input class="form-control" name="name" type="text" required autofocus>
						<span class="input-group-addon glyphicon glyphicon-pencil"></span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">金額</span>
						<input class="form-control" name="money" type="number" required>
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
					<table cellspacing="0" cellpadding="2" class="table table-hover table-condensed">
					<tr>
						<th>ID</th>
						<th>名稱</th>
						<th>金額</th>
						<th>操作</th>
					</tr>
					<?php
					$query = new query;
					$query->table = "booklist";
					$query->where = array(
						array("bookgroup",$groupid)
					);
					$row = SELECT($query);
					foreach($row as $book){
					?>
					<tr>
						<td><?php echo $book["bookid"]; ?></td>
						<td><?php echo $book["name"]; ?></td>
						<td><?php echo $book["money"]; ?></td>
						<td>
							<button name="input" type="button" data-toggle="modal" data-target="#delModal"  class="btn btn-danger" onClick="delbook('<?php echo $book["bookid"]; ?>','<?php echo $book["name"]; ?>');" >
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
						function delbook(id, name){
							bookdelid.value = id;
							delshowname.innerHTML = name;
						}
					</script>
					<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method="post">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="delModalLabel">刪除教科書</h4>
								</div>
								<div class="modal-body">
									<input type="hidden" name="del">
									<input type="hidden" name="bookid" id="bookdelid">
									確認刪除 <span id="delshowname"></span>
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