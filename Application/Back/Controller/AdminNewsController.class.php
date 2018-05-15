<?php
namespace Back\Controller;
use Think\Controller;
class AdminNewsController extends Controller {
	public function companyNewsList(){
		$this->display();
	}
	public function newsList(){
		
		$page=$_POST['page'];
		$sum=$_POST['sum'];
		$count=M('message')->count();
		if($page==1){
			$start=0;	
		}
		if($page>1){
			$start=($page-1)*$sum;
		}
		$data['count']=$count;
		$title=$_POST['text'];
		$where['n.message']=array('like',"%$title%");
		$type=$_POST['text'];
		$where['m.cate']=array('like',"%$type%");
		$where['_logic'] = 'or';
		$map['_complex'] = $where;
		$map['n.is_delete']=array('neq',3);
		$res=M('message')->alias('n')
		->join('left join dhj_messagecate as m on m.id=n.cateid')
		->join('left join dhj_users u on u.id=n.userid')
		->where($map)
		->order('n.id desc')
		->limit($start,5)
		->field('n.message as title,n.id,n.time,n.is_delete as status,m.cate as type,n.userid,u.nickname,n.zd')->select();
		// echo M('message')->getlastsql();
		foreach ($res as $k => $v) {
			$res[$k]['time']=date('Y-m-d H:i:s',$v['time']);
			$res[$k]['title']=mb_substr($v['title'],0,10,'utf-8').'......';
			$res[$k]['release_path']='/AdminNews/release/id/'.$res[$k]['id'];
			$res[$k]['cancel_path']='/AdminNews/cancel/id/'.$res[$k]['id'];
			$res[$k]['delete_path']='/AdminNews/delete_path/id/'.$res[$k]['id'];
			$res[$k]['update_path']='/AdminNews/companyNewsUpdate/id/'.$res[$k]['id'];
			$res[$k]['will_path']='/AdminNews/companyNewsUpdate/id/'.$res[$k]['id'];
			// $info[$key]['path']=$res[$key]['see_path'].'/'.'id'.'/'.$res[$key]['id'];
			// $content=json_encode($info);
		}
		$data['data']=$res;
		echo json_encode($data);
	}
	public function companyNewsAdd(){
		$info=M('news_type')->field('id,type_name')->select();
		$this->assign('info',$info);
		$this->display();
	}
	public function aadd(){
		$data=I('post.');
        $data['timer'] = trim($data['timer']) == 'on' ? (int)$data['time'] : time();

        unset($data['time']);
		$data['user_id']=session('user_id');
		$data['title']=I('post.title');
		$data['type']=I('post.type');
		$content = $_POST['content'];
		$content = str_replace("<img onload=javascript:resizepic(this)","<img",$content);
		$content = str_replace("<img","<img onload=javascript:resizepic(this)",$content);
		$data['content'] = $content;
		$data['keyword']=I('post.keyword');
		$data['description']=I('post.description');
		$data['is_show'] = 1;
		$data['addtime']=date("Y-m-d");
		$data['release_path']='release';
		$data['cancel_path']='cancel';
		$data['delete_path']='delete';
		$data['update_path']='companyNewsUpdate';
		$data['will_path']='/Back/AdminNews/companyNewsUpdate';
		$data['see_path']="http://www.donghuajie.com/Home/index/news";//查看
		
		$path="./Public/pic/";
		if(!file_exists($path)){
                @mkdir($path,0777,true);
           }
        $slt_path="./Public/slt/";
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
        $data['ytgpath']=trim($newFilePath,'./');

        //缩略图
        $img_dir =$newFilePath;
        $file_mini=$slt_path.$filename;
        $ima = new \Think\Image(2);
        $ima->open($img_dir);
        $sd=$ima->thumb(320, 180,6);
        // var_dump($sd);die;
        $ima->save($file_mini,null,85);
//        //等比压缩图片
//        gzipPic($img_dir,$file_mini);

        $data['sltpath']=substr($file_mini,1);
       
        $news=M('news');
        $res=$news->data($data)->add();
        $type=$data['type'];
        $count=M('news')->where('type='.$type)->count();
        $con['count']=$count;
        M('news_type')->where('id='.$type)->data($con)->save();
	    $this->redirect('AdminNews/companyNewsList');
	}
	//点击发布
	public function release(){
		$id=I('get.id');
		$data['is_delete']=1;
		$info=M('message')->where('id='.$id)->data($data)->save();
		// $this->redirect('AdminNews/companyNewsList');
		$this->success('发布成功!');
	}
	//取消发布
	public function cancel(){
		$id=I('get.id');
		$data['is_delete']=2;
		$info=M('message')->where('id='.$id)->data($data)->save();
		$this->success('取消成功!');
	}
	//删除
	public function delete(){
		$id=I('get.id');
		$info=M('news')->where('id='.$id)->delete();
		$this->redirect('companyNewsList');
	}
	public function companyNewsUpdate(){
		$id=I('get.id');
		$info=M('message')
		->alias('n')
		->join('left join dhj_users as u on u.id=n.userid ')
		->join('left join dhj_messagecate m on m.id=n.cateid')
		->where("n.id=$id")
		->field('n.*,u.nickname,u.avatar,m.cate')
		->find();
		$info['img']=explode(',',$info['imgpath']);
		$this->assign('info',$info);
		$this->display();
		
	}
	//置顶
	public function newzd()
	{
		if (I('get.type')==1) {
			$data['zd']=2;
			$re=M('message')->data($data)->where('id='.I('get.id'))->save();
			if ($re===false) {
				$dat['status']=4;
			}else{
				$dat['status']=1;
			}
		}elseif (I('get.type')==2) {
			$data['zd']=1;
			$re=M('message')->data($data)->where('id='.I('get.id'))->save();
			if ($re===false) {
				$dat['status']=4;
			}else{
				$dat['status']=1;
			}
		}
		
		echo json_encode($dat);

	}
}