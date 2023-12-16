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
     * 判断文件名合法性
     * @param string $file 文件信息数组
     * @return boolean 合法返回true 不合法返回false
     */
    public function checkName($file){
        //        检测文件类型
        for($i=0;$i<count($this->allowType);$i++){
            $pos=strpos($file['name'],".".$this->allowType[$i]);  //扩展名在文件名中的位置
            $nameLength=strlen($file['name']);    //文件名长度
            $expendLength=strlen(".".$this->allowType[$i]);  //扩展名长度
            $addLength=$pos+$expendLength;    // 用于判断是否和文件名长度相等
            var_dump($pos,$nameLength,$expendLength,$addLength,":");
            if(strpos($file['type'],$this->allowType[$i]) !== false and (((int)$pos==0 and $addLength==$expendLength) or ((int)$pos!=0 and $addLength == $nameLength))){
                // file type或者filename最后包含合法扩展名即跳过循环
                break;
            }
            if($i==count($this->allowType)-1){
                // 如果循环到最后没有跳出循环则有问题
                return false;
            }
        }
        return true;
    }

    /**
     * 上传文件接口
     * @var $_FILES 上传过来的文件数组
     * @var $file 图片信息数组
     */
    public function upload(){

        $file=$_FILES['img'];  //      上传过来的文件

        if(empty($_FILES['img'])){
            return json_encode(['status'=>'error','msg'=>'请上传文件！']);
        }

        // 检查文件名是否合法
        if(!$this->checkName($file)){
            return json_encode(array("status"=>"error","msg"=>"文件类型不合法！"));
        }

        // 检查是否启用七牛云储存
        $this->allowQiniu=Config::where(["class"=>"qiniu",'name'=>"is_used"])->select()[0]['value'];
        $this->saveLocal=Config::where(["class"=>"qiniu",'name'=>"is_local_saved"])->select()[0]['value'];


        //      生成文件信息
        $tmp=$file['tmp_name'];   //临时文件地址
        $root=$_SERVER['DOCUMENT_ROOT'].'/assets/uploads/'.date("Ymd");    //本地文件路径
        $type=str_replace("image/","",$file['type']);   //文件后缀
        $name=md5(time().$_SERVER['REMOTE_ADDR'].rand(10000000,99999999)).".".$type;
        $remote_path=sprintf("%s/%s/%s/%s",date("Y"),date("m"),date("d"),$name);    //远程文件路径

        if($this->allowQiniu == 1){
            // 上传到七牛云
            // 取得文件名
            $filename=$this->dealFileName($file["name"]);
            if($this->saveLocal == 1){
                // 如果储存在本地
                $path=$this->saveLocal($file);
                return $path;
            }
        }
//        移动临时文件到路径
        

        
        // if($rs!==false){
        //     return json_encode(['status'=>'success','msg'=>'上传成功！','filename'=>$filename,'path'=>str_replace($_SERVER['DOCUMENT_ROOT'],"",$root)."/".$filename,'url'=>"http://".$_SERVER['HTTP_HOST']."/".str_replace($_SERVER['DOCUMENT_ROOT'],"",$root)."/".$filename]);
        // }else{
        //     return json_encode(['status'=>'error','msg'=>'未知错误！']);
        // }
    }

    /**
     * 将文件储存在本地
     * @param $file Array 储存文件信息的数组
     * @return boolean|string 失败返回false 成功返回文件相对路径
     */
    public function saveLocal($file){
        //      生成文件信息
        $this->dirname='/assets/uploads/'; //文件储存地址
        $root=$_SERVER['DOCUMENT_ROOT'].$this->dirname.date("Ymd");    //本地文件路径
        $name=$this->dealFileName($file["name"]);  //文件名
        $relativeName=$this->dirname.$name;    //文件相对路径
        // 移动到新路径
        file_exists($root) || mkdir($root);
        $filepath=$root."/".$name;
        $rs=move_uploaded_file($file['tmp_name'],$filepath);

        if(!$rs){
            return false;
        }

        // 返回
        return $relativeName;
    }

    /**
     *  文件重命名算法
     *  文件名包含：用户IP地址 原文件名
     * @param $name 原文件名
     */
    public static function dealFileName($name){
        // 首先取得当前时间戳、用户IP地址、文件后缀
        $time=time();
        $ip=$_SERVER['REMOTE_ADDR'];
        $arr=explode(".",$name);
        $type=$arr[count($arr)-1];   //文件后缀
        // 转换文件名
        $nameList=[];
        $nameStr="";
        echo "原数字：";
        for($i=0;$i<strlen($name);$i++){
            // 获得ASII码
            $ascii=ord($name[$i]);
            $nameList[$i]=$ascii;
            // 对ASCII码与下一个数按位异或运算 如果已经是最后一位则不运算
            if($i!=strlen($name)-1){
                $back=(int)$ascii ^ (int)ord($name[$i+1]);
            }else{
                $back=$ascii;
            }
            $nameStr.="x".$back;
            echo $ascii.",";
        }
        $nameStr=base64_encode($nameStr);

        // 还原
        $fname="";
        $arr=explode("x",base64_decode($nameStr));
        array_splice($arr,0,1);
        for($i=count($arr)-1;$i>=0;$i--){
            if($i!=count($arr)-1){
                $temp=(int)$arr[$i] ^ (int)$arr[$i+1];
            }else{
                $temp=(int)$arr[$i];
            }
            $arr[$i]=$temp;
            $fname[$i]=chr($temp);
        }


        // 转换IP地址
        // 转为二进制再拼接
        $ip_arr=explode(".",$ip);
        $ipStr="";
        foreach ($ip_arr as $v) {
            $ipStr.=(((int)decbin($v))).",";
        }
        $ipStr=base64_encode($ipStr);
        
        // 拼接再转换
        $str=$nameStr.":".$ipStr;
        $result=base64_encode($str);
        
        // 转md5
        $result=md5($str).".".$type;
        return $result;
    }
    
}