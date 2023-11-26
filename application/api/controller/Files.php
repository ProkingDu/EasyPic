<?php
namespace app\api\controller;
use think\Controller;
class Files extends Controller{
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
//        允许上传的文件对象
    }
    public function upload(){
        if(empty($_FILES['img'])){
            return json_encode(['status'=>'error','msg'=>'请上传文件！']);
        }
        $file=$_FILES['img'];
//        包含文件信息的数组
//        检测文件类型
        for($i=0;$i<count($this->allowType);$i++){
            if(strpos($file['type'],$this->allowType[$i]) !== false){
                break;
            }
            if($i==count($this->allowType)-1){
                return json_encode(['status'=>'error','msg'=>'你瞎jb乱上传什么？傻逼！！！！']);
            }
        }
//        移动临时文件到路径
        $tmp=$file['tmp_name'];
        $root=$_SERVER['DOCUMENT_ROOT'].'/assets/uploads/'.date("Ymd");
        file_exists($root) || mkdir($root);
        $type=str_replace("image/","",$file['type']);
        $filename=md5(time().$_SERVER['REMOTE_ADDR'].rand(10000000,99999999)).".".$type;
        $filepath=$root."/".$filename;
        $rs=move_uploaded_file($file['tmp_name'],$filepath);
        if($rs!==false){
            return json_encode(['status'=>'success','msg'=>'上传成功！','filename'=>$filename,'path'=>str_replace($_SERVER['DOCUMENT_ROOT'],"",$root)."/".$filename,'url'=>"http://".$_SERVER['HTTP_HOST']."/".str_replace($_SERVER['DOCUMENT_ROOT'],"",$root)."/".$filename]);
        }else{
            return json_encode(['status'=>'error','msg'=>'未知错误！']);
        }
    }
    
}