<?php
namespace Api\Controller;
use Think\Controller;
class DynamicController extends Controller
{
	//首页朋友圈
	public function Index()
	{
			
	}
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
           $info=A('Index')->upload();
           if ($info==0) {
           	   $dat['errorCode']=206;
               echo json_encode($dat);
               exit();
           }else{
           	  $datas['messageid']=$mess;
           	  $da['id']=$mess;
           	  $da['message']=I('post.message');
           	  foreach ($info as $k => $v) {
           	  	$datas['imgpath']='https://v.gubaobiao.cn/uploadfile/User/video/'.$v['savepath'].$v['savename'];
           	  	$da['imgpath'][]='https://v.gubaobiao.cn/uploadfile/User/video/'.$v['savepath'].$v['savename'];
           	  	$re=M('messageimg')->add($datas);
           	  	//添加不成功删除之前添加的数据
           	  	if (!$re) {
           	  		M('messageimg')->where('messageid='.$mess)->delete();
           	  		M('message')->where('id='.$mess)->delete();
           	  		$dat['errorCode']=206;
	                echo json_encode($dat);
	                exit();
           	  	}
           	  }
           }
           $dat['errorCode']=200;
           $dat['data']=$da;

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
    		$re=M('comment')->add();
    		if (!$re) {
    			   $dat['errorCode']=203;
	               echo json_encode($dat);
	               exit();
    		}
    		$info=A('Index')->upload();
           if ($info==0) {
           	   $dat['errorCode']=206;
               echo json_encode($dat);
               exit();
           }else{
           	  $datas['commentid']=$re;
           	  $data['id']=$re;
           	  foreach ($info as $k => $v) {
           	  	$datas['imgpath']='https://v.gubaobiao.cn/uploadfile/User/video/'.$v['savepath'].$v['savename'];
           	  	$datas['imgpath']=2;
           	  	$da['imgpath'][]='https://v.gubaobiao.cn/uploadfile/User/video/'.$v['savepath'].$v['savename'];
           	  	$re=M('messageimg')->add($datas);
           	  	//添加不成功删除之前添加的数据
           	  	if (!$re) {
           	  		M('messageimg')->where('commentid='.$re)->delete();
           	  		M('comment')->where('id='.$re)->delete();
           	  		$dat['errorCode']=206;
	                echo json_encode($dat);
	                exit();
           	  	}
           	  }
           }
           $dat['errorCode']=200;
           $dat['data']=$da;
    	}else{
    		$dat['errorCode']=201;
    	}
    	echo json_encode($dat);
    }
}