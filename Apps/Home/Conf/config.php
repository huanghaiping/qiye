<?php
$system_config=F("SysConfig",'',INCLUDE_PATH);
if (empty($system_config)){ 
	$system_config=array();
}
$RULES= $system_config['URL_MODEL']==2 ? F ( 'Routes' ,'',INCLUDE_PATH) : array();
$config= array(
	'TAGLIB_PRE_LOAD'=>'Ad,Vo',
	'URL_CASE_INSENSITIVE'  =>  true, 
	'VAR_PAGE'=>'p',
	'URL_ROUTER_ON' => true,
	'URL_ROUTE_RULES' => $RULES,
	'ERROR_PAGE'=>'/404.html',
	
);

return array_merge($config, $system_config );