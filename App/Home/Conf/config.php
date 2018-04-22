<?php
return array(
	'HTML_CACHE_ON'     =>    true, 
	'HTML_CACHE_TIME'   =>    120,   
	'HTML_FILE_SUFFIX'  =>    '.shtml', 
	'HTML_CACHE_RULES'  =>     array( 
     // 定义格式1 数组方式
      'Index:index'=>array('{:module}/Index/{:action}',36000),
	  'Index:anli'=>array('{:module}/Index/{id}_{$_SERVER.REQUEST_URI|md5}',36000),
	  'Index:about'=>array('{:module}/Index/{$_SERVER.REQUEST_URI|md5}',36000),
	  'Index:news'=>array('{:module}/Index/{id}_{$_SERVER.REQUEST_URI|md5}',36000),
	  'Index:circuit'=>array('{:module}/Index/{$_SERVER.REQUEST_URI|md5}',360000),
	  'Index:contact'=>array('{:module}/Index/{$_SERVER.REQUEST_URI|md5}',360000),
	  'Index:worker'=>array('{:module}/Index/{$_SERVER.REQUEST_URI|md5}',360000),


)
);