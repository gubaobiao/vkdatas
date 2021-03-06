<?php
namespace Api\Controller;
use Think\Controller;
class DynamicController extends Controller
{
	    //发布朋友圈动态
    public function addMessage()
    {
        if (IS_POST) {
           $data=I('post.');
           $data['time']=time();
           $data['message']=base64_encode($data['message']);
          // M()->query('set names utf8');
           $mess=M('message')->add($data);
           //添加信息
           if (!$mess) {
           	   $dat['errorCode']=203;
               echo json_encode($dat);
               exit();
           }
           $dat['errorCode']=200;
           $dat['data']=$mess;

        }else{
           $dat['errorCode']=201;  
        }
        echo json_encode($dat);exit; 
    }
    //上传图片
    public function uploadimg()
    {
       if (IS_POST) {
          $info=A('Index')->upload();
           if ($info==0) {
               $dat['errorCode']=206;
               echo json_encode($dat);
               exit();
           }else{
                $dat['imgpath']='https://v.gubaobiao.cn/uploadfile/User/video/'.$info['img']['savepath'].$info['img']['savename'];
                $re=M('messageimg')->add($dat);
                $dat['errorCode']=200;
           }
       }else{
         $dat['errorCode']=201;
       }
        echo json_encode($dat);exit;
    }
    //点赞量
    public function  praisenums()
    {
        if (IS_GET) {
            $data=I('get.');
            $data['time']=time();
            $re=M('praise')->add($data);
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
    //取消点赞
    public function cancelpraise()
    {
        if (IS_GET) {
            $data['userid']=I('get.userid');
            $data['messageid']=I('get.messageid');
            $re=M('praise')->where($data)->delete();
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
    //获取这个人是否点赞
    public function getpraise($userid,$messageid)
    {
       
            $data['userid']=$userid;
            $data['messageid']=$messageid;
            $re=M('praise')->where($data)->find();
            if ($re) {
                return 1;
            }else{
               return 0;
            }
    }
    //添加评论
    public function addComment()
    {
    	if (IS_POST) {
    		$data=I('post.');
    		$data['time']=time();
        $data['content']=base64_encode($data['content']); 
    		$re=M('comment')->add($data);
    		if (!$re) {
    			   $dat['errorCode']=203;
	               echo json_encode($dat);
	               exit();
    		}
        $dat['errorCode']=200;
        $dat['data']=M('comment')->where('id='.$re)->find();
    	}else{
    		$dat['errorCode']=201;
    	}
    	echo json_encode($dat);
    }
    //获取动态下的所有评论
    public function getMessage()
    {
      if (IS_GET) {
         if (I('get.messageid')) {
            $re=M('comment')->alias('c')
            ->field('c.content,c.userid,c.time,c.imgpath,u.avatar,u.nickname')
            ->join('left join dhj_users u on u.id=c.userid')
            ->where('c.messageid='.I('get.messageid'))
            ->find();
            $comment=$this->getallComment(I('get.messageid'));
           $res['content']=$re;
           $res['comment']=$comment;
           $res['errorCode']=200;
           echo json_encode($res);
           exit();
          }else{
            $dat['errorCode']=204;
          } 
      }else{
        $dat['errorCode']=201;
      }
      echo json_encode($dat);
    }
    //分类
    public function getallComment($messageid)
    {
        if ($messageid) {
          $result=M('comment')->alias('c')
          ->join('left join dhj_users u on u.id=c.userid')
          ->where('messageid='.$messageid)
          ->field('c.id,c.content,c.pid,c.time,c.userid,c.imgpath,u.nickname,u.avatar')
          ->select();
          foreach ($result as $k => $v) {
            $resu[$v['id']]=$v; 
          }
          foreach ($result as $k => $v) {
            $result[$k]['content']=base64_decode($v['content']);
            if ($v['pid']!=0) {
              $result[$k]['pnickname']=$resu[$v['pid']]['nickname'];
              $result[$k]['pavatar']=$resu[$v['pid']]['avatar'];
            }
          }
          $res['commentnum']=count($result);
          $res['data']=$result;
          $res['praisenum']=M('praise')->where('messageid='.$messageid)->count();
          $res['errorCode']=200;
         echo json_encode($res);
        }
    }
    //获取总的页码
    public function getallPage()
    {
      if (IS_GET) {
        if (!I('get.cateid')) {
         $where['is_delete']=1;
        }else{
          $where['is_delete']=1;
          $where['cateid']=I('cateid');
        }
        //关注的
        if (isset($_GET['userid'])) {
          $wher['f.userid']=$_GET['userid'];
          $res=M('follow')->alias('f')
                ->join('left join dhj_message m on m.userid=f.followuserid')
                ->where('m.is_delete=1')
                ->where($wher)
                ->count();
        }else{
          //所有的加分类
          $res=M('message')->where($where)->field('id')->count();
        }
        $page=ceil($res/6);
        $dat['data']['allpage']=$page;
        $dat['errorCode']=200;
      }else{
        $dat['errorCode']=201;
      }
      echo json_encode($dat);
      exit();
    } 
    //首页朋友圈
    public function Index()
    {
       if (IS_GET) {
       if (!I('get.cateid')) {
          $where['m.is_delete']=1;
        }else{
          $where['m.is_delete']=1;
          $where['m.cateid']=I('cateid');
        }
        if (isset($_GET['page'])) {
          $p=((int)I('get.page')-1)*5;
        }else{
          $p=0;
        }
        if (isset($_GET['type']) && $_GET['type']==2) {
          //关
        	$res=M('follow')->alias('f')
                ->join('left join dhj_message m on m.userid=f.followuserid')
                ->join('left join dhj_users u on u.id=f.followuserid')
                ->field('m.id,m.message,m.time,m.imgpath,m.userid,u.avatar,u.nickname')
                ->limit($p,5)
                ->where('f.userid='.I('get.userid'))
                ->order('f.id desc')
                ->select();

        }elseif (isset($_GET['type']) && $_GET['type']==1) {
          //未
           $res=M('message')->alias('m')
          ->join('left join dhj_messagecate c on c.id = m.cateid')
          ->join('left join dhj_users u on u.id = m.userid ')
          ->where($where)
          ->field('m.id,m.message,m.time,m.imgpath,m.userid,c.cate as catename,u.avatar,u.nickname')
          ->limit($p,5)
           ->order('m.zd desc,m.id desc')
          ->select();
        }
        if (count($res)==0) {
          $dat['errorCode']=200;
          $dat['data']=array();
          echo json_encode($dat);exit();
        }
       
        foreach ($res as $k => $v) {
          $is=$this->getpraise(I('get.userid'),$v['id']);
          $praisenum=M('praise')->where('messageid='.$v['id'])->field('id')->count();
          $comment=M('comment')->where('is_delete= 1 and messageid='.$v['id'])->field('id')->count();
          $follow=$this->isfollow(I('get.userid'),$v['userid']);
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
      exit(); 
        
    }
    //判断用户是否关注了这个用户
    public function isfollow($userid,$followuserid)
    {
    	    $data['userid']=$userid;
            $data['followuserid']=$followuserid;
            $re=M('follow')->where($data)->find();
            if ($re) {
                return 1;
            }else{
               return 0;
            }
    }
    //动态关注用户接口
    public function addFollow()
    {
    	if (IS_GET) {
            $data['userid']=I('get.userid');
            $data['followuserid']=I('get.followuserid');
            $re=M('follow')->data($data)->add();
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
     //删除动态关注用户接口
    public function deleteFollow()
    {
    	if (IS_GET) {
            $data['userid']=I('get.userid');
            $data['followuserid']=I('get.followuserid');
            $re=M('follow')->where($data)->delete();
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
    //获取上面的热门分类所有的
    public function hotcate()
    {
      if (IS_GET) {
        $res=M('messagecate')->where('is_delete=1')->field('id,cate')->select();
        $dat['data']=$res;
        $dat['errorCode']=200;
      }else{
        $dat['errorCode']=201;
      }
      echo json_encode($dat);
      exit();
    }
    //首页的搜索
    public function searchShop()
    {
        if (IS_POST) {
          //&& I('post.userid') && I('post.longitude') && I('post.latitude')
            if (I('post.content') && I('post.userid') && I('post.longitude') && I('post.latitude')) {
                $where['shopname']=array('like','%'.I('post.content').'%');
                $re=M('shop')->field('id,userid,mobile,address,shopname,money,longitude,latitude,shopimg')->where($where)->select();
                 foreach ($re as $k => $v) {
                    $re[$k]['distance']=getdistance(I('post.longitude'),I('post.latitude'),$v['longitude'],$v['latitude']);
                    $count=M('collection')->where('shopid='.$v['userid'])->count();
                    $re[$k]['fans']=$count;
                    $re[$k]['isgz']=$this->isfollow(I('post.userid'),$v['userid']);
                    }
                    $dat['errorCode']=200;
                    $dat['data']=$re;
                $dat['data']=$re;
            }else{
               $dat['errorCode']=204;
            }
        }else{
          $dat['errorCode']=201;
        }
        echo json_encode($dat);
    }
    //刷新用户的类型接口
    public function shopType()
    {
      if (IS_GET) {
        if (I('get.userid')) {
          $re=M('users')->where('id='.I('get.userid'))->getField('type');
          if ($re) {
            $dat['data']['usertype']=$re;
            $dat['errorCode']=200;
          }else{
            $dat['errorCode']=205;
          }
        }else{
          $dat['errorCode']=204;
        }
      }else{
        $dat['errorCode']=201;
      }
      echo json_encode($dat);
      exit();
    }
}