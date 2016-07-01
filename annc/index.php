<!DOCTYPE html>
<?php
require("../function/common.php");
if($login == false)header("Location: ../login/?from=annc");
$query = new query;
$query->table = "system";
$query->where = array(
	array("id", "announcement_user")
);
$annc_user = fetchone(SELECT($query))["value"];
$query = new query;
$query->table = "system";
$query->where = array(
	array("id", "announcement_admin")
);
$annc_admin = fetchone(SELECT($query))["value"];
if (isset($_POST["annc_user"])) {
	$query = new query;
	$query->table = "system";
	$query->value = array(
		array("value", $_POST["annc_user"])
	);
	$query->where = array(
		array("id", "announcement_user")
	);
	UPDATE($query);
	$annc_user = $_POST["annc_user"];
	insertlog($login["account"], "annc", "edit_user", $annc_user);
} else if (isset($_POST["annc_admin"])) {
	$query = new query;
	$query->table = "system";
	$query->value = array(
		array("value", $_POST["annc_admin"])
	);
	$query->where = array(
		array("id", "announcement_admin")
	);
	UPDATE($query);
	$annc_admin = $_POST["annc_admin"];
	insertlog($login["account"], "annc", "edit_admin", $annc_admin);
} else {
	insertlog($login["account"], "annc", "view");
}
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/comhead.php");
?>
<title>編輯公告-臺南一中教科書訂購系統</title>
<script src="../res/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
	selector: 'textarea',
	language:'zh_TW',
	height: 500,
	theme: 'modern',
	plugins: [
		'advlist autolink lists link image charmap print preview hr anchor pagebreak',
		'searchreplace wordcount visualblocks visualchars code fullscreen',
		'insertdatetime media nonbreaking save table contextmenu directionality',
		'emoticons template paste textcolor colorpicker textpattern imagetools'
	],
	toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	toolbar2: 'print preview media | forecolor backcolor emoticons',
	image_advtab: true,
	content_css: [
		'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
		'//www.tinymce.com/css/codepen.min.css'
	]
});
</script>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/header_admin.php");
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
		<h2>編輯公告</h2>
		<h3>使用者</h3>
		<form method="post">
			<textarea name="annc_user"><?php echo $annc_user; ?></textarea>
			<button type="submit" class="btn btn-success">
				<span class="glyphicon glyphicon-pencil"></span>
				修改
			</button>
		</form>
		<h3>管理員</h3>
		<form method="post">
			<textarea name="annc_admin"><?php echo $annc_admin; ?></textarea>
			<button type="submit" class="btn btn-success">
				<span class="glyphicon glyphicon-pencil"></span>
				修改
			</button>
		</form>
<?php
	include("../res/footer.php");
?>
</body>
</html>