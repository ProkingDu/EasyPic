<?php
namespace app\api\controller;
use think\Controller;
use app\common\model\Config;

class Files extends Controller{
    /**
     * @var array $allowType 允许上传的文件类型数组
     * @var int $allowQiniu 启用七牛云储存
     * @var int $saveLocal 启用七牛云后是否保存本地
     */
    protected $allowType;
    protected $allowQiniu;
    protected $saveLocal;
    /**
     * 文件类构造方法
     */
    public function initialize(){
        $this->allowType=[
            "png",
            "jpg",
            'webp',
            'jpeg',
        ];
    }

    /**
     * 上传文件接口
     * @var $_FILES 文件数组
     */
    public function upload(){
        if(empty($_FILES['img'])){
            return json_encode(['status'=>'error','msg'=>'请上传文件！']);
        }

        // 检查是否启用七牛云储存
        $this->allowQiniu=Config::where(["class"=>"qiniu",'name'=>"is_used"])->select()[0]['value'];
        $this->saveLocal=Config::where(["class"=>"qiniu",'name'=>"is_local_saved"])->select()[0]['value'];

//      获取POST的文件
        $file=$_FILES['img'];

//        检测文件类型
        for($i=0;$i<count($this->allowType);$i++){
            if(strpos($file['type'],$this->allowType[$i]) !== false){
                break;
            }
            if($i==count($this->allowType)-1){
                return json_encode(['status'=>'error','msg'=>'你瞎jb乱上传什么？傻逼！！！！']);
            }
        }
//      生成文件信息
        $tmp=$file['tmp_name'];   //临时文件地址
        $root=$_SERVER['DOCUMENT_ROOT'].'/assets/uploads/'.date("Ymd");    //本地文件路径
        $type=str_replace("image/","",$file['type']);   //文件后缀
        $filename=md5(time().$_SERVER['REMOTE_ADDR'].rand(10000000,99999999)).".".$type;
        $remote_path=sprintf("%s/%s/%s/%s",date("Y"),date("m"),date("d"),$filename);    //远程文件路径

        if($this->allowQiniu == 1){
            // 上传到七牛云

        }
//        移动临时文件到路径
        

        file_exists($root) || mkdir($root);
        $filepath=$root."/".$filename;
        $rs=move_uploaded_file($file['tmp_name'],$filepath);
        if($rs!==false){
            return json_encode(['status'=>'success','msg'=>'上传成功！','filename'=>$filename,'path'=>str_replace($_SERVER['DOCUMENT_ROOT'],"",$root)."/".$filename,'url'=>"http://".$_SERVER['HTTP_HOST']."/".str_replace($_SERVER['DOCUMENT_ROOT'],"",$root)."/".$filename]);
        }else{
            return json_encode(['status'=>'error','msg'=>'未知错误！']);
        }
    }

    /**
     *  文件重命名算法
     *  文件名包含：用户IP地址 时间戳 原文件名
     * @param $name 原文件名
     */
    public static function dealFileName($name){
        // 首先取得当前时间戳、用户IP地址
        $time=time();
        $ip=$_SERVER['REMOTE_ADDR'];

        // 转换文件名
        $nameList=[];
        $nameStr="";
        for($i=0;$i<strlen($name);$i++){
            // 获得ASII码
            $ascii=ord($name[$i]);
            $nameList[$i]=$ascii;
            // 对ASCII码与下一个数按位与运算 如果已经是最后一位则不运算
            if($i!=strlen($name)-1){
                $back=$ascii & ord($name[$i+1]);
            }else{
                $back=$ascii;
            }
            $nameStr.="x".$back;
        }
        
        // 还原
        $arr=explode("x",$nameStr);
        unset($arr[0]);
        for($i=count($arr)-1;$i>0;$i--){
            var_dump(chr($arr[$i] | $arr[$i+1]));
        }
1100 i
0101 i+1
0100 

0100 i2
0101 i+1 2
0101 


        // 转换IP地址
        // 转为二进制取反再拼接
        $ip_arr=explode(".",$ip);
        $ipStr="";
        foreach ($ip_arr as $v) {
            // $ipStr.=~decbin($v).".";
        }
        var_dump($ip);
        $n="0";
        foreach ($nameList as $key => $value) {
            $n.=$value;
        }
    }
    
}