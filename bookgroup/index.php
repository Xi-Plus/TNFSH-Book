<!DOCTYPE html>
<?php
require("../function/common.php");
if($login == false)header("Location: ../login/?admin&from=bookgroup");
else if(!$login["admin"]){
	addmsgbox("danger", "你沒有權限");
	?><script>setTimeout(function(){location="../home";}, 1000);</script><?php
} else if(isset($_POST["add"])){
	$query = new query;
	$query->table ="bookgroup";
	$query->value = array(
		array("name", $_POST["name"]),
		array("starttime", $_POST["starttime"]),
		array("endtime", $_POST["endtime"]),
		array("grade", $_POST["grade"])
	);
	INSERT($query);
	addmsgbox("success", "已增加 ".$_POST["name"]." ".$_POST["starttime"]."~".$_POST["endtime"]." ".$_POST["grade"]);
} else if(isset($_POST["del"])){
	$query = new query;
	$query->table = "bookgroup";
	$query->where = array(
		array("groupid", $_POST["groupid"])
	);
	DELETE($query);
	addmsgbox("success", "已刪除 ".$_POST["groupid"]);
}
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/comhead.php");
?>
<title>訂購組管理-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/header_admin.php");
if($login["admin"]){
?>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<h2>訂購組管理</h2>
		<div class="row">
			<div class="col-sm-12">
				<form method="post">
					<h2>新增</h2>
					<input type="hidden" name="add">
					<div class="input-group">
						<span class="input-group-addon">名稱</span>
						<input class="form-control" name="name" type="text" required>
						<span class="input-group-addon glyphicon glyphicon-pencil"></span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">開始時間</span>
						<input class="form-control" name="starttime" type="datetime-local" required>
						<span class="input-group-addon glyphicon glyphicon-pencil"></span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">結束時間</span>
						<input class="form-control" name="endtime" type="datetime-local" required>
						<span class="input-group-addon glyphicon glyphicon-pencil"></span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">年級群組</span>
						<input class="form-control" name="grade" type="text" required>
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
					function checkdelgroup(id){
						if(!confirm('確認刪除?'))return false;
						groupdelid.value = id;
						groupdel.submit();
					}
				</script>
				<table cellspacing="0" cellpadding="2" class="table table-hover table-condensed">
				<div style="display:none">
					<form method="post" id="groupdel">
						<input name="del" type="hidden">
						<input name="groupid" type="hidden" id="groupdelid">
					</form>
				</div>
				<tr>
					<th>ID</th>
					<th>名稱</th>
					<th>開始時間</th>
					<th>結束時間</th>
					<th>年級群組</th>
					<th>操作</th>
				</tr>
				<?php
				$query = new query;
				$query->table = "bookgroup";
				$query->order = array(
					array("starttime", "ASC")
				);
				$row = SELECT($query);
				foreach($row as $bookgroup){
				?>
				<tr>
					<td><?php echo $bookgroup["groupid"]; ?></td>
					<td><?php echo $bookgroup["name"]; ?></td>
					<td><?php echo $bookgroup["starttime"]; ?></td>
					<td><?php echo $bookgroup["endtime"]; ?></td>
					<td><?php echo $bookgroup["grade"]; ?></td>
					<td>
						<button type="button" class="btn btn-success" onClick="location='../books/?id=<?php echo $bookgroup["groupid"]; ?>';" >
						<span class="glyphicon glyphicon-pencil"></span>
						修改 
						</button>
						<button type="button" class="btn btn-success" onClick="location='../export/?id=<?php echo $bookgroup["groupid"]; ?>';" >
						<span class="glyphicon glyphicon-pencil"></span>
						匯出 
						</button>
						<button type="button" class="btn btn-danger" onClick="checkdelgroup('<?php echo $bookgroup["groupid"]; ?>');" >
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
	require("../res/footer.php");
?>
</body>
</html>