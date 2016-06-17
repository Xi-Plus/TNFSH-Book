<!DOCTYPE html>
<?php
require("../function/common.php");
if($login == false)header("Location: ../login/?from=userlist");
else if($login["grade"] != "admin"){
	addmsgbox("danger", "你沒有權限");
	?><script>setTimeout(function(){location="../home";}, 1000);</script><?php
} else if(isset($_POST["add"])){
	if (!isset($_FILES["file"]) || $_FILES["file"]["error"] != 0) {
		addmsgbox("danger", "上傳失敗");
	}
	$listtext = file_get_contents($_FILES["file"]["tmp_name"]);
	if (mb_check_encoding($listtext, "UTF-8")) {
		addmsgbox("success", "檔案字符編碼為UTF-8");
	} else if (mb_check_encoding($listtext, "BIG5")) {
		addmsgbox("success", "檔案字符編碼為BIG5");
		$listtext = iconv("BIG5", "UTF-8", $listtext);
	} else {
		addmsgbox("danger", "無法確認檔案字符編碼，已取消上傳");
		$listtext = "";
	}
	$listtext = explode("\n", $listtext);
	unset($listtext[count($listtext)-1]);
	set_time_limit(0);
	$success = 0;
	foreach ($listtext as $temp) {
		$temp = str_getcsv($temp);
		if ($temp !== null && count($temp) == 4) {
			$success++;
			$query = new query;
			$query->table ="account";
			$query->value = array(
				array("account", $temp[0]),
				array("password", password_hash($temp[1], PASSWORD_DEFAULT)),
				array("name", $temp[2]),
				array("grade", $temp[3])
			);
			INSERT($query);
		}
	}
	addmsgbox("success", "找到".count($listtext)."行，成功".$success."行，失敗".(count($listtext)-$success)."行");
} else if(isset($_POST["del"])){
	$query = new query;
	$query->table = "account";
	$query->where = array(
		array("grade", $_POST["grade"])
	);
	DELETE($query);
	addmsgbox("success", "已刪除 ".$_POST["grade"]);
}
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/comhead.php");
?>
<title>使用者管理-臺南一中教科書訂購系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/header_admin.php");
if($login["grade"] == "admin"){
?>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<h2>使用者管理</h2>
		<div class="row">
			<div class="col-sm-12">
				<form method="post" enctype="multipart/form-data" onsubmit="$('#uploadbtn').button('loading');">
					<h2>新增</h2>
					需有4欄，依序為帳戶、密碼、姓名、年級群組 (年級群組為admin視為管理員) <a href="example.csv">範例下載</a>
					<input type="hidden" name="add">
					<div class="input-group">
						<span class="input-group-addon">資料上傳 (.csv)</span>
						<input class="form-control" name="file" type="file" required accept=".csv">
						<span class="input-group-addon glyphicon glyphicon-open"></span>
					</div>
					<button type="submit" class="btn btn-success" id="uploadbtn"  data-loading-text="上傳中，資料量大需較多時間，請耐心等待，勿關閉網頁">
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
					function checkdeluser(id){
						if(!confirm('確認刪除?'))return false;
						userdel.value = id;
						userdelform.submit();
					}
				</script>
				<table cellspacing="0" cellpadding="2" class="table table-hover table-condensed">
				<div style="display:none">
					<form method="post" id="userdelform">
						<input name="del" type="hidden">
						<input name="grade" type="hidden" id="userdel">
					</form>
				</div>
				<tr>
					<th>年級群組</th>
					<th>數量</th>
					<th>操作</th>
				</tr>
				<?php
				$query = new query;
				$query->table = "account";
				$query->column = array("COUNT(*) AS `count`", "grade");
				$query->group = array("grade");
				$query->order = array(
					array("grade", "ASC")
				);
				$row = SELECT($query);
				foreach($row as $grade){
				?>
				<tr>
					<td><?php echo $grade["grade"]; ?></td>
					<td><?php echo $grade["count"]; ?></td>
					<td>
						<?php
						if ($grade["grade"] != "admin") {
						?>
						<button name="input" type="button" class="btn btn-danger" onclick="checkdeluser('<?php echo $grade["grade"]; ?>');" >
						<span class="glyphicon glyphicon-trash"></span>
						刪除 
						</button>
						<?php 
						} else {
							echo '<a href="../adminlist/">管理員請點此刪除</a>';
						}
						?>
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