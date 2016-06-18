<!DOCTYPE html>
<?php
require("../function/common.php");
if($login == false)header("Location: ../login/?from=bookgroup");
else if($login["grade"] != "admin"){
	addmsgbox("danger", "你沒有權限");
	?><script>setTimeout(function(){location="../home/";}, 1000);</script><?php
	insertlog($login["account"], "bookgroup", "access_denied");
} else if(isset($_POST["type"])){
	if ($_POST["type"] == "add") {
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
		insertlog($login["account"], "bookgroup", "add", $_POST["name"]." ".$_POST["starttime"]."~".$_POST["endtime"]." ".$_POST["grade"]);
	} else if($_POST["type"] == "edit"){
		$query = new query;
		$query->table ="bookgroup";
		$query->value = array(
			array("name", $_POST["name"]),
			array("starttime", $_POST["starttime"]),
			array("endtime", $_POST["endtime"]),
			array("grade", $_POST["grade"])
		);
		$query->where = array(
			array("groupid", $_POST["groupid"])
		);
		UPDATE($query);
		addmsgbox("success", "已修改 ".$_POST["name"]." ".$_POST["starttime"]."~".$_POST["endtime"]." ".$_POST["grade"]);
		insertlog($login["account"], "bookgroup", "edit", $_POST["name"]." ".$_POST["starttime"]."~".$_POST["endtime"]." ".$_POST["grade"]);
	} else if($_POST["type"] == "del"){
		$query = new query;
		$query->table = "bookgroup";
		$query->where = array(
			array("groupid", $_POST["groupid"])
		);
		DELETE($query);
		insertlog($login["account"], "bookgroup", "del", $_POST["groupid"]);
		addmsgbox("success", "已刪除 ".$_POST["groupid"]);
	}
} else {
	insertlog($login["account"], "bookgroup", "view");
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
if($login["grade"] == "admin"){
?>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<h2>訂購組管理</h2>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table cellspacing="0" cellpadding="2" class="table table-hover table-condensed">
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
							<a href="../books/?id=<?php echo $bookgroup["groupid"]; ?>" class="btn btn-primary" role="button">
								<span class="glyphicon glyphicon-book"></span>
								教科書
							</a>
							<button type="button" class="btn btn-info" onclick="location='../export/?id=<?php echo $bookgroup["groupid"]; ?>';" >
								<span class="glyphicon glyphicon-save"></span>
								匯出 
							</button>
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal" onclick="edit('<?php echo $bookgroup["groupid"]; ?>','<?php echo $bookgroup["name"]; ?>','<?php echo date("Y-m-d\TH:i:s", strtotime($bookgroup["starttime"])); ?>','<?php echo date("Y-m-d\TH:i:s", strtotime($bookgroup["endtime"])); ?>','<?php echo $bookgroup["grade"]; ?>')">
								<span class="glyphicon glyphicon-pencil"></span>
								修改 
							</button>
							<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#Modal"  onclick="del('<?php echo $bookgroup["groupid"]; ?>','<?php echo $bookgroup["name"]; ?>','<?php echo date("Y-m-d\TH:i:s", strtotime($bookgroup["starttime"])); ?>','<?php echo date("Y-m-d\TH:i:s", strtotime($bookgroup["endtime"])); ?>','<?php echo $bookgroup["grade"]; ?>');" >
								<span class="glyphicon glyphicon-trash"></span>
								刪除 
							</button>
						</td>
					</tr>
					<?php
					}
					?>
					</table>
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal" onclick="add()">
						<span class="glyphicon glyphicon-plus"></span>
						新增 
					</button>
					<script>
						function add(){
							acttype.value = "add";
							headeracttype.innerHTML = "新增";
							insertvalue("", "", "", "", "");
							$("#addbtn").show();
							$("#editbtn").hide();
							$("#delbtn").hide();
						}
						function edit(groupid, name, starttime, endtime, grade){
							acttype.value = "edit";
							actid.value = groupid;
							headeracttype.innerHTML = "修改";
							insertvalue(name, starttime, endtime, grade);
							$("#addbtn").hide();
							$("#editbtn").show();
							$("#delbtn").hide();
						}
						function del(groupid, name, starttime, endtime, grade){
							acttype.value = "del";
							actid.value = groupid;
							headeracttype.innerHTML = "刪除";
							insertvalue(name, starttime, endtime, grade);
							$("#addbtn").hide();
							$("#editbtn").hide();
							$("#delbtn").show();
						}
						function insertvalue(name, starttime, endtime, grade){
							actname.value = name;
							actstarttime.value = starttime;
							actendtime.value = endtime;
							actgrade.value = grade;
						}
					</script>
					<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method="post">
								<input type="hidden" name="type" id="acttype">
								<input type="hidden" name="groupid" id="actid">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="ModalLabel"><span id="headeracttype"></span>訂購組</h4>
								</div>
								<div class="modal-body">
									<div class="input-group">
										<span class="input-group-addon">名稱</span>
										<input class="form-control" name="name" id="actname" type="text" required>
										<span class="input-group-addon glyphicon glyphicon-font"></span>
									</div>
									<div class="input-group">
										<span class="input-group-addon">開始時間</span>
										<input class="form-control" name="starttime" id="actstarttime" type="datetime-local" required>
										<span class="input-group-addon glyphicon glyphicon-calendar"></span>
									</div>
									<div class="input-group">
										<span class="input-group-addon">結束時間</span>
										<input class="form-control" name="endtime" id="actendtime" type="datetime-local" required>
										<span class="input-group-addon glyphicon glyphicon-calendar"></span>
									</div>
									<div class="input-group">
										<span class="input-group-addon">年級群組</span>
										<input class="form-control" name="grade" id="actgrade" type="text" required>
										<span class="input-group-addon glyphicon glyphicon-user"></span>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
									<button type="submit" class="btn btn-success" id="addbtn">
										<span class="glyphicon glyphicon-plus"></span>
										新增
									</button>
									<button type="submit" class="btn btn-success" id="editbtn">
										<span class="glyphicon glyphicon-pencil"></span>
										修改
									</button>
									<button type="submit" class="btn btn-danger" id="delbtn">
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
	require("../res/footer.php");
?>
</body>
</html>