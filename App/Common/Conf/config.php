<?php
return array(
	//'配置项'=>'配置值'

    //模块路由配置
    'MODULE_ALLOW_LIST'=>array('Home'),
    'DEFAULT_MODULE'=>'Home',
    'URL_MODEL'=>2,

    //数据库连接
    'DB_TYPE'=>'mysql',
    'DB_HOST'=>'127.0.0.1',
    'DB_NAME'=>'donghuajie',
    'DB_USER'=>'root',
    'DB_PWD'=>'genpichong9952',
    'DB_PORT'=>'3306',
    'DB_PREFIX'=>'dhj_',
    'DB_CHARSET'=>'utf8',

    //每页显示数据
    'PAGESIZE'=>5,
     //'TMPL_EXCEPTION_FILE' => 'Public/four/404.html',

    'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES'=>array(
        //静态地址路由
        '/^index$/'		=>	'Index/index',
        '/^about$/'   	=>	'Index/about',
        '/^circuit$/'	=>	'Index/circuit',
        '/^anli$/'		=>	'Index/anli',
        'videoLazys/:type/:page_num'=>'Home/Index/videoLazy',
        'newslazy/:page_num'=>'Home/Index/newslazy',
        '/^videolazy$/'	=>	'Index/videoLazy',
        '/^worker$/'		=>	'Index/worker',
        '/^wegot/'		=>	'Index/wegot',
        '/^news$/'		=>	'Index/news',
        '/^video$/'    =>  'Index/video',
        '/^contact$/'	=>	'Index/contact',
        '/^map$/'	=>	'Index/map',
        'anli/:id' 	=> 	'Index/anli',
        'type/:type' 	=> 	'Index/anli',
        'news/:id' 	=> 	'Index/news',
        'video/:id' =>  'Index/video',
        '/^type$/'	=>	'Index/anli/type',
    ),

    //定义移动端资源路径
    'ASSET_WAP'=>'/Public/wap'
);