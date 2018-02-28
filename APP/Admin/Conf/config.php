<?php
return array(
	//'配置项'=>'配置值'
	'TMPL_PARSE_STRING'  =>array(
		'__JS__'     	=> __ROOT__ . '/Public/Styles/js', // 增加新的JS类库路径替换规则 
		'__CSS__'     	=> __ROOT__ . '/Public/Styles/css', // 增加新的JS类库路径替换规则 
		'__IMG__' 		=> __ROOT__ . '/Public/Images', // 增加新的上传路径替换规则
		'__PLUGINS__' 		=> __ROOT__ . '/Public/Pugins', // 增加新的插件替换规则
	),
	'LAYOUT_ON'			=> false,
	'SHOW_PAGE_TRACE'	=> true,
	// 'DEFAULT_MODULE'    => 'Admin',
);