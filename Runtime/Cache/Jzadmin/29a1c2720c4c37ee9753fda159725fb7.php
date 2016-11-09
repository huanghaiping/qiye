<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<title>网站后台管理系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Apps/Jzadmin/View/Public/Bui/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="/Apps/Jzadmin/View/Public/Bui/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="/Apps/Jzadmin/View/Public/Bui/css/main-min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/Apps/Jzadmin/View/Public/css/style.css" />
</head>
<body>
 
<div class="content">
  <div class="dl-main-nav">
    <div style="font-size:30px; font-family:'微软雅黑'; text-align:center;  height: 50px;  line-height: 50px; float:left; width:130px; color:#FFFFFF;">LOGO</div>
    <ul id="J_Nav"  class="nav-list ks-clear">
    
   	<?php if(is_array($show_menu)): $i = 0; $__LIST__ = $show_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="nav-item dl-selected">
      <?php if($key == 0): ?><div class="nav-item-inner nav-home"><?php echo ($vo["title"]); ?></div>
       <?php else: ?>
        <div class="nav-item-inner nav-order"><?php echo ($vo["title"]); ?></div><?php endif; ?>
      </li><?php endforeach; endif; else: echo "" ;endif; ?> 
     <li><div class="dl-log">欢迎您，<span class="dl-log-user"><?php echo (session('loginUserName')); ?></span><a href="<?php echo U('Login/logout');?>" title="退出系统" class="dl-log-quit">[退出]</a> <a href="/" target="_blank" title="前台首页" class="dl-log-quit">[前台首页]</a> <a href="<?php echo U('Site/cleancache');?>" target="_blank" title="清除缓存" class="dl-log-quit">[清除缓存]</a>  </div></li>
    </ul>
    
  </div>
  <ul id="J_NavContent" class="dl-tab-conten">
  </ul>
</div>
<script type="text/javascript">
var _PUBLIC_="/Apps/Jzadmin/View/Public/";
</script>
<script type="text/javascript" src="/Apps/Jzadmin/View/Public/Bui/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="/Apps/Jzadmin/View/Public/Bui/js/bui-min.js"></script> 
<script type="text/javascript" src="/Apps/Jzadmin/View/Public/Bui/js/config-min.js"></script> 
<script>
    BUI.use("common/main",function(){
      var config = <?php echo ($show_sub_menu); ?>;
      new PageUtil.MainPage({
        modulesConfig : config
      });
    });
  </script>
<div style="text-align:center;">
  <p>版权说明</p>
</div>
</body>
</html>