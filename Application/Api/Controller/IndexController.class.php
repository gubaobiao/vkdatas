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
            if ($img==0) {
               $dat['errorCode']=206;
               echo json_encode($dat);
               exit();
            }
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
            return 0;
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
                $re=M('shop')->field('id,userid,mobile,address,shopname,money,longitude,latitude,shopimg')->where($where)->select();
                if ($re) {
                    foreach ($re as $k => $v) {
                    $re[$k]['distance']=getdistance(I('get.longitude'),I('get.latitude'),$v['longitude'],$v['latitude']);
                    $count=M('collection')->where('shopid='.$v['userid'])->count();
                    $re[$k]['fans']=$count;
                    $re[$k]['isgz']=A('Dynamic')->isfollow(I('get.userid'),$v['userid']);
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
    //判断用户是否收藏了商家
    public function isCollection()
    {
         if (IS_GET) {
            // $data=I('get.userid');
            // $shopid=I('get.shopid');
            $data=I('get.');
            $re=M('collection')->where($data)->find();
            if ($re) {
                $dat['data']['issc']=1;
               $dat['errorCode']=200; 
            }else{
                $dat['data']['issc']=0;
               $dat['errorCode']=200;   
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
            if (I('get.type')==1) {
                $re=M('collection')->alias('c')
                ->join('left join dhj_shop s on s.userid=c.shopid')
                ->where('c.userid='.I('get.userid'))
                ->field('s.id,s.userid,s.address,s.shopname,s.money,s.longitude,s.latitude,s.shopimg')
                ->select();
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
                    $dat['data']=$re;
                
                }else{
                    $data['data']=array();
                }
                $dat['errorCode']=200;
             //获取商家被收藏的用户
            }elseif (I('get.type')==2) {
                $re=M('collection')->alias('c')
                ->join('left join dhj_users u on u.id=c.userid')
                ->field('c.time,u.nickname,u.avatar')
                ->where('c.shopid='.I('get.userid'))
                ->select();
                if (count($re)!=0) {
                   $dat['data']=$re; 
                }else{
                   $dat['data']=array();
                }
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
                $result=M('collection')->where($data)->delete();
                if ($result) {
                    $dat['errorCode']=200;
                }else{
                   $dat['errorCode']=207; 
                }
            }else{
                $dat['errorCode']=204;
            }
        }else{  
            $dat['errorCode']=201; 
        }
        echo json_encode($dat);exit; 

    }
    //获取用户的浏览记录
    public function gethistory()
    {
        if (IS_GET) {
            $type=M('users')->where('id='.I('get.userid'))->getField('type');
            if ($type==2) {
                $re=M('history')->alias('h')
                ->join('left join dhj_users u on u.id=h.userid')
                ->field('h.time,u.nickname,u.avatar')
                ->where('h.shopid='.I('get.userid'))
                ->select();
                if (count($re)!=0) {
                   $dat['data']=$re;
                }else{
                   $dat['data']=array();
                }
                $dat['errorCode']=200;
             //获取用户浏览的历史记录
            }else{
                 $re=M('history')->alias('h')
                ->join('left join dhj_shop s on s.userid=h.shopid')
                ->where('h.userid='.I('get.userid'))
                ->field('s.mobile,s.userid,s.address,s.shopname,s.money,s.longitude,s.latitude,s.shopimg')
                ->select();
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
                    $data['data']=array();
                }
                $dat['errorCode']=200; 
            }
             $dat['usertype']=$type;
        }else{
           $dat['errorCode']=201; 
        }
        echo json_encode($dat);exit; 
    }
    //删除浏览记录历史
    public function deleteHistory()
    {
        if (IS_POST) {

            if (I('post.history')) {
                // dump(I('post.history'));die();
                //$str=implode(',',I('post.history'));
                $data['id']=array('in',I('post.history'));
                $result=M('history')->where($data)->delete();
                if ($result) {
                    $dat['errorCode']=200;
                }else{
                   $dat['errorCode']=207; 
                }
            }else{
                $dat['errorCode']=204;
            }
        }else{  
            $dat['errorCode']=201; 
        }
       
    }
    //判断店铺设置是否设置过
    public function issetShop()
    {
        if (IS_GET) {
            $re=M('shop')->where('userid='.I('get.userid'))->find();
            if ($re) {
                $dat['errorCode']=200;
            }else{
                $dat['errorCode']=205;
            }
        }else{
            $dat['errorCode']=201;
        }
         echo json_encode($dat);exit; 
    }
    //申请成为商家
    public function applyShop()
    {
        if (IS_POST) {
            $data=I('post.');
            $data['shoptime']=time();
            $data['type']=3;
            $img=$this->upload();
            $data['business']='https://v.gubaobiao.cn/uploadfile/User/video/'.$img['shopimg']['savepath'].$img['shopimg']['savename'];
            $re=M('users')->where('id='.I('post.userid'))->data($data)->save();
            if ($re===false) {
                $dat['errorCode']=202;
            }else{
                $dat['errorCode']=200;
            } 
        }else{
            $dat['errorCode']=201;
        }
         echo json_encode($dat);exit; 
    }
    public function test()
    {
        $code=I('get.code');
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=wx4365511071d1dd31&secret=cb7ddcf68da4fbd1c74f5c44f3bdc53c&js_code=$code&grant_type=refresh_token";
        $data=file_get_contents($url);
        $re=json_decode($data,true);
        if ($re['openid']) {
            $where['openid']=$re['openid'];
            $res=M('users')->where($where)->find();
            if (!$res) {
              $id=M('users')->data($where)->add();
            }else{
                $id=$res['id'];
            }
            $dat['data']['id']=$id;
            $dat['errorCode']=200;
        }else{
            $dat['errorCode']=208;
        }
        echo json_encode($dat);
    } 
     //获取用户授权
    public function userAuthorize()
    {   $code   =   I('get.code');
        $encryptedData   =   I('get.encryptedData');
        $iv   =   I('get.iv');
        $appid  =  "wx4365511071d1dd31" ;
        $secret =   "cb7ddcf68da4fbd1c74f5c44f3bdc53c";
        $URL = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$code&grant_type=authorization_code";

        Vendor('small.wxBizDataCrypt');
        $apiData=file_get_contents($URL);
        //dump($apiData);
        if(!isset($apiData['errcode'])){
            $sessionKey = json_decode($apiData)->session_key;
            $userifo = new \wxBizDataCrypt($appid, $sessionKey);
            $errCode = $userifo->decryptData($encryptedData, $iv, $data );
            $result=json_decode($data,true);

            if ($result['openId']) {
                $openid['openid']=$result['openId'];
            $re=M('users')->where($openid)->find();
                $dad['nickname']=$result['nickName'];
                $dad['sex']=$result['gender'];
                $dad['avatar']=$result['avatarUrl'];
                $dad['logintime']=time();
            if ($re) {
                $rer=M('users')->where($openid)->data($dad)->save();
                if ($rer===false) {
                    $dat['errorCode']=202;
                }else{
                    $dat['errorCode']=200;
                    $dat['data']['userid']=$re['id'];
                    $dat['data']['usertype']=$re['type'];
                }
            }else{
                $dat['time']=time();
                $dad['openid']=$result['openId'];
                $re=M('users')->data($dad)->add();
                if (!$re) {
                   $dat['errorCode']=203;
                }else{
                   $dat['errorCode']=200;
                   $dat['data']['id']=$re;
                   $dat['data']['usertype']=1;
                }
            }
            }else{
              $dat['errorCode']=209;  
            }
        }else{
            $dat['errorCode']=208;
        }
        echo json_encode($dat);
    }
    //首页地图调取城市对应的商家
    public function getMapShop()
    {
        if (IS_GET) {
            if (I('get.city') && I('get.longitude') && I('get.latitude')) {
                $where['city']=I('get.city');
                $result=M('shop')
                ->field('shopname,mobile,address,longitude,latitude,money,shopimg,userid')
                ->where($where)
                ->select();
                if (0<count($result)) {
                    foreach ($result as $k => $v) {
                        $result[$k]['distance']=getdistance(I('get.longitude'),I('get.latitude'),$v['longitude'],$v['latitude']);
                    }
                    $dat['data']=$result;
                    $dat['errorCode']=200; 
                }else{
                    $dat['data']=array();
                    $dat['errorCode']=205;
                }
            }else{
                $dat['errorCode']=204;
            }
        }else{
            $dat['errorCode']=201;
        }
        echo json_encode($dat);
    }
    //头部图片
    public function getTopimg()
    {
       if (IS_GET) {
            $arr=M('banners')->field('imgpath,id')->where('is_delete=1 and type=1')->select();
            $dat['data']['img']=$arr;
            $dat['errorCode']=200;
        }else{
            $dat['errorCode']=201;
        } 
        echo json_encode($dat);exit();
    }
    //头部图片
    public function getFooterimg()
    {
       if (IS_GET) {
            $arr=M('banners')->field('imgpath,id')->where('is_delete=1 and type=2')->select();
            $dat['data']['img']=$arr;
            $dat['errorCode']=200;
        }else{
            $dat['errorCode']=201;
        } 
        echo json_encode($dat);exit();
    }
    //获取会员等级的接口
    public function getRank()
    {
        if (IS_GET) {
            $res=M('usertype')->select();
            $dat['data']=$res;
            $dat['errorCode']=200; 
        }else{
            $dat['errorCode']=201;
        }
        echo json_encode($dat);
    }
    //获取当前会员的等级
    public function getUseRank()
    {
        if (IS_GET) {
            $res=M('users')->alias('u')
            ->join('left join dhj_usertype t on t.id=u.rank')
            ->field('t.id,t.rankname')
            ->where('u.id='.I('get.userid'))
            ->find();
            $dat['data']=$res;
            $dat['errorCode']=200; 
        }else{
            $dat['errorCode']=201;
        }
        echo json_encode($dat);
    }
    //提交的用户修改的等级
     public function editUseRank()
    {
        if (IS_POST) {
            $data['rank']=I('post.rankid');
            $res=M('users')
            ->data($data)
            ->where('id='.I('post.userid'))
            ->save();
            if ($res===false) {
                $dat['errorCode']=202;
            }else{
               $dat['errorCode']=200;   
            }
        }else{
            $dat['errorCode']=201;
        }
        echo json_encode($dat);
    }
    //获取个人的信息
    public function getUserMessage()
    {
         if (IS_GET) {
             if (isset($_GET['page'])) {
                $p=((int)I('get.page')-1)*5;
                }else{
                  $p=0;
                }
             //未
             $where['m.is_delete']=1;
             $where['m.userid']=I('get.userid');
           $res=M('message')->alias('m')
          ->join('left join dhj_messagecate c on c.id = m.cateid')
          ->join('left join dhj_users u on u.id = m.userid ')
          ->where($where)
          ->field('m.id,m.message,m.time,m.imgpath,m.userid,c.cate as catename,u.avatar,u.nickname')
          ->limit($p,5)
           ->order('m.id desc')
          ->select();
          if (count($res)==0) {
          $dat['errorCode']=200;
          $dat['data']=array();
          echo json_encode($dat);exit();
        }
       
        foreach ($res as $k => $v) {
          $is=A('Dynamic')->getpraise(I('get.userid'),$v['id']);
          $praisenum=M('praise')->where('messageid='.$v['id'])->field('id')->count();
          $comment=M('comment')->where('is_delete= 1 and messageid='.$v['id'])->field('id')->count();
          $follow=A('Dynamic')->isfollow(I('get.userid'),$v['userid']);
          $res[$k]['isgz']=$follow;
          $res[$k]['isdz']=$is;
          $res[$k]['praisenum']=$praisenum;
          $res[$k]['commentnum']=$comment;
          $res[$k]['message']=base64_decode($v['message']);
        }
        $dat['data']=$res;
        $dat['errorCode']=200;
        }else{
            $dat['errorCode']=201;
        }
        echo json_encode($dat);
    }
}