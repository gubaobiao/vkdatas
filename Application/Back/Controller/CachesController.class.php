<?php
namespace Back\Controller;
use Think\Controller;
class CachesController extends Controller
{
	public  function _initialize()
	{
		 if (!isset($_SESSION['user_id'])) {
            $this->redirect('Back/Login/login');
            exit();
        }
	}
	public function index()
	{

		$this->display();
	}
	/*
		删除首页静态缓存
	*/
	public function deleteIndex()
	{
		if (IS_POST) {
			$path='./Application/Html';
			$result=deldir($path);
			if ($result==1) {
				$da['status']=1;
				echo json_encode($da);
			}else{
				$da['status']=0;
				echo json_encode($da);
				
			}
		}
	}
	/*
		删除网站运行时缓存
	*/
	public function deleteRuntime()
	{
		if (IS_POST) {
			$path='./Application/Runtime';
			$result=deldir($path);
			if ($result==1) {
				$da['status']=1;
				echo json_encode($da);
			}else{
				$da['status']=0;
				echo json_encode($da);
				
			}
		}
		
	}
}