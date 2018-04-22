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
    	$video=M('video')->where(time() . '>=timing AND is_show=1')->limit(0,8)->order('createtime desc')->select();
        //echo   M('video')->getLastsql();die();
        foreach($video as $k=>$v){
            if(strlen($v['title'])>54){
                $v['title'] = substr($v['title'],0,42).'...';
            }
            $video[$k] = $v;
        }
        $this->assign('video',$video);

    	//动画街简介
    	$intro=M('intro')->find();
    	$this->assign('intro',$intro);

    	//文章中心
    	$news=M('news')->where(time() . '>= timer AND is_show=1')->order('addtime desc')->limit(3)->select();
		//var_dump($news);die();
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
    /*start冒泡排序*/
    function bubbleSort($arr){  
        $len=count($arr);
        //该层循环控制 需要冒泡的轮数
        for($i=1;$i<$len;$i++){ //该层循环用来控制每轮 冒出一个数 需要比较的次数
            for($k=0;$k<$len-$i;$k++){
               if($arr[$k]<$arr[$k+1]){
                    $tmp=$arr[$k+1];
                    $arr[$k+1]=$arr[$k];
                    $arr[$k]=$tmp;
                }
            }
        }
        return $arr;
    }
    /*end冒泡排序*/
    public function anli(){
        $id=I('get.id');
        if(empty($id)) {

            $where['is_show']=1;
            $where['timing'] = ['ELT', time()];
            $typ=I('get.type');
            if($typ){
                $where['type']=$typ;
            }
            $video=M('video')->where($where)->order('id desc')->select();
            $this->assign('video',$video);
            //$type=M('video_type')->alias('vt')->field('vt.id,vt.type_name')->select();
            //遍历出所有动画类型
            $type_kind = M('video_type')->field('id')->select();
            foreach($type_kind as $kind){
                $where['type'] = $kind['id'];
                $newest[] = M('video')->field('id')->where($where)->order('id desc')->find();
            }
            foreach($newest as $v){
                foreach($v as $v_id){
                    $v_ids[] = $v_id;
                }
            }
            $v_ids = $this->bubbleSort($v_ids);
            //依次从数据库取出动画类别type
            $len = count($v_ids);
            for($i=0;$i<$len;$i++){
                $cdt['v.id'] = $v_ids[$i];
                $type[] = M('video')->alias('v')->join('left join dhj_video_type as vt on v.type = vt.id')->where($cdt)->field('vt.id,vt.type_name')->find();
            }
            //dump($type);

            $this->assign('type',$type);
            $but=M('but')->where('is_show=1')->select();
            $this->assign('but',$but);

            $seoWord['title']       = '动画制作案例-MG动画作品-flash动画制作脚本-创意MG视频短片-创意广告动画设计案例';
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
                $seo = [];
                if ($video2['type'] == 22) {
                    $seo['title'] = '原创设计型-原创动画-杭州动画制作-杭州动画公司-动画街';
                    $seo['keywords'] = ['原创动画','杭州动画制','杭州动画公司','杭州flash动画','杭州三维动画', '杭州二维动画制作','杭州MG动画','动画街'];
                    $seo['description'] = '原创设计型动画，包括了原创动画、杭州flash动画、杭州三维动画、杭州二维动画、杭州MG动画等动画案例，动画街影视制作公司，专业的杭州动画公司，专业策划提供一流的MG动画制作服务';
                } elseif ($video2['type'] == 23) {
                    $seo['title'] = '创意app动画-创意动画-flash动画创意设计-杭州mg动画-动画街';
                    $seo['keywords'] = ['创意动画','flash动画创意设计','flash动画创意设计','杭州mg动画','杭州动画制作','动画案例','杭州的动画公司','动画街'];
                    $seo['description'] = '创意app动画，包括了创意动画、flash动画创意设计、lash动画创意设计、杭州mg动画等动画案例，动画街影视制作公司，专业的杭州动画公司，专业策划提供一流的flash动画制作服务';
                } elseif ($video2['type'] == 24) {
                    $seo['title'] = '手绘动画-手绘二维动画-杭州动画-杭州动画制作公司-动画街';
                    $seo['keywords'] = ['手绘动画','手绘二维动画','杭州动画','杭州动画制作公司','杭州二维动画制作','杭州三维动画制作','动画案例','动画街'];
                    $seo['description'] = '手绘动画，包括了手绘动画,、手绘二维动画、杭州二维动画制、杭州三维动画制作等动画案例，动画街影视制作公司，专业的杭州动画制作公司，专业策划提供一流的三维动画制作服务';
                } elseif ($video2['type'] == 25) {
                    $seo['title'] = '企业动画-动画宣传片-企业宣传动画-杭州三维动画公司-动画街';
                    $seo['keywords'] = ['企业动画','动画宣传片','企业宣传动画','杭州三维动画公司','杭州flash动画','杭州mg动画','动画案例','动画街'];
                    $seo['description'] = '企业动画，包括了动画宣传片,、企业宣传动画、杭州三维动画公司、杭州flash动画、杭州mg动画等动画案例，动画街影视制作公司，专业的杭州三维动画公司，专业策划提供一流的mg动画制作服务';
                }

                if (!empty($seo['keywords'])) {
                    $excludeIndex = [];
                    while(count($excludeIndex) < 3) {
                        $temp = rand(0,7);
                        if (!in_array($temp,$excludeIndex))
                            array_push($excludeIndex,$temp);
                    }
                    foreach ($excludeIndex as $key => $value) {
                        unset($seo['keywords'][$value]);
                    }
                    $seo['keywords'] = implode(',',$seo['keywords']);
                }
                $seo['title']='动画街';
                $this->assign('seo',$seo);
            }
          // dump($seo);
            $video_all=M('video')->where('is_show=1')->select();
           
            $this->assign('video_all',$video_all);

            $seoWord['title']       = $video2['title'];
            $seoWord['keywords']    = $video2['keyword'];
            $seoWord['description'] = $video2['description'];
            $this->assign('seoWord',$seoWord);

            $description = mb_strlen($seoWord['description'], 'UTF8') >= 15 ? $seoWord['description'] : $seo['description'];
            $this->assign('description',$description);

            $this->display('video');
        }
    }
    public function news(){
        $id=I('get.id');
        if(empty($id)) {
            $count =M('news')->where('is_show=1 and timer <='.time())->count();
            $Page=new \Org\Util\Page($count,12);
            $Page->config['theme']='%upPage% %first%  %prePage%  %linkPage%  %nextPage% %downPage%  %end%';
            $show=$Page->show();
            $news=M('news')->where('is_show=1 and timer <='.time())->order('timer desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            foreach ($news as $key => $v) {
                // $news[$key]['content']=strip_tags($v['content']);
                $news[$key]['content']=mb_substr(strip_tags($v['content']),0,200,'utf-8');
            }
            
             //dump($news);
             foreach ($news as $k => $v) {
                        $news[$k]['addtime']=$v['timer']!=0?date('Y-m-d',$v['timer']):$v['addtime'];
             }
            $this->assign('news',$news);
            $this->assign('page',$show);
            $this->display();
        } else {
            $info=M('news')->where('id='.$id)->find();
            $info['addtime']= $info['timer']!=0?date('Y-m-d', $info['timer']): $info['addtime'];
            $this->assign('info',$info);
            $but=M('but')->where('is_show=1')->select();
            $this->assign('but',$but);

            $keywords = ['杭州动画街','产品演示动画','二维动画','创意广告动画','杭州flash动画设计','品牌动画制作'];
            $excludeIndex = rand(0,5);
            unset($keywords[$excludeIndex]);
            $seo['keywords'] = implode(',',$keywords);

            $seoWord['title']       = $info['title'];
            $seoWord['keywords']    = implode(',',$keywords);
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