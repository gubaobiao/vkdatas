<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
	//获取首页分类
    public function index(){
        $cate=M('cate')->field('id,catename')->where('is_delete=1')->select();
        echo json_encode($cate);
        exit();
        
    }
    //获取分类下的商家
    public function getUser()
    {
    		
    }
}