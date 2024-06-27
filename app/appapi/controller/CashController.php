<?php
/**
 * 提现记录
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;

class CashController extends HomebaseController {
    
    var $action=array(
        '1'=>'完成邀请',
        '2'=>'观看视频',
        '3'=>'收费视频收入',
        '4'=>'视频送礼物',
        '5'=>'直播间送礼物',
        '6'=>'开通守护',
    );


    //收益明细
	public function record(){
        
        $data = $this->request->param();

        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']);

		if(checkToken($uid,$token)==700){
			$this->assign("reason",$Think.\lang('LOGIN_STATUS_INVALID'));
			return $this->fetch(':error');
			exit;
		}
        
        $list=Db::name("votes_record")
			->where("(uid={$uid} and votes>0) or (touid={$uid} and touid_votes>0)")
			->order("id desc")
			->limit(0,50)
			->select()
			->toArray();
		
		foreach($list as $k=>$v){
			
			$userinfo=getUserInfo($v['touid']);
			if($v['touid_votes']>0 || $v['touid']==0){
				$userinfo=getUserInfo($v['uid']);
			}
            
           
            $v['userinfo']=$userinfo;
            
            if($uid==$v['uid']){
                /* 自己的收益 */
                $total=$v['votes'];
            }else{
                /* 下级奖励 */
                $total=$v['touid_votes'];
            }
            $v['total']=$total;
			$v['action_name']=$this->action[$v['action']];
            
            $list[$k]=$v;
		}

		$configpub=getConfigPub();
        $this->assign("name_votes",$configpub['name_votes']);
        
        $this->assign('uid',$uid);
        $this->assign('token',$token);
        $this->assign('list',$list);

		return $this->fetch();
        

	}

	public function record_more(){
		$data = $this->request->param();

        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']); 
		
		$result=array(
			'data'=>array(),
			'nums'=>0,
			'isscroll'=>0,
		);
	
		if(checkToken($uid,$token)==700){
			echo json_encode($result);
			exit;
		} 
		
		$p=$data['page'];
		$pnums=50;
		$start=($p-1)*$pnums;

        $list=Db::name("votes_record")->where(["uid|touid"=>$uid])->order("id desc")->limit($start,$pnums)->select();
		foreach($list as $k=>$v){
            
            $userinfo=getUserInfo($v['touid']);
			if($v['touid_votes']>0  || $v['touid']==0){
				$userinfo=getUserInfo($v['uid']);
			}
            
            if($uid==$v['uid']){
                /* 自己的收益 */
                $total=$v['votes'];
            }else{
                /* 下级奖励 */
                $total=$v['touid_votes'];
            }
            $v['total']=$total;
			$v['action_name']=$this->action[$v['action']];
            
            $list[$k]=$v;
		}

		
		$nums=count($list);
		if($nums<$pnums){
			$isscroll=0;
		}else{
			$isscroll=1;
		}
		
		$result=array(
			'data'=>$list,
			'nums'=>$nums,
			'isscroll'=>$isscroll,
		);

		echo json_encode($result);
		exit;
	}

    var $status=array(
        '0'=>'审核中',
        '1'=>'成功',
        '2'=>'失败',
    );
    

    //提现明细
	public function cash(){
        
        $data = $this->request->param();

        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']); 
		
		if(checkToken($uid,$token)==700){
			$this->assign("reason",$Think.\lang('LOGIN_STATUS_INVALID'));
			return $this->fetch(':error');
			exit;
		}
        
        $list=Db::name("user_cashrecord")->where(["uid"=>$uid])->order("addtime desc")->limit(0,50)->select()->toArray();

		foreach($list as $k=>$v){

			$list[$k]['addtime']=date('Y.m.d',$v['addtime']);
			$list[$k]['status_name']=$this->status[$v['status']];
		}
        
        $this->assign('list',$list);

		return $this->fetch();
	}

	public function cash_more(){
		$data = $this->request->param();

        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']); 
		
		$result=array(
			'data'=>array(),
			'nums'=>0,
			'isscroll'=>0,
		);
	
		if(checkToken($uid,$token)==700){
			echo json_encode($result);
			exit;
		} 
		
		$p=$data['page'];
		$pnums=50;
		$start=($p-1)*$pnums;

        $list=Db::name("user_cashrecord")->where(["uid"=>$uid])->order("addtime desc")->limit($start,$pnums)->select();
		foreach($list as $k=>$v){

			$list[$k]['addtime']=date('Y.m.d',$v['addtime']);
			$list[$k]['status_name']=$this->status[$v['status']];
		}
		
		$nums=count($list);
		if($nums<$pnums){
			$isscroll=0;
		}else{
			$isscroll=1;
		}
		
		$result=array(
			'data'=>$list,
			'nums'=>$nums,
			'isscroll'=>$isscroll,
		);

		echo json_encode($result);
		exit;
	}



}