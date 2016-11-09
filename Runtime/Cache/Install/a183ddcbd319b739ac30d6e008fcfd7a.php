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
                            <li  class="active"><a href="javascript:;"><span>3</span>创建数据库</a></li>
                            <li><a href="javascript:;"><span>4</span><?php if(($_SESSION['update']) == "1"): ?>升级<?php else: ?>安装<?php endif; ?></a></li>
                            <li><a href="javascript:;"><span>5</span>完成</a></li>
                    	</ul>
                    </div>
                </div>
            </div>
        </div>

 

 <div class="jumbotron masthead">
            <div class="container">
    <div class="notice">
    <?php
 defined('SAE_MYSQL_HOST_M') or define('SAE_MYSQL_HOST_M', '127.0.0.1'); defined('SAE_MYSQL_HOST_M') or define('SAE_MYSQL_PORT', '3306'); ?>
    <h1>创建数据库</h1>
    <form action="/install.php?s=/install/step2.html" method="post" target="_self">
        <div class="create-database">
            <div>
                <select name="db[]" class="form-control w30">
	                <option>mysql</option>
                </select>
                <span>数据库类型</span>
            </div>
            <div>
                <input type="text" name="db[]"  class="form-control w30" value="<?php if(defined("SAE_MYSQL_HOST_M")): echo (SAE_MYSQL_HOST_M); else: ?>localhost<?php endif; ?>">
                <span>数据库服务器，数据库服务器IP，一般为localhost</span>
            </div>
            <div>
                <input type="text" name="db[]"  class="form-control w30" value="haipingdata">
                <span>数据库名</span>
            </div>
            <div>
                <input type="text" name="db[]" class="form-control w30" value="<?php if(defined("SAE_MYSQL_USER")): echo (SAE_MYSQL_USER); endif; ?>">
                <span>数据库用户名</span>
            </div>
            <div>
                <input type="password" name="db[]"  class="form-control w30" value="<?php if(defined("SAE_MYSQL_PASS")): echo (SAE_MYSQL_PASS); endif; ?>">
                <span>数据库密码</span>
            </div>
            <div>
                <input type="text" name="db[]"  class="form-control w30" value="<?php if(defined("SAE_MYSQL_PORT")): echo (SAE_MYSQL_PORT); else: ?>3306<?php endif; ?>">
                <span>数据库端口，数据库服务连接端口，一般为3306</span>
            </div>

            <div>
                <input type="text" name="db[]"  class="form-control w30" value="db_">
                <span>数据表前缀，同一个数据库运行多个系统时请修改为不同的前缀</span>
            </div>
        </div>

        <div class="create-database">
            <h2>创始人帐号信息</h2>
            <div>
                <input type="text" name="admin[]"  class="form-control w30" value="Administrator">
                <span>用户名</span>
            </div>
            <div>
                <input type="password" name="admin[]"  class="form-control w30" value="">
                <span>密码</span>
            </div>
            <div>
                <input type="password" name="admin[]"  class="form-control w30" value="">
                <span>确认密码</span>
            </div>
            <div>
                <input type="text" name="admin[]"  class="form-control w30" value="">
                <span>邮箱，请填写正确的邮箱便于收取提醒邮件</span>
            </div>
        </div>
    </form>
    </div>
</div>
        </div>


 <footer class="footer">
            <div class="container">
                <div>
    <a class="btn btn-success btn-large" href="<?php echo U('Install/step1');?>">上一步</a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <button id="submit" type="button" class="btn btn-primary btn-large" onclick="$('form').submit();return false;">下一步</button>
   </div>
            </div>
        </footer>

    </body>
</html>