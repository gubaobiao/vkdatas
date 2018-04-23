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
    //获取商家管理下的店铺信息
    public function getUser()
    {
            if (IS_GET) {
                if (I('get.id')) {
                    $re=M('shop')->where('userid='.I('get.id'))->find();
                    if ($re) {
                        $dat['errorCode']=200;
                        $dat['data']=$re;
                    }else{
                        $dat['errorCode']=205;
                    }
                }else{
                    $dat['errorCode']=204;  
                }
            }else{
                $dat['errorCode']=201;
            }
            
            echo json_encode($dat);exit();
    }
    //商家管理接收表单数据
    public function shopAdd()
    {
        if (IS_POST) {
            $data=I('post.');
            $re=M('shop')->where('userid='.$data['id'])->find();
            if ($re['shopname']) {
                $result=M('shop')->where('id='.$data['id'])->data($data)->save();
                if ($result===false) {
                    $dat['errorCode']=202;
                }else{
                    $dat['errorCode']=200;
                }
            }else{
                $result=M('shop')->where('id='.$data['id'])->add($data);
                if (0<$result) {
                    $dat['errorCode']=200;
                }else{
                    $dat['errorCode']=203;
                    
                }
            }
            
        }else{
            $dat['errorCode']=201;
            
            
        }
        $dat['data']=array();
        echo json_encode($dat);exit();

    }
    //商家类目二级页面 分类id
    public function getshop()
    {
        if (IS_GET) {
            if (I('get.cateid') && I('get.longitude') && I('get.latitude')) {
                $re=M('shop')->alias();
                
            }else{
                $dat['errorCode']=204;
            }
        }else{
            $dat['errorCode']=201;
        }
    }
}