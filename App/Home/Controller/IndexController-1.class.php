<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    //初始化首页数据
    public function _initialize()
    {
        /*  轮播图展示  */
        $res=M('banner')->where('is_show=1')->order('rank asc')->select();
        $this->assign('res',$res);

        /*  服务介绍  */
        $service=M('service')->where('is_show=1')->select();
        $this->assign('service',$service);

        /*  获取所有动画类型信息  */
        $typeIds = M('video_type')->field(['id','type_name','img_path'])->select();
        $this->assign('videoTypes',$typeIds);

        /*  获取不同类型的展示案例  */
        $videoModel = M('video');
        //构建查询条件
        $condition['is_show'] = 1;
        $condition['timing'] = ['elt',time()];
        //循环取值
        $anli = [];
        foreach ($typeIds as $key=>$value) {
            $condition['type'] = $value['id'];
            $anli[$value['id']] = $videoModel
                ->field(['id','title','type','ytpath','sltpath','mp4','swf','see_path','createtime'])
                ->where($condition)
                ->limit(0,8)
                ->order(['createtime'=>'desc'])
                ->select();
            $count = count($anli[$value['id']]);
            for ($i=0;$i<$count;$i++){
                $pubtime= $anli[$value['id']][$i]['timing']!=0?$anli[$value['id']][$i]['timing']: strtotime($anli[$value['id']][$i]['createtime']);
                $anli[$value['id']][$i]['pubday']=floor((time()-$pubtime)/24/60/60);
            }
        }
        $this->assign('anli',$anli);


        /*  最新动态  */
        $news=M('news')->where(time() . '>= timer AND is_show=1')->order('addtime desc')->limit(3)->select();
        foreach ($news as $key => $v) {
            $pubtime= $news[$key]['timer']!=0?$news[$key]['timer']: strtotime($news[$key]['addtime']);
            $news[$key]['pubday']=floor((time()-$pubtime)/24/60/60);
        }

        $this->assign('news',$news);


        /*  联系我们  */
        $g=M('contact')->field('id')->select();
        $a=M('contact')->where('id='.$g[0]['id'])->field('connect')->find();
        $n=json_decode($a['connect'],true);
        $this->assign('contact',$n);

        $domain = "http://{$_SERVER['HTTP_HOST']}";
        $this->assign('domain', $domain);
    }

    /*
     * func：首页数据渲染
     * author:北海
     */
    public function index()
    {
        $seoWord = [];
        $seoWord = $this->setSeoWord($seoWord,1);
        $seoWord['keywords'] = implode(',',$seoWord['keywords']);
        $this->assign('seoWord',$seoWord);
        $this->display();
    }

    /*
     * func：获取展示最新动态列表或详情
     * author:北海
     */
    public function news()
    {
        $id=I('get.id');
        if(empty($id)) {
            $count =M('news')->where('is_show=1 and timer <='.time())->count();
            $Page=new \Org\Util\Page($count,12);
            $Page->config['theme']='%upPage% %first%  %prePage%  %linkPage%  %nextPage% %downPage%  %end%';
            $show=$Page->show();
            $news=M('news')->where('is_show=1 and timer <='.time())->order('timer desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            //数据处理
            foreach ($news as $key => $v) {
                $news[$key]['content']=mb_substr(strip_tags($v['content']),0,200,'utf-8').'......';
                $news[$key]['pubday']=floor((time()-$news[$key]['timer'])/24/60/60);
            }

            $seoWord = [];
            $seoWord = $this->setSeoWord($seoWord,3);
            $seoWord['keywords'] = implode(',',$seoWord['keywords']);
            $this->assign('seoWord',$seoWord);

            //输出
            $this->assign('news',$news);
            $this->assign('page',$show);
            $this->display();
        } else {
            $info=M('news')->where('id='.$id)->find();
            $info['addtime']= $info['timer']!=0?$info['timer']: strtotime($info['addtime']);
            $info['pubday']=floor((time()-$info['addtime'])/24/60/60);
            $info['content']=strip_tags($info['content'],'<img>');
            $this->assign('info',$info);

            //关键词优化
            $seoWord = [];
            $seoWord = $this->setSeoWord($seoWord,3);
            $seoWord['title']       = $info['title'];
            $randArr = array_rand($seoWord['keywords'],5);
            $tmpWord = [];
            foreach ($randArr as $key=>$value){
                $tmpWord[] = $seoWord['keywords'][$value];
            }
            $seoWord['keywords']    = $info['keyword'].','.implode(',',$tmpWord);
            $seoWord['description'] = $info['description'];
            $this->assign('seoWord',$seoWord);

            $this->display('newsdetail');
        }
    }

    /*
     * func：来撩我们页面数据渲染
     * author:北海
     */
    public function wegot()
    {
        //联系方式数据
        $g=M('contact')->field('id')->select();
        $a=M('contact')->where('id='.$g[0]['id'])->field('connect')->find();
        $n=json_decode($a['connect'],true);
        $this->assign('contact',$n);

        $seoWord = [];
        $seoWord = $this->setSeoWord($seoWord,4);
        $seoWord['keywords'] = implode(',',$seoWord['keywords']);
        $this->assign('seoWord',$seoWord);

        //print_r($n);die;
        $this->display();
    }


    /*
     * func：获取所有动画类型信息
     * author:北海
     */
    public function worker()
    {
        $typeIds = M('video_type')->field(['id','type_name','count'])->select();
        $seoWord = [];
        $seoWord = $this->setSeoWord($seoWord,2);
        $seoWord['keywords'] = implode(',',$seoWord['keywords']);

        $this->assign('seoWord',$seoWord);
        $this->assign('videoTypes',$typeIds);
        $this->display();
    }


    /*
     * func：获取展示视频案例列表或详情
     * author:北海
     */
    public function anli()
    {
        $id=I('get.id');
        if(empty($id)) {

            //构建语句
            $where['is_show']=1;
            $where['timing'] = ['ELT', time()];
            $typ=I('get.type',22);
            $where['type'] = $typ;

            //查询
            $video=M('video')->field(['id','createtime','timing','title','type','sltpath'])->where($where)->order('id desc')->select();
            $typeData = M('video_type')->where(['id'=>$typ])->find();
            $countNum = M('video')->where($where)->count();
            $total = ceil($countNum/5);

            //数据处理
            foreach ($video as $key=>$value){
                $pubtime= $video[$key]['timing']!=0?$video[$key]['timing']: strtotime($video[$key]['createtime']);
                $video[$key]['pubday']=floor((time()-$pubtime)/24/60/60);
            }

            $this->assign('video',$video);
            $this->assign('total',$total);
            $this->assign('typeData',$typeData);

            //seo优化
            $seoWord = [];
            $seoWord = $this->setSeoWord($seoWord,$typ);
            $seoWord['keywords'] = implode(',',$seoWord['keywords']);
            $this->assign('seoWord',$seoWord);

            $this->display();
        } else {
            if(is_numeric($id)){
                //构建查询条件
                $sqlStr['id'] = $id;
                $sqlStr['timing'] = ['lt',time()];
                $sqlStr['is_show'] = 1;
                //获取数据
                $videoData=M('video')->where($sqlStr)->find();
                $videoData['content'] = strip_tags($videoData['content'],'<img>');
                $pubtime= $videoData['timing']!=0?$videoData['timing']: strtotime($videoData['createtime']);
                $videoData['pubday']=floor((time()-$pubtime)/24/60/60);
                $pattern = '/onload.*this\)/';
                $videoData['content'] = preg_replace($pattern,' ',$videoData['content']);

                $this->assign('video',$videoData);

                //seo关键词优化
                $seoWord = [];
                $seoWord = $this->setSeoWord($seoWord,$videoData['type']);
                $seoWord['title']       = $videoData['title'];
                //随机5关键词
                $randArr = array_rand($seoWord['keywords'],5);
                $tmpWord = [];
                foreach ($randArr as $key=>$value){
                    $tmpWord[] = $seoWord['keywords'][$value];
                }
                $seoWord['keywords']    = $videoData['keyword'].','.implode(',',$tmpWord);
                $seoWord['description'] = $videoData['description'];

                $this->assign('seoWord',$seoWord);
            }
            //推荐视频
            $sql['is_show'] = 1;
            $sql['timing'] = ['lt',time()];
            $video_all=M('video')->where($sql)->order(['id'=>'desc'])->limit(5)->select();
            foreach ($video_all as $key=>$value){
                $pubtime= $video_all[$key]['timing']!=0?$video_all[$key]['timing']: strtotime($video_all[$key]['createtime']);
                $video_all[$key]['pubday']=floor((time()-$pubtime)/24/60/60);
            }
            $this->assign('video_all',$video_all);
            // dump($videoData);
            $this->display('video');
        }
    }


    //视频下拉加载借口
    public function videolazy(){
        // dump(I('get.'));
        $pageNum = I('get.page_num');
        $pages = I('get.pages',5);
        $type = I('get.type');

        //参数判断
        if(!is_numeric($pageNum) || !is_numeric($pages) ||!is_numeric($type)){
            $data['msg'] = '非法参数';
            $data['status'] = 10000;
            $data['data'] = [];
            $data['total'] = [];
           echo json_encode($data);
        }

        //构建查询语句
        $where['type'] = $type;
        $where['timing'] = ['lt',time()];
        $where['is_show'] = 1;

        //查询
        $countNum = M('video')->where($where)->count();
        $videoData = M('video')->where($where)->page($pageNum,$pages)->order(['rank'=>'asc'])->select();
        
        if(empty($videoData)){
            $data['status'] = 1;
            echo  json_encode($data);
        }else{
            $data['msg'] = 'success';
            $data['status'] = 0;
            //数据处理
            foreach ($videoData as $key=>$value){
                $pubtime= $videoData[$key]['timing']!=0?$videoData[$key]['timing']: strtotime($videoData[$key]['createtime']);
                $videoData[$key]['pubday']=floor((time()-$pubtime)/24/60/60);
            }
            $data['data'] = $videoData;
            $data['total'] = ceil($countNum/$pages);
            echo  json_encode($data);
        }
    }

    /*
     * 设置SEO关键词
     */
    private function setSeoWord($seoWord,$num)
    {
        switch ($num){
            case 1:     //首页seo
                $seoWord['title'] = '动画制作-动画公司-二维动画-动画宣传片-动画街';
                $seoWord['keywords'] = ['动画制作','动画公司','二维动画','动画宣传片','FLASH动画','二维动画制作','MG动画','动画街'];
                $seoWord['description'] = '动画街动画制作公司，是一家专业设计MG动画制作的企业，擅长领域包括flash动画，二维动画动漫，创意动画片，产品宣传片等动画视频制作。拥有别具风格的动画制作团队，制作出品多部优秀的MG动画案例，提供专业策划案，享受一线动画制作服务。';
                break;
            case 2:     //作品案例seo
                $seoWord['title'] = '动画作品案例-科普教育动画-app产品动画-品牌宣传动画-商业动画制作';
                $seoWord['keywords'] = ['动画作品案例','科普教育动画','app产品动画','品牌宣传动画','商业动画制作','政府动画制作','工业动画制作','情H5动画制作'];
                $seoWord['description'] = '动画作品案例中有科普教育、app/产品、品牌宣传、H5/无线等多种类型的动画作品案例。一流的动画制作团队，制作出品多部优秀的MG动画、二维动画、三维动画等案例，专业策划提供一流的动画制作服务。';
                break;
            case 3:     //最新动态seo
                $seoWord['title'] = '最新动态-杭州动画宣传片-动画公司-创意动画-产品动画制作';
                $seoWord['keywords'] = ['杭州动画宣传片','动画公司','创意动画','产品动画制作','杭州动漫设计','影视动画','动画制作','杭州三维动画制作'];
                $seoWord['description'] = '最新动态分享动画公司的动画宣传片、创意动画、产品动画、影视动画、二维动画等等动画制作技巧以及最新行业动态。';
                break;
            case 4:     //来撩我们seo
                $seoWord['title'] = '来撩我们-动画街联系方式-动画街';
                $seoWord['keywords'] = ['来撩我们','动画街联系方式','动画街'];
                $seoWord['description'] = '动画街公司地址浙江省杭州市未来科技城梦想小镇，联系电话400-8656-770或1633219006。';
                break;
            case 5:     //服务流程
                $seoWord['title'] = '服务流程-动画制作流程-动画设计流程-动画视频制作流程-杭州产品动画制作报价';
                $seoWord['keywords'] = ['服务流程','动画制作流程','动画设计流程','动画视频制作流程','产品动画制作报价'];
                $seoWord['description'] = '动画街制作公司，是一家专业制作MG动画的企业，具备完善的flash动画报价标准，公司制作二维动画，创意动画案例多达1000余部，拥有别具风格的设计流程，提供一流的MG策划制作服务。';
                break;
            case 6:     //关于我们
                $seoWord['title'] = '关于我们-动画街概况-动画街企业文化-杭州动漫设计-动画设计';
                $seoWord['keywords'] = ['动画街概况','动画街企业文化','杭州动漫设计','动画设计','创意广告片制作','动画设计制作公司','杭州MG动画制作公司','动画视频制作'];
                $seoWord['description'] = '动画街影视制作公司，具备一流动画设计制作团队，专业策划制作二维动画，MG动画，产品动画，创意广告宣传片制作等，是您合作首选，设计方案与制作报价完全透明！';
                break;
            case 22:    //品牌宣传seo
                $seoWord['title'] = '品牌宣传-企业动画宣传片-品牌动漫宣传片-品牌宣传动画-品牌宣传三维动画';
                $seoWord['keywords'] = ['企业动画宣传片','品牌动漫宣传片','品牌宣传动画','品牌宣传三维动画','动画宣传片制作','品牌宣传动画制作','MG动画制作','二维动画制作'];
                $seoWord['description'] = '品牌宣传动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                break;
            case 23:   //app/产品seo
                $seoWord['title'] = '科普教育-app动画宣传片-产品动画制作-产品动画宣传片-app动画制作';
                $seoWord['keywords'] = ['app动画宣传片','产品动画','产品动画宣传片','app动画制作','app动画宣传视频','产品动画','产品三维动画','产品宣传动画制作'];
                $seoWord['description'] = 'app动画和产品动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                break;
            case 24:    //科普教育seo
                $seoWord['title'] = '科普教育-科普教育动画-教育动画宣传片-科普知识动画-教育动画宣传视频';
                $seoWord['keywords'] = ['科普教育动画','教育动画宣传片','科普知识动画','教育动画宣传视频','教育动画制作','教育类动画','教育动画视频','创意动画制作'];
                $seoWord['description'] = '科普类动画和教育类动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                break;
            case 25:    //政府、报告
                $seoWord['title'] = '政府报告-政府动画宣传片-政府工作报告动画-政府创意动画-杭州动画公司';
                $seoWord['keywords'] = ['政府动画宣传片','政府工作报告动画','政府创意动画','动画公司','MG动画','政府动画制作','产品动画制作'];
                $seoWord['description'] = '政府报告类动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                break;
            case 26:    //H5/无线
                $seoWord['title'] = 'H5无线-H5动画-无线端动画-无线动画视频制作-H5动画制作';
                $seoWord['keywords'] = ['H5动画','无线端动画','无线动画视频制作','H5动画制作','H5页面动画','三维动画制作','H5创意动画','flash动画制作'];
                $seoWord['description'] = 'H5动画和无线端动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                break;
            case 27:    //流程演示
                $seoWord['title'] = '流程演示-流程动画制作-演示动画制作-工程动画制作-工业动画制作';
                $seoWord['keywords'] = ['流程动画制作','演示动画制作','工程动画制作','工业动画制作','建筑动画制作','动画演示制作','机械三维动画','杭州动画公司'];
                $seoWord['description'] = '流程演示动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                break;
            case 28:    //商业插画
                $seoWord['title'] = '商业插画-商业动画制作-商业插画制作-商业插画设计-广告商业插画';
                $seoWord['keywords'] = ['商业动画制作','商业插画制作','商业插画设计','广告商业插画','商业动画宣传片','商业宣传动画','金融动画'];
                $seoWord['description'] = '商业插画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                break;
            case 29:    //叙事剧集
                $seoWord['title'] = '叙事剧情-叙事动画-剧情动画-情景动画制作-动画制作';
                $seoWord['keywords'] = ['叙事动画','剧情动画','情景动画制作','动画制作','MG动画制作','FLASH动画制作','动画宣传片制作','动画制作报价'];
                $seoWord['description'] = '叙事动画和剧情动画可以通过MG动画和FLASH动画等二维动画制作、三维动画制作形式展现。动画街影视制作公司，专业的动画公司，专业策划提供一流的动画制作服务。';
                break;
            default:
                $seoWord['title']       = '动画制作案例-MG动画作品-flash动画制作脚本-创意MG视频短片-创意广告动画设计案例';
                $seoWord['keywords']    = 'MG动画案例，广告动画设施案例，三维动画设计案例，创意MG视频制作案例，产品APP视频';
                $seoWord['description'] = '动画街影视制作公司，是一家专业制作MG动画的企业，专业制作MG动画，flash动画，二维动画，三维动画，创意动画视频制作。一流的MG动画制作团队，制作出品多部优秀的MG动画案例，专业策划提供一流的MG动画制作服务。';
        }
        return $seoWord;
    }

}