<?php
namespace app\common\controller;
use think\Controller;
use think\Db;
use app\common\model\Config;
/**
 * 全局控制器基类
 */

class Base extends Controller{
    /**
     * @var array $config 配置信息 结构：'class'=>array
     */
    protected $config;
    /**
     * 构造方法：
     * 1.获取站点基础配置信息
     */
    public function __construct(){
        {
            // 获取配置信息
            // Config::
        }
    }
}
?>