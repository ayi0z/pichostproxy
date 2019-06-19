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
            $data[$this->field] = curl_file_create($file->tmp_name, $file->type, $file->name);
            $curl_info = curlupload::upload($data, $this->url, $this->referer);

            $res["msg"] = 'ok';
            $res["data"] = json_decode($curl_info, JSON_UNESCAPED_UNICODE);
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
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
