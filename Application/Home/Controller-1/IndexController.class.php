<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    
    public function _initialize(){
    	//轮播图展示
    	$res=M('banner')->where('is_show=1')->order('rank asc')->select();
    	$this->assign('res',$res);

    	//服务介绍
    	$service=M('service')->where('is_show=1')->select();
    	$this->assign('service',$service);

    	//视频案例
    	$video=M('video')->where('is_show=1')->limit(0,8)->order('rank asc')->select();
    	$this->assign('video',$video);

    	//动画街简介
    	$intro=M('intro')->find();
    	$this->assign('intro',$intro);

    	//文章中心
    	$news=M('news')->where('is_show=1')->order('addtime desc')->limit(10)->select();
    	$this->assign('news',$news);

    	//友情链接
    	$link=M('link')->where('is_show=1')->order('rank asc')->select();
    	$this->assign('link',$link);

    	//底部广告
    	$but=M('but')->where('is_show=1')->select();
    	$this->assign('but',$but);

    	//联系我们
    	$g=M('contact')->field('id')->select();
		$a=M('contact')->where('id='.$g[0]['id'])->field('connect')->find();
    	$n=json_decode($a['connect'],true);
    	$this->assign('n',$n);

        $domain = "http://{$_SERVER['HTTP_HOST']}";
        $this->assign('domain', $domain);

    }
    public function index(){
        $this->display();
    }
    public function anli(){
        $id=I('get.id');
        if(empty($id)) {
            $where['is_show']=1;
            $typ=I('get.type');
            if($typ){
                $where['type']=$typ;
            }
            $video=M('video')->where($where)->order('rank asc')->select();
            $this->assign('video',$video);
            $type=M('video_type')->alias('vt')->field('vt.id,vt.type_name')->select();
            $this->assign('type',$type);
            $but=M('but')->where('is_show=1')->select();
            $this->assign('but',$but);

            $seoWord['title']       = '动画制作案例 MG动画作品 flash动画制作脚本 创意MG视频短片 创意广告动画设计案例';
            $seoWord['keywords']    = 'MG动画案例，广告动画设施案例，三维动画设计案例，创意MG视频制作案例，产品APP视频';
            $seoWord['description'] = '动画街影视制作公司，是一家专业制作MG动画的企业，专业制作MG动画，flash动画，二维动画，三维动画，创意动画视频制作。一流的MG动画制作团队，制作出品多部优秀的MG动画案例，专业策划提供一流的MG动画制作服务。';
            $this->assign('seoWord',$seoWord);

            //seo
            if ($typ ==22){
                $seoWord['title']       = '原创设计型-原创动画-杭州动画制作-杭州动画公司-动画街';
                $seoWord['keywords']    = '原创动画,杭州动画制,杭州动画公司,杭州flash动画,杭州三维动画，杭州二维动画制作,杭州MG动画';
                $seoWord['description'] = '原创设计型动画，包括了原创动画、杭州flash动画、杭州三维动画、杭州二维动画、杭州MG动画等动画案例，动画街影视制作公司，专业的杭州动画公司，专业策划提供一流的MG动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }

            if ($typ ==23){
                $seoWord['title']       = '创意app动画-创意动画-flash动画创意设计-杭州mg动画-动画街';
                $seoWord['keywords']    = '创意动画,flash动画创意设计,flash动画创意设计,杭州mg动画,杭州动画制作,动画案例,杭州的动画公司';
                $seoWord['description'] = '创意app动画，包括了创意动画、flash动画创意设计、lash动画创意设计、杭州mg动画等动画案例，动画街影视制作公司，专业的杭州动画公司，专业策划提供一流的flash动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }

            if ($typ ==24){
                $seoWord['title']       = '手绘动画-手绘二维动画-杭州动画-杭州动画制作公司-动画街';
                $seoWord['keywords']    = '手绘动画,手绘二维动画,杭州动画,杭州动画制作公司,杭州二维动画制作,杭州三维动画制作,动画案例';
                $seoWord['description'] = '手绘动画，包括了手绘动画,、手绘二维动画、杭州二维动画制、杭州三维动画制作等动画案例，动画街影视制作公司，专业的杭州动画制作公司，专业策划提供一流的三维动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }

            if ($typ ==25){
                $seoWord['title']       = '企业动画-动画宣传片-企业宣传动画-杭州三维动画公司-动画街';
                $seoWord['keywords']    = '企业动画,动画宣传片,企业宣传动画,杭州三维动画公司,杭州flash动画,杭州mg动画,动画案例';
                $seoWord['description'] = '企业动画，包括了动画宣传片,、企业宣传动画、杭州三维动画公司、杭州flash动画、杭州mg动画等动画案例，动画街影视制作公司，专业的杭州三维动画公司，专业策划提供一流的mg动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }
            $this->display();
        } else {
            if($id){
                $video2=M('video')->where('id='.$id)->find();
                $this->assign('video2',$video2);
            }

            $video_all=M('video')->where('is_show=1')->select();
            $this->assign('video_all',$video_all);

            $seoWord['title']       = $video2['title'];
            $seoWord['keywords']    = $video2['keyword'];
            $seoWord['description'] = $video2['description'];

            $keyarray[] = '杭州flash动画,杭州三维动画,杭州MG动画,杭州动画制作,';
            $keyarray[] = '杭州三维动画,flash动画创意设计,杭州MG动画,创意动画，';
            $keyarray[] = '杭州MG动画,杭州三维动画公司，企业宣传动画,flash动画创意设计,';
            $keyarray[] = '杭州动画制作,动画制作报价，杭州flash动画,杭州MG动画,';
            $keyarray[] = 'flash动画创意设计,杭州三维动画,杭州MG动画,动画宣传片,';
            $keyarray[] = '动画宣传片,杭州三维动画公司，原创动画，动画制作报价，';
            $keyarray[] = '企业宣传动画,动画制作报价，flash动画创意设计,杭州MG动画,';
            $keyarray[] = '杭州三维动画公司，杭州动画公司，原创动画，动画制作报价，';
            $keyarray[] = '杭州动画公司，杭州动画设计，杭州MG动画,杭州三维动画,';
            $keyarray[] = '杭州动画设计，杭州三维动画,原创动画，杭州MG动画,';
            $keyarray[] = '杭州三维动画制作，杭州动画公司，杭州三维动画公司，杭州动画制作,';
            $keyarray[] = '创意动画，杭州三维动画制作，原创动画，杭州三维动画,';
            $keyarray[] = '原创动画，杭州MG动画,动画宣传片,杭州动画公司，';
            $keyarray[] = '动画制作报价，flash动画创意设计,杭州MG动画,创意动画，';
            $a = mt_rand(0,13);
            $keywords2 = $keyarray["$a"];
            $this->assign('keywords2',$keywords2);


//            if(mb_strlen($seoWord['description'])<=15){
//                $seoWord['description'] = '杭州动画街动漫制作公司，是一家专业设计MG动画制作的企业，擅长领域包括flash动画，二维动画动漫，创意动画片，产品宣传片视频制作等。拥有别具风格的动画制作团队，制作出品多部优秀的MG动画案例，提供专业策划案，享受一线动画制作服务。';
//            }
            $this->assign('seoWord',$seoWord);
            $this->display('video');
        }
    }
    public function news(){
        $id=I('get.id');
        if(empty($id)) {
            $count =M('news')->where('is_show=1')->count();
            $Page=new \Org\Util\Page($count,12);
            $Page->config['theme']='%upPage% %first%  %prePage%  %linkPage%  %nextPage% %downPage%  %end%';
            $show=$Page->show();
            $news=M('news')->where('is_show=1')->order('addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            foreach ($news as $key => $v) {
                // $news[$key]['content']=strip_tags($v['content']);
                $news[$key]['content']=mb_substr(strip_tags($v['content']),0,200,'utf-8');
            }
            
            // dump($news);
            $this->assign('news',$news);
            $this->assign('page',$show);
            $this->display();
        } else {
            $info=M('news')->where('id='.$id)->find();
            $this->assign('info',$info);
            $but=M('but')->where('is_show=1')->select();
            $this->assign('but',$but);


            $seoWord['title']       = $info['title'];
            $seoWord['keywords']    = $info['keyword'];
            $seoWord['description'] = $info['description'];
            $this->assign('seoWord',$seoWord);

            $this->display('news1');
        }
    }
//    public function video(){
//    	$id=I('get.id');
//    	if($id){
//    		$video2=M('video')->where('id='.$id)->find();
//    		$this->assign('video2',$video2);
//    	}
//
//    	$video_all=M('video')->where('is_show=1')->select();
//    	$this->assign('video_all',$video_all);
//    	$this->display();
//    }
    public function about(){
    	$but=M('but')->where('is_show=1')->select();
    	$this->assign('but',$but);
    	$this->display();
    }
    public function circuit(){
    	$but=M('but')->where('is_show=1')->select();
    	$this->assign('but',$but);
    	$this->display();
    }
    public function contact(){
    	$but=M('but')->where('is_show=1')->select();
    	$this->assign('but',$but);
    	$this->display();
    }
//    public function news1(){
//    	$id=I('get.id');
//    	$info=M('news')->where('id='.$id)->find();
//    	$this->assign('info',$info);
//    	$but=M('but')->where('is_show=1')->select();
//    	$this->assign('but',$but);
//    	$this->display();
//    }
}