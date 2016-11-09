<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>信息提示</title>
<link rel="stylesheet" type="text/css" href="/Public/bootstrap/css/bootstrap.min.css" />
<style type="text/css">
.message{width: 40%; margin:auto;margin-top: 120px;  border:1px solid #D5D5D5; font-family:"微软雅黑";}
 .success ,.error{text-align: center; font-size:22px;}
 .jump{text-align: center;margin-top: 10px;}
 .alert-warning{   padding: 0 80px 0 20px; border-color:#F8F8F8;  height: 42px;  line-height: 42px;  border-bottom: 1px solid #eee;  font-size: 14px;  color: #333;  overflow: hidden;  text-overflow: ellipsis;  white-space: nowrap;  background-color: #F8F8F8;  border-radius: 2px 2px 0 0;}
 .error .glyphicon{ font-size:40px;margin-right:15px; vertical-align: text-top; color:#337ab7;}
</style>
</head>

<body>
<div class="panel panel-warning message">
  <div class="alert alert-warning" role="alert"><span>信息提示</span></div>
  <div class="panel-body">
    <p class="error"><i class="glyphicon glyphicon-ok"></i><?php echo($message); ?></p>
    
  </div>
  <p class="jump">等待时间： <b id="wait"><?php echo($waitSecond); ?></b> &nbsp; 秒,如果你的浏览器没有自动跳转，请点击 <a id="href" href="<?php echo($jumpUrl); ?>">这里</a></p>
</div>
<script type="text/javascript">

    (function(){
		var wait = document.getElementById('wait'),href = document.getElementById('href').href;
		var interval = setInterval(function(){
		var time = --wait.innerHTML;
		if(time <= 0) {
		location.href = href;
		clearInterval(interval);
		};
		}, 1000);

    })();
    </script>
</body>
</html>