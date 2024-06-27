<?php
/**
 * 关于短视频
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;


class AboutController extends HomeBaseController {
	
	public function index(){
		
		$data = $this->request->param();
	
		
		$uid=(int)checkNull($data['uid']);
        $token=checkNull($data['token']);
        $version=checkNull($data['version']);
        $device=checkNull($data['device']);
        $ios=checkNull($data['ios']);

		if(checkToken($uid,$token)==700){
			$this->assign("reason",$Think.\lang('LOGIN_STATUS_INVALID'));
			return $this->fetch(':error');
		}

		//获取网站标题
		$now=time();

		//获取关于我们分类下的文章列表
		$list=Db::name("posts")
			->field("id,post_title")
			->order("orderno")
			->where("termid=11")
			->select();
		$this->assign("list",$list);


		//获取分类里id为13的分类名称
		$name=Db::name("terms")
			->where("id=13")
			->value("name");

		$this->assign("time",$now);
		
		$this->assign("name",$name);
		$this->assign("version",$version);
		
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("device",$device);
		$this->assign("ios",$ios); //联系客服点击电话弹窗判断
		
		
		
		return $this->fetch();
	    
	}


	

		
}