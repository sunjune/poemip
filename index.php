<!DOCTYPE html>
<html lang="en">
<head>
<title>设备材料采购专业化信息数据平台</title>
<meta charset="utf-8">
<meta name="description" content="CMEC第三工程成套事业部 设备专业化数据平台">
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<link rel="stylesheet" href="/css/main.css">
<script src="/js/jquery-2.1.3.min.js"></script>
</head>
<body class="docs">
<?php
?>
	<div class="area_main">
		<div class="area_banner">
		  <div class="div_logo"><img class="pic_logo" src="/css/img/CMEC_Logo.png" /></div>
		  <div class="txt_title">CMEC第三工程成套事业部 设备专业化数据平台</div>
		</div>
		<div class="area_body">
			<div class="area_left">
				<div class="nav_project">
					<ul>
						<li><a id="main_nav_p1" api="p1" obj="project" act="select" data="{}">全过程问题汇总</a></li>
						<li><a id="main_nav_p2" api="p2" obj="project" act="select" data="{}">市场价格分析</a></li>
						<li><a id="main_nav_p3" api="p3" obj="project" act="select" data="{}">监造点形式/生产周期</a></li>
					</ul>
				</div>
			</div>
			<div class="area_right">
				<div class="info_head">
				    <div class="head_rtnhome"><img class="btn_rtnhome" src="/css/img/ico_rtnhome.png" title="返回首页" /></div>
				    <div class="head_left"></div>
				    <div class="head_middle"></div>
				    <div class="head_right"></div>
				</div>
                <div class="info_body">
                    <div class="contblock">
                        <!-- <ul>
                            <li><input type="text" id="searchkey" name="searchkey" class="searchbox" maxlength="50" placeholder="填写搜索关键词..." /><input type="button" class="searchbtn" value="搜索" /></li>
                            <li><input type="button" id="statistics" class="statistics" value="查看全过程问题数据统计 " /></li>
                        </ul> -->
                    </div>
                </div>
				<div class="info_foot"></div>
				<div class="info_dialog">
				    <div class="dialog_background"></div>
				    <div class="dialog_frontpage">
				        <div class="form_window">
				            <div class="form_title">
				                <div class="title_text"></div>
				                <div class="message_text"></div>
				            </div>
				            <div class="form_body"></div>
				        </div>
				    </div>
				</div>
			</div>
		</div>
		<div class="area_bottom"></div>
	</div>

<?php
?>
    <script src="/js/index.js"></script>
	<script type="text/javascript">
        //为左侧导航添加click事件监听
        $('.nav_project ul li a').click(function(){return view_button_submit_click(this);});

        //展示首页内容，并为首页图标添加click事件
        returnhome();
        $('.area_right .info_head .head_rtnhome .btn_rtnhome').click(function(){return returnhome();});
    </script>
</body>
</html>