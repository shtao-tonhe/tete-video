<?php

/**
 * 收入记录
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class VotesrecordController extends AdminbaseController {
    
    protected function getType($k=''){
        $type=array(
            '1'=>$Think.\lang('INVITE_USERS'),
            '2'=>$Think.\lang('ADMIN_SETTING_CONFIGPRI_DAY_WATCH_VEDIO'),
            '3'=>$Think.\lang('PAID_VIDEO_REVENUE'),
            '4'=>$Think.\lang('VIDEO_GIFTS'),
            '5'=>$Think.\lang('GIFTS_GIVE_IN_STUDIO'),
            '6'=>$Think.\lang('OPEN_GUARD'),
        );
        if($k===''){
            return $type;
        }

        return isset($type[$k]) ? $type[$k] : '';
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
        
        $type=isset($data['type']) ? $data['type']: '';
        if($type!=''){
            $map[]=['action','=',$type];
        }
        
        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
            
            $map[]=['uid','=',$uid];
            
        }

        $touid=isset($data['touid']) ? $data['touid']: '';
        if($touid!=''){
            
            $map[]=['touid','=',$touid];
            
        }
        
        $lists = Db::name("votes_record")
            ->where($map)
			->order("addtime desc")
			->paginate(20);
        
        $lists->each(function($v,$k){
			$v['userinfo']=getUserInfo($v['uid']);
            if($v['touid']){
                $v['touserinfo']=getUserInfo($v['touid']);
            }else{
                $v['touserinfo']=[];
            }
            
            return $v;           
        });
        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);

        $configpub=getConfigPub();
        $this->assign('name_votes',$configpub['name_votes']?$configpub['name_votes']:'');

        $this->assign('type',$this->getType());
    	
       
    	return $this->fetch();
    }
     
    
    function del(){
        $id = $this->request->param('id', 0, 'intval');

        if($id){
            $rs=DB::name('votes_record')->where("id={$id}")->delete();
            if(!$rs){
                $this->success($Think.\lang('DELETE_SUCCESS'));
            }
        }else{
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }
                    
        							  			
    }

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
        
        $type=isset($data['type']) ? $data['type']: '';
        if($type!=''){
            $map[]=['type','=',$type];
        }
        
        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
            
            $map[]=['uid','=',$uid];
            
        }

        $touid=isset($data['touids']) ? $data['touid']: '';
        if($touid!=''){
            
            $map[]=['touid','=',$touid];
            
        }

        $configpub=getConfigPub();
        $name_coin=$configpub['name_coin'];
        
        
        $xlsName  = "收入记录";

        $xlsData=Db::name("votes_record")
            ->where($map)
            ->order('id desc')
			->select()
            ->toArray();
        foreach ($xlsData as $k => $v){

            $userinfo=getUserInfo($v['uid']);
            $touserinfo=getUserInfo($v['touid']);

            $xlsData[$k]['user_nicename']= $userinfo['user_nicename']."(".$v['uid'].")";
            $xlsData[$k]['touser_nicename']= $touserinfo['user_nicename']."(".$v['touid'].")";
            $xlsData[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']); 
            $xlsData[$k]['type']=$this->getType($v['action']);
            $xlsData[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
        }

        
        $cellName = array('A','B','C','D','E','F','G');
        $xlsCell  = array(
            array('id','序号'),
            array('type','收支行为'),
            array('user_nicename','会员(ID)'),
            array('touser_nicename','上级会员(ID)'),
            array('votes',$name_coin),
            array('touid_votes','上级'.$name_coin),
            array('addtime','时间'),
           
        );
        exportExcel($xlsName,$xlsCell,$xlsData,$cellName);
    }

}
