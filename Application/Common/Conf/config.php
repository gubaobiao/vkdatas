<?php
return array(
	'MODULE_ALLOW_LIST'=>array('Back','Api'),
	'DEFAULT_MODULE'=>'Back',
	 'URL_MODEL'=>2,

	//数据库连接
	'DB_TYPE'=>'mysql',
	'DB_HOST'=>'101.200.49.150',
	'DB_NAME'=>'vkdatas',
	'DB_USER'=>'rootone',
	'DB_PWD'=>'biao521521',
	'DB_PORT'=>'3306',
	'DB_PREFIX'=>'dhj_',
	'DB_CHARSET'=>'utf8',

	//每页显示数据
	'PAGESIZE'=>5,
	

	'URL_ROUTER_ON'   => true, 
	'URL_ROUTE_RULES'=>array(
	//静态地址路由
	'/^index$/'		=>	'Index/index',
	'/^about$/'   	=>	'Index/about',
	'/^circuit$/'	=>	'Index/circuit',
	'/^anli$/'		=>	'Index/anli',
	'/^news$/'		=>	'Index/news',
	 '/^video$/'    =>  'Index/video',
	'/^contact$/'	=>	'Index/contact',
	'/^map$/'		=>	'Index/map',
        'anli/:id' 	=> 	'Index/anli',
        'news/:id' 	=> 	'Index/news',
        'video/:id' =>  'Index/video',
        '/^type$/'	=>	'Index/anli/type',
		'Index/news/:p'	=>'Index/news',
		),
	);