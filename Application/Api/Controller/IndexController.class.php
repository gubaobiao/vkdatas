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
            $img=$this->upload();
            $data['shopimg']='https://v.gubaobiao.cn/uploadfile/User/video/'.$img['shopimg']['savepath'].$img['shopimg']['savename'];
            $re=M('shop')->where('userid='.$data['userid'])->find();
            if ($re['shopname']) {
                $result=M('shop')->where('id='.$re['id'])->data($data)->save();
                if ($result===false) {
                    $dat['errorCode']=202;
                }else{
                    $dat['errorCode']=200;
                }
            }else{
                $result=M('shop')->add($data);
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
    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './uploadfile/User/video/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $dat['errorCode']=201; 
           echo json_encode();exit();
        }else{// 上传成功
           return $info;
        }
    }
    //商家类目二级页面 分类id 
    public function getshop()
    {
        //火车站 34.7501001215,113.6682371214 中原福塔34.7276804206,113.7370301898
        // $l=getdistance(34.7501,113.6682,34.7276,113.7370);30.2476719137,120.1904975493
        // echo $l;die;&& I('get.longitude') && I('get.latitude')
        if (IS_GET) {
            if (I('get.cateid') && I('get.longitude') && I('get.latitude') && I('get.city')) {
                $where['cateid']=I('get.cateid');
                $where['city']=I('get.city');
                $re=M('shop')->field('id,userid,address,shopname,money,longitude,latitude,shopimg')->where($where)->select();
                if ($re) {
                    foreach ($re as $k => $v) {
                    $re[$k]['distance']=getdistance(I('get.longitude'),I('get.latitude'),$v['longitude'],$v['latitude']);
                    $count=M('collection')->where('shopid='.$v['userid'])->count();
                    $re[$k]['fans']=$count;
                    unset($re[$k]['longitude']);
                    unset($re[$k]['latitude']);
                    }
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
    //获取商家的详细信息
    public function getshopinfo()
    {
       if (IS_GET) {
          if (I('get.userid' && I('get.longitude') && I('get.latitude') )) {
                $re=M('shop')->where('userid='.I('get.userid'))->find();
                if ($re) {
                    $re['distance']=getdistance(I('get.longitude'),I('get.latitude'),$re['longitude'],$re['latitude']);
                    $count=M('collection')->where('shopid='.$v['userid'])->count();
                    $re['fans']=$count;
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
        echo json_encode($dat);exit;
    }
    //用户点击收藏商家的ajax
    public function collectionShop()
    {
        if (IS_GET) {
            // $data=I('get.userid');
            // $shopid=I('get.shopid');
            $data=I('get.');
            $data['time']=time();
            $re=M('collection')->add($data);
            if ($re) {
               $dat['errorCode']=200; 
            }else{
               $dat['errorCode']=203;   
            }
        }else{
           $dat['errorCode']=201; 
        }
        echo json_encode($dat);exit;
    }
    //查看用户收藏了哪些商家1 收藏 2代表被收藏 以及查看商户被那些用户收藏
    public function getcollectionShop()
    {
        if (IS_GET) {
            //代表是获取用户收藏的商家
            if (I('type')==1) {
                $re=M('collection')->where('userid='.I('get.userid'))->select();
                $dat['data']=$re;
                $dat['errorCode']=200;
             //获取商家被收藏的用户
            }elseif (I('type')==2) {
                $re=M('collection')->where('shopid='.I('get.userid'))->select();
                $dat['data']=$re;
                $dat['errorCode']=200;
            }else{
                $dat['errorCode']=204;
            }
        }else{
           $dat['errorCode']=201; 
        }
        echo json_encode($dat);exit; 
    }
    //添加用户的浏览记录
    public function addhistory()
    {
        if (IS_GET) {
            if (I('get.userid') && I('get.shopid')) {
                $data['userid']=I('get.userid');
                $data['shopid']=I('get.shopid');
                $result=M('history')->where($data)->find();
                if (!$result) {
                    $data['time']=time();
                    $re=M('history')->add($data);
                    if (!$re) {
                        $dat['errorCode']=203;
                        echo json_encode($dat);exit;
                    }
                    $dat['errorCode']=200;
                    echo json_encode($dat);exit();
                }
            }else{
                $dat['errorCode']=204;
            }
        }else{  
            $dat['errorCode']=201; 
        }
        echo json_encode($dat);exit; 
    }
    //取消收藏
    public function cancelCollection()
    {
        if (IS_GET) {
            if (I('get.userid') && I('get.shopid')) {
                $data['userid']=I('get.userid');
                $data['shopid']=I('get.shopid');
                $result=M('history')->where($data)->find();
                if (!$result) {
                    $data['time']=time();
                    $re=M('history')->add($data);
                    if (!$re) {
                        $dat['errorCode']=203;
                        echo json_encode($dat);exit;
                    }
                    $dat['errorCode']=200;
                    echo json_encode($dat);exit();
                }
            }else{
                $dat['errorCode']=204;
            }
        }else{  
            $dat['errorCode']=201; 
        }
        echo json_encode($dat);exit; 

    }
}