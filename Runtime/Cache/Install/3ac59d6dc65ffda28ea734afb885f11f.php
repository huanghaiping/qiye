<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo ($sys_name); ?> 安装</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/Apps/Install/View/Public/install.css" rel="stylesheet">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="/Apps/Install/View/Public/html5shiv.js?v=<?php echo SITE_VERSION;?>"></script>
        <![endif]-->
        <script src="/Public/js/jquery.min.js"></script>
        <script src="/Public/bootstrap/js/bootstrap.min.js"></script>
    </head>

    <body data-spy="scroll" data-target=".bs-docs-sidebar">
   

  




<div class="navbar navbar-inverse">
            <div class="navbar-inner">
                <div class="container">
                    <div class="nav-collapse">
                    	<p class="install_logo"><span><?php echo ($sys_name); ?></span></p>
                    	<ul id="step" class="nav">
                    		      <li class="done"><a href="javascript:;"><span>&nbsp;&nbsp;</span>安装协议</a></li>
    <li class="done"><a href="javascript:;"><span>&nbsp;&nbsp;</span>环境检测</a></li>
    <li  class="done"><a href="javascript:;"><span>&nbsp;&nbsp;</span>创建数据库</a></li>
    <li class="active"><a href="javascript:;"><span>4</span><?php if(($_SESSION['update']) == "1"): ?>升级<?php else: ?>安装<?php endif; ?></a></li>
    <li><a href="javascript:;"><span>5</span>完成</a></li>
                    	</ul>
                    </div>
                </div>
            </div>
        </div>

 

 <div class="jumbotron masthead">
            <div class="container">
    <!---
    <h1><?php if(($_SESSION['update']) == "1"): ?>升级<?php else: ?>安装<?php endif; ?></h1>
    -->
    <div id="show-list" class="install-database">
    </div>
    
    <script type="text/javascript">
        var list   = document.getElementById('show-list');
        function showmsg(msg, classname){
            var li = document.createElement('p'); 
            li.innerHTML = msg;
            classname && li.setAttribute('class', classname);
            list.appendChild(li);
            document.scrollTop += 30;
        }
    </script>
</div>
</div>

 <footer class="footer">
            <div class="container">
                <div>
	<center><img src="/Apps/Install/View/Public/images/loading.gif"/><br/><br/></center>
    <button class="btn btn-warning btn-large disabled">正在<?php if(($_SESSION['update']) == "1"): ?>升级<?php else: ?>安装<?php endif; ?>，请稍后...</button>
   </div>
            </div>
        </footer>
        
    </body>
</html>