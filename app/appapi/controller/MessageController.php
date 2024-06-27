<?php
/**
 * 推送消息
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;

class MessageController extends HomebaseController {
	
	/*官方推送信息详情*/
	public function msginfo(){
	
		$id = $this->request->param('id',0,'intval');
		
		//判断该信息是否存在
		$info=Db::name("admin_push")->where("id={$id}")->find();

		if(!$info){
			$this->assign("reason",'信息不存在');
			return $this->fetch(':error');
			exit;
		}

		$this->assign("info",$info);
		
		return $this->fetch();
	    
	}

}