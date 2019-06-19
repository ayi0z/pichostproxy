<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="post" action="http://up-z0.qiniup.com" enctype="multipart/form-data">
        <!-- <input name="key" type="hidden" value="<resource_key>"> -->
        <!-- <input name="x:<custom_name>" type="hidden" value="<custom_value>"> -->
            <?php 
                require __DIR__ . '/qiniu.php';
                use picp\token\qiniu;
                $qn = new qiniu('', '');
                $token = $qn->uploadToken('test_pichost');
            ?>
        <input name="token" type="hidden" value="<?php echo $token ?>">
        <!-- <input name="crc32" type="hidden" /> -->
        <!-- <input name="accept" type="hidden" value="text/plain"/> -->
        <input name="file" type="file" />
        <input type="submit" value="上传文件" />
      </form>
</body>
</html>