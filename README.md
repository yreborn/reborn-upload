# reborn-upload
图片上传

demo
~~~~
<?php

namespace app\index\controller;
use reborn\fileupload\FileUpload;

class Index
{
    public function upload()
        {
            $file=(new FileUpload())->upload_local();
            return $file;
        }
}

html

<form action="/index/index/upload" enctype="multipart/form-data" method="post">
    <input type="file" name="file" /> <br>
    <input type="submit" value="上传" />
</form>