<div id="wrap" sytle="min-height: 100%;">
	<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner" style="background-color: #FECB2C; margin: 0;">
		<div class="container">
			<div class="navbar-header" style="background-color: #FECB2C;">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse" style="background-color:#802915;">
					<span class="sr-only" style="background-color:#FECB2C;">巡覽切換</span>
					<span class="icon-bar" style="background-color:#FECB2C;"></span>
					<span class="icon-bar" style="background-color:#FECB2C;"></span>
					<span class="icon-bar" style="background-color:#FECB2C;"></span>
				</button>
				<a href="../home/" class="navbar-brand">
					<div style="float: left;">
						<img src="<?php echo $config['img']['header']; ?>" alt="臺南第一高級中學" height="30px">
					</div>
					<div style="float: left;">
						&nbsp;&nbsp;<span style="font-weight: bold; font-size: px; font-family: '標楷體';">教科書訂購系統</span><br>
					</div>
				</a>
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
				<ul class="nav navbar-nav">
					<li>
						<a href="../orderlist/">訂購列表</a>
					</li>
					<li>
						<a href="../passwordchange/">變更密碼</a>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="../guide/">使用說明</a>
					</li>
					<?php
					if ($login["grade"] == "admin") {
					?>
					<li>
						<a href="../admin/">進入管理介面</a>
					</li>
					<?php
					}
					if($login){
					?>
					<li>
						<a href="../logout/"><?php echo $login["account"]."(".het($login["name"]).")"; ?> 登出</a></li>
					<?php
					}else{
					?>
					<li><a href="../login/">登入</a></li>
					<?php
					}
					?>
				</ul>
			</nav>
		</div>
	</header>
<?php
	showmsgbox();
?>
	<div class="container-fluid">