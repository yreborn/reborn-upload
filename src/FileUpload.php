<?php
/**
 * Created by PhpStorm.
 * User: Reborn
 * Date: 2019/4/18
 * Time: 15:49
 */

namespace reborn\fileupload;

use reborn\exception\ParamException;
use think\facade\Request;
use think\facade\Config;

class FileUpload
{
    /**
     * 本地单图上传
     * User: Reborn
     * 2019/4/17 16:59:15
     */
    public function upload_local(){
        $file = Request::file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move( './uploads');
        if($info){
            $data['url']=$info->getFilename();
            $data['srcurl']=Config::get('upload_url').'/uploads/'.$info->getSaveName();
            $data['baseurl']=Config::get('upload_url');
            return $data;
        }else{
            throw new ParamException('文件上传失败');
        }
    }

    /**
     * 文件上传
     * User: Reborn
     * 2019/4/17 16:54:18
     * @param bool $type
     * @return mixed
     * @throws ForbiddenException
     * @throws ParamException
     */
    public function upload_online($type = true)
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = Request::file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        if ($type) {
            $info = $file->validate(['size' => 10000, 'ext' => 'jpg,png,git'])->move('./uploads/');
        } else {
            $info = $file->move('./uploads/');
        }
        if(!$info){
            throw new ParamException('上传失败');
        }

        $url=Config::get('upload_url');
        $image=$info->getSaveName();
        $path='./uploads/'.$image;

        try{

            $result=self::curlFile($url,$path);
            @unlink($path);

        }catch (\Exception $exception){

            throw new ParamException($exception->getMessage());
        }

        if (!is_array($result)){
            $result=json_decode($result,true);
        }

        if (array_key_exists('result',$result) && $result['result']=='success'){

            $data['url']=$result['url'];
            $data['srcurl']=Config::get('upload_url').$result['url'];
            $data['baseurl']=Config::get('upload_url');
            return $data;
        }else{
            throw new ParamException('文件上传失败');
        }
    }

    /**
     * 上传到指定服务器
     * Created by Reborn
     * @param $url 服务器路径
     * @param $path 图片路径
     * @return mixed
     * @throws \Exception
     */
    private function curlFile($url, $path)
    {
        $data = array('file' => new \CURLFile(realpath($path)));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, "TEST");
        $result = curl_exec($curl);
        curl_close($curl);
        if ($result) {
            return $result;
        }
        throw  new \Exception('curl错误:' . curl_errno($curl));
    }

}