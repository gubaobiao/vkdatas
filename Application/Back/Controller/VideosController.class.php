<?php
namespace Back\Controller;
use Think\Controller;

class VideosController extends Controller {
	public function _initialize()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('Back/Login/login');
            exit();
        }
    }
	public function videoList(){
		$this->display();
	}

	public function video_show(){
		$page=$_POST['page'];
		$sum=$_POST['sum'];
		$count=M('video')->count();
		if($page==1){
			$start=0;	
		}
		if($page>1){
			$start=($page-1)*$sum;
		}
		$data['count']=$count;
		$res=M('video')->alias('v')->join('left join dhj_video_type as vt on vt.id=v.type ')->order('is_show desc,id desc')->limit($start,5)->field('v.*,vt.type_name')->select();
		foreach ($res as $key => $value) {
			$info[$key]['id']=$res[$key]['id'];
			$info[$key]['time']=$res[$key]['createtime'];
			$info[$key]['title']=$res[$key]['title'];
			$info[$key]['type']=$res[$key]['type_name'];
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
			$info[$key]['update_path']=$res[$key]['update_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['will_path']=$res[$key]['will_path'].'/'.'id'.'/'.$res[$key]['id'];
			$info[$key]['path']=$res[$key]['see_path'].'/'.'id'.'/'.$res[$key]['id'];
			$content=json_encode($info);
		}
		$data['data']=json_decode($content);
		echo json_encode($data);

	}

	public function videoAdd(){
		$res=M('video_type')->field('id,type_name')->select();
		$this->assign('res',$res);
		$this->display();
	}

	public function addvideo(){

		$data=I('post.');
        $data['timing'] = $data['timer'] == 'on' ? (int)$data['time'] : time();
        unset($data['timer']);
		$data['type']=I('post.type');
		$content = $_POST['content'];
		//$content = str_replace("<img","<img onload=javascript:resizepic(this)",$content);
		$data['content'] = $content;
		$data['user_id']=session('user_id');
		$data['createtime']=date('Y-m-d');
		$data['is_show']=1;
		$data['up_path']='up';//向上排序
		$data['down_path']='down';//向下排序
		$data['release_path']='release';//点击发布
		$data['cancel_path']='cancel';//取消发布
		$data['delete_path']='delete';//删除
		$data['update_path']='/Back/Videos/videoUpdate';//修改
		$data['will_path']='/Back/Videos/videoUpdate';//修改
		$data['see_path']="http://www.donghuajie.com/Home/index/video";//查看

		$path="./Public/video_pic/";
		if(!file_exists($path)){
                @mkdir($path,0777,true);
           }
        $slt_path="./Public/video_slt/";
        if(!file_exists($slt_path)){
                @mkdir($slt_path,0777,true);
           }


        $imgDir =$path;
		$image=I('post.pic');
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
        $data['ytpath']=trim($newFilePath,'./');

        //imagick缩略图
        $img_dir =$newFilePath;
        $file_mini=$slt_path.$filename;
        //var_dump($file_mini);
        $ima = new \Think\Image(2);
        $ima->open($img_dir);
        $sd=$ima->thumb(750, 420,6);
        // var_dump($sd);die;
        $ima->save($file_mini,null,85);

        //等比压缩图片
//        gzipPic($img_dir,$file_mini);

        $data['sltpath']=substr($file_mini,1);
        M('video')->data($data)->add();

        $type=$data['type'];
        $count=M('video')->where('type='.$type)->count();
        $con['count']=$count;
        M('video_type')->where('id='.$type)->data($con)->save();
		$this->redirect('videoList');
	}
	//点击发布
	public function release(){
		$id=I('get.id');
		$data['is_show']=1;
		$info=M('video')->where('is_show=1')->field('rank')->select();
		if(!$info){
			$data['rank']=1;
			M('video')->where('id='.$id)->data($data)->save();
		}
		if($info){
			foreach ($info as $key => $value) {
				$rank=array_column($info,'rank');
				$max=max($rank);
				$max=$max+1;
				$da['rank']=$max;
				$da['is_show']=1;
				M('video')->where('id='.$id)->data($da)->save();
			}
			$res=M('video')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('video')->where('id='.$value['id'])->data($shu)->save();
			}	
		}	

		$this->redirect('videoList');
	}
	//取消发布
	public function cancel(){
		$id=I('get.id');
		$data['is_show']=0;
		M('video')->where('id='.$id)->data($data)->save();
		$res=M('video')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('video')->where('id='.$value['id'])->data($shu)->save();
			}
		$this->redirect('videoList');
	}
	//点击删除
	public function delete(){
		$id=I('get.id');
		M('video')->where('id='.$id)->delete();
		$res=M('video')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('video')->where('id='.$value['id'])->data($shu)->save();
			}
		$this->redirect('videoList');
	}
	//点击向上排序
	public function up(){
		$id=I('get.id');
		$up=M('video')->where('id='.$id)->field('rank')->find();
		$uup=$up['rank']-1;
		$m['rank']=$uup;
		$where['is_show']=1;
		$where['rank']=$uup;
		$ids=M('video')->where($where)->field('id')->find();
		if($ids){
			M('video')->where('id='.$id)->data($m)->save();
			M('video')->where('id='.$ids['id'])->data($up)->save();
			$this->redirect('videoList');
		}else{
			$g['rank']=1;
			M('video')->where('id='.$id)->data($g)->save();
			$this->redirect('videoList');
		}
	}
	//点击向下排序
	public function down(){
		$id=I('get.id');
		$up=M('video')->where('id='.$id)->field('rank')->find();
		$uup=$up['rank']+1;
		$m['rank']=$uup;
		$where['is_show']=1;
		$where['rank']=$uup;
		$ids=M('video')->where($where)->field('id')->find();
		if($ids){
			M('video')->where('id='.$id)->data($m)->save();
			M('video')->where('id='.$ids['id'])->data($up)->save();
			$this->redirect('videoList');
		}else{
			$this->redirect('videoList');
		}
	}
	public function videoUpdate(){

		$id=I('get.id');
		// dump($id);die();
		if($_POST){
			$data=I('post.');
			$type=I('post.type');
			if($type){
				$data['type']=$type;
			}
			$ids=I('post.ids');
			$content = $_POST['content'];
			$content = str_replace("<img","<img onload=javascript:resizepic(this)",$content);
			$data['content'] = $content;
			$data['user_id']=session('user_id');
			$data['createtime']=date('Y-m-d');
			$data['up_path']='up';//向上排序
			$data['down_path']='down';//向下排序
			$data['release_path']='release';//点击发布
			$data['cancel_path']='cancel';//取消发布
			$data['delete_path']='delete';//删除
			$data['update_path']='/Back/Videos/videoUpdate';//修改
			$data['will_path']='/Back/Videos/videoUpdate';//修改

			$path="./Public/video_pic/";
			if(!file_exists($path)){
	                @mkdir($path,0777,true);
	           }
	        $slt_path="./Public/video_slt/";
	        if(!file_exists($slt_path)){
	                @mkdir($slt_path,0777,true);
	           }
	        $imgDir =$path;
			$image=I('post.pic');
			if($image){
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
		        $data['ytpath']=trim($newFilePath,'./');

		        //缩略图
		        $img_dir =$newFilePath;
		        $file_mini=$slt_path.$filename;
		        $ima = new \Think\Image(2);
		        $ima->open($img_dir);
		        $sd=$ima->thumb(750, 420,6);
		        // var_dump($sd);die;
		        $ima->save($file_mini,null,85);

		        $data['sltpath']=substr($file_mini,1);
		        
			}
			M('video')->where('id='.$ids)->data($data)->save();
			$this->redirect('videoList');
		}else{
			$info=M('video')->alias('vi')->join('left join dhj_video_type as dvt on dvt.id=vi.type ')->where("vi.id=$id")->field('vi.*,dvt.type_name')->find();
			$this->assign('info',$info);
			$res=M('video_type')->field('id,type_name')->select();
			$this->assign('res',$res);
			$this->display();
		}
	}
	public function resort(){
		$link_id=$_POST['id'];
		$rank=$_POST['rank'];
		$where['rank']=array('egt',$rank);
		$where['is_show']=array('eq','1');
		$res=M('video')->where($where)->select();
		$res_id=array_column($res,'id');
		$key=array_keys($res_id,$link_id,true);
		unset($res_id[$key['0']]);
		foreach ($res_id as $key => $value) {
			$i=++$i;
			$here['id']=$value;
			$data['rank']=$rank+$i;
			M('video')->where($here)->data($data)->save();
		}
		M('video')->where('id='.$link_id)->setField('rank',$rank);
	}
}