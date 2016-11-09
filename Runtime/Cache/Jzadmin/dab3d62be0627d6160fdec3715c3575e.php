<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>广告管理列表</title>
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
  <input name="title" type="text" class="form-control w30" value="<?php echo ($keyword); ?>" placeholder="广告别名">
  &nbsp;
  <button name="btn" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i>查 询</button>
  &nbsp;
  <button type="button" class="btn btn-success" id="addnew" onClick="javascript:window.location.href='<?php echo U('addAd');?>'"><i class=" glyphicon glyphicon-plus"></i>添加广告</button>
</form>
<form action="<?php echo U('delAll');?>" method="post" name="form2" id="playList" class="form-inline definewidth m10" style="margin-left:0px;">
  <input name="typename" type="hidden" value="main">
<table class="table table-bordered table-hover definewidth m10" style="font-size:12px">
 
    <tr>
      <th>选择</th>
      <th>广告位位置</th>
      <th>广告位标识</th>
   
      <th>类型</th>
      <th>添加时间</th>
      <th>操作</th>
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr>
        <td align="left"><input name="id[]"  class="id" type="checkbox"  value="<?php echo ($vo["id"]); ?>">
          <?php echo ($vo["id"]); ?></td>
        <td align="left"><?php echo ($vo["adname"]); ?></td>
        <td align="left"><?php echo ($vo["adid"]); ?></td>
        
        <td align="left"><?php switch($vo["typeid"]): case "1": ?>表示代码(网页代码)<?php break;?>
            <?php case "2": ?>文字广告<?php break;?>
            <?php case "3": ?>图片广告<?php break;?>
            <?php case "4": ?>flash广告<?php break;?>
            <?php case "5": ?>幻灯片<?php break;?>
            <?php default: ?>
            未知类型<?php endswitch;?></td>
        <td align="left"><?php echo (Date('Y-m-d H:i:s',$vo["ctime"])); ?></td>
        <td align="left"><?php if($vo["typeid"] == 5): ?><a href="<?php echo U('slideList',array('id'=>$vo['id']));?>">上传图片</a> |<?php endif; ?>
         <!-- <a href="<?php echo U('getCode',array('id'=>$vo['id']));?>">获取代码</a> |--> <a href="<?php echo U('edit',array('id'=>$vo['id']));?>"> 修改</a> | <a href="javascript:void(0)" link="<?php echo U('del',array('id'=>$vo['id']));?>" class="delCate"> 刪除 </a></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="7" align="center">
      <?php if(trim($page) != '' ): ?><ul class="pagination text-center" style="margin:10px 0px"><?php echo ($page); ?> </ul><?php endif; ?>
        </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td colspan="7" align="left">* 广告的类型: 1 表示代码(网页代码,2 表示文字广告,3 表示图片广告, 4 表示flash广告，5 表示幻灯片</td>
    </tr>
  </table>
</form>
</div>
<script type="text/javascript">
$(function(){
	$(".delCate").click(function(){
		var $this=$(this);
		layer.confirm('确定要删除嘛?', {
		  btn: ['确定','取消'] //按钮
		}, function(){
			var url = $this.attr("link");
		  	$.get(url,function(data){
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