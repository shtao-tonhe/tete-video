<?php
/**
 * 短视频
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;

class VideoController extends HomebaseController {

	public function index(){
		$videoid = $this->request->param('videoid',0,'intval');
		
		if( !$videoid ){
			$this->assign("reason",$Think.\lang('INFORMATION_ERROR'));
			return $this->fetch(':error');
			exit;
		} 
		$Video=Db::name("user_video");
        $map['id']=$videoid;
        
		$videoinfo=$Video->where($map)->find();
		
		if(!$videoinfo){
			$this->assign("reason",'视频丢失啦，看看其他视频吧');
			return $this->fetch(':error');
			exit;
		}

		if($videoinfo['status']==0){
			$this->assign("reason",'视频审核中，先看看其他视频吧');
			return $this->fetch(':error');
			exit;
		}

		if($videoinfo['status']==2){
			$this->assign("reason",'视频已下架，看看其他视频吧');
			return $this->fetch(':error');
			exit;
		}

		if($videoinfo['isdel']==1){
			$this->assign("reason",'视频已下架，看看其他视频吧');
			return $this->fetch(':error');
			exit;
		}

		if($videoinfo['coin']>0){
			$this->assign("reason",'该视频为付费视频，请登录APP观看');
			return $this->fetch(':error');
			exit;
		}



		$videoinfo['thumb']=get_upload_path($videoinfo['thumb']);

		if($videoinfo['uid']==0){
			$liveinfo=[
				'avatar_thumb'=>get_upload_path("/default_thumb.png"),
				'user_nicename'=>'系统平台',
				'id'=>0
			];
		}else{
			$liveinfo=getUserInfo($videoinfo['uid']);
		}
		
		$this->assign("hls",get_upload_path($videoinfo['href_w']));
		$this->assign("videoinfo",$videoinfo);
		$this->assign("liveinfo",$liveinfo);

		return $this->fetch();
	}


	/*更新曝光值（一小时请求一次）*/

	public function updateshowval(){
		$lastid = $this->request->param('lastid',0,'intval');
		if(!$lastid){
			$lastid=0;
		}

		$limit=1000;

		$now=time();

		$effective_time=$now-1*60*60;  //当前时间往前推一小时
		$Video=Db::name("user_video");

		//获取后台配置的每小时减去的曝光值
		$configPri=getConfigPri();
		$hour_minus_val=$configPri['hour_minus_val'];

		//获取视频列表中可被扣除曝光值的视频列表
		$video_list=$Video->where("isdel=0 and status=1 and show_val>={$hour_minus_val} and id>{$lastid} and addtime<={$effective_time}")->order("id asc")->limit($limit)->select()->toArray();


		$list_nums=count($video_list);

		foreach ($video_list as $k => $v) {

			Db::name("user_video")->where("id={$v['id']}")->setDec('show_val',$hour_minus_val);//曝光值减
			$lastid=$v['id'];
		}

		if($list_nums<$limit){
			echo "NO";
            exit;  
		}

		echo 'OK-'.$lastid;
        exit;
		
		
	}


	//更新上热门不到指定播放量的退款
	public function updatePopular(){
		$lastid=$this->request->param('lastid',0,'intval');
		if(!$lastid){
			$lastid=0;
		}
		$limit=1000;

		$now=time();

		$popular_list=Db::name("user_video")->field("id,p_nums")->where("isdel=0 and status=1 and id>{$lastid} and p_nums>0 and p_expire<{$now}")->order("id asc")->limit($limit)->select()->toArray();

		
		foreach ($popular_list as $k => $v) {
			$popinfo=Db::name("popular_orders")->where("videoid={$v['id']} and refund_status=0")->field("id,uid,money,nums")->find();

			if($popinfo['nums']){

				$coin=$v['p_nums']/$popinfo['nums']*$popinfo['money'];

				$coin=floor($coin);

				if($coin>=1){
					$isok=changeUserCoin($popinfo['uid'],$coin,1);

					if($isok){

						$data=array(
							'type'=>'income',
							'action'=>'pop_refund',
							'uid'=>$popinfo['uid'],
							'touid'=>$popinfo['uid'],
							'totalcoin'=>$coin,
							'videoid'=>$v['id'],
							'addtime'=>$now

						);
						//写入钻石消费记录
						setCoinRecord($data);

						//更新视频的热门信息
						$data1=array(
							'p_expire'=>0,
							'p_nums'=>0,
							'p_add'=>0
						);
						Db::name("user_video")->where("id={$v['id']}")->update($data1);
					}
				}

				//将上热门记录的退款状态修改一下
				Db::name("popular_orders")->where("id={$popinfo['id']}")->update(["refund_status"=>1,"end_nums"=>$v['p_nums']]);

				$lastid=$v['id'];
			}
			
		}

		$list_nums=count($popular_list);

		if($list_nums<$limit){
			echo "NO";
            exit;  
		}

		echo 'OK-'.$lastid;
        exit;
	}
}