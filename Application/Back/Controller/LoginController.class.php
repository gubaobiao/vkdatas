<?php
namespace Back\Controller;
use Think\Controller;
class LoginController extends Controller {
	public function login(){
		if($_POST){
			$login['username']=I('post.name');
			$login['password']=MD5(I('post.password'));
			$info=M('user')->where($login)->find();
			if($info){
				$res['user_id']=$info['id'];
				$res['success']=1;
				$res['username']=$login['username'];
				$res['password']=I('post.password');
				$res['url']='index';
				session('user_id',$res['user_id']);
				echo json_encode($res);
			}else{
				$res['message']='用户名或密码不正确';
				echo json_encode($res);
			}
		}else{
			$this->display();
		}
		
	}
	public function index(){
		$this->redirect('Back/Index/index');
	}
}
