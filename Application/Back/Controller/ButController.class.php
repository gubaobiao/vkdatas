<?php
namespace Back\Controller;
use Think\Controller;
//底部广告图片
class ButController extends Controller {
	public function _initialize()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('Back/Login/login');
            exit();
        }
    }
	public function adlist(){
		$this->display();
	}

	public function butList(){
		$page=$_POST['page'];
		$sum=$_POST['sum'];
		$count=M('but')->count();
		if($page==1){
			$start=0;	
		}
		if($page>1){
			$start=($page-1)*$sum;
		}
		$data['count']=$count;
		$res=M('but')->order('is_show desc')->limit($start,5)->select();
		foreach ($res as $key => $value) {
			$info[$key]['src']=$res[$key]['sltpath'];
			$info[$key]['time']=$res[$key]['addtime'];
			
			$info[$key]['src_hover']=$res[$key]['src_hover'];
			$info[$key]['status']=$res[$key]['is_show'];
			$info[$key]['update_path']=$res[$key]['update_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['will_path']=$res[$key]['will_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['release_path']=$res[$key]['release_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['cancel_path']=$res[$key]['cancel_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['delete_path']=$res[$key]['delete_path'].'/'.'id'.'/'.$res[$key]['id'];
			$content=json_encode($info);
		}
		$data['data']=json_decode($content);
		echo json_encode($data);
	}
	//底部添加
	public function adAdd(){
		if($_POST){
			$data=I('post.');
			$path="./upload/but/image1/";
			if(!file_exists($path)){
                @mkdir($path,0777,true);
            }
            $imgDir =$path;
            //默认图片
			$image=I('post.image1');
			$img = str_replace('data:image/png;base64', '', $image);
			$img=str_replace('data:image/jpeg;base64', '', $img);
			$img = str_replace(' ', '+', $img);
			$img = base64_decode($img);
			$filename = md5(time().mt_rand(10, 99)).".jpg"; //新图片名称
			$newFilePath = $imgDir.$filename;
			$newFile = fopen($newFilePath,"a+"); //打开文件准备写入
			 //写入二进制流到文件
            fwrite($newFile,$img);
            fclose($newFile); //关闭文件
      
            //缩略图
		    $img_dir = $newFilePath;
		    $file_mini=$imgDir.$filename;   
		    $ima = new \Think\Image();
		    $ima->open($img_dir);
		    $sd=$ima->thumb(199,78);
		    // var_dump($sd);die;
		    $ima->save($file_mini);
		    $data['sltpath']=substr($file_mini,1);
#############################################################################################
            //hover鼠标悬浮图片
            $path2="./upload/but/image2/";
            if(!file_exists($path2)){
                @mkdir($path2,0777,true);
            }
            $img2Dir =$path2;
            $image2=I('post.image2');
            $filename2 = md5(time().mt_rand(10, 99)).".jpg";
            $img2 = str_replace('data:image/png;base64', '', $image2);
            $img2=str_replace('data:image/jpeg;base64', '', $img2);
            $img2 = str_replace(' ', '+', $img2);
            $img2 = base64_decode($img2);
            $newFilePath2 = $img2Dir.$filename2;
            $newFile2 = fopen($newFilePath2,"a+"); //打开文件准备写入
             //写入二进制流到文件
            fwrite($newFile2,$img2);
            fclose($newFile2); //关闭文件
            
            //缩略图
		    $img_dir2 = $newFilePath2;
		    $file_mini2=$img2Dir.$filename2;   
		    $ima2 = new \Think\Image();
		    $ima2->open($img_dir2);
		    $sd=$ima2->thumb(199,78);
		    // var_dump($sd);die;
		    $ima2->save($file_mini2);
		    $data['src_hover']=substr($file_mini2,1);

            $data['user_id']=session('user_id');
            $data['addtime']=date('Y-m-d');
			$data['release_path']='release';//点击发布
			$data['cancel_path']='cancel';//取消发布
			$data['delete_path']='delete';//删除
			$data['update_path']='butUpdate';//更新
			$data['will_path']='/Back/but/butUpdate';//更新

			M('but')->data($data)->add();
			$this->redirect('adList');
		}else{
			$this->display();
		}
		
	}
	//点击发布
	public function release(){
		$id=I('get.id');
		$data['is_show']=1;
		$info=M('but')->where('id='.$id)->data($data)->save();
		$this->redirect('adList');
	}
	//取消发布
	public function cancel(){
		$id=I('get.id');
		$data['is_show']=0;
		$info=M('but')->where('id='.$id)->data($data)->save();
		$this->redirect('adList');
	}
	//删除
	public function delete(){
		$id=I('get.id');
		$info=M('but')->where('id='.$id)->delete();
		$this->redirect('adList');
	}
	//修改
	public function butUpdate(){
		if($_POST){
			$ids=I('post.id');
			$path="./upload/but/image1/";
			if(!file_exists($path)){
                @mkdir($path,0777,true);
            }
            $imgDir =$path;
            //默认图片
			$image=I('post.image1');
			$img = str_replace('data:image/png;base64', '', $image);
			$img=str_replace('data:image/jpeg;base64', '', $img);
			$img = str_replace(' ', '+', $img);
			$img = base64_decode($img);
			$filename = md5(time().mt_rand(10, 99)).".jpg"; //新图片名称
			$newFilePath = $imgDir.$filename;
			$newFile = fopen($newFilePath,"a+"); //打开文件准备写入
			 //写入二进制流到文件
            fwrite($newFile,$img);
            fclose($newFile); //关闭文件
            if($image){
            	$data['sltpath']=trim($newFilePath,'./');
            }
            
            //hover鼠标悬浮图片
            $path="./upload/but/image2/";
            if(!file_exists($path)){
                @mkdir($path,0777,true);
            }
            $img2Dir =$path;
            $image2=I('post.image2');
            $img2 = str_replace('data:image/png;base64', '', $image2);
            $img2=str_replace('data:image/jpeg;base64', '', $img2);
            $img2 = str_replace(' ', '+', $img2);
            $img2 = base64_decode($img2);
            $newFilePath2 = $img2Dir.$filename;
            $newFile2 = fopen($newFilePath2,"a+"); //打开文件准备写入
             //写入二进制流到文件
            fwrite($newFile2,$img2);
            fclose($newFile2); //关闭文件
            if($image2){
            	$data['src_hover']=trim($newFilePath2,'./');
            }
            
            if($data){
            	$data['user_id']=session('user_id');
            	M('but')->where('id='.$ids)->data($data)->save();
            }
            $this->redirect('adList');
		}else{
			$id=I('get.id');
			$this->assign('id',$id);
			$this->display();
		}
	}


}