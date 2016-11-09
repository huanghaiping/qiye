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
select {
	width:100px;
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
<form class="form-inline margin_bottom m10 " action="<?php echo U('index');?>" method="post">
  用户名称：
  <input type="text" name="keyword" id="keyword"  class="form-control w30" placeholder="输入昵称或邮箱" value="<?php echo ($keyword); ?>"  style="width:100px;">
  &nbsp;&nbsp;
  <select name="status"  class="form-control w30">
    <option value="">==请选择==</option>
    <option value="0" 
    <?php if($status == '0'): ?>selected<?php endif; ?>
    >已审核
    </option>
    <option value="1" 
    <?php if($status == '1'): ?>selected<?php endif; ?>
    >已禁用
    </option>
  </select>
  &nbsp;&nbsp;
  <select name="usertype"  class="form-control w30">
    <option value="">==请选择==</option>
    <option value="0"  
    <?php if($usertype == '0'): ?>selected<?php endif; ?>
    >web
    </option>
    <option value="1" 
    <?php if($usertype == 1): ?>selected<?php endif; ?>
    >腾讯
    </option>
    <option value="2" 
    <?php if($usertype == 2): ?>selected<?php endif; ?>
    >新浪
    </option>
  </select>
 
  &nbsp;&nbsp;
   
  始：<input name="startTime" type="text" value="<?php echo ($startTime); ?>" onFocus="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"style="width:100px"  class="form-control w30">终：<input name="endTime" type="text" value="<?php echo ($endTime); ?>" onFocus="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" style="width:100px"  class="form-control w30">
  &nbsp;&nbsp;
  <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i>查询</button>
  &nbsp;&nbsp;
  <button class="btn checkStatus btn-danger" val="status" ><i class="glyphicon glyphicon-trash"></i>批量禁用</button>
</form>

<table class="table table-bordered table-hover definewidth m10">
  <thead>
    <tr>
      <th width="115"> <input name="" class="chooseAll" type="checkbox" title="点击全选"/>
        &nbsp;用户id</th>
      <th width="114">昵称</th>
      <th width="102">头像</th>
      <th width="82">手机号码</th>
      <th width="73">邮箱</th>
      <th width="165">用户类型</th>
      <th width="117">状态</th>
      <th width="221">注册日期</th>
      <th width="96">操作</th>
    </tr>
  </thead>
  <?php if(is_array($userlist)): $i = 0; $__LIST__ = $userlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="<?php echo ($vo["uid"]); ?>">
      <td><input name="aid[]" type="checkbox" value="<?php echo ($vo["uid"]); ?>">
        &nbsp;<?php echo ($vo["uid"]); ?></td>
      <td><?php echo ($vo["nickname"]); ?></td>
      <td><?php if($vo['faceurl'] != ''): ?><img src="<?php echo ($vo["faceurl"]); ?>" width="50" height="50" />
        <?php else: ?>
        未上传<?php endif; ?></td>
      <td><?php echo ($vo["mobile"]); ?></td>
      <td><?php echo ($vo["email"]); ?></td>
      <td><?php echo (getPlatform($vo["usertype"])); ?></td>
      <td title="点击更改状态"><a href="javascript:void(0);" class="userStatus" val="<?php echo ($vo["status"]); ?>" name="status">
        <?php if(($vo['status']) == "1"): ?>已禁用<?php else: ?>已审核<?php endif; ?></a></td>
      <td><?php echo (date('Y-m-d H:i',$vo["reg_time"])); ?> </td>
      <td><a href="<?php echo U('edit',array('id'=>$vo['uid']));?>">编辑</a>&nbsp;|&nbsp;<a href="<?php echo U('Order/index',array('user_id'=>$vo['uid']));?>">订单</a>
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
  
  <tr>
   
    <td colspan="9" style="text-align:right"><?php if(trim($page) != '' ): ?><ul class="pagination text-center"> <?php echo ($page); ?> </ul><?php endif; ?> </td>
    </tr>
 
</table>
</div>
</body>
</html>
<script type="text/javascript" charset="utf-8" src="/Public/layer/laydate.js"> </script>
<script>
    $(function () {
		 clickCheckbox();
		 $(".userStatus").click(function(){
		 	var id=$(this).parents("tr").attr("id");
			var val=$(this).attr("val");
			var type=$(this).attr("name");
			var obj=$(this);
			$.post("<?php echo U('updateStaus');?>",{"id":id,"value":val,"type":type},function(data){
				if(data.status==1){
					if(type=="status"){
						$(obj).html(val==0 ? "已禁用" : "已审核").attr("val",val==1 ? 0 : 1);
					}else{
						$(obj).html(val==1 ? "会员" : "教师").attr("val",val==1 ? 0 : 1);
					}
					layer.msg(data.info)
				}else{
					layer.msg(data.info)
				}
			}); 
		 });
		 
		 $(".checkStatus").click(function(){
			 var files=[];
			 $("tbody input[type='checkbox'][name='aid[]']:checked").each(function(i){
					  files[i]=$(this).val();
			 });
			  if(files==""){
				layer.msg('请选择要删除的内容', {icon: 2});
				return false;	
			  } 
			 var type=$(this).attr("val");
			 layer.confirm('确定要删除所有内容?', {
			  btn: ['确定','取消'] //按钮
			}, function(){
				$.post("<?php echo U('updateStaus');?>",{"id":files,"value":0,"type":type},function(data){
					if(data.status==1){
					  layer.msg(data.info, {icon: 1});
					}else{
					  layer.msg(data.info, {icon: 2});
					}
					setTimeout("window.location.reload();",2000);
				});
			}); 
			 return false;
		});
		 
		 
 
		 

});
</script>