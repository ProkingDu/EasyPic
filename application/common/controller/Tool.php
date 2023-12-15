<?php
namespace app\common\controller;
use think\Controller;
/**
 * 全局工具类 这里定义一些常用复用的方法
 * 
 */
class Tool extends Controller{
    public static function err() : string {
        return "ok";
    }
}


?>