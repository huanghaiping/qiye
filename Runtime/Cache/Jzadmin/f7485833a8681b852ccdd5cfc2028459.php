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
.ts{ color:#F00; display:none; margin-top:10px; margin-left:10px; margin-bottom:5px;}
.contents{ display:none;}
.upgrade_list{ margin:0px 10px 10px 10px;}
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
  <table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td width="50%" valign="top" style="  padding-right: 15px;" >
         <table border="0" cellspacing="0" cellpadding="0"  class="table table-bordered table-hover" >
 <tr> 
 <th>系统更新消息</th>  
 </tr>
  <tr> 
 <td style=" line-height:25px;">
 	<div style="display:inline-block; width:100%; height:155px;">
 	<div style="width:100%; display:inline-block">当前版本名称：<?php echo (C("SOFT_NAME")); ?>
    <?php if($domain_info['status'] == 1): ?><a href="javascript:void(0)">(<strong><?php echo ($domain_info["info"]); ?></strong>)</a>
    <div style="width:100%; display:inline-block">
    授权时间：<a href="javascript:void(0)">[<?php echo ($domain_info["data"]["start_time"]); ?>到<?php echo ($domain_info["data"]["end_time"]); ?>]</a>
    </div>
    <?php else: ?>
    <a href="javascript:void(0)" style=" color:#F00">(<strong><?php echo ($domain_info["info"]); ?></strong>)</a><?php endif; ?>
    </div>
    
     <div style="width:100%; display:inline-block">版本号：<?php echo (C("SOFT_VERSION")); ?><a href="javascript:void(0)" style="display:none">(最新版本)</a></div>
     
    <?php if($domain_info['status'] == 1): if($domain_info['is_upgrade'] == 1): ?><div style="width:100%; display:inline-block">
        <a href="javascript:void(0);" class="np coolbg" id="online_upgrade" style="display:block">进行在线更新</a> 
        <div style="display:block; margin:10px 0px 0px 0px;color:#F00; ">升级前请备份数据库,代码,数据资源以防止升级失败,如有修改代码或者数据库就不适合升级</div>
        </div>
        
        <div  id="upgrade_list" style="display:none">
        	<div class="ts">升级前请备份数据库,代码,数据资源以防止升级失败,如有修改代码或者数据库就不适合升级</div>
        	<div class="upgrade_list">
            <table class="table table-bordered table-hover definewidth m10" style="font-size:12px">
            <tr>
            	<td width="250">版本名称</td>
                <td>适用版本</td>
                <td>升级说明</td>
                <td>下载地址</td>
            </tr>
           
            </table>
           </div>
        </div>
        <?php else: ?>
        	<div style="width:100%; display:inline-block"><a href="javascript:void(0);" class="np coolbg">已是最新版本</a></div><?php endif; ?>
        
    <?php else: ?>
     <div style="width:100%; display:inline-block">
     <a href="javascript:void(0)" class="np coolbg"  style=" color:#F00">未授权无法更新</a>
     <a href="<?php echo (C("ADMIN_SITE_URL")); ?>/service/" class="np coolbg" target="_blank">(立即授权)</a>
     </div><?php endif; ?>
 </div>
 </td>  
 </tr>
 </table>
 
    </td>
    <td width="50%">     <table  border="0" cellspacing="0" cellpadding="0"  class="table table-bordered table-hover" >
 <tr> 
 <th>商业授权查询</th>  
 </tr>
 <tr>
 	 <td style="height: 110px;">
      <iframe scrolling="auto" allowtransparency="true" id="layui-layer-iframe122" name="layui-layer-iframe1-2" width="100%" frameborder="0" src="<?php echo (C("ADMIN_SITE_URL")); ?>/Station/authorize"></iframe>
     </td>
 </tr>
 </table></td>
  </tr>
  
    <tr>
    <td width="50%" valign="top" style="  padding-right: 15px;" >
         <table border="0" cellspacing="0" cellpadding="0"  class="table table-bordered table-hover" >
 <tr> 
 <th>帮助中心</th>  
 </tr>
  <tr> 
 <td>
 <iframe scrolling="auto" allowtransparency="true" id="layui-layer-iframe1" name="layui-layer-iframe1" width="100%" frameborder="0" src="<?php echo (C("ADMIN_SITE_URL")); ?>/Station/help"></iframe>

 </td>  
 </tr>
 </table>
 
    </td>
    <td width="50%">     <table  border="0" cellspacing="0" cellpadding="0"  class="table table-bordered table-hover" >
 <tr> 
 <th>营销中心</th>  
 </tr>
 <tr>
 	 <td>
 <iframe scrolling="auto" allowtransparency="true" id="layui-layer-iframe2" name="layui-layer-iframe2" width="100%" frameborder="0" src="<?php echo (C("ADMIN_SITE_URL")); ?>/Station/marketing"></iframe>

     </td>
 </tr>
 </table>
 	</td>
  </tr>
</table>

  <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-bordered table-hover">
 <tr>      
        <th colspan="4" align="left">系统基本信息</th>
       
          </tr>
    <?php if(is_array($server_info)): $k = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($k%2==1) echo "
        <tr>"; ?>
      
        <td width="120" align="left"><?php echo ($key); ?>：</td>
        <td><?php echo ($vo); ?></td>
          <?php if($k%2==0) echo "</tr>
      "; endforeach; endif; else: echo "" ;endif; ?>
    <?php if(count($caches)%2==1) echo '
      
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>'; ?>
  </table>
</div>
<?php if(!empty($new_order)): ?><div id="popMsgContent" style="font-size:12px; margin:10px 15px;">
      <p>您有 <strong style="color:#ff0000" id="spanNewOrder"><?php echo ($new_order["new_total_order"]); ?></strong> 个新订单以及       <strong style="color:#ff0000" id="spanNewPaid"><?php echo ($new_order["pay_success"]); ?></strong> 个新付款的订单</p>
      <p align="center" style="word-break:break-all"><a href="<?php echo U('Order/index');?>"><span style="color:#ff0000">点击查看新订单</span></a></p>
</div>
<script type="text/javascript">
$(function(){
	layer.open({
	  type: 1,
	  title: "新订单通知",
	  closeBtn: 1, //不显示关闭按钮
	  shade: [0],
	  area: ['250px', '150px'],
	  offset: 'rb', //右下角弹出
	  time: 5000, //2秒后自动关闭
	  shift: 2,
	  content: $('#popMsgContent') //iframe的url，no代表不显示滚动条
	  
	});	
})
</script><?php endif; ?>
<script type="text/javascript">
$(function(){
  $("#online_upgrade").click(function(){
		var index=layer.confirm('确定要升级嘛？升级前请备份数据库,代码,数据资源以防止升级失败,如有修改代码或者数据库就不适合升级', {
		  btn: ['确定','取消'] //按钮
		}, function(){
			 $.post("<?php echo U('Index/upgrade');?>",function(data){
				layer.close(index);
				if(data.status==1){
					$("#online_upgrade").hide();
					$(".ts").show();
					//$("#upgrade_list").show();
					var html_string='';
					$.each(data.url,function(name,value){
						var typeid=value['typeid']==1 ? "升级包":"补丁";
						html_string+='<tr class="rows"><td>'+value['title']+'<span style="color:#f00">('+typeid+')</span></td><td>'+value['version']+'</td><td ><div   style="max-height:100px; overflow:auto">'+value['content']+'</div></td><td><a href="'+value['download_url']+'">下载('+value['download_size']+')</a></td</tr>';
					});
					$("#upgrade_list").find("table").append(html_string);
					layer.open({
					  type: 1,
					   title: "在线升级",
					  area: ['700px', '530px'],
					  fix: false, //不固定
					  maxmin: true,
					  content: $("#upgrade_list").html()
					});
				}else{
					layer.alert(data.info);	
				}	 
			});
		}); 
		return false;
  });

 

});
</script>
</body>
</html>