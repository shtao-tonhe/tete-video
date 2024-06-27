<?php
/**
 * 联系客服
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;


class ServiceController extends HomebaseController {
	
	public function index(){
		
		$data=$this->request->param();
		$ios=checkNull($data['ios']);
		$this->assign("ios",$ios);
		return $this->fetch();
	    
	}

	
}