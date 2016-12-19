/**
 * Created with JetBrains PhpStorm.
 * User: kk
 * Date: 13-8-28
 * Time: 下午4:44
 */
function U() {
    var url = arguments[0] || [];
    var param = arguments[1] || {};
    var url_arr = url.split('/');
    if (!$.isArray(url_arr) || url_arr.length < 2 || url_arr.length > 3) {
        return '';
    }
    if (url_arr.length == 2)
        url_arr.unshift(_GROUP_);

    var pre_arr = ['g', 'm', 'a'];
    var arr = [];
    for (d in pre_arr)
        arr.push(pre_arr[d] + '=' + url_arr[d]);

    for (d in param)
        arr.push(d + '=' + param[d]);

    return _APP_+'?'+arr.join('&');
}

/**
 *+——----------------------------------------
 * 检查复选框的的多选和反选控制
 *+——----------------------------------------
*/
function clickCheckbox(){
	$(".chooseAll").click(function(){
		var status=$(this).prop('checked');
		$("tbody input[type='checkbox']").prop("checked",status);
		$(".chooseAll").prop("checked",status);
		$(".unsetAll").prop("checked",false);
	});
	$(".unsetAll").click(function(){
		var status=$(this).prop('checked');
		$("tbody input[type='checkbox']").each(function(){
			$(this).prop("checked",! $(this).prop("checked"));
		});
		$(".unsetAll").prop("checked",status);
		$(".chooseAll").prop("checked",false);
	});
}

/**
 * [dialogIframe iframe弹窗]
 * @param  {[type]} url   [iframe地址]
 * @param  {[type]} title [标题]
 */
function dialogIframe(url,title){
    var url = url + "?iframe=" + window.name;
    layer.open({
        title:title,
        type:2,
        area: ['80%','80%'],
        maxmin:true,
        content:url
    });
}

/**
 * [closeIframe 关闭父级iframe]
 */
function closeIframe(){
    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    parent.layer.close(index);
}

/**
 * [delFiles 删除文件]
 * @param  {[type]} obj [description]
 * @return {[type]}     [description]
 */
function delFiles (obj,url,field) {
	var img_url=$(obj).siblings('img').attr('delfile'); 
	var json = {"url":img_url};
	$.post(url, json, function(data, textStatus, xhr) {
		if(data.status){
			$(obj).parent('div').remove();
			$('#'+field).val('');
		} else {
			parent.layer.msg(data.info,{icon:2,time:2000,shade: [0.3,'#000']});
		}

	});
}