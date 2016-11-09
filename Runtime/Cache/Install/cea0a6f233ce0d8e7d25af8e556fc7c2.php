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
    <li class="done"><a href="javascript:;"><span>&nbsp;&nbsp;</span><?php if(($_SESSION['update']) == "1"): ?>升级<?php else: ?>安装<?php endif; ?></a></li>
    <li class="done"><a href="javascript:;"><span>&nbsp;&nbsp;</span>完成</a></li>
                    	</ul>
                    </div>
                </div>
            </div>
        </div>

 
 <div class="jumbotron masthead">
            <div class="container">
    <p><center><?php if(($_SESSION['update']) == "1"): ?>升级<?php else: ?>安装<?php endif; ?>完成！</center></p>
	<?php if(isset($info)): echo ($info); endif; ?>
</div>
</div>


 <footer class="footer">
            <div class="container">
                <div>
    <a class="btn btn-primary btn-large" href="/index.php?s=/jzadmin">登录后台</a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a class="btn btn-success btn-large" href="/index.php">访问首页</a>
   </div>
            </div>
        </footer>
        
    </body>
</html>