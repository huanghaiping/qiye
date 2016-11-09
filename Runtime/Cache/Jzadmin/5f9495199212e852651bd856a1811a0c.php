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
#tips_forumlis,.tip{ margin:10px 20px;}
#tips_forumlis li{ width:40%; float:left; list-style:none; line-height:25px;}
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
<form class="form-inline  m15 margin_bottom"  method="post">
 
  <button type="button" class="btn btn-success" id="addnew"><i class="glyphicon glyphicon-plus"></i>新增菜单</button>
</form>
<table class="table table-bordered table-hover definewidth m10" >
  <thead>
    <tr>
      <th>id</th>
      <th width="400">菜单标题</th>
      <th>模型名称</th>
      <th>位置</th>
      <th>排序</th>
      <th>状态</th>
      <th>访问</th>
      <th>管理操作</th>
    </tr>
  </thead>
   <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr align="center" id="<?php echo ($vo["id"]); ?>" pid="<?php echo ($vo["pid"]); ?>">
    <td><?php echo ($vo["id"]); ?></td>
    <td align="left" class="tree" style="cursor: pointer;"><?php echo ($vo["fullname"]); ?></td>
    <td><?php if($vo['typeid'] == 0): ?>外部链接<?php else: echo ($vo["module_name"]["title"]); endif; ?></td>
    <td><?php echo (showPosition($vo["position"])); ?></td>
    <td fd="sort" edit="0"><?php echo ($vo["sort"]); ?></td>
    <td><a href="javascript:void(0)" class="opStatus" val="<?php echo ($vo["status"]); ?>"><?php if($vo['status'] == 1): ?>显示<?php else: ?> <span  style="color:red">隐藏</span><?php endif; ?></a></td>
    <td><a href="<?php echo ($vo["url"]); ?>" target="_blank">访问</a></td>
    <td>
      <a href="<?php echo U('add',array('fid'=>$vo['id']));?>">添加子栏目</a>&nbsp;|&nbsp;
      <a href="<?php echo U('edit',array('id'=>$vo['id']));?>">编辑</a>&nbsp;|&nbsp;
      <a href="javascript:void(0);" val="<?php echo U('del',array('id'=>$vo['id']));?>" class="del">删除</a>
    </td>
  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
</div>
<script type="text/javascript">
$(function () {
	$('#addnew').click(function(){
			window.location.href="<?php echo U('add');?>";
	 });
	 
	 $(".del").click(function(){
			var delLink=$(this).attr("val");
			var $this=$(this);
			layer.confirm('确定要删除菜单?', {
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
	
	//快捷启用禁用操作
	$(".opStatus").click(function(){
		var obj=$(this);
		var id=$(this).parents("tr").attr("id");
		var status=$(this).attr("val");
		$.post("<?php echo U('updateStatus');?>", { "id":id, "value":status==1?0:1,'field':"status"}, function(json){
			if(json.status==1){
				layer.alert(json.info);
				$(obj).attr("val",status==1?0:1).html(status==0 ?"显示":"<span  style=\"color:red\">隐藏</span>");
			}else{
				layer.alert(json.info);
			}
		});
	});

	
	//快捷改变操作排序dblclick
	$("tbody>tr>td[fd]").click(function(){  
			var inval = $(this).html();
			var infd = $(this).attr("fd");
			var inid =  $(this).parents("tr").attr("id");
			if($(this).attr('edit')==0){
			 $(this).attr('edit','1').html("<input class='input' style='width:30px;' size='5' id='edit_"+infd+"_"+inid+"' value='"+inval+"' />").find("input").select();
			}
			var moduleid="21";
			$("#edit_"+infd+"_"+inid).focus().bind("blur",function(){
				var editval = $(this).val();
				$(this).parents("td").html(editval).attr('edit','0');
				if(inval!=editval){ 
					$.post("<?php echo U('updateStatus');?>",{id:inid,'field':infd,"value":editval});
				}
			})
		});

		

}); 
</script>
</body>
</html>