<?php
namespace Org\Util;
/**
 * 
 * Form.php (模型表单生成)
 *
 * @package      	YOURPHP
 * @author          Alan
 */
use Think\View;

class Form extends View {
	public $data = array() ,$isadmin=1,$doThumb=1,$doAttach=1,$lang;

    public function __construct($data=array()) {
         $this->data = $data;
		 $this->lang = LANG_SET;
    }
    
 	/**
 	 * +-------------------------------------------
 	 * 获取栏目的内容
 	 * 
 	 * @param Array $info	字段的类型
 	 * @param string $value	字段的值
 	 * @return String $parseStr 返回组成成的字符串
 	 */
	public function catid($info,$value){
        $validate = getvalidate($info);		
		$Category = F('Category_'.$this->lang);			
		$id = $field = strtolower($info['field']);
		$value = $value ? $value : $this->data[$field];
		$moduleid =$info['moduleid'];
		$menu_model=D("Menu");
		$category_list = $menu_model->getMenuByModuleId ($moduleid,$value);
		$parseStr .= '<select  id="'.$id.'" name="'.$field.'" class="form-control w30"  '.$validate.'>';
		$parseStr .= '<option value="">==请选择'.$info['name'].'==</option>';
		foreach ($category_list as $value){
			$parseStr.=$value['option'];
		}
		$parseStr .= '</select>';
		return $parseStr;
	}

	/**
 	 * +-------------------------------------------
 	 * 获取标题的内容
 	 * 
 	 * @param Array $info	字段的类型
 	 * @param string $value	字段的值
 	 * @return String $parseStr 返回组成成的字符串
 	 */
	public function title($info,$value){
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$thumb=$info['setup']['thumb'];
		$style=$info['setup']['style'];
		$id = $field = strtolower($info['field']);
	    $validate = getvalidate($info);
		$value = $value ? $value : $this->data[$field];
		$parseStr   = '<input type="text"   class="form-control w30" name="'.$field.'"  id="'.$id.'" value="'.$value.'" size="'.$info['setup']['size'].'"  '.$validate.'  /> ';
		return $parseStr;
	}

	public function text($info,$value){
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
	    $validate = getvalidate($info);
		$info['setup']['ispassword'] ? $inputtext = 'password' : $inputtext = 'text';
        if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
		$parseStr   = '<input type="'.$inputtext.'"   class="form-control w30" name="'.$field.'"  id="'.$id.'" value="'.stripcslashes($value).'" size="'.$info['setup']['size'].'"  '.$validate.'/> ';
		return $parseStr;
	}



	public function verify($info,$value){
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
	    $validate = getvalidate($info);
		$parseStr   = '<input   class="form-control w30 '.$info['class'].'" name="'.$field.'"  id="'.$id.'" value="" size="'.$info['setup']['size'].'"  '.$validate.' /><img src="'.U('Home/Verify/index',array('w'=>120,'len'=>4,'h'=>30,'size'=>14)).'" onclick="this.src=this.src+\'?rand=\'+Math.random();" class="checkcode" align="absmiddle"  title="点击刷新验证码" id="verifyImage"/>';
		return $parseStr;
	}




	public function number($info,$value){
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
	    $validate = getvalidate($info);
		$info['setup']['ispassowrd'] ? $inputtext = 'passowrd' : $inputtext = 'text';
        if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
		$parseStr   = '<input type="'.$inputtext.'"   class="form-control w30 '.$info['class'].'" name="'.$field.'"  id="'.$id.'" value="'.$value.'" size="'.$info['setup']['size'].'"  '.$validate.'/> ';
		return $parseStr;
	}

	public function textarea($info,$value){
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
        $validate = getvalidate($info);
        if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
		$parseStr   = '<textarea  class=" form-control w30 '.$info['class'].'" name="'.$field.'"  rows="'.$info['setup']['rows'].'" cols="'.$info['setup']['cols'].'"  id="'.$id.'"   '.$validate.' style="width:420px; height:100px;"/>'.stripcslashes($value).'</textarea>';
		return $parseStr;
	}


	public function select($info,$value){

		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
		$validate = getvalidate($info);
        if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
       
        if($value != '') $value = strpos($value, ',') ? explode(',', $value) : $value;
        if(is_array($info['options'])){
             if($info['options_key']){
				$options_key=explode(',',$info['options_key']);
				foreach((array)$info['options'] as $key=>$res){
					if($options_key[0]=='key'){
						$optionsarr[$key]=$res[$options_key[1]];
					}else{
						$optionsarr[$res[$options_key[0]]]=$res[$options_key[1]];
					}
				}
			}else{
             $optionsarr = $info['options'];
			}
        }else{
            $options    = $info['setup']['options'];
            $options = explode("\n",$info['setup']['options']);
        	foreach($options as $r) {
        		$v = explode("|",$r);
        		$k = trim($v[1]);
        		$optionsarr[$k] = $v[0];
        	}
        }
        if(!empty($info['setup']['multiple'])) {
            $parseStr = '<select id="'.$id.'" name="'.$field.'"  onchange="'.$info['setup']['onchange'].'" class="form-control w30 '.$info['class'].'"  '.$validate.' size="'.$info['setup']['size'].'" multiple="multiple" >';
        }else {
        	$parseStr = '<select id="'.$id.'" name="'.$field.'" onchange="'.$info['setup']['onchange'].'"  class="form-control w30 '.$info['class'].'" '.$validate.'>';
        }
		
        if(is_array($optionsarr)) {
			foreach($optionsarr as $key=>$val) {
				if($value!=""){
				    $selected='';
					if($value==$key || in_array($key,$value)){
						 $selected = ' selected="selected"';
					}
				    $parseStr   .= '<option '.$selected.' value="'.$key.'">'.$val.'</option>';
				}else{
					$parseStr   .= '<option value="'.$key.'">'.$val.'</option>';
				}
			}
		}
        $parseStr   .= '</select>';
        return $parseStr;
	}
	public function checkbox($info,$value){
	     
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
		$validate = getvalidate($info);
		if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
        $labelwidth = $info['setup']['labelwidth'];

        if(is_array($info['options'])){
			if($info['options_key']){
				$options_key=explode(',',$info['options_key']);
				foreach((array)$info['options'] as $key=>$res){
					if($options_key[0]=='key'){
						$optionsarr[$key]=$res[$options_key[1]];
					}else{
						$optionsarr[$res[$options_key[0]]]=$res[$options_key[1]];
					}
				}
			}else{
             $optionsarr = $info['options'];
			}
        }else{
            $options    = $info['setup']['options'];
            $options = explode("\n",$info['setup']['options']);
        	foreach($options as $r) {
        		$v = explode("|",$r);
        		$k = trim($v[1]);
        		$optionsarr[$k] = $v[0];
        	}
        }
		if($value != '') $value = (strpos($value, ',') && !is_array($value)) ? explode(',', $value) :  $value ;
		$value = is_array($value) ? $value : array($value);
		$i = 1;
		$onclick = $info['setup']['onclick'] ? ' onclick="'.$info['setup']['onclick'].'" ' : '' ;

		foreach($optionsarr as $key=>$r) {
			$key = trim($key);
            if($i>1) $validate='';
			$checked = ($value && in_array($key, $value)) ? 'checked' : '';
			if($labelwidth){ 
				$parseStr .= '<label style="float:left;width:'.$labelwidth.'px" class="checkbox_'.$id.'" >';
			}else{
				$parseStr .= '<label style="float:left;margin-right:10px;" class="checkbox_'.$id.'" >';
			}
			$parseStr .= '<input type="checkbox" class="input_checkbox '.$info['class'].'" name="'.$field.'[]" id="'.$id.'_'.$i.'" '.$checked.$onclick.' value="'.htmlspecialchars($key).'"  '.$validate.'> '.htmlspecialchars($r);
			 $parseStr .= '</label>';
			$i++;
		}
		return $parseStr;

	}
	public function radio($info,$value){

       $info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
		$validate = getvalidate($info);
		if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
        $labelwidth = $info['setup']['labelwidth'];

        if(is_array($info['options'])){
             if($info['options_key']){
				$options_key=explode(',',$info['options_key']);
				foreach((array)$info['options'] as $key=>$res){
					if($options_key[0]=='key'){
						$optionsarr[$key]=$res[$options_key[1]];
					}else{
						$optionsarr[$res[$options_key[0]]]=$res[$options_key[1]];
					}
				}
			}else{
             $optionsarr = $info['options'];
			}
        }else{
            $options    = $info['setup']['options'];
            $options = explode("\n",$info['setup']['options']);
        	foreach($options as $r) {
        		$v = explode("|",$r);
        		$k = trim($v[1]);
        		$optionsarr[$k] = $v[0];
        	}
        }
		$onclick = $info['setup']['onclick'] ? ' onclick="'.$info['setup']['onclick'].'" ' : '' ;
        $i = 1;
        foreach($optionsarr as $key=>$r) {
            if($i>1) $validate ='';
			$checked = trim($value)==trim($key) ? 'checked' : '';
			if(empty($value) && empty($key) ) $checked = 'checked';
			if($labelwidth) $parseStr .= '<label style="float:left;width:'.$labelwidth.'px" class="checkbox_'.$id.'" >';
			$parseStr .= '<input type="radio" class="input_radio '.$info['class'].'" name="'.$field.'" id="'.$id.'_'.$i.'" '.$checked.$onclick.' value="'.$key.'" '.$validate.'> '.$r;
			if($labelwidth) $parseStr .= '</label>';
            $i++;
		}
		return $parseStr;
	}


	public function editor($info,$value){
 
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
		$validate = getvalidate($info);
		if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
			$value =  stripcslashes($value);
        }
		 $textareaid = $field;
		 $toolbar = $info['setup']['toolbar'];
		 $moduleid = $info['moduleid'];
		 $height = $info['setup']['height'] ? $info['setup']['height'] : 600;
		 $flashupload = $info['setup']['flashupload']==1 ? 1 : '';
		 $alowuploadexts = $info['setup']['alowuploadexts'] ? $info['setup']['alowuploadexts'] :  'jpg,gif,png';
		 $alowuploadlimit=$info['setup']['alowuploadlimit'] ? $info['setup']['alowuploadlimit'] : 20 ;
		 $show_page=$info['setup']['showpage'];
	
		$str ='<script type="text/javascript"> $(document).ready(function(){ var ue = UE.getEditor("'.$field.'");})</script>';
		$str .= '<div class="editor_box"><div style="display:none;" id="'.$field.'_aid_box"></div><textarea name="'.$field.'" class="'.$info['class'].'"   style="height: '.$height.'px; width: 90%;"  id="'.$id.'"  boxid="'.$id.'" '.$validate.' >'.$value.'</textarea>';
		return $str;
	}
	public function datetime($info,$value){
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
		$validate = getvalidate($info);
		if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
		$value = $value ?  getTimeInfo($value) : getTimeInfo(time());

		$parseStr = '<input  class="laydate-icon  form-control w30'.$info['class'].'" style="height:35px"  '.$validate.'  name="'.$field.'" type="text" id="'.$id.'" onFocus="laydate({istime: true, format: \'YYYY-MM-DD hh:mm:ss\'})" value="'.$value.'" />';
        return $parseStr;
	}
	
    public function groupid($info,$value){
        $newinfo = $info;
        $info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
        $groups=F('Role');$options=array();
        foreach($groups as $key=>$r) {
            if($r['status']){
                $options[$key]=$r['name'];
            }
		}
        $newinfo['options']=$options;
        $fun=$info['setup']['inputtype'];
        return $this->$fun($newinfo,$value);
    }
    public function posid($info,$value){
        $newinfo = $info;
        //获取推荐位
        $menu_model=D ( "Menu" );
		$posids = $menu_model->getMenuFlag ($info['moduleid']);
        $options=array();
        $options[0]= '==请选择推荐位==';
        foreach($posids as $key=>$r) {
           $options[$r['id']]=$r['name'];
		}
        $newinfo['options']=$options;
        $fun=$info['setup']['inputtype'];
        return $this->select($newinfo,$value);
    }


    public function template($info,$value){
	   
    	$moduleid =$info['moduleid'];
		$module_model=D("Module");
		$module_info = $module_model->getModuleIdByModuleId ($moduleid);
        $templates= template_file($module_info['controller_name']);
        $newinfo = $info;
        $info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
        $options=array();
        $options[0]= '==请选择模板==';
        foreach($templates as $key=>$r) {
                $options[$r['value']]=$module_info['controller_name']."_".$r['filename'];
		}
        $newinfo['options']=$options;
        $fun=$info['setup']['inputtype']; 
        return $this->select($newinfo,$value);
    }


	public function image($info,$value){
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
	    $validate = getvalidate($info);
        if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
        if(empty($info['setup']['upload_maxnum'])){
        	$info['setup']['upload_maxnum']=1;
        }
		if(empty($info['setup']['upload_maxsize'])){
			$info['setup']['upload_maxsize']=C ( 'UPLOAD_IMG_SIZE' );
		}else{
			$info['setup']['upload_maxsize']=$info['setup']['upload_maxsize']*1024*1024;
		}
		$image_string=$block='';
		if($value){
			$image_string='<div class="'.$info['type'].'_list"><img class="fancybox" src="'.$value.'" delfile="'.$value.'"><div class="imgdel" title="删除" onclick="delFiles(this,\''.U('Attachment/del',array('moduleid'=>$info['moduleid'])).'\',\''.$info['field'].'\')"></div></div>';
			$block='style="display:block"';
		}
		$setup=$info['setup'];
        $info['path']="images";
        unset($info['setup']);
        $info['module']=MODULE_NAME;
        $info=array_merge($info,$setup);
		 
        $url=U(ADMIN_NAME.'/Attachment/index',$info);
       $parseStr='<div class="input-group-btn">
	      <div style="width:300px; float:left;">
	       	<input name="'.$field.'" id="'.$field.'" value="'.$value.'" type="text" class="form-control" style="border-radius: 4px 0px 0px 4px;" '.$validate.' />
	       </div> 
	       <div  class="btn btn-primary btn-file" style="float:left;" onclick="dialogIframe(\''.$url.'\',\'上传图片\')">
	       <i class="glyphicon glyphicon-picture"></i> <span class="hidden-xs">上传图片</span>
	       </div>
		</div>
		  <div class="img_info img_info_'.$field.'" id="'.$field.'_txt" '.$block.'>
		  '.$image_string.'
		 </div>
		';
		return $parseStr;
	}

	public function images($info,$value){ 
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
	    $validate = getvalidate($info);
        if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
		$data=$block='';
		if($value){
			$options = explode(":::",$value);
			if(is_array($options)){
				foreach($options as  $key=>$r) {
						$v = explode("|",$r);
						$k = trim($v[1]);
						$data .='<div class="'.$info['type'].'_list"><img class="fancybox" src="'.$v[0].'" delfile="'.$v[0].'"><div class="'.$info['type'].'_name"><input type="text" class="common-text form-control" name="'.$info['field'].'['.$key.'][name]" placeholder="文件名称" value="'.$v[0].'"></div><div class="'.$info['type'].'_sort"><input type="text" class="common-text form-control" name="'.$info['field'].'['.$key.'][sort]" placeholder="文件排序" value="'.$v[1].'" onkeyup="value=value.replace(/[^\d]/ig,\'\')"></div><div class="imgdel" title="删除" onclick="delFiles(this,\''.U('Attachment/del',array('moduleid'=>$info['moduleid'])).'\',\''.$info['field'].'\')"></div></div>';
				}
			}
			$block='style="display:block"';
		}
		if(empty($info['setup']['upload_maxsize'])){
			$info['setup']['upload_maxsize']=C ( 'UPLOAD_IMG_SIZE' );
		}else{
			$info['setup']['upload_maxsize']=$info['setup']['upload_maxsize']*1024*1024;	
		}
		if(empty($info['setup']['upload_maxnum'])){
			$info['setup']['upload_maxnum']=5;
		}
		$setup=$info['setup'];
        $info['path']="images";
        unset($info['setup']);
        $info['module']=MODULE_NAME;
        $info=array_merge($info,$setup);
        $url=U(ADMIN_NAME.'/Attachment/index',$info);
       $parseStr='<div class="input-group-btn">
	       
	       <div  class="btn btn-primary btn-file" style="float:left;" onclick="dialogIframe(\''.$url.'\',\'多图上传\')">
	       <i class="glyphicon glyphicon-picture"></i> <span class="hidden-xs">多图上传</span>
	       </div>
		</div>
		  <div class="images_info images_info_'.$field.'" id="'.$field.'_txt" '.$block.'>
		   '.$data.'
		 </div>
		';
		return $parseStr;
	}
	public function file($info,$value){
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
	    $validate = getvalidate($info);
        if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
		if(empty($info['setup']['upload_maxsize'])){
			$info['setup']['upload_maxsize']=C ( 'UPLOAD_IMG_SIZE' );
		}else{
			$info['setup']['upload_maxsize']=$info['setup']['upload_maxsize']*1024*1024;	
		}
		if(empty($info['setup']['upload_maxnum'])){
			$info['setup']['upload_maxnum']=1;
		}
	$image_string=$block='';
		if($value){
			$image_string='<div class="'.$info['type'].'_list"><img class="fancybox" src="/Apps/'.ADMIN_NAME.'/View/Public/images/readme.png" delfile="'.$value.'"><div class="imgdel" title="删除" onclick="delFiles(this,\''.U('Attachment/del',array('moduleid'=>$info['moduleid'])).'\',\''.$info['field'].'\')"></div></div>';
			$block='style="display:block"';
		}
		$setup=$info['setup'];
        $info['path']="file";
        unset($info['setup']);
        $info['module']=MODULE_NAME;
        $info=array_merge($info,$setup);
        $url=U(ADMIN_NAME.'/Attachment/index',$info);
        $parseStr='<div class="input-group-btn">
	      <div style="width:300px; float:left;">
	       	<input name="'.$field.'" id="'.$field.'" value="'.$value.'" type="text" class="form-control" style="border-radius: 4px 0px 0px 4px;" '.$validate.' />
	       </div> 
	       <div  class="btn btn-primary btn-file" style="float:left;" onclick="dialogIframe(\''.$url.'\',\'上传文件\')">
	       <i class="glyphicon glyphicon-folder-open"></i> <span class="hidden-xs">上传文件</span>
	       </div>
		</div>
		  <div class="img_info img_info_'.$field.'" id="'.$field.'_txt" '.$block.'>
		 	'.$image_string.'
		 </div>
		';
		return $parseStr;
	}

	public function files($info,$value){  
		$info['setup']=is_array($info['setup']) ? $info['setup'] : serialize2array($info['setup']);
		$id = $field = strtolower($info['field']);
	    $validate = getvalidate($info);
        if(ACTION_NAME=='add'){
			$value = $value ? $value : $info['setup']['default'];
        }else{
			$value = $value ? $value : $this->data[$field];
        }
		$data=$block='';
		if($value){
			$options = explode(":::",$value);
			if(is_array($options)){
				foreach($options as  $key=>$r) {
						$v = explode("|",$r);
						$k = trim($v[1]);
						$data .='<div class="'.$info['type'].'_list"><img class="fancybox" src="/Apps/'.ADMIN_NAME.'/View/Public/images/readme.png" delfile="'.$v[0].'"><div class="'.$info['type'].'_name"><input type="text" class="common-text form-control" name="'.$info['field'].'['.$key.'][name]" placeholder="文件名称" value="'.$v[0].'"></div><div class="'.$info['type'].'_sort"><input type="text" class="common-text form-control" name="'.$info['field'].'['.$key.'][sort]" placeholder="文件排序" value="'.$v[1].'" onkeyup="value=value.replace(/[^\d]/ig,\'\')"></div><div class="imgdel" title="删除" onclick="delFiles(this,\''.U('Attachment/del',array('moduleid'=>$info['moduleid'])).'\',\''.$info['field'].'\')"></div></div>';
				}
			}
			$block='style="display:block"';
		}
		if(empty($info['setup']['upload_maxsize'])){
			$info['setup']['upload_maxsize']=C ( 'UPLOAD_IMG_SIZE' );
		}else{
			$info['setup']['upload_maxsize']=$info['setup']['upload_maxsize']*1024*1024;	
		}
		if(empty($info['setup']['upload_maxnum'])){
			$info['setup']['upload_maxnum']=5;
		}
		$setup=$info['setup'];
        $info['path']="file";
        unset($info['setup']);
        $info['module']=MODULE_NAME;
        $info=array_merge($info,$setup);
        $url=U(ADMIN_NAME.'/Attachment/index',$info);
       $parseStr='<div class="input-group-btn">
	      
	       <div  class="btn btn-primary btn-file" style="float:left;" onclick="dialogIframe(\''.$url.'\',\'多文件上传\')">
	       <i class="glyphicon glyphicon-folder-open"></i> <span class="hidden-xs">多文件上传</span>
	       </div>
		</div>
		  <div class="images_info images_info_'.$field.'" id="'.$field.'_txt" '.$block.' >
		  '.$data.'
		 </div>
		';
		return $parseStr;
	}
	
	public function linkage(){
			
	}
}
?>