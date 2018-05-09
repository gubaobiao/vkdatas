<?php
namespace Back\Controller;
use Think\Controller;
class NewstypeController extends Controller {
	public function _initialize()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('Back/Login/login');
            exit();
        }
    }
	public function industryList(){
		$this->display();
	}
	public function newsShow(){
		$page=$_POST['page'];
		$sum=$_POST['sum'];
		$count=M('messagecate')->where('is_delete=1')->count();
		if($page==1){
			$start=0;	
		}
		if($page>1){
			$start=($page-1)*$sum;
		}
		$data['count']=$count;
		$res=M('messagecate')->where('is_delete=1')->limit($start,5)->select();
		foreach ($res as $key => $value) {
			$res[$key]['num']=$res[$key]['count'];
			$res[$key]['delete_path']='/Back/Newstype/delete/id/'.$res[$key]['id'];
			$res[$key]['update_path']='/Back/Newstype/industryUpdate?id='.$res[$key]['id'];
			$res[$key]['catenum']=M('message')->where('is_delete=1 and cateid='.$value['id'])->count();
		}
		$data['data']=$res;
		echo json_encode($data);
	}
	public function addtype(){
		$data=I('post.');
		M('messagecate')->data($data)->add();
		header('HTTP/1.1 301 Moved Permanently');
		header('token,123');
		header("Location: http://www.kanqiye.com");die;
		$this->redirect('industryList');
	}
	//修改
	public function industryUpdate(){
		if($_POST){
			$ids=I('post.ids');
			$res=I('post.');
			M('messagecate')->where('id='.$ids)->data($res)->save();
			$this->redirect('industryList');
		}else{
			$id=I('get.id');
			$info=M('messagecate')->where('id='.$id)->field('id,cate')->find();
			$this->assign('info',$info);
			$this->display();
		}
	}
	//删除
	public function delete(){
		$id=I('get.id');
		$info=M('messagecate')->where('id='.$id)->delete();
		$this->success('删除成功');
	}
	
}