<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<title></title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="/Public/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/Apps/Jzadmin/View/Public/css/style.css" />
<script type="text/javascript" src="/Public/js/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/layer/layer.js" ></script>
<script type="text/javascript" src="/Public/layer/extend/layer.ext.js" ></script>
<script type="text/javascript" src="/Public/js/common.js"></script>
<style type="text/css">
body {
	padding-bottom: 40px;
}
select {
	color:#000;
}
.sidebar-nav {
	padding: 9px 0;
}
 @media (max-width: 980px) {
/* Enable use of floated navbar text */
            .navbar-text.pull-right {
	float: none;
	padding-left: 5px;
	padding-right: 5px;
}
}
</style>
</head>
<body>
<div class="container-fluid"> 
<div class="page m15 position">
  <ul class="breadcrumb">
    <li><a href="<?php echo U('Index/main');?>" ><i class="glyphicon glyphicon-home"></i>首页</a> </li>
    <?php if(is_array($position)): $i = 0; $__LIST__ = $position;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["title"]); ?></a> </li><?php endforeach; endif; else: echo "" ;endif; ?>
  </ul>
  <div class="lang_class">
    <?php
 parse_str($_SERVER['QUERY_STRING'],$urlarr); unset($urlarr[l]); $url='?'.http_build_query($urlarr); ?>
    <ul>
      <?php if(is_array($lang_list)): $i = 0; $__LIST__ = $lang_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li 
        <?php if(LANG_SET == $vo['mark']): ?>class="current"<?php endif; ?>
        ><a href="<?php echo ($url); ?>&l=<?php echo ($vo["mark"]); ?>"><?php echo ($vo["name"]); ?></a>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
  </div>
</div>
<div class="main">
  <div class="definewidth m10" style="margin-bottom:15px;">
    <div class="fLeft m10">
     <button type="button" onclick="javascript:window.location.href='<?php echo U('add');?>'" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>添加会员等级</button>
    </div>
  </div>
  <div class="list">
    <table class="table table-bordered table-hover definewidth m10">
      <tbody>
        <tr class="nbg">
          <th>等级值</th>
          <th>等级名称</th>
           <th>购买次数</th>
           <th>折扣</th>
          <th>状态</th>
          <th>操作</th>
        </tr>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><?php echo ($vo["sortby"]); ?></td>
            <td><?php echo ($vo["name"]); ?>              
              <?php if($vo['sortby'] == '0'): ?><span style="color:#F00">(默认等级)</span><?php endif; ?></td>
            <td><?php echo ($vo["buy_num"]); ?></td>
             <td><?php echo ($vo["discount"]); ?></td>
            <td><?php if(($vo['status']) == "1"): ?>禁用<?php else: ?>启用<?php endif; ?></td>
            <td><a href="<?php echo U('edit',array('id'=>$vo['id']));?>">修改</a><!--&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="del" link="<?php echo U('foreverdelete',array('id'=>$vo['id']));?>">删除</a>--></td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
      </tbody>
    </table>
    <div class="th" style="clear: both;"><?php echo ($page); ?></div>
  </div>
</div>
</div>
<script type="text/javascript">
$(function(){
		$(".del").click(function(){
		var delLink=$(this).attr("link");
		var $this=$(this);
		layer.confirm('确定要删除?', {
		  btn: ['确定','取消'] //按钮
		}, function(){
			$.get(delLink,function(data){
				if(data.status==1){
				  layer.msg(data.info, {icon: 1});
				}else{
				  layer.msg(data.info, {icon: 2});
				}
				setTimeout("window.location.reload();",2000);
			});
		});
	});	
})
</script>