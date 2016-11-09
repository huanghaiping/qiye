<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo ($site_title); ?></title>
	<meta name="keywords" content="<?php echo ($site_keywords); ?>" />
	<meta name="description" content="<?php echo ($site_description); ?>" />
    <link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Apps/Home/View/default/Public/css/swiper.min.css"/>
    <link rel="stylesheet" type="text/css" href="/Apps/Home/View/default/Public/css/basis.css"/>
    <script src="/Apps/Home/View/default/Public/js/jquery.js" type="text/javascript" charset="utf-8"></script>
    <script src="/Apps/Home/View/default/Public/js/respond.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/Apps/Home/View/default/Public/js/swiper.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/Apps/Home/View/default/Public/js/jquery.sidr.min.js"></script>
	<script>
		$(function(){
		   $('#left-menu').sidr({
		name: 'sidr-left',
		side: 'right'
		    });
		});
	</script>
    <!--[if lt IE 9]>
	    <script src="/Apps/Home/View/default/Public/js/html5shiv.min.js"></script>
	    <script src="/Apps/Home/View/default/Public/js/respond.min.js"></script>
    <![endif]-->

  </head>
	<body>
					<!--小屏菜单 -->
		<div class="clearfix xs-nav" id="sidr-left">
			<ul>
            	
				<li  <?php if(empty($topid)): ?>class="active"<?php endif; ?> ><a href="/<?php echo ($lang); ?>"><?php echo L('HOME');?></a></li>
                <?php if(empty($categorys)){ $categorys=D('Menu')->getAllMenu(); }foreach($categorys as $key=>$nav):if($nav["status"]==0){ continue; }if( ( intval(0)==$nav["parent_id"] ) and ( 2==$nav["position"]) ) :?><li <?php if(($topid) == $nav['id']): ?>class="active"<?php endif; ?>><a href="<?php echo ($nav["url"]); ?>"><?php echo ($nav["title"]); ?></a></li><?php endif; endforeach;?>
		 
			</ul>
		</div>
        
		<header id="header">
			<div class="top-bar">
				<div class="container">
					<div class="fl">
						<?php echo L('ZIXUN_HOT');?>： xxx-xxx-xxxxx
					</div>
					<div class="fr top-a">
                   	<?php if(is_array($lang_list)): $i = 0; $__LIST__ = $lang_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>						 
					</div>
                     <div class="navbar-right member" style="margin-right: 15px;">
				
                <?php if($userInfo): ?><div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                    <img src="<?php echo ($userInfo["faceurl"]); ?>" width="24"><?php echo ($userInfo["name"]); ?> 
                    <span class="label label-danger hide gomyCart_top"></span>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="<?php echo U('User/order');?>" rel="nofollow" title="<?php echo L('ORDER_LIST');?>"><?php echo L('ORDER_LIST');?></a></li>
                    <li><a href="<?php echo U('User/index');?>" rel="nofollow" title="<?php echo L('MEMBER_CENTER');?>"><?php echo L('MEMBER_CENTER');?></a></li>
                    <li role="separator" class="divider"></li>		
                    <li><a href="<?php echo U('Reg/logout');?>" rel="nofollow" title="<?php echo L('SIGN_OUT');?>"><?php echo L('SIGN_OUT');?></a></li>
                  </ul>
            </div>
                <?php else: ?>
                <a href="<?php echo U('Login/index');?>"  title="<?php echo L('SIGN_IN');?>" class="btn btn-info"><?php echo L('SIGN_IN');?></a>
				<a href="<?php echo U('Reg/index');?>" title="<?php echo L('REGISTER');?>"  class="btn btn-success"><?php echo L('REGISTER');?></a><?php endif; ?>
				
			</div>
            
            
				</div>
			</div>
			
			<div class="nav-box">
				<div class="container">
					<div class="fl">
						<a href="/"><img src="<?php echo (C("SITE_LOGO")); ?>" class="logo" /></a>
					</div>
					<nav class="fr nav">
						<i class="icon-language"><img src="/Apps/Home/View/default/Public/img/yy.png"/></i>
						<i class="icon-menu"id="left-menu" >
							<img src="/Apps/Home/View/default/Public/img/nav.png"/>
						</i>
						<!--大屏菜单 -->
						<ul>
							<li <?php if(empty($topid)): ?>class="active"<?php endif; ?> ><a href="/<?php echo ($lang); ?>"><?php echo L('HOME');?></a></li>
                             <?php if(empty($categorys)){ $categorys=D('Menu')->getAllMenu(); }foreach($categorys as $key=>$nav):if($nav["status"]==0){ continue; }if( ( intval(0)==$nav["parent_id"] ) and ( 2==$nav["position"]) ) :?><li <?php if(($topid) == $nav['id']): ?>class="active"<?php endif; ?> ><a href="<?php echo ($nav["url"]); ?>"><?php echo ($nav["title"]); ?></a></li><?php endif; endforeach;?>
						 
						 
						</ul>
                        
					</nav>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="language-txt">
				<a href="#">English</a>
				<a href="#">中文简体</a>
			</div>
		</header>
<div class="swiper-container" id="t-ban">
  <div class="swiper-wrapper">
    <?php $info=D ( "Ad" )->where ( " adid ='Home_slide' and lang='$lang' " )->field ( "id" )->limit ( 1 )->find (); if($info){ $slide=$_result=D('Slide')->where("typeid='".$info['id']."'")->order('sortslide desc ,id desc')->select(); foreach($_result as $key=>$slide):?><div class="swiper-slide"><a href="<?php echo ($slide["linkurl"]); ?>"><img src="<?php echo ($slide["picurl"]); ?>"/></a></div><?php endforeach; } ?>
  </div>
  <!-- Add Pagination -->
  <div class="swiper-pagination"></div>
</div>

<!--最新产品区域-->
<div class="container">
  <div class="title-01">
    <h2><?php echo L('NEW_PRODUCT');?></h2>
    <h3>LATEST PRODUCTS</h3>
  </div>
  <div class="swiper-container" id="sy-cplist">
    <div class="swiper-wrapper">
    
     <?php if(empty($categorys)){ $categorys=D('Menu')->getAllMenu(); } $param=array(); try { $_result=M("Product")->field("id,title,catid,thumb")->where(" 1  and lang='$lang' AND status=0 ")->order("id desc")->limit("20")->select();; } catch (\Exception $e) { echo $e->getMessage();} if ($_result): $i=0;foreach($_result as $key=>$list):if(isset($list["jump_url"])&&!empty($list["jump_url"])){ $param["jump_url"]=$list["jump_url"]; }$list["url"]=createHomeUrl($categorys[$list["catid"]],$list["id"],$param);++$i;$mod = ($i % 2 );?><div class="swiper-slide">
        <div class="sy-cpbox">
          <div class="cp-img"> <a href="<?php echo ($list["url"]); ?>"><img src="<?php echo ($list["thumb"]); ?>"/></a> </div>
          <p><a href="<?php echo ($list["url"]); ?>"><?php echo ($list["title"]); ?></a></p>
        </div>
      </div><?php endforeach; endif;?>
     
      
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
  </div>
  <a href="/product/" class="cp-gd"><?php echo L('SEE_MORE');?> ></a> </div>
<!--最新产品区域 结束--> 

<!--关于我们区域-->
<div class="box2">
  <div class="container">
    <div class="title-01">
      <h2><?php echo L('ABOUT_US');?></h2>
      <h3>ABOUT  US</h3>
    </div>
     <?php $_result=M('Block')->where(" pos ='home_about' and lang='$lang'")->select(); foreach($_result as $key=>$block): $block["content"]=isset($block["content"]) ? stripslashes($block["content"]) : ""; echo ($block["content"]); endforeach;?>
  </div>
</div>
<!--关于我们区域 结束--> 

<!--新闻咨询-->
<div class="container">
  <div class="title-01">
    <h2><?php echo L('Latest_Information');?></h2>
    <h3>LATEST NEWS</h3>
  </div>
  <div class="sy-newsbox row">
    <ul class="clearfix">
    	<?php if(empty($categorys)){ $categorys=D('Menu')->getAllMenu(); } $param=array(); try { $_result=M("Article")->field("id,title,catid,thumb,description")->where(" 1  and lang='$lang' AND status=0 ")->order("id desc")->limit("8")->select();; } catch (\Exception $e) { echo $e->getMessage();} if ($_result): $i=0;foreach($_result as $key=>$list):if(isset($list["jump_url"])&&!empty($list["jump_url"])){ $param["jump_url"]=$list["jump_url"]; }$list["url"]=createHomeUrl($categorys[$list["catid"]],$list["id"],$param);++$i;$mod = ($i % 2 );?><li class="col-md-6 col-lg-6 col-sm-12">
        <div class="sy-n-img"> <a href="<?php echo ($list["url"]); ?>"><img src="<?php echo ($list["thumb"]); ?>"/></a> </div>
        <div class="sy-n-txt">
          <h2><a href="<?php echo ($list["url"]); ?>"><?php echo ($list["title"]); ?></a></h2>
          <p><?php echo ($list["description"]); ?></p>
        </div>
      </li><?php endforeach; endif;?>
      
    </ul>
  </div>
  
  <div class="newlist">
      	<div class="title"><?php echo L('Link');?></div>
        <div class="flink">
        	<ul class="x mbm cl list-inline ">
            	<?php  $_result=D("Link")->field("name,siteurl,logo")->where(" status = 1 and lang='$lang'")->order("listorder desc,id desc")->limit("10")->select();; if ($_result): $i=0;foreach($_result as $key=>$link):++$i;$mod = ($i % 2 );?><li><a href="<?php echo ($link["siteurl"]); ?>" target="_blank" title="<?php echo ($link["name"]); ?>" rel="nofollow"><?php echo ($link["name"]); ?></a></li><?php endforeach; endif;?>
              </ul>
               <?php echo W('LinkCommon/flink');?> 
        </div>
        
 </div>
</div>
<!--新闻咨询 结束--> 

<!--在线客服-->
<?php if(C("KEFU_OPEN")== 1): ?><style>
.keifu{ position:fixed; top:10%; right:0; max-width:131px;  _position:absolute; _top:expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-this.offsetHeight-(parseInt(this.currentStyle.bottom,10)||0)-(parseInt(this.currentStyle.marginTop,10)||0)-(parseInt(this.currentStyle.marginBottom,10)||0)));
 z-index:990; font-family:"微软雅黑";}
.keifu_tab{max-width:160px;}
.icon_keifu{max-height:130px; float:left; background:#029bdb; background-position: 0 40px; position:relative; cursor:pointer; text-align:center; color:#fff; font-weight:100; font-size:16px;  padding: 10px;border-top-left-radius: 5px;  border-bottom-left-radius: 5px; line-height:25px;}

.keifu_box{ float:left; width:131px; display:none;}
.keifu_head{ width:131px; height:41px; background:url(/Apps/Home/View/default/Public/img/keifu.png); background-position: 0 -103px; font-size:14px; line-height:0; position:relative;}
.keifu_head span{ text-align:center; color:#fff; font-size:14px; width:100%; display:inline-block; line-height:40px;}
.keifu_close:link,.keifu_close:visited{ display:block; width:11px; height:11px; background:url(/Apps/Home/View/default/Public/img/keifu.png) -42px 0; position:absolute; top:14px; right:5px;}
.keifu_close:hover{ background-position:-60px 0;}
.keifu_con{ border-left:7px solid #029bdb; border-right:7px solid #029bdb; padding-top:15px; background:#fbfbfb;}
.keifu_con dd{ padding:2px 0px 5px 15px; width:100%; text-align:center; display:inline-block}
.keifu_con dt{ text-align:center; margin-bottom:5px; padding:0px;}
.keifu_con .weixin{ height:96px;}
.keifu_con .bt{ font-size:16px; height:30px; line-height:30px; text-align:left; padding:0 0 0 20px; color:#012646;}
.keifu_bot{ width:131px; height:15px; background:url(/Apps/Home/View/default/Public/img/keifu.png); background-position:0 -149px; }
</style>

<div class="keifu">
  <div class="keifu_tab">
    <div class="icon_keifu"><?php echo L('ONLINE_KEFY');?></div>
    
    <div class="keifu_box">
      <div class="keifu_head"><span><?php echo L('ONLINE_KEFY');?></span><a href="javascript:void(0)" class="keifu_close"></a></div>
      <?php $_result=M('Kefu')->where(" status =1 and lang='$lang'")->select(); foreach($_result as $key=>$kefu): $kefu["content"]=isset($kefu["content"]) ? stripslashes($kefu["content"]) : ""; ?><dl class="keifu_con">
        <?php if($kefu['typeid'] == 5): ?><dd><?php echo ($kefu["content"]); ?>  </dd>
        <?php elseif($kefu['typeid'] == 6): ?>
        	 <dt><?php echo ($kefu["name"]); ?></dt>
        	  <dd><img src="<?php echo ($kefu["logo"]); ?>" width="80" />  </dd>
        <?php else: ?>
        	<dt><?php echo ($kefu["name"]); ?></dt>
            <?php $_result=kfInfo($kefu['pay_config']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_vo): $mod = ($i % 2 );++$i;?><dd><?php echo kfType($kefu['typeid'],$_vo['key'],$_vo['value']);?></dd><?php endforeach; endif; else: echo "" ;endif; endif; ?>
       	
      </dl><?php endforeach;?>
      <div class="keifu_bot"></div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
	var KF = $(".keifu");
	var wkbox = $(".keifu_box");
	var kf_close = $(".keifu .keifu_close");
	var icon_keifu = $(".icon_keifu");
	var lang="<?php echo ($lang); ?>";
	icon_keifu.css("width",lang=="cn" ? 36 : 130);
	var kH = wkbox.height();
	var kW = wkbox.width();
	var wH = $(window).height();
	KF.css({height:kH});
	icon_keifu.css("top",parseInt((kH-100)/2));
	var KF_top = (wH-kH)/2;
	if(KF_top<0) KF_top=0;
	KF.css("top",KF_top);
	
	$(kf_close).click(function(){
		KF.animate({width:"0"},200,function(){
			wkbox.hide();
			icon_keifu.show();
			KF.animate({width:36},300);		
		});	
	});
	$(icon_keifu).click(function(){
			$(this).hide();
			wkbox.show();
			KF.animate({width:kW},300);
	});
});
</script><?php endif; ?>
<!--在线客服-->

<!--网页底部--> 

<footer id="footer">
    	<div class="container b-navbox clearfix">
    		<ul class="fl b-nav">
            	  <?php if(empty($categorys)){ $categorys=D('Menu')->getAllMenu(); }foreach($categorys as $key=>$nav):if($nav["status"]==0){ continue; }if( ( intval(0)==$nav["parent_id"] ) and ( 2==$nav["position"]) ) :?><li>
    				<h2><a href="<?php echo ($nav["url"]); ?>"><?php echo ($nav["title"]); ?></a></h2>
                    <?php if(!empty($nav['arcchild_list'])): if(is_array($nav['arcchild_list'])): $i = 0; $__LIST__ = $nav['arcchild_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($_vo["url"]); ?>"><?php echo ($_vo["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    			</li><?php endif; endforeach;?>
    			 
    		</ul>
    		<div class="fr rmw">
    			<img src="/Apps/Home/View/default/Public/img/rwm.png"/>
    			<p><?php echo L('WeChat');?></p>
    		</div>
    	</div>
    	
    	<div class="copy">
    		<div class="container">
    		<?php $_result=M('Block')->where(" pos ='footer' and lang='$lang'")->select(); foreach($_result as $key=>$block): $block["content"]=isset($block["content"]) ? stripslashes($block["content"]) : ""; echo ($block["content"]); endforeach;?>
    		</div>
    	</div>
    	
    </footer>
    <!--网页底部 结束--> 

<script src="/Public/bootstrap/js/bootstrap.min.js"></script>

<!--首页语言切换JS--> 
<script type="text/javascript">
	$(document).ready(function(){
	  $(".icon-language").click(function(){
	  $(".language-txt").toggle(200);
	  });
});
</script> 
<!--首页焦点图 JS--> 
<script type="text/javascript">
var swiper = new Swiper('#t-ban', {
	pagination: '#t-ban .swiper-pagination',
	paginationClickable: true,
	loop : true,
});
</script> 

<!--最新产品区域 JS--> 
<script type="text/javascript">
    var swiper = new Swiper('#sy-cplist', {
        pagination: '#sy-cplist .swiper-pagination',
        paginationClickable: true,
        slidesPerView: 4,
        spaceBetween: 50,
        breakpoints: {
            1000: {
                slidesPerView: 3,
                spaceBetween: 40
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 30
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            480: {
                slidesPerView: 1,
                spaceBetween: 10
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10
            }
        }
    });
    </script>
</body></html>