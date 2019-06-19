<?php

namespace picp\upload;
class local implements IUploader
{
    private $tmpPath = 'tmp/';
    public function __construct(){
         $this->tmpPath = 'tmp/'. date("Y-m-d") . '/';
    }

    public function upload($file)
    {
        $go = file_exists($this->tmpPath);
        if (!$go) {
            $go = mkdir($this->tmpPath, 0777, true);
        }

        if ($go) {
            $targetname = $this->_new_file_name($file);
            $go = move_uploaded_file($file->tmp_name, $targetname);
            if ($go){
                $res["msg"] = 'ok';
                $res["data"] = array('url'=>$_SERVER['HTTP_HOST'] . '/' . $targetname);
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    protected function _new_file_name($file)
    {
        $target_name = uniqid() . '.' . $file->ext;
        $target_full_name = $this->tmpPath . $target_name;
        if (file_exists($target_full_name)) {
            return $this->_new_file_name($file);
        } else {
            return $target_full_name;
        }
    }
}
