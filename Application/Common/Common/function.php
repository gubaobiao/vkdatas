<?php
function getpage($count, $pagesize = 10) {
    $p = new Think\Page($count, $pagesize);
    $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev', '上一页');
    $p->setConfig('next', '下一页');
    $p->setConfig('last', '末页');
    $p->setConfig('first', '首页');
    $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
    $p->lastSuffix = false;//最后一页不显示为总页数
    return $p;
}
//递归删除文件件
function deldir($path){
 $dh = opendir($path);
 while(($d = readdir($dh)) !== false){
 if($d == '.' || $d == '..'){//如果为.或..
 continue;
 }
 $tmp = $path.'/'.$d;
 if(!is_dir($tmp)){//如果为文件
 @unlink($tmp);
 }else{//如果为目录
 deldir($tmp);
 }
 }
 closedir($dh);
 rmdir($path); 
 return 1;
}

//图片压缩功能     已废弃
function gzipPic($oldPath,$newPath,$width=275,$height=155,$method='fit'){
    \Org\Tinify\Tinify::setKey('1-fd1GiweRxC_f5xyVmIB49DXRHsgk8J');
    $source = \Org\Tinify\fromFile($oldPath);
    $resized = $source->resize(array(
        "method" => "fit",
        "width" => 750,
        "height" => 420
    ));
    $resized->toFile($newPath);
}

//图片压缩
function imgCompress($path,$level=50,$height=null,$width=null,$filterDir=[]){

    if(is_dir($path)){
        $dh = opendir($path);//打开目录
        while(($dirname = readdir($dh))!= false){
            //特定文件名跳过
            if(!empty($filterDir)){
                $tempDir = array_merge($filterDir,['.','..']);
            }else{
                $tempDir = ['.','..'];
            }
            if(in_array($dirname,$tempDir)){//判断是否为.或..，默认都会有
                continue;
            }
            $dirname = iconv("GB2312","UTF-8",$dirname);
            //echo $path.'/'.$dirname."<br />";
            //中文跳过
            if (preg_match("/[\x7f-\xff]/", $dirname)) {
                //echo "中文名跳过<br />";
                continue;
            }
            $fileSize = abs(filesize($path.'/'.$dirname));
            if(is_dir($path.'/'.$dirname)){
                imgCompress($path.'/'.$dirname,$filterDir,$level);
            }elseif($fileSize > 10240){         //10K跳过


                $imgData = getimagesize($path.'/'.$dirname);
                if($imgData!==false && in_array($imgData[2],[1,3])){  //其中1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM//$extName = pathinfo($dirname, PATHINFO_EXTENSION);

                    $imagick = new Imagick($path);
                    if(!empty($width) && !empty($height)){
                        $imagick->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);
                    }
                    $imagick->setFormat('jpg');
                    $imagick->setImageCompression(\Imagick::COMPRESSION_UNDEFINED);
                    $imagick->setCompressionQuality(50);
                    $imagick->enhanceImage();
                    $imagick->writeImage($path);
                }elseif ($imgData!==false && in_array($imgData[2],[2])){
                    $Imagick = new \Think\Image(2,$path);
                    if(!empty($width) && !empty($height)){
                        $Imagick->thumb($width,$height,6);
                    }
                    $Imagick->save($path,null,$level);
                }
            }
        }
        closedir($dh);
    }else{
        $path = iconv("GB2312","UTF-8",$path);
        //echo $path."<br />";
        //中文跳过
        if (preg_match("/[\x7f-\xff]/", $path)) {
            //die('中文跳过');
            die;
        }

        $imgData = getimagesize($path);

        if($imgData!==false && in_array($imgData[2],[1,3])){  //其中1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM//$extName = pathinfo($dirname, PATHINFO_EXTENSION);

            $imagick = new Imagick($path);
            if(!empty($width) && !empty($height)){
                $imagick->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);
            }
            $imagick->setFormat('jpg');
            $imagick->setImageCompression(\Imagick::COMPRESSION_UNDEFINED);
            $imagick->setCompressionQuality(50);
            $imagick->enhanceImage();
            $imagick->writeImage($path);

        }elseif ($imgData!==false && in_array($imgData[2],[2])){
            $Imagick = new \Think\Image(2,$path);
            if(!empty($width) && !empty($height)){
                $Imagick->thumb($width,$height,6);
            }
            $Imagick->save($path,null,$level);

        }
    }
//获取经纬度的距离
function getDistance($lat1, $lng1, $lat2, $lng2)
{

    //将角度转为狐度

    $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度

    $radLat2=deg2rad($lat2);

    $radLng1=deg2rad($lng1);

    $radLng2=deg2rad($lng2);

    $a=$radLat1-$radLat2;

    $b=$radLng1-$radLng2;

    $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;

    return $s;

}
}