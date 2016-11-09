<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>模型管理页面</title>
<base target="_self">
<link rel="stylesheet" type="text/css" href="/Public/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/Apps/Jzadmin/View/Public/css/style.css" />
<script type="text/javascript" src="/Public/js/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/layer/layer.js" ></script>
<script type="text/javascript" src="/Public/layer/extend/layer.ext.js" ></script>
<script type="text/javascript" src="/Public/js/common.js"></script>
<style type="text/css">
.fenye li {
	float:left;
	margin:10px 5px;
}
.listnav {
	margin:0px;
	padding:0px;
	height:150px;
	overflow:auto
}
.listnav li {
	width:50%;
	float:left;
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
<form action="<?php echo U('index');?>" method="post" name="form" class="form-inline definewidth m10">
  <input name="title" type="text" style="width:250px"  value="<?php echo ($keyword); ?>" placeholder="名称"  class="form-control w30">
  &nbsp;
  <button name="btn" type="submit"  class="btn btn-primary"><i class="glyphicon glyphicon-search"></i>查 询</button>
  &nbsp;
  <button type="button" class="btn btn-success" id="addnew" onClick="javascript:window.location.href='<?php echo U('add');?>'"><i class="glyphicon glyphicon-plus"></i>添加模型</button>
</form>
<form action="<?php echo U('delAll');?>" method="post" name="form2" id="playList" class="form-inline definewidth m10" style="margin-left:0px;">
  <input name="typename" type="hidden" value="main">
<table class="table table-bordered table-hover definewidth m10" style="font-size:12px">
 
    <tr>
      <th>ID</th>
      <th>模型名称</th>
      <th>模型表名</th>
      <th>控制器名称</th>
      <th>模型简介</th>
      <th>状态</th>
 
      <th>添加时间</th>
      <th>操作</th>
    </tr>
    <?php if(is_array($module_list)): $k = 0; $__LIST__ = $module_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr>
        <td align="left"><input name="id[]"  class="id" type="checkbox"  value="<?php echo ($vo["id"]); ?>">
          <?php echo ($vo["id"]); ?></td>
        <td align="left"><?php echo ($vo["title"]); ?></td>
        <td align="left"> <?php echo ($vo["name"]); ?></td>
        <td align="left"><?php echo ($vo["controller_name"]); ?></td>
        <td align="left"><?php echo ($vo["description"]); ?></td>
        <td align="left"><?php if($vo['status'] == 1): ?>显示<?php else: ?>隐藏<?php endif; ?></td>
 
        <td align="left"><?php echo (Date('Y-m-d H:i:s',$vo["ctime"])); ?></td>
        <td align="left"><a href="<?php echo U('field',array('id'=>$vo['id']));?>">模型字段</a> | 
        <a href="<?php echo U('edit',array('id'=>$vo['id']));?>"> 修改</a> | 
        <a href="javascript:void(0)" link="<?php echo U('del',array('id'=>$vo['id']));?>" class="del"> 刪除 </a></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
 
    
  </table>
</form>
</div>
<script type="text/javascript">
	$(function(){
		$(".del").click(function(){
			var delLink=$(this).attr("link");
			var $this=$(this);
			layer.confirm('确定要删除嘛?', {
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
	});
</script>
</body>
</html>