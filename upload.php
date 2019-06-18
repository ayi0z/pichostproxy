<?php
namespace picp;

header("content-type:application/json;charset=utf-8");
require_once __DIR__.'/loader.php';

$upload = new Upload();
$upload->upload();

class UploadFile
{
    public $name = '';
    public $size = '';
    public $type = '';
    public $tmp_name = '';
    public $ext = '';
    public $err = 0;

    public function __construct()
    {
        $this->err = $_FILES['file']['error'];
        if ($this->err > 0) {
            $res["msg"] = '上传失败' . $this->err;
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $this->name = $_FILES['file']['name'];
        $this->type = $_FILES['file']['type'];
        $this->size = $_FILES['file']['size'];
        $this->tmp_name = $_FILES['file']['tmp_name'];

        $nams_explode = explode('.', $this->name);
        $this->ext = end($nams_explode);
    }
}

class Upload
{
    protected $allowExts = array('git', 'png', 'jpg', 'jpeg');
    protected $allowTypes = array('image/gif', 'image/png', 'image/x-png', 'image/jpg', 'image/jpeg', 'image/pjpeg');
    protected $maxSize = 5 * 1024 * 1024;   // 5M
    protected $tmpPath = './tmp/';
    protected $file = null;

    public function __construct()
    {
        if (is_null($this->file)) {
            $this->file = new UploadFile();
        }
    }

    public function upload()
    {
        $go = $this->files_valid();
        if ($go) {
            $pichost = isset($_POST['pichost']) ? $_POST['pichost'] : 'local';
            upload\UploaderBox::get($pichost)->upload($this->file);
        }
    }

    // 图片检查：图片类型、图片大小、图片格式
    public function files_valid()
    {
        if (
            !in_array($this->file->type, $this->allowTypes)
            || !in_array($this->file->ext, $this->allowExts)
            || $this->file->size > $this->maxSize
        ) {
            $res["msg"] = '非法的图片格式';
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        return true;
    }
}
