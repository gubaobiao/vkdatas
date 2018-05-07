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
		$count=M('dhj_messagecate')->count();
		if($page==1){
			$start=0;	
		}
		if($page>1){
			$start=($page-1)*$sum;
		}
		$data['count']=$count;
		$res=M('dhj_messagecate')->limit($start,5)->select();
		foreach ($res as $key => $value) {
			$info[$key]['time']=$res[$key]['adtime'];
			$info[$key]['type']=$res[$key]['type_name'];
			$info[$key]['num']=$res[$key]['count'];
			$info[$key]['delete_path']=$res[$key]['delete_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['update_path']=$res[$key]['update_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['will_path']=$res[$key]['will_path'].'/'.'id'.'/'.$res[$key]['id'];
			$content=json_encode($info);
		}
		$data['data']=json_decode($content);
		echo json_encode($data);
	}
	public function addtype(){
		$type['type_name']=I('post.type_name');
		$type['adtime']=date('Y-m-d');
		$type['user_id']=session('user_id');
		$type['delete_path']='delete';//删除
		$type['update_path']='industryUpdate';//更新
		$type['will_path']='/Back/Newstype/industryUpdate';//更新
		M('news_type')->data($type)->add();
		$this->redirect('industryList');
	}
	//修改
	public function industryUpdate(){
		if($_POST){
			$ids=I('post.ids');
			$res['type_name']=I('post.type_name');
			$res['adtime']=date('Y-m-d');
			$res['user_id']=session('user_id');
			M('news_type')->where('id='.$ids)->data($res)->save();
			$this->redirect('industryList');
		}else{
			$id=I('get.id');
			$info=M('news_type')->where('id='.$id)->field('id,type_name')->find();
			$this->assign('info',$info);
			$this->display();
		}
	}
	//删除
	public function delete(){
		$id=I('get.id');
		$info=M('news_type')->where('id='.$id)->delete();
		$this->redirect('industryList');
	}
	
}