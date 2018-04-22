<?php
namespace Back\Controller;
use Think\Controller;
class ServiceController extends Controller {
	public function _initialize()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('Back/Login/login');
            exit();
        }
    }
	//服务介绍
	public function serviceList(){
		$this->display();
	}
	public function servicedd(){
		if($_POST){
			$data=I('post.');
			$path="./upload/service/image/";
			if(!file_exists($path)){
                @mkdir($path,0777,true);
            }
            $imgDir =$path;
			$image=I('post.image');
			$img = str_replace('data:image/png;base64', '', $image);
			$img=str_replace('data:image/jpeg;base64', '', $img);
			$img = str_replace(' ', '+', $img);
			$img = base64_decode($img);
			$filename = md5(time().mt_rand(10, 99)).".png"; //新图片名称
			$newFilePath = $imgDir.$filename;
			$newFile = fopen($newFilePath,"a+"); //打开文件准备写入
			 //写入二进制流到文件
            fwrite($newFile,$img);
            fclose($newFile); //关闭文件
            $data['ytpath']=trim($newFilePath,'./');

            $data['user_id']=session('user_id');
            $data['addtime']=date('Y-m-d');
            $data['is_show']=1;
			$data['up_path']='up';//向上排序
			$data['down_path']='down';//向下排序
			$data['release_path']='release';//点击发布
			$data['cancel_path']='cancel';//取消发布
			$data['delete_path']='delete';//删除
			$data['update_path']='serviceUpdate';//修改
			$data['will_path']='/Back/Service/serviceUpdate';//xiugai
			
			M('service')->data($data)->add();
			$this->redirect('serviceList');
		}
		
	}
	public function serList(){
		$page=$_POST['page'];
		$sum=$_POST['sum'];
		$count=M('service')->count();
		if($page==1){
			$start=0;	
		}
		if($page>1){
			$start=($page-1)*$sum;
		}
		$data['count']=$count;
		$res=M('service')->order('is_show desc,rank asc')->limit($start,5)->select();
		foreach ($res as $key => $value) {
			$info[$key]['src']=$res[$key]['ytpath'];
			$info[$key]['title']=$res[$key]['title'];
			$info[$key]['path']=$res[$key]['path'];
			$info[$key]['text1']=$res[$key]['text1'];
			$info[$key]['text2']=$res[$key]['text2'];
			$info[$key]['text3']=$res[$key]['text3'];
			$info[$key]['time']=$res[$key]['addtime'];
			$info[$key]['status']=$res[$key]['is_show'];
			if($res[$key]['is_show'] && $res[$key]['is_show'] !=0){
				$info[$key]['rank']=$res[$key]['rank'];
			}else{
				$info[$key]['rank']='';
			}
			$info[$key]['up_path']=$res[$key]['up_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['down_path']=$res[$key]['down_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['path']=$res[$key]['path'];
			$info[$key]['release_path']=$res[$key]['release_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['cancel_path']=$res[$key]['cancel_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['delete_path']=$res[$key]['delete_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['update_path']=$res[$key]['update_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['will_path']=$res[$key]['will_path'].'/'.'id'.'/'.$res[$key]['id'];
			$content=json_encode($info);
		}
		$data['data']=json_decode($content);
		echo json_encode($data);
	}
	
	//点击发布
	public function release(){
		$id=I('get.id');
		$data['is_show']=1;
		$info=M('service')->where('is_show=1')->field('rank')->select();
		if(!$info){
			$data['rank']=1;
			M('service')->where('id='.$id)->data($data)->save();
		}
		if($info){
			foreach ($info as $key => $value) {
				$rank=array_column($info,'rank');
				$max=max($rank);
				$max=$max+1;
				$da['rank']=$max;
				$da['is_show']=1;
				M('service')->where('id='.$id)->data($da)->save();
			}
			$res=M('service')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('service')->where('id='.$value['id'])->data($shu)->save();
			}	
		}	
		$this->redirect('serviceList');
	}
	//取消发布
	public function cancel(){
		$id=I('get.id');
		$data['is_show']=0;
		M('service')->where('id='.$id)->data($data)->save();
		$res=M('service')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('service')->where('id='.$value['id'])->data($shu)->save();
			}
		$this->redirect('serviceList');
	}
	//点击删除
	public function delete(){
		$id=I('get.id');
		M('service')->where('id='.$id)->delete();
		$res=M('service')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('service')->where('id='.$value['id'])->data($shu)->save();
			}
		$this->redirect('serviceList');
	}
	//点击向上排序
	public function up(){
		$id=I('get.id');
		$up=M('service')->where('id='.$id)->field('rank')->find();
		$uup=$up['rank']-1;
		$m['rank']=$uup;
		$where['is_show']=1;
		$where['rank']=$uup;
		$ids=M('service')->where($where)->field('id')->find();
		if($ids){
			M('service')->where('id='.$id)->data($m)->save();
			M('service')->where('id='.$ids['id'])->data($up)->save();
			$this->redirect('serviceList');
		}else{
			$g['rank']=1;
			M('service')->where('id='.$id)->data($g)->save();
			$this->redirect('serviceList');
		}
		
		
	}
	//点击向上排序
	public function down(){
		$id=I('get.id');
		$up=M('service')->where('id='.$id)->field('rank')->find();
		$uup=$up['rank']+1;
		$m['rank']=$uup;
		$where['is_show']=1;
		$where['rank']=$uup;
		$ids=M('service')->where($where)->field('id')->find();
		if($ids){
			M('service')->where('id='.$id)->data($m)->save();
			M('service')->where('id='.$ids['id'])->data($up)->save();
			$this->redirect('serviceList');
		}else{
			$this->redirect('serviceList');
		}
		
	}
	public function serviceUpdate(){
		$id=I('get.id');
		if($_POST){
			$data=I('post.');
			$path="./upload/service/image/";
			if(!file_exists($path)){
                @mkdir($path,0777,true);
            }
            $imgDir =$path;
			$image=I('post.image');
			if($image){
				$img = str_replace('data:image/png;base64', '', $image);
				$img=str_replace('data:image/jpeg;base64', '', $img);
				$img = str_replace(' ', '+', $img);
				$img = base64_decode($img);
				$filename = md5(time().mt_rand(10, 99)).".png"; //新图片名称
				$newFilePath = $imgDir.$filename;
				$newFile = fopen($newFilePath,"a+"); //打开文件准备写入
				 //写入二进制流到文件
	            fwrite($newFile,$img);
	            fclose($newFile); //关闭文件
	            $data['ytpath']=trim($newFilePath,'./');

				}
	            $data['user_id']=session('user_id');
	            $data['addtime']=date('Y-m-d');
				M('service')->where('id='.$data['id'])->data($data)->save();
				$this->redirect('serviceList');
		}else{
			$data=M('service')->where('id='.$id)->field('id,title,text1,text2,text3,path')->find();
			$this->assign('data',$data);
			$this->display();
		}
		
	}
}