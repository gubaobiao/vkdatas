<?php
namespace Back\Controller;
use Think\Controller;
class NetmanageController extends Controller {
	public function _initialize()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('Back/Login/login');
            exit();
        }
    }
	//banner
	public function barList(){
		$this->display();
	}
	public function barsList(){
		$page=$_POST['page'];
		$sum=$_POST['sum'];
		$count=M('banner')->count();
		if($page==1){
			$start=0;	
		}
		if($page>1){
			$start=($page-1)*$sum;
		}
		$data['count']=$count;
		$res=M('banner')->order('is_show desc,rank asc')->limit($start,5)->select();
		foreach ($res as $key => $value) {
			$info[$key]['src']=$res[$key]['sltpath'];
			$info[$key]['time']=$res[$key]['addtime'];
			
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
			
			$content=json_encode($info);
		}
		$data['data']=json_decode($content);
		echo json_encode($data);
	}
	public function bar_add(){
		if($_FILES){
			$info=$this->bdupload();
			if($info){
				$data['user_id']=session('user_id');
				$data['sltpath']=$info['file']['sltpath'];
				$data['ytpath']=$info['file']['ytpath'];
				$data['addtime']=date('Y-m-d');
				$data['is_show']=1;
				$data['up_path']='up';//向上排序
				$data['down_path']='down';//向下排序
				$data['release_path']='release';//点击发布
				$data['cancel_path']='cancel';//取消发布
				$data['delete_path']='delete';//删除

				M('banner')->data($data)->add();
				$this->redirect('barList');
			}else{
				$this->error('添加失败',U('barList'),1);
			}
		}
	}
	//取消发布
	public function cancel(){
		$id=I('get.id');
		$data['is_show']=0;
		M('banner')->where('id='.$id)->data($data)->save();
		$res=M('banner')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('banner')->where('id='.$value['id'])->data($shu)->save();
			}
		$this->redirect('barList');
	}
	//点击发布
	public function release(){
		$id=I('get.id');
		$data['is_show']=1;
		$info=M('banner')->where('is_show=1')->field('rank')->select();
		if(!$info){
			$data['rank']=1;
			M('banner')->where('id='.$id)->data($data)->save();
		}
		if($info){
			foreach ($info as $key => $value) {
				$rank=array_column($info,'rank');
				$max=max($rank);
				$max=$max+1;
				$da['rank']=$max;
				$da['is_show']=1;
				M('banner')->where('id='.$id)->data($da)->save();
			}
			$res=M('banner')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('banner')->where('id='.$value['id'])->data($shu)->save();
			}	
		}	
		$this->redirect('barList');
	}
	//点击删除
	public function delete(){
		$id=I('get.id');
		M('banner')->where('id='.$id)->delete();
		$res=M('banner')->where('is_show=1')->field('id,rank')->select();
			$shu['rank']=1+$i++;
			foreach ($res as $key => $value) {
				$shu['rank']=$i++;
				M('banner')->where('id='.$value['id'])->data($shu)->save();
			}
		$this->redirect('barList');
	}
	//点击向上排序
	public function up(){
		$id=I('get.id');
		$up=M('banner')->where('id='.$id)->field('rank')->find();
		$uup=$up['rank']-1;
		$m['rank']=$uup;
		$where['is_show']=1;
		$where['rank']=$uup;
		$ids=M('banner')->where($where)->field('id')->find();
		if($ids){
			M('banner')->where('id='.$id)->data($m)->save();
			M('banner')->where('id='.$ids['id'])->data($up)->save();
			$this->redirect('barList');
		}else{
			$g['rank']=1;
			M('banner')->where('id='.$id)->data($g)->save();
			$this->redirect('barList');
		}
	}
	//点击向下排序
	public function down(){
		$id=I('get.id');
		$up=M('banner')->where('id='.$id)->field('rank')->find();
		$uup=$up['rank']+1;
		$m['rank']=$uup;
		$where['is_show']=1;
		$where['rank']=$uup;
		$ids=M('banner')->where($where)->field('id')->find();
		if($ids){
			M('banner')->where('id='.$id)->data($m)->save();
			M('banner')->where('id='.$ids['id'])->data($up)->save();
			$this->redirect('barList');
		}else{
			$this->redirect('barList');
		}
	}
	######################################################################
	//动画街简介
	public function intro(){
		if($_POST){
			$data=I('post.');
			$path="./upload/intro/image/";
			if(!file_exists($path)){
                @mkdir($path,0777,true);
            }
            $sltpath='./Public/intro/Imagessm/';
            if(!file_exists($sltpath)){
                @mkdir($sltpath,0777,true);
            }
            $slydit=$sltpath;
            $imgDir =$path;
			$image=I('post.image');
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
		        $img_dir = $newFilePath;
		        $file_mini=$slydit.$filename;   
		        $ima = new \Think\Image();
		        $ima->open($img_dir);
		        $sd=$ima->thumb(175, 175);
		        // var_dump($sd);die;
		        $ima->save($file_mini);
		        $data['sltpath']=substr($file_mini,1);
			}
			
            $data['user_id']=session('user_id');
            $data['addtime']=date('Y-m-d');
            $intro_id=M('intro')->field('id')->select();
            if(!$intro_id){
            	$info=M('intro')->data($data)->add();
            }else{
            	M('intro')->where('id='.$intro_id[0]['id'])->data($data)->save();
            	$res=M('intro')->where('id='.$intro_id[0]['id'])->find();
            	$this->assign('res',$res);
            }
            
            if($info){
            	$res=M('intro')->where('id='.$info)->find();
            	$this->assign('res',$res);
            	$this->display();
            }
		}else{
			
			$intro_id=M('intro')->field('id')->select();
			if($intro_id){
				$res=M('intro')->where('id='.$intro_id[0]['id'])->find();
            	$this->assign('res',$res);
			}
			
			$this->display();
		}
		
	}
	public function contact(){
		if($_POST){
			$data=I('post.');
	    	$content['connect']=json_encode($data);
	    	$content['addtime']=date('Y-m-d');
	    	$content['user_id']=session('user_id');
	    	$con=M('contact')->select();
	    	if(!$con){
	    		$info=M('contact')->data($content)->add();
	    		$m=M('contact')->where('id='.$info)->field('connect')->find();
	    		$n=json_decode($m['connect'],true);
	    		$this->assign('n',$n);
	    		$this->display();	
	    	}else{
	    		$g=M('contact')->field('id')->select();
	    		M('contact')->where('id='.$g[0]['id'])->data($content)->save();
	    		$a=M('contact')->where('id='.$g[0]['id'])->field('connect')->find();
	    		$n=json_decode($a['connect'],true);
	    		$this->assign('n',$n);
	    		$this->display();
	    	}
    		$this->redirect('contact');
		}else{
			$g=M('contact')->field('id')->select();
			$a=M('contact')->where('id='.$g[0]['id'])->field('connect')->find();
    		$n=json_decode($a['connect'],true);
    		$this->assign('n',$n);
			$this->display();
		}
		
	}
	public function bdupload() {  
        $upload = new \Think\Upload(); 
        // 实例化上传类  
        $upload -> maxSize = 7145728;  
        // 设置附件上传大小  
        $upload -> exts = array('jpg', 'gif', 'png', 'jpeg');  
        // 设置附件上传类型  
        $upload -> rootPath = './Public/Images/';  
        // 设置附件上传根目录  
        $upload -> savePath = '';  
        // 设置附件上传（子）目录  
        // 上传文件  
        $info = $upload -> upload();
        if (!$info) {// 上传错误提示错误信息  
            // $this -> error($upload -> getError());  
        } else {// 上传成功  
            foreach($info as &$file){  
            $img_dir = './Public/Images/'.$file['savepath'].$file['savename'];  
            $arr=explode('/', $_SERVER['SCRIPT_NAME']);
            //var_dump($img_dir); die; 
            $str="/".$arr[1].$img_dir;  
            //dump($str);die;
            $filename=$_SERVER['DOCUMENT_ROOT'].$str;   //最终获取的文件名绝对路径文件名    
            // dump($filename);die;
            $file_mini='./Public/Imagessm/'.$file['savename'];  
            $str_mini="/".$arr[1].$file_mini; 
            $filename_mini=$_SERVER['DOCUMENT_ROOT'].$str_mini;//设置缩略图保存目录  
            //var_dump($filename_mini);die; 
            $image = new \Think\Image();
            // var_dump($image);die;  
            $image->open($img_dir);
            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg  
            $image->thumb(1920, 480)->save($file_mini);  
            $file['sltpath']=substr($file_mini,1);
            $file['ytpath']=substr($img_dir,1); 
            } 
            return $info;  
        }  
    }
}