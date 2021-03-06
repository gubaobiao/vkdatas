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
function getdistance($lat1, $lng1, $lat2, $lng2)
{

    //将角度转为狐度

    $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度

    $radLat2=deg2rad($lat2);

    $radLng1=deg2rad($lng1);

    $radLng2=deg2rad($lng2);

    $a=$radLat1-$radLat2;

    $b=$radLng1-$radLng2;

    $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;

    return round($s,2);

}
//base64图片存取
function baseImg($img)
{
            $imgdata=$img;
            $path='./uploadfile/User/video/';
            $paths='https://v.gubaobiao.cn/uploadfile/User/video/';
            //转换文件路径编码
            if(!file_exists($path)){  
            mkdir(iconv('utf-8', 'gbk', "$path"),0777,true);  
            }
            $imgDir =$path;
            $img = str_replace('data:image/jpeg;base64,', '', $imgdata);
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $img = base64_decode($img);
            $filename = md5(time().mt_rand(10, 99)).".jpg"; //新图片名称
            $newFilePath =$path.$filename;
            $newFile = fopen(iconv("UTF-8","gbk",$newFilePath),"a+"); //打开文件准备写入
             //写入二进制流到文件
            fwrite($newFile,$img);
            fclose($newFile); //关闭文件
            $data['newFilePath']=$paths.$filename;
            return $data;
}
