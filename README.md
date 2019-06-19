### 为了方便日常编码测试而写的图床工具，方便使用网路上的免费图床

#### http上传
```
/upload.php

method: POST

data:
{
    pichost: '图床名称key',
    file: '图片文件'
}

return:
{
    msg: 'ok',
    data: '图床api返回的json'
}


/download.php?url=<图床返回的图片外链>

```