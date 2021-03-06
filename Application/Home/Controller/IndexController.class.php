<?php
namespace Home\Controller;
use Think\Controller;
use Think\Image;

class IndexController extends Controller {
    
    public function _initialize(){
    	//轮播图展示
    	$res=M('banner')->where('is_show=1')->order('rank asc')->select();
    	$this->assign('res',$res);

    	//服务介绍
    	$service=M('service')->where('is_show=1')->select();
    	$this->assign('service',$service);

    	//视频案例
    	/*$video=M('video')->where(time() . '>=timing AND is_show=1')->limit(0,8)->order('createtime desc')->select();
        //echo   M('video')->getLastsql();die();
        foreach($video as $k=>$v){
            if(strlen($v['title'])>54){
                $v['title'] = substr($v['title'],0,42).'...';
            }
            $video[$k] = $v;
        }
        $this->assign('video',$video);*/

    	//动画街简介
    	$intro=M('intro')->find();
    	$this->assign('intro',$intro);

    	//文章中心
    	$news=M('news')->where(time() . '>= timer AND is_show=1')->order('addtime desc')->limit(3)->select();
		//dump($news);die();
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
        $videoid='249,248,243,246,245,159,252,224';
        $wheres['id'] =array('in', $videoid);
        $videot=M('video')->where($wheres)->select(); 
        foreach($videot as $k=>$v){
            if(strlen($v['title'])>54){
                $v['title'] = substr($v['title'],0,42).'...';
            }
            $videot[$k] = $v;
        }
        $this->assign('videot',$videot);
        $this->display();
    }

    public function test(){
        $count =M('news')->where(time() . '>= timer AND is_show=1')->count();

        $Page=new \Org\Util\Page($count,12);
        $Page->config['theme']='%upPage% %first%  %prePage%  %linkPage%  %nextPage% %downPage%  %end%';
        $show=$Page->show();
        //$news=M('news')->where('is_show=1 and timer <='.time())->order('timer desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $news=M('news')->where(time() . '>= timer AND is_show=1')->order('addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($news as $key => $v) {
            // $news[$key]['content']=strip_tags($v['content']);
            $news[$key]['content']=mb_substr(strip_tags($v['content']),0,200,'utf-8');
        }

        //dump($news);
        foreach ($news as $k => $v) {
            $news[$k]['addtime']=$v['timer']!=0?date('Y-m-d',$v['timer']):$v['addtime'];
        }

        echo '<pre>';
        print_r($news);die;
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
            header("Content-type: text/html; charset=utf-8");
            //dump($video);die();
            $contentvideo =count($video);
            $pages=20;
            $sumpage=ceil($contentvideo/$pages);
            /*dump($sumpage);
            dump($typ);*/
            $this->assign('typ',$typ);
            $this->assign('sumpage',$sumpage);
            $this->assign('id',$id);
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

            $seoWord['title']       = '动画作品案例-科普教育动画-app产品动画-品牌宣传动画-商业动画制作';
            $seoWord['keywords']    = '动画作品案例,科普教育动画,app产品动画,品牌宣传动画,商业动画制作,政府动画制作,工业动画制作,情H5动画制作';
            $seoWord['description'] = '动画作品案例中有科普教育、app/产品、品牌宣传、H5/无线等多种类型的动画作品案例。一流的动画制作团队，制作出品多部优秀的MG动画、二维动画、三维动画等案例，专业策划提供一流的动画制作服务。';
            $this->assign('seoWord',$seoWord);

            //seo
            if ($typ ==22){
                $seoWord['title'] = '品牌宣传-企业动画宣传片-品牌动漫宣传片-品牌宣传动画-品牌宣传三维动画';
                $seoWord['keywords'] = '企业动画宣传片,品牌动漫宣传片,品牌宣传动画,品牌宣传三维动画,动画宣传片制作,品牌宣传动画制作,MG动画制作,二维动画制作';
                $seoWord['description'] = '品牌宣传动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }

            if ($typ ==23){
                $seoWord['title'] = '科普教育-app动画宣传片-产品动画制作-产品动画宣传片-app动画制作';
                $seoWord['keywords'] = 'app动画宣传片,产品动画,产品动画宣传片,app动画制作,app动画宣传视频,产品动画,产品三维动画,产品宣传动画制作';
                $seoWord['description'] = 'app动画和产品动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }

            if ($typ ==24){
                $seoWord['title'] = '科普教育-科普教育动画-教育动画宣传片-科普知识动画-教育动画宣传视频';
                $seoWord['keywords'] = '科普教育动画,教育动画宣传片,科普知识动画,教育动画宣传视频,教育动画制作,教育类动画,教育动画视频,创意动画制作';
                $seoWord['description'] = '科普类动画和教育类动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }

            if ($typ ==25){
                $seoWord['title'] = '政府报告-政府动画宣传片-政府工作报告动画-政府创意动画-杭州动画公司';
                $seoWord['keywords'] = '政府动画宣传片,政府工作报告动画,政府创意动画,动画公司,MG动画,政府动画制作,产品动画制作';
                $seoWord['description'] = '政府报告类动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }
            if ($typ ==26){
                $seoWord['title'] = 'H5无线-H5动画-无线端动画-无线动画视频制作-H5动画制作';
                $seoWord['keywords'] = 'H5动画,无线端动画,无线动画视频制作,H5动画制作,H5页面动画,三维动画制作,H5创意动画,flash动画制作';
                $seoWord['description'] = 'H5动画和无线端动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }
            if ($typ ==27){
                $seoWord['title'] = '流程演示-流程动画制作-演示动画制作-工程动画制作-工业动画制作';
                $seoWord['keywords'] = '流程动画制作,演示动画制作,工程动画制作,工业动画制作,建筑动画制作,动画演示制作,机械三维动画,杭州动画公司';
                $seoWord['description'] = '流程演示动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }
            if ($typ ==28){
                $seoWord['title'] = '商业插画-商业动画制作-商业插画制作-商业插画设计-广告商业插画';
                $seoWord['keywords'] = '商业动画制作,商业插画制作,商业插画设计,广告商业插画,商业动画宣传片,商业宣传动画,金融动画';
                $seoWord['description'] = '商业插画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }
            if ($typ ==29){
                $seoWord['title'] = '叙事剧情-叙事动画-剧情动画-情景动画制作-动画制作';
                $seoWord['keywords'] = '叙事动画,剧情动画,情景动画制作,动画制作,MG动画制作,FLASH动画制作,动画宣传片制作,动画制作报价';
                $seoWord['description'] = '叙事动画和剧情动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                $this->assign('seoWord',$seoWord);
            }
            $this->display();
        } else {

            if($id){

                $video2=M('video')->where('id='.$id)->find();

                //标签属性过滤
                //file_put_contents('D:\ftp\pc_video_before.txt',$video2['content']);
                $pattern = '/<([^ai][a-z]*?)\s+?.*?>/i';
                $video2['content'] = preg_replace($pattern,'<$1>',$video2['content']);
//                $pattern = '/<img\s(onload=.*")\s(src="[^\s]*")\s(title="[^\s]*")\s(alt=".*")>/i';
//                $video2['content'] = preg_replace($pattern,'<img $1 $2 $3 />',$video2['content']);

                $pattern = '/<img [^>]*(src=\"[^\s]*\")[^>]*(title=\"[^\s]*\")[^>]*(alt=\"(.+?)\")[^>]*\/>/i';
                $video2['content'] = preg_replace($pattern,'<img  $1 $2 $3 />',$video2['content']);
                //file_put_contents('D:\ftp\pc_video.txt',$video2['content']);

                if(!empty($video2)){
                    //判断时间
                    $video2['timing'] = $video2['timing']== 0 ? strtotime($video2['create_time']):$video2['timing'];
                    $nowStamp = time();
                    $extStamp = $nowStamp - $video2['timing'];
                    if ($extStamp <= 24*3600 && $extStamp >= 3600) {
                        $video2['timing'] = floor($extStamp/3600).'小时前';
                    }elseif ($extStamp > 24*3600 && $extStamp <= 24*3600*7){
                        $video2['timing'] = floor($extStamp/24/3600).'天前';
                    }elseif ($extStamp > 24*3600*7){
                        $video2['timing'] = date('Y年m月d日',$video2['timing']);
                    }elseif ($extStamp < 3600){
                        $video2['timing'] = floor($extStamp/60).'分钟前';
                    }
                    //类型名
                    $result = M('video_type')->where(['id'=>$video2['type']])->find();
                    $video2['type_name'] = $result['type_name'];
                }


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
            $this->assign('id',$id);
            $this->display('video');
        }
    }

    public function voideajax(){
            //header('Content-Type:application/json; charset=utf-8');  
            $pagenum=$_GET['pagenum'];//第几页
            $typ    =$_GET['type'];
            $where['is_show']=1;
            $where['timing'] = ['ELT', time()];
            if($typ){
                $where['type']=$typ;
            }
            $video=M('video')->field('id,title,sltpath,swf,createtime,timing')->where($where)->page($pagenum,20)->order('id desc')->select();
           
            foreach ($video as $k => $v) {
                     if($v['timing']==0){
                        $video[$k]['createtime'] =$v['createtime'];
                     }else{
                        $video[$k]['createtime'] =date("Y-m-d",$v['timing']);
                     }
                     $host="http://www.donghuajie.com";
                     $video[$k]['web'] =$host.'/anli/'.$v['id'].'.html';
                     $video[$k]['img-src'] =$video[$k]['sltpath'];
                     //unset($video[$k]['createtime']);
                     unset($video[$k]['timing']);
            }
            $vides['searchData']=$video;
            //dump($vides);die();
            $this->ajaxReturn($vides);
    }


    public function news(){

        $id=I('get.id');
        if(empty($id)) {
            $count =M('news')->where(time() . '>= timer AND is_show=1')->count();
            $Page=new \Org\Util\Page($count,12);
            $Page->config['theme']='%upPage% %first%  %prePage%  %linkPage%  %nextPage% %downPage%  %end%';
            $show=$Page->show();

            $news=M('news')->where(time() . '>= timer AND is_show=1')->order('timer desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            foreach ($news as $key => $v) {
                $news[$key]['content']=mb_substr(strip_tags($v['content']),0,200,'utf-8');
            }

            foreach ($news as $k => $v) {
                    $news[$k]['addtime']=$v['timer']!=0?date('Y-m-d',$v['timer']):$v['addtime'];
            }
            $seoWord['title']       = '最新动态-动画宣传片-动画制作公司-二维动画制作三维动画制作';
            $seoWord['keywords']    = '杭州动画宣传片,动画制作公司,创意动画,产品动画制作,杭州动漫设计,影视动画,二维动画制作,杭州三维动画制作';
            $seoWord['description'] = '最新动态分享动画制作公司的动画宣传片、创意动画、产品 动画、三维动画制作、二维动画等等动画制作技巧以及最新行业动态。';

            $this->assign('seoWord',$seoWord);
            $this->assign('news',$news);
            $this->assign('page',$show);
            $this->display();
        } else {
            $info=M('news')->where('id='.$id)->find();
            $info['timer'] = $info['timer']== 0 ? strtotime($info['addtime']):$info['timer'];

            $nowStamp = time();
            $extStamp = $nowStamp - $info['timer'];
            if ($extStamp <= 24*3600 && $extStamp >= 3600) {
                $info['timer'] = floor($extStamp/3600).'小时前';
            }elseif ($extStamp > 24*3600 && $extStamp <= 24*3600*7){
                $info['timer'] = floor($extStamp/24/3600).'天前';
            }elseif ($extStamp > 24*3600*7){
                $info['timer'] = date('Y年m月d日',$info['timer']);
            }elseif ($extStamp < 3600){
                $info['timer'] = floor($extStamp/60).'分钟前';
            }
            //类型名
            $result = M('news_type')->where(['id'=>$info['type']])->find();
            $info['type_name'] = $result['type_name'];
//            echo '<pre>';
//            print_r($info['content']);die;

//            file_put_contents('D:\ftp\pc_news_before.txt',$info['content']);
            //标签属性过滤
            $pattern = '/<([^ai][a-z]*?)\s+?.*?>/i';
            $info['content'] = preg_replace($pattern,'<$1>',$info['content']);

            $pattern = '/<img [^>]*(src=\"[^\s]*\")[^>]*(title=\"[^\s]*\")[^>]*(alt=\"(.+?)\")[^>]*\/>/i';
            $info['content'] = preg_replace($pattern,'<img  $1 $2 $3 />',$info['content']);

//            file_put_contents('D:\ftp\pc_news.txt',$info['content']);
            $this->assign('info',$info);

            //相关推荐
            $similar =M('news')->where(['type'=>$info['type']])->limit(3)->select();
            foreach ($similar as $k=>$v){
                //$similar[$k]['description'] = mb_substr($similar[$k]['description'],0,10,'utf-8');

                $similar[$k]['timer'] =  $similar[$k]['timer']== 0 ? strtotime( $similar[$k]['addtime']): $similar[$k]['timer'];
                $nowStamp = time();
                $extStamp = $nowStamp -  $similar[$k]['timer'];
                if ($extStamp <= 24*3600 && $extStamp >= 3600) {
                    $similar[$k]['timer'] = floor($extStamp/3600).'小时前';
                }elseif ($extStamp > 24*3600 && $extStamp <= 24*3600*7){
                    $similar[$k]['timer'] = floor($extStamp/24/3600).'天前';
                }elseif ($extStamp > 24*3600*7){
                    $similar[$k]['timer'] = date('Y/m/d',$similar[$k]['timer']);
                }elseif ($extStamp < 3600){
                    $similar[$k]['timer'] = floor($extStamp/60).'分钟前';
                }
            }
            $this->assign('similar',$similar);

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
    /**jz search 开始***/
     public function Search(){
       $data=I('get.');
       $search=$data['search'];
       $where['title']   =array('like',"%$search%");
       $where['is_show'] =1;
       $where['timing'] =array('lt',time());
       $videolistone=M('video')
                   ->field('id,title,sltpath,createtime')
                   ->where($where)
                   ->order('timing desc')
                   ->count();
                   $sumpage=ceil($videolistone/20);
                   $this->assign('maxPage',$sumpage);
                   $this->assign('search',$search);
        $this->display();
     }

    public function ajaxSearchData(){
       $data=I('get.');
       $page=$data['indexPage'];
       $search=$data['search'];

       $where['title']   =array('like',"%$search%");
       $where['is_show'] =1;
       $where['timing'] =array('lt',time());
       $videolistone=M('video')
                   ->field('id,title,sltpath,createtime')
                   ->where($where)
                   ->order('timing desc')
                   ->select();

        $count =count($videolistone);
        $result=M('video')
                   ->field('id,title,sltpath,createtime')
                   ->where($where)
                   ->order('timing desc')
                   ->page($page,20)
                   ->order('timing desc')
                   ->select();
        //echo M('video')->getLastsql();
                   foreach ($result as $k => $v) {
                       $searchData['searchData'][$k]=$v;
                       $searchData['searchData'][$k]['web'] ="/anli/$v[id].html";
                       $searchData['searchData'][$k]['img-src'] =$v['sltpath'];

                   }
       //$result['count']=$count;
       $this->ajaxReturn($searchData);
    }
    /**jz search 结束***/


    public function imgCompress(){

        $req = I('param.');
        set_time_limit(0);
        $path = $_SERVER['DOCUMENT_ROOT'].'/Public/pic/00ce121711b0e7b9cbdce75d16b4d979.jpg';
        $level = null;
        $height = null;
        $width = null;
        $filterDir = [];
        //$path = $_SERVER['DOCUMENT_ROOT'].'/upload';
        if(!empty($req)){
            $path = $req['path'];
            $level = $req['level'];
            $height = $req['height'];//420
            $width = $req['width'];//750
        }

        imgCompress($path,70,$height,$width,[]);
    }


    //关键词检测接口
    public function badwordsCheck(){

        //参数验证
        if(!isset($_POST['wordstr'])){
            $data['status'] = 1001;
            $data['msg'] = '参数错误';
            $data['data'] = [];
            header("Access-Control-Allow-Origin: *");
            echo json_encode($data);die;
        }

        //获取敏感词字典
        $badword =  M('word_filter')->where(['is_enable'=>1])->select();

        //比对
        $tempArr = [];
        foreach ($badword as $key=>$value){
            $position = stripos($_POST['wordstr'],$value['word']);
            if($position !== false){
                $tempArr[] = $value['word'];
                M('word_filter')->where(['id'=>$value['id']])->setInc('frequency',1);
            }
        }

        //反馈
        if(count($tempArr) == 0){
            $data['status'] = 0;
            $data['msg'] = 'ok';
            $data['data']['des'] = '未含有敏感词';
            $data['data']['code'] = 'A001';
            $data['data']['word'] = [];
            header("Access-Control-Allow-Origin: *");
            echo json_encode($data);die;
        }else{
            $data['status'] = 0;
            $data['msg'] = 'ok';
            $data['data']['des'] = '含有敏感词';
            $data['data']['code'] = 'A002';
            $data['data']['word'] = $tempArr;
            $data['data']['wordstr'] = implode('、',$tempArr);
            header("Access-Control-Allow-Origin: *");
            echo json_encode($data);die;
        }
    }

    //关键词过滤接口
    public function badwordsFilter(){

        //参数验证
        if(!isset($_POST['wordstr'])){
            $data['status'] = 1001;
            $data['msg'] = '参数错误';
            $data['data'] = [];
            header("Access-Control-Allow-Origin: *");
            echo json_encode($data);die;
        }

        $badword =  M('word_filter')->where(['is_enable'=>1])->getField('word',true);
        $badword1 =array_combine($badword,array_fill(0,count($badword),''));

        $tempstr = strtr($_POST['wordstr'],$badword1);
        $data['status'] = 0;
        $data['msg'] = 'ok';
        $data['data']['str'] = $tempstr;

        header("Access-Control-Allow-Origin: *");
        echo json_encode($data);die;
    }

    //关键词过滤方法
    private function badwordsFilterLocal($wordstr){

        $badword =  M('word_filter')->where(['is_enable'=>1])->getField('word',true);
        $badword1 =array_combine($badword,array_fill(0,count($badword),''));

        $tempstr = strtr($wordstr,$badword1);

        return $tempstr;
    }
   //网站地图
    public function map(){
        //echo "string";die;
        $this->display();
    }

   //视频点击量
    public function videoclick(){
        $vid = (int)$_POST['id'];
        $wherev['id']=$vid;
        $res =M('video')->where($wherev)->setInc('click',1); // 用户的视频点击加1 ,click默认值为0
        if($res){
             $resdata =M('video')->where($wherev)->field('click')->find(); // 用户的视频点击加1 ,click默认值为0
             $return['data']=$resdata['click'];
             $return['status'] =1;
        }else{
            $return['status'] =0;
        }
        $this->ajaxReturn($return);
    }
}

