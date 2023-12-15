<?php
namespace app\index\controller;
use app\index\controller\Base;
use app\common\controller\QiNiu;
use app\common\model\Config;
use app\api\controller\Files;
class Index extends Base
{
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        return $this->fetch();
    }
    public function qiniu(Qiniu $q){
        // var_dump($q);
        // new Qiniu;
        // var_dump($q->serviceUpload("16955631272f4388a528b781ed15bf2b3e89d07bc5.png","t/t.png"));
        // var_dump($q->readFile("test/test1.png"));
        // var_dump();
        Files::dealFileName("test.png");
    }
}
