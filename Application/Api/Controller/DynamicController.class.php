<?php
namespace Api\Controller;
use Think\Controller;
class DynamicController extends Controller
{
	    //发布朋友圈动态
    public function addMessage()
    {
        if (IS_POST) {
           $data['message']=I('post.message');
           $data['userid']=I('post.userid');
           $data['time']=time();
           $mess=M('message')->add($data);
           //添加信息
           if (!$mess) {
           	   $dat['errorCode']=203;
               echo json_encode($dat);
               exit();
           }
           $dat['errorCode']=200;
           $dat['data']=$da;

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
    		$re=M('comment')->add($data);
    		if (!$re) {
    			   $dat['errorCode']=203;
	               echo json_encode($dat);
	               exit();
    		}
        $dat['errorCode']=200;
        $dat['data']['id']=$re;
    	}else{
    		$dat['errorCode']=201;
    	}
    	echo json_encode($dat);
    }
    //获取动态下的所有评论
    public function getComment()
    {
      if (IS_GET) {
         if (I('get.messageid')) {
            $re=M('comment')->where('is_delete= 1 and messageid='.I('get.messageid'))->select();
            dump($re);
            die;
          }else{
            $dat['errorCode']=204;
          } 
      }else{
        $dat['errorCode']=201;
      }
      echo json_encode($dat);
    }
    //分类
    public function getTree()
    {

    }
    //获取总的页码
    public function getallPage()
    {
      if (IS_GET) {
        if (!I('get.cateid')) {
          $res=M('message')->where('is_delete=1')->field('id')->count();
        }else{
          $res=M('message')->where('is_delete=1 and cateid='.I('cateid'))->field('id')->count();
        }
        $page=ceil($res/6);
        $dat['data']['allpage']=$page;
        $dat['errCode']=200;
      }else{
        $dat['errCode']=201;
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
          $p=((int)I('get.page')-1)*2;
        }else{
          $p=0;
        }
        $res=M('message')->alias('m')
        ->join('left join dhj_messagecate c on c.id = m.cateid')
        ->join('left join dhj_users u on u.id = m.userid ')
        ->where($where)
        ->field('m.id,m.message,m.time,m.imgpath,m.praisenums,c.cate as catename,u.avatar,u.nickname')
        ->limit($p,2)
        ->select();
        foreach ($res as $k => $v) {
          $is=$this->getpraise(I('get.userid'),$v['id']);
          $comment=M('comment')->where('is_delete= 1 and messageid='.$v['id'])->field('id')->count();
          $v[$k]['dz']=$is;
          $v[$k]['commentnum']=$comment;
        }
        $dat['data']=$res;
        $dat['errCode']=200;
      }else{
        $dat['errCode']=201;
      }
      echo json_encode($dat);
      exit(); 
        
    }
    //获取上面的热门分类所有的
    public function hotcate()
    {
      if (IS_GET) {
        $res=M('messagecate')->where('is_delete=1')->field('id,cate')->select();
        $dat['data']=$res;
        $dat['errCode']=200;
      }else{
        $dat['errCode']=201;
      }
      echo json_encode($dat);
      exit();
    }
}