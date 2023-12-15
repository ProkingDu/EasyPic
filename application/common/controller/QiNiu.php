<?php
namespace app\common\controller;
use think\Controller;
use think\Loader;
use Qiniu\Auth;
use app\common\model\Config;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use app\common\controller\Base;
class Qiniu extends Base{
    /**
     * @var $Ak 七牛云Ak
     * @var $Sk 七牛云Sk
     * @var $auth 鉴权对象
     * @var $bucket 储存桶名称
     * @var $token 上传凭证
     */
    private $Ak;
    private $Sk;
    private $auth;
    protected $bucket;
    private $token;

    /**
     * 七牛云公共类库
     * @method __construct 构造方法 用于实例化SDK类库。
     * @var $auth 鉴权对象
     * 
     */
    public function __construct(){
        parent::__construct();
        // 初始化参数
        $this->Ak=Config::where(["class"=>"qiniu","name"=>"access_key"])->find()["value"];
        $this->Sk=Config::where(["class"=>"qiniu","name"=>"secret_key"])->find()["value"];
        $this->token=Config::where(["class"=>"qiniu","name"=>"upload_token"])->find()["value"];
        $this->bucket=Config::where(["class"=>"qiniu","name"=>"bucket"])->find()["value"];

        $this->auth=new Auth($this->Ak,$this->Sk);
        // 我是傻逼 这里auth属性的赋值放到最上面了一直出错。。。

        // 判断上传凭证是否过期
        if($info=json_decode(Config::where(["class"=>"qiniu","name"=>"upload_token"])->find()["extend"])){
            if($info->expire < ($time=(time()))){
                // 更新上传凭证
                $this->token=$this->auth->uploadToken($this->bucket);
                $info->expire=$time+3600;
                $str=json_encode($info);
                $config=new Config;
                $config->where(["class"=>"qiniu","name"=>"upload_token"])->update(["extend"=>$str,"value"=>$this->token]);
            }
        }
    }

    /**
     * 服务端上传方法
     * @param $file 文件路径 
     * @param $path 远程文件路径
     * @var $ret 七牛云返回信息
     * @var $err 错误消息 如果没有错误则为NULL
     * @var $read 读取文件方法返回的数组
     * */ 
    public function serviceUpload($file,$path){
        // 实例化上传对象
        $upload=new UploadManager();
        list($ret,$err)=$upload->putFile($this->token,$path,$file);
        if($err!==null){
            return array("code"=>"0","msg"=>$err);
        }else{
            // 没有错误 判断文件是否已经上传并且可以成功读取
            $read=$this->readFile($path);
            if($read['code']!==1){
                return $read;
            }else{
                return array("code"=>"1","msg"=>"上传成功","info"=>$read['info']);
            }
        }
    }

    /**
     * 从七牛云读取图片
     * @var $path 要读取的文件路径 是在七牛云相对根目录的路径
     * @var $ret 服务器返回的信息 当产生错误时为NUL
     * @var $err 返回的错误信息 当没有错误时为NULL
     */
    public function readFile($path){
        if(empty($path)){
            return array("code"=>"-1","msg"=>"请指定文件路径");
        }
        // 实例化文件读取类
        $bucket=new BucketManager($this->auth);
        // 提交读取文件
        list($ret,$err)=$bucket->stat($this->bucket,$path);
        if(empty($ret) && $err!==null){
            // 获取失败 返回错误信息
            return array("code"=>0,"msg"=>$err->getResponse()->error);
        }else if(empty($err) && $ret!==null){
            // 获取成功 返回文件信息
            return ["code"=>1,"msg"=>"success","info"=>$ret];
        }else{
            // 灵异事件
            return ["code"=>1,"msg"=>"未知错误"];
        }
    }

    // /**
    //  * 从七牛云返回的错误对象中得的消息
    //  * @param $obj Error类的实例
    //  * @var $response Error类返回的响应对象
    //  */
    // public function getError($obj){
    //     if(empty($obj)){
    //         return null;
    //     }else{
    //         $response=$obj->getResponse();
    //         $headers=$response->headers;
    //         $body=$response->body;
    //         $error=$response->error;
    //         return $response;
    //     }
    // }
}