<?php
/**
 * 直播权限说明
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;

class LiveauthorityController extends HomebaseController {
	
	
	public function index(){

		$data=$this->request->param();
		$uid=checkNull($data['uid']);
		$token=checkNull($data['token']);

		if(checkToken($uid,$token)==700){
			$this->assign("reason",$Think.\lang('LOGIN_STATUS_INVALID'));
			return $this->fetch(':error');
			exit;
		}

		$fans_status=0;
        $video_status=0;

        $configpri=getConfigPri();
        $live_videos=$configpri['live_videos'];
        $live_fans=$configpri['live_fans'];

        //获取用户的粉丝数量
        $fans=getFans($uid);

        //获取用户发布视频数量
        $videonum=Db::name("user_video")->where("uid={$uid} and is_ad=0 and status=1 and isdel=0")->count();

        if($fans>=$live_fans){
            $fans_status=1;
        }

        if($videonum>=$live_videos){
            $video_status=1;
        }

        $time=time();

        $this->assign("time",$time);
        $this->assign("live_videos",$live_videos);
        $this->assign("live_fans",$live_fans);
        $this->assign("fans_status",$fans_status);
        $this->assign("video_status",$video_status);

        return $this->fetch();

	}
	
}