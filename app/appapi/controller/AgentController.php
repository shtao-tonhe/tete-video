<?php
/**
 * 分销
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;


class AgentController extends HomebaseController {
	
	public function index(){       
		$data = $this->request->param();

        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']);
		
		if(checkToken($uid,$token)==700){
			$this->assign("reason",$Think.\lang('LOGIN_STATUS_INVALID'));
			return $this->fetch(':error');
			exit;
		} 
		  
		$nowtime=time();
        
        //当天0点
        $today=date("Ymd",$nowtime);
        $today_start=strtotime($today);
        //昨天0点
        $yes_start=strtotime("{$today} - 1 day");

		$Agent=Db::name("agent");

		$agentinfo=array();
		
        $count=$Agent->where(['one'=>$uid])->count();
        
        $map=[];
        $map['touid']=$uid;
 
        $votes_record=Db::name("votes_record");
        $total=$votes_record->where(function (Query $query) {
				$query->where('uid', $uid);

				$query->where('addtime', 'between' , [$yes_start,$today_start]);
           					
				})
				->sum('touid_votes');
        if(!$total){
            $total=0;
        }
        
		
		$Agent_profit=Db::name("agent_profit");
		
		$agentprofit=$Agent_profit->where(["uid"=>$uid])->find();
		
		$one_p=$agentprofit['one_p'];
		if(!$one_p){
			$one_p=0;
		}


		$agnet_profit=array(
			'count'=>$count,
			'total'=>$total,
			'one_p'=>$one_p,
		);

		$configpub=getConfigPub();
		$configpri=getConfigPri();

		$agnet_code=Db::name("user")->where("id={$uid}")->value("code");

		$name_votes=$configpub['name_votes'];
		$agent_reward=$configpri['agent_reward'];
		
		
		
		/* 是否是分销下级 */
        $users_agent=Db::name("agent")->where(["uid"=>$uid])->find();
		if($users_agent){
			$agentinfo= getUserInfo($users_agent['one']);
		}

		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("agnet_profit",$agnet_profit);
		$this->assign("agnet_code",$agnet_code);
		$this->assign("agent_reward",$agent_reward);
		$this->assign("name_votes",$name_votes);
		$this->assign('agentinfo', $agentinfo);

		return $this->fetch();
	    
	}
	
	
	public function agent(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason=$Think.\lang('LOGIN_STATUS_INVALID');
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}
		
		$agentinfo=array();
		
		$users_agent=Db::name('agent')->where(["uid"=>$uid])->find();
		if($users_agent){
			$agentinfo=getUserInfo($users_agent['one']);
			
			$code=Db::name('user')->where("id={$users_agent['one']}")->value('code');
			
			$agentinfo['code']=$code;
			$code_a=str_split($code);

			$this->assign("code_a",$code_a);
		}
	
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);

		$this->assign("agentinfo",$agentinfo);

		return $this->fetch();
	}
	
	public function setAgent(){
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $code=isset($data['code']) ? $data['code']: '';
        $uid=(int)checkNull($uid);
        $token=checkNull($token);
        $code=checkNull($code);
		
		$rs=array('code'=>0,'info'=>array(),'msg'=>$Think.\lang('SET_SUCCESSFULLYf'));
		
		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']=$Think.\lang('LOGIN_STATUS_INVALID');
			echo json_encode($rs);
			exit;
		} 

		if($code==""){
			$rs['code']=1001;
			$rs['msg']=$Think.\lang('INVITATION_CODE_NOT_EMPTY');
			echo json_encode($rs);
			exit;
		}
		
		$isexist=Db::name('agent')->where(["uid"=>$uid])->find();
		if($isexist){
			$rs['code']=1001;
			$rs['msg']=$Think.\lang('ALREADY_SET');
			echo json_encode($rs);
			exit;
		}
		
		$oneinfo=Db::name('user')->field("id")->where(["code"=>$code])->find();
		if(!$oneinfo){
			$rs['code']=1002;
			$rs['msg']=$Think.\lang('INVITATION_CODE_ERROR');
			echo json_encode($rs);
			exit;
		}
		
		if($oneinfo['id']==$uid){
			$rs['code']=1003;
			$rs['msg']=$Think.\lang('NOT_FILL_IN_YOUR_CODE');
			echo json_encode($rs);
			exit;
		}
		
		$one_agent=Db::name('agent')->where("uid={$oneinfo['id']}")->find();
		if(!$one_agent){
			$one_agent=array(
				'uid'=>$oneinfo['id'],
				'one'=>0,
			);
		}else{

			if($one_agent['one']==$uid){
				$rs['code']=1004;
				$rs['msg']=$Think.\lang('ALREADY_THE_SUPERIOR_OF_USER');
				echo json_encode($rs);
				exit;
			}
		}
		
		$data=array(
			'uid'=>$uid,
			'one'=>$one_agent['uid'],
			'addtime'=>time(),
		);
		Db::name('agent')->insert($data);
		
		/* 邀请奖励 */
		$one_uid=$oneinfo['id'];
		
		
        $configPri=getConfigPri();
        $nowtime=time();
        $data=[
            'action'=>'1',
            'uid'=>$uid,
            'votes'=>0,
            'addtime'=>$nowtime,
        ];

        $agent_reward=$configPri['agent_reward'];

        //更新用户的映票
        changeUserVotes($one_uid,$agent_reward,1);   
        $data['touid']=$one_uid;
        $data['touid_votes']=$agent_reward;
        
       $rs2= Db::name('agent_profit')
            ->where("uid={$one_uid}")
            ->find();
        if(!$rs2){
            Db::name('agent_profit')
                ->insert(array('uid'=> $one_uid,'one_p'=> $agent_reward));
        }else{
            Db::name('agent_profit')
                ->where("uid={$one_uid}")
				->setInc("one_p",$agent_reward);
        }
        $rs3=Db::name('agent_profit')
                ->where("uid={$uid}")
                ->find();
        if(!$rs3){
			Db::name('agent_profit')->insert(array('uid'=> $uid,'one'=> $agent_reward));
        }else{
            Db::name('agent_profit')
				->where("uid={$uid}")
				->setInc("one",$agent_reward);
               
        }

        //写入映票收入记录        
        setVoteRecord($data);

		echo json_encode($rs);
		exit;
	}

	
	public function one(){
		$data = $this->request->param();

        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']);
		
		if(checkToken($uid,$token)==700){
			$this->assign("reason",$Think.\lang('LOGIN_STATUS_INVALID'));
			$this->display(':error');
			exit;
		} 
		  
		$Agent_profit=Db::name("user_agent_profit_recode");
		
		$list=$Agent_profit
			->field("uid,sum(one_profit) as total")
			->where(["one_uid"=>$uid])
			->group("uid")
			->order("addtime desc")
			->limit(0,50)
			->select()
			->toArray();
			
		
		foreach($list as $k=>$v){
			$v['userinfo']=getUserInfo($v['uid']);
			$v['total']=NumberFormat($v['total']);
			
			$list[$k]=$v;
		}
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("list",$list);
		return $this->fetch();
	}

	public function one_more(){
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

		$Agent_profit=Db::name("user_agent_profit_recode");
		
		$list=$Agent_profit->field("uid,sum(one_profit) as total")
			->where(["one_uid"=>$uid])
			->group("uid")
			->order("addtime desc")
			->limit($start,$pnums)
			->select()
			->toArray();
			
		
		foreach($list as $k=>$v){
			$v['userinfo']=getUserInfo($v['uid']);
			$v['total']=NumberFormat($v['total']);
			
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

}