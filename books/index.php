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
}
else if (!isset($groupid)) {
	addmsgbox("danger", "沒有給ID");
	$ok = false;
}
$query = new query;
$query->table ="bookgroup";
$query->where = array(
	array("groupid",$groupid)
);
if ($ok&&fetchone(SELECT($query))===null) {
	addmsgbox("danger", "找不到ID");
	$ok = false;
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
	addmsgbox("success", "已增加 ".$_POST["name"]);
}
else if(isset($_POST["del"])){
	$query = new query;
	$query->table = "booklist";
	$query->where = array(
		array("bookid",$_POST["bookid"])
	);
	DELETE($query);
	addmsgbox("success", "已刪除 ".$_POST["bookid"]);
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
				<script>
					function checkdelbook(id){
						if(!confirm('確認刪除?'))return false;
						bookdelid.value = id;
						bookdel.submit();
					}
				</script>
				<table cellspacing="0" cellpadding="2" class="table table-hover table-condensed">
				<div style="display:none">
					<form method="post" id="bookdel">
						<input name="del" type="hidden">
						<input name="bookid" type="hidden" id="bookdelid">
					</form>
				</div>
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
						<button name="input" type="button" class="btn btn-danger" onClick="checkdelbook('<?php echo $book["bookid"]; ?>');" >
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