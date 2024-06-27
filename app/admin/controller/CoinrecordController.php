<?php

/**
 * 收入记录
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class CoinrecordController extends AdminbaseController {
    

	protected function getType($k=''){
        $type=array(
            'income'=>$Think.\lang('INCOME'),
            'expend'=>$Think.\lang('EXPEND')
        );
        if($k===''){
            return $type;
        }

        return isset($type[$k]) ? $type[$k] : '';
    }

    protected function getAction($k=''){
        $action=array(
            'sendgift'=>$Think.\lang('GIFTS_IN_STUDIO'),
            'buyvip'=>$Think.\lang('BALANCE_PURCHASE_VIP'),
            'video_sendgift'=>$Think.\lang('VIDEO_GIFT_GIVING'),
            'pop_refund'=>$Think.\lang('TOP_REFUND'),
            'uppop'=>$Think.\lang('POPULAR_ON'),
            'watchvideo'=>$Think.\lang('ADMIN_ADVERT_INDEX_GUANKANSHIPING'),
            'yurntable_game'=>$Think.\lang('TURNTABLE_GAME'),
            'turntable_wins'=>$Think.\lang('TURNTABLE_WINNING'),
            'open_guard'=>$Think.\lang('OPEN_GUARD'),
            'daily_tasks'=>$Think.\lang('DAILY_TASK'),
            'reg_reward'=>$Think.\lang('REGISTRATION_AWARD'),
            'backpack_sendgift'=>$Think.\lang('BACKPACK_GIFT_IN_STUDIO'),
        );
        if($k===''){
            return $action;
        }

        return isset($action[$k]) ? $action[$k] : '';
    } 
    
    function index(){
        $data = $this->request->param();
        $map=[];
        
        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';
        
        if($start_time!=""){
           $map[]=['addtime','>=',strtotime($start_time)];
        }

        if($end_time!=""){
           $map[]=['addtime','<=',strtotime($end_time) + 60*60*24];
        }
        
        
        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
            $map[]=['uid','=',$uid];
        }

        $touid=isset($data['touid']) ? $data['touid']: '';
        if($touid!=''){
            $map[]=['touid','=',$touid];
        }

        $actions=isset($data['actions']) ? $data['actions']: '';
        if($actions!=''){
            $map[]=['action','=',$actions];
        }

        $type=isset($data['type']) ? $data['type']: '';
        if($type!=''){
            $map[]=['type','=',$type];
        }
        
        $lists = Db::name("user_coinrecord")
            ->where($map)
			->order("addtime desc")
			->paginate(20);
        
        $lists->each(function($v,$k){
			$v['userinfo']=getUserInfo($v['uid']);
            $touserinfo=getUserInfo($v['touid']);
            if($v['touid']==0){
            	$touserinfo['user_nicename']='平台';
            }
            $v['touserinfo']=$touserinfo;

            $action=$v['action'];
            if($action=='sendgift'){
            	$giftinfo=Db::name("gift")->field("giftname")->where("id='$v[giftid]'")->find();
            	$v['giftinfo']=$giftinfo;
            }else if($action=='backpack_sendgift'){
                $giftinfo=Db::name("gift")->field("giftname")->where("id='$v[giftid]'")->find();
                $v['giftinfo']=$giftinfo;
            }else if($action=='video_sendgift'){
                $giftinfo=Db::name("gift")->field("giftname")->where("id='$v[giftid]'")->find();
				$v['giftinfo']= $giftinfo;
            }else if($action=='buyvip'){
            	$giftinfo['giftname']=$Think.\lang('BALANCE_PURCHASE_VIP');
				$v['giftinfo']= $giftinfo;
            }else if($action=='uppop'){
				$giftinfo['giftname']=$Think.\lang('POPULAR_ON');
				$v['giftinfo']= $giftinfo;
			}else if($action=='pop_refund'){
				$giftinfo['giftname']=$Think.\lang('TOP_REFUND');
				$v['giftinfo']= $giftinfo;
			}else if($action=='watchvideo'){
				$giftinfo['giftname']=$Think.\lang('ADMIN_ADVERT_INDEX_GUANKANSHIPING');
				$v['giftinfo']= $giftinfo;
			}else{
				$giftinfo['giftname']=$Think.\lang('UNKNOWN');
				$v['giftinfo']= $giftinfo;
			}

            return $v;           
        });
        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);

        $configpub=getConfigPub();
        $this->assign('name_coin',$configpub['name_coin']?$configpub['name_coin']:'');
        $this->assign("actions",$this->getAction());
        $this->assign("type",$this->getType());
    	return $this->fetch();
    }
	
	
	
	//钻石消费记录
	function export(){
    

        
        $data = $this->request->param();
        $map=[];
        
        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';
        
        if($start_time!=""){
           $map[]=['addtime','>=',strtotime($start_time)];
        }

        if($end_time!=""){
           $map[]=['addtime','<=',strtotime($end_time) + 60*60*24];
        }
        
        
        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
            $map[]=['uid','=',$uid];
        }

        $touid=isset($data['touid']) ? $data['touid']: '';
        if($touid!=''){
            $map[]=['touid','=',$touid];
        }

        $actions=isset($data['actions']) ? $data['actions']: '';
        if($actions!=''){
            $map[]=['action','=',$actions];
        }

        $type=isset($data['type']) ? $data['type']: '';
        if($type!=''){
            $map[]=['type','=',$type];
        }
        
        
        
        
        $xlsName  = "钻石消费记录";
		$lists = Db::name("user_coinrecord")
            ->where($map)
			->order("id desc")
			->select()
            ->toArray();
        
      
		foreach($lists as $k=>$v){
			$userinfo=getUserInfo($v['uid']);
			$v['user_nicename']= $userinfo['user_nicename']."(".$v['uid'].")";
			

            $touserinfo=getUserInfo($v['touid']);
            if($v['touid']==0){
            	$v['touser_nicename']=$Think.\lang('PLATFORM');
            }else{
				$touserinfo=getUserInfo($v['touid']);
				$v['touser_nicename']= $touserinfo['user_nicename']."(".$v['touid'].")";
			}
     

            $action=$v['action'];
            if($action=='sendgift'){
            	$giftinfo=Db::name("gift")->field("giftname")->where("id='$v[giftid]'")->find();
            	$v['giftinfo']=$giftinfo;
            }else if($action=='sendgift'){
                $giftinfo=Db::name("gift")->field("giftname")->where("id='$v[giftid]'")->find();
                $v['giftinfo']=$giftinfo;
            }else if($action=='video_sendgift'){
                $giftinfo=Db::name("gift")->field("giftname")->where("id='$v[giftid]'")->find();
				$v['giftinfo']= $giftinfo;
            }else if($action=='buyvip'){
            	$giftinfo['giftname']=$Think.\lang('BALANCE_PURCHASE_VIP');
				$v['giftinfo']= $giftinfo;
            }else if($action=='uppop'){
				$giftinfo['giftname']=$Think.\lang('POPULAR_ON');
				$v['giftinfo']= $giftinfo;
			}else if($action=='pop_refund'){
				$giftinfo['giftname']=$Think.\lang('TOP_REFUND');
				$v['giftinfo']= $giftinfo;
			}else if($action=='watchvideo'){
				$giftinfo['giftname']=$Think.\lang('ADMIN_ADVERT_INDEX_GUANKANSHIPING');
				$v['giftinfo']= $giftinfo;
			}else{
				$giftinfo['giftname']=$Think.\lang('UNKNOWN');
				$v['giftinfo']= $giftinfo;
			}

    
            $v['giftname']= $giftinfo['giftname']."(".$v['giftid'].")";
           
            $v['type']= $this->getType($v['type']);
            $v['action']= $this->getAction($v['action']);
			$v['addtime']=date("Y-m-d H:i:s",$v['addtime']); 
             
            $lists[$k]=$v;     

		}

        
        $cellName = array('A','B','C','D','E','F','G','H','I','J');
        $xlsCell  = array(
            array('id','序号'),
            array('type','收支类型'),
            array('action','收支行为'),
            array('user_nicename','会员 (ID)'),
            array('touser_nicename','主播 (ID)'),
            array('giftname','行为说明 (ID)'),
            array('giftcount','数量'),
            array('totalcoin','总价'),
            array('showid','直播id'),
            array('addtime','时间')
        );
        exportExcel($xlsName,$xlsCell,$lists,$cellName);
    }
     


}
