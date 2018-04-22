<?php
namespace Back\Controller;
use Think\Controller;
class IndexController extends Controller {
	public function _initialize()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('Back/Login/login');
            exit();
        }
    }
    public function index(){
        $this->display();
    }
    public function home(){
    	$id=$_SESSION['user_id'];
    	$usr=M('user')->where('id='.$id)->field('username,last_up_time')->find();
    	$this->assign('usr',$usr);
        $this->display();
    }
}