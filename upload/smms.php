<?php

namespace picp\upload;

class smms implements IUploader
{
    private $url = 'https://sm.ms/api/upload';
    private $referer = 'https://sm.ms/';
    private $field = 'smfile';
    protected $allowExts = array('git', 'png', 'jpg', 'jpeg');
    protected $maxSize = 5 * 1024 * 1024;   // 5M

    public function upload($file)
    {
        $go = $this->files_valid($file);
        if ($go) {
            $curl = curl_init();
            try {
                curl_setopt($curl, CURLOPT_URL, $this->url); // 要访问的地址
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
                curl_setopt($curl, CURLOPT_REFERER, $this->referer); //模拟来路
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
                curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
                $data[$this->field] = curl_file_create($file->tmp_name, $file->type, $file->name);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
                curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
                $curl_info = curl_exec($curl); // 执行操作

                $curl_err = curl_errno($curl);
                if ($curl_err) {
                    throw new Exception($curl_err);
                }
                $res["msg"] = 'ok';
                $res["data"] = json_decode($curl_info, JSON_UNESCAPED_UNICODE);
                echo json_encode($res, JSON_UNESCAPED_UNICODE); // 返回数据，json格式
            } catch (Exception $e) {
                $res["msg"] = '图片上传失败:' . $e->getMessage();
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                die();
            } finally {
                curl_close($curl); // 关闭CURL会话
            }
        }
    }

    public function files_valid($file)
    {
        if (
            !in_array($file->ext, $this->allowExts)
            || $file->size > $this->maxSize
        ) {
            $res["msg"] = '非法的图片格式';
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        return true;
    }
}
