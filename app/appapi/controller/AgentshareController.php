<?php
/**
 * 邀请分享
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;

class AgentshareController extends HomebaseController {
	
	public function index(){


		$uid = $this->request->param('uid',0,'intval');

		$userinfo=Db::name("user")->where("id={$uid}")->field("code,user_nicename,avatar")->find();

		$userinfo['avatar']=get_upload_path($userinfo['avatar']);
        
        $configpri=getConfigPri();
        $openinstall_switch=$configpri['openinstall_switch'];
        
       
        $this->assign('uid',$uid);
        $this->assign('userinfo',$userinfo);
        $this->assign('apk_url',$apk_url);
        $this->assign('openinstall_switch',$openinstall_switch);


		return $this->fetch();
	    
	}

	public function downapp(){

		$configpri=getConfigPri();
		$openinstall_appkey=$configpri['openinstall_appkey'];
		$this->assign('openinstall_appkey',$openinstall_appkey);

		return $this->fetch();
	}

	

}