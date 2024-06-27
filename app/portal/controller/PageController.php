<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;
use think\Db;
use think\db\Query;

use cmf\controller\HomeBaseController;

class PageController extends HomeBaseController{

    // 首页
    public function index(){

    	$id=$this->request->param('id');

    	if(!$id){
    		$this->assign("reason",$Thin.lang('PARAMENTER_ERROT'));
			$this->display(':error');
			exit;
    	}

    	$info=Db::name("posts")->field("id,post_title,post_content")->where("id={$id} and post_type=1")->find();

    	if(!$info){
    		$this->assign("reason",$Thin.lang('PARAMENTER_ERROT'));
			$this->display(':error');
			exit;
    	}

    	$this->assign("info",$info);

        return $this->fetch();
    }

}

