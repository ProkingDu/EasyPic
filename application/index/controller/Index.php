<?php
namespace app\index\controller;
use app\index\controller\Base;
class Index extends Base
{
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        return $this->fetch();
    }
}
