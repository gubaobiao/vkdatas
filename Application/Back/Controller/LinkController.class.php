<?php
namespace Back\Controller;
use Think\Controller;
class LinkController extends Controller {
	public function _initialize()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('Back/Login/login');
            exit();
        }
    }
	//友情链接
	public function linkList(){
		$this->display();
	}
	public function list_show(){
		$page=$_POST['page'];
		$sum=$_POST['sum'];
		$count=M('link')->count();
		if($page==1){
			$start=0;	
		}
		if($page>1){
			$start=($page-1)*$sum;
		}
		$data['count']=$count;
		$wname=$_POST['text'];
		if($wname){
			$where['wName']=array('like',"%$wname%");
		}
		$res=M('link')->where($where)->order('is_show desc,rank asc')->limit($start,20)->select();
		
		foreach ($res as $key => $value) {
			$info[$key]['id']=$res[$key]['id'];
			$info[$key]['time']=$res[$key]['addtime'];
			$info[$key]['name']=$res[$key]['wname'];
			$info[$key]['path']=$res[$key]['wpath'];
			$info[$key]['status']=$res[$key]['is_show'];
			if($res[$key]['is_show'] && $res[$key]['is_show'] !=0){
				$info[$key]['rank']=$res[$key]['rank'];
			}else{
				$info[$key]['rank']='';
			}
			$info[$key]['up_path']=$res[$key]['up_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['down_path']=$res[$key]['down_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['release_path']=$res[$key]['release_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['cancel_path']=$res[$key]['cancel_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['delete_path']=$res[$key]['delete_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['update_path']=$res[$key]['modify_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['will_path']=$res[$key]['will_path'].'/'.'id'.'/'.$res[$key]['id'];
			$content=json_encode($info);
		}
		$data['data']=json_decode($content);
		echo json_encode($data);

	}
	//友情链接添加功能
	public function linkAdd(){
		if($_POST){
			$data=I('post.');
			$data['user_id']=session('user_id');
			$data['addtime']=date('Y-m-d');
			$data['is_show']=1;
			$data['up_path']='up';//向上排序
			$data['down_path']='down';//向下排序
			$data['release_path']='release';//点击发布
			$data['cancel_path']='cancel';//取消发布
			$data['delete_path']='delete';//删除
			$data['modify_path']='linkUpdate';//修改
			$data['will_path']='/Back/Link/linkUpdate';//修改
			M('link')->data($data)->add();
			$this->redirect('linkList');
		}else{
			$this->display();
		}
	}
	//取消发布
	public function cancel(){
		$id=I('get.id');
		$data['is_show']=0;
		M('link')->where('id='.$id)->data($data)->save();
		$res=M('link')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('link')->where('id='.$value['id'])->data($shu)->save();
			}
		$this->redirect('linkList');
	}
	//点击发布
	public function release(){
		$id=I('get.id');
		$data['is_show']=1;
		$info=M('link')->where('is_show=1')->field('rank')->select();
		if(!$info){
			$data['rank']=1;
			M('link')->where('id='.$id)->data($data)->save();
		}
		if($info){
			foreach ($info as $key => $value) {
				$rank=array_column($info,'rank');
				$max=max($rank);
				$max=$max+1;
				$da['rank']=$max;
				$da['is_show']=1;
				M('link')->where('id='.$id)->data($da)->save();
			}
			$res=M('link')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('link')->where('id='.$value['id'])->data($shu)->save();
			}	
		}	
		$this->redirect('linkList');
	}
	//点击删除
	public function delete(){
		$id=I('get.id');
		M('link')->where('id='.$id)->delete();
		$res=M('link')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('link')->where('id='.$value['id'])->data($shu)->save();
			}
		$this->redirect('linkList');
	}
	// //点击向上排序
	// public function up(){
	// 	$id=I('get.id');
	// 	$up=M('link')->where('id='.$id)->field('rank')->find();
	// 	$uup=$up['rank']-1;
	// 	$m['rank']=$uup;
	// 	$where['is_show']=1;
	// 	$where['rank']=$uup;
	// 	$ids=M('link')->where($where)->field('id')->find();
	// 	if($ids){
	// 		M('link')->where('id='.$id)->data($m)->save();
	// 		M('link')->where('id='.$ids['id'])->data($up)->save();
	// 		$this->redirect('linkList');
	// 	}else{
	// 		$g['rank']=1;
	// 		M('link')->where('id='.$id)->data($g)->save();
	// 		$this->redirect('linkList');
	// 	}
	// }
	// //点击向下排序
	// public function down(){
	// 	$id=I('get.id');
	// 	$up=M('link')->where('id='.$id)->field('rank')->find();
	// 	$uup=$up['rank']+1;
	// 	$m['rank']=$uup;
	// 	$where['is_show']=1;
	// 	$where['rank']=$uup;
	// 	$ids=M('link')->where($where)->field('id')->find();
	// 	if($ids){
	// 		M('link')->where('id='.$id)->data($m)->save();
	// 		M('link')->where('id='.$ids['id'])->data($up)->save();
	// 		$this->redirect('linkList');
	// 	}else{
	// 		$this->redirect('linkList');
	// 	}
	// }
	public function linkUpdate(){
		$id=I('get.id');
		if($_POST){
			$data['wName']=I('post.wName');
			$data['wPath']=I('post.wPath');
			$data['user_id']=session('user_id');
            $data['addtime']=date('Y-m-d');
			M('link')->where('id='.$id)->data($data)->save();
			$this->redirect('linkList');
		}else{
			$info=M('link')->where("id=$id")->field('wName,wPath')->find();
			$this->assign('info',$info);
			$this->display();
		}
	}
	public function resort(){
		$link_id=$_POST['id'];
		$rank=$_POST['rank'];
		$where['rank']=array('egt',$rank);
		$where['is_show']=array('eq','1');
		$res=M('link')->where($where)->select();
		$res_id=array_column($res,'id');
		$key=array_keys($res_id,$link_id,true);
		unset($res_id[$key['0']]);
		foreach ($res_id as $key => $value) {
			$i=++$i;
			$here['id']=$value;
			$data['rank']=$rank+$i;
			M('link')->where($here)->data($data)->save();
		}
		M('link')->where('id='.$link_id)->setField('rank',$rank);
	}
}