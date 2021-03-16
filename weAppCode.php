<?php
$appId='###appId###';
$weapp_secret = '###weapp_secret###';
header('Access-Control-Allow-Origin:*');
//header('Content-type: image/jpg');
$page=$_GET['page'];
function curl_get($url,$callBack,$page){
    $header = array(
        'Accept: application/json',
     );
     $curl = curl_init();
     //设置抓取的url
     curl_setopt($curl, CURLOPT_URL, $url);
     //设置头文件的信息作为数据流输出
     curl_setopt($curl, CURLOPT_HEADER, 0);
     // 超时设置,以秒为单位
     curl_setopt($curl, CURLOPT_TIMEOUT, 1);
  
     // 超时设置，以毫秒为单位
     // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);
  
     // 设置请求头
     curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
     //设置获取的信息以文件流的形式返回，而不是直接输出。
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
     //执行命令
     $data = curl_exec($curl);
  
     // 显示错误信息
     if (curl_error($curl)) {
         print "Error: " . curl_error($curl);
     } else {
         // 打印返回的内容
         $callBack($data,$page);
         curl_close($curl);
     }
 }

 // $url 是请求的链接
// $postdata 是传输的数据，数组格式
function curl_post( $url, $postdata,$callBack,$page) {
 
    $header = array(
         'Accept: application/json' 
     );
  
     //初始化
     $curl = curl_init();
     //设置抓取的url
     curl_setopt($curl, CURLOPT_URL, $url);
     //设置头文件的信息作为数据流输出
     curl_setopt($curl, CURLOPT_HEADER, 0);
     //设置获取的信息以文件流的形式返回，而不是直接输出。
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     // 超时设置
     curl_setopt($curl, CURLOPT_TIMEOUT, 10);
  
     // 超时设置，以毫秒为单位
     // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);
  
     // 设置请求头
     curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  
     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE );
     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE );
  
     //设置post方式提交
     curl_setopt($curl, CURLOPT_POST, 1);
     curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
     //执行命令
     $data = curl_exec($curl);
  
     // 显示错误信息
     if (curl_error($curl)) {
         print "Error: " . curl_error($curl);
     } else {
         // 打印返回的内容
         $callBack($data,$page);
         curl_close($curl);
     }
 }

   function post2($url, $data){//file_get_content
    $postdata = http_build_query($data);
    $opts = array('http' => 
        array( 'method'  => 'POST','header'  => 'Content-type: application/json', 'content' => $postdata ) );
    $context = stream_context_create($opts);
    $result = file_get_contents($url, false, $context);
    var_dump($result);
    return $result;
}  
function saveAsImage($data, $path,$filename)
    {
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }
        file_put_contents($path.$filename,$data);
        return $path.$filename;
        
    }
 

curl_get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$weapp_secret,function($data,$page){
    $result = json_decode($data,true);
    $token = $result['access_token'];
    curl_post('https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.$token,
   json_encode(array("path"=>$page),JSON_UNESCAPED_SLASHES),
    function($data,$page){
      if($data){
          //写文件 /pages/receipt/receipt?id=11111.jpg  
          $p = explode('?',$page);
          $path=$p[0];
          $filename=explode('=',$p[1]);
          $imgpath = '###imgpath###'.saveAsImage($data,'tmsimg/'. $path.'/', $filename[0].'_'.$filename[1].'.jpg');
          echo json_encode(array("status"=>1,"data"=>"$imgpath"),JSON_UNESCAPED_SLASHES);
      }
    },$page );
  /*   $a = post2("https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=".$token,
    array('scene'=>'dz','page'=>'pages/index/index'));
    echo($a); */


},$page)


?>