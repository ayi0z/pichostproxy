<?php

namespace picp;

// header("content-type:image/jpeg;charset=utf-8");
require_once __DIR__ . '/loader.php';
var_dump($_GET['url']);
if(isset($_GET['url'])){
    $url = $_GET['url'];
    $download = new Download();
    $download->download($url);
}else{
    $res["msg"] = '缺少url参数';
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    die();
}

class Download
{
    public function download($url)
    {
        var_dump($url);
        $curl = curl_init();
        try {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
            curl_setopt($curl, CURLOPT_HEADER, 0);  // 丢掉头信息，这里只需要内容
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);  // 解决301错误
            $curl_data = curl_exec($curl);
            $curl_err = curl_errno($curl);
            var_dump($curl_err);
            if ($curl_err) {
                throw new Exception($curl_err);
            }
            var_dump($curl_data);
            echo $curl_data;
        } catch (Exception $e) {
            $res["msg"] = ' 图片读取失败:' . $e->getMessage();
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        } finally {
            curl_close($curl);
        }
    }
}
