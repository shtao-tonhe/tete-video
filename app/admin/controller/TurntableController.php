<?php

/**
 * 大转盘
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class TurntableController extends AdminbaseController {
    
    protected function getTypes($k=''){
        $type=[
            '0'=>$Think.\lang('NO_PRIZE'),
            '1'=>$Think.\lang('DIAMONDS'),
            '2'=>$Think.\lang('GIFT'),
            '3'=>$Think.\lang('OFFLINE_PRIZES'),
        ];
        
        if($k==''){
            return $type;
        }
        
        return isset($type[$k])?$type[$k]:'';
    }
    
    function index(){
        
        $lists = Db::name("turntable")
			->order("id asc")
			->paginate(20);
        
        $lists->each(function($v,$k){
            $name=$Think.\lang('NOT_PRIZES');
            if($v['type']==1){
                $name=$v['type_val'];
            }
            
            if($v['type']==2){
                $name=$Think.\lang('DELETED');
                $giftinfo=Db::name("gift")->field('giftname')->where("id={$v['type_val']}")->find();
                if($giftinfo){
                    $name=$giftinfo['giftname'];
                }
            }
            
            if($v['type']==3){
                $name=$v['type_val'];
            }
            
            $v['name']=$name;
            
			$v['thumb']=get_upload_path($v['thumb']);
            return $v;           
        });
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
        $this->assign('type', $this->getTypes());
    	
    	return $this->fetch();
    }
		
	
    function edit(){
        
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('turntable')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error($Think.\lang('INFORMATION_ERROR'));
        }
        
        /* $gift=Db::name('gift')->field('id,giftname')->where([ ['type','<>',3] ])->order('orderno desc')->select(); */
        $gift=Db::name('gift')->field('id,giftname')->where('type not in (3)')->order('orderno desc')->select();
		$data['thumb']=get_upload_path($data['thumb']);
            
        $this->assign('gift', $gift);
        
        $this->assign('data', $data);
        
        $this->assign('type', $this->getTypes());
        
        return $this->fetch();			
    }
    
    function editPost(){
		if ($this->request->isPost()) {
            
            $data      = $this->request->param();
            
            $type=$data['type'];
            if($type==1){
                $type_val=intval($data['coin']);
                if($type_val<1){
                    $this->error($Think.\lang('ENTER_CORRECT_NUM_OF_DIAMONDS'));
                }
                $data['type_val']=$type_val;
            }
             
            if($type==2){
                $type_val=intval($data['giftid']);
                 if($type_val<1){
                     $this->error($Think.\lang('ENTER_A_GIFT'));
                 }
                $data['type_val']=$type_val;
            }
             
            if($type==3){                     
                $type_val=$data['name'];
                if($type_val==''){
                    $this->error($Think.\lang('ENTER_PRIZE_NAME'));
                }
                $data['type_val']=$type_val;
                 
                $thumb=$data['thumb'];
                if($thumb==''){
                    $this->error($Think.\lang('UPLOAD_PRIZE_PICTURE'));
                }
                 
            }
            
            if($type==0){
                $data['type_val']=0;
                $data['thumb']='';
                $data['rate']=0;
            }
             
            $data['uptime']=time();
            
            unset($data['coin']);
            unset($data['name']);
            unset($data['giftid']);
            
			$rs = DB::name('turntable')->update($data);
            if($rs===false){
                $this->error($Think.\lang('UPDATE_FAILED'));
            }
            
            $this->resetcache();
            $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
		}
	}
    
    function resetcache(){
        $key='turntable';
        $list=Db::name('turntable')
                ->field("id,type,type_val,thumb,rate")
                ->select();
        if($list){
            setcaches($key,$list);
        }else{
			delcache($key);
		}
        return 1;
    }
    
    function index2(){
        
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
        
        $liveuid=isset($data['liveuid']) ? $data['liveuid']: '';
        if($liveuid!=''){
        
            $map[]=['liveuid','=',$liveuid];
            
        }
        
        
        $showid=isset($data['showid']) ? $data['showid']: '';
        if($showid!=''){
            $map[]=['showid','=',$showid];
        }
			

    	$lists = Db::name("turntable_log")
                ->where($map)
                ->order("id DESC")
                ->paginate(20);
                
        $lists->each(function($v,$k){
			$userinfo=getUserInfo($v['uid']);
            $v['userinfo']=$userinfo;
            $liveuidinfo=getUserInfo($v['liveuid']);
            $v['liveuidinfo']=$liveuidinfo;
            
            $winlist=[];
            if($v['iswin']==1){
                $winlist=Db::name("turntable_win")->where("logid={$v['id']}")->select();
                
                foreach($winlist as $k2=>$v2){
                    
                    if($v2['type']==3){
                        $name=$v2['type_val'];
                    }
                    
                    if($v2['type']==2){
                        $name=$Think.\lang('DELETED');
                        $giftinfo=Db::name("gift")->field('giftname')->where("id={$v2['type_val']}")->find();
                        if($giftinfo){
                            $name=$giftinfo['giftname'];
                        }
                    }
                    
                    if($v2['type']==1){
                        $name=$v2['type_val'];
                    }
                    
                    $v2['name']=$name;
                    $winlist[$k2]=$v2;
                }
            }
            
            $v['winlist']=$winlist;
            
            return $v;           
        });
        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
        $count=Db::name("turntable_log")->where($map)->count();
        $total=Db::name("turntable_log")->where($map)->sum('coin');
        if(!$total){
            $total=0;
        }
        
        $this->assign("count", $count);
        $this->assign("total", $total);
        $this->assign('type', $this->getTypes());
    	
    	return $this->fetch();
    }
        
    function index3(){
        
        $data = $this->request->param();
        $map=[];
        $map[]=['type','=',3];
        
        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
        }
        
        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
            $map[]=['uid','=',$uid];
        }
        
    	$lists = Db::name("turntable_win")
            ->where($map)
			->order("id desc")
			->paginate(20);
        
        $lists->each(function($v,$k){
			$userinfo=getUserInfo($v['uid']);
            
            $v['userinfo']=$userinfo;
            
            return $v;           
        });
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
        $this->assign('type', $this->getTypes());
    	
    	return $this->fetch();
        
    }
        
    function setStatus(){
        
        $id = $this->request->param('id', 0, 'intval');
        $status = $this->request->param('status', 0, 'intval');
        
        $rs = DB::name('turntable_win')->where("id={$id}")->update(['status'=>$status,'uptime'=>time()]);
        if(!$rs){
            $this->error($Think.\lang('OPERATION_FAILED'));
        }
        
        $this->success($Think.\lang('OPERATION_SUCCESSFUL'));
    }
		
}
