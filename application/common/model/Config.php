<?php
namespace app\common\model;
use think\Model;
/**
 * Config表模型 用于查询站点配置信息。
 */
class Config extends Model{
    public static function qiniu($query,$name){
        $query->where(["class"=>"qiniu","name"=>$name]);
    }
}


?>