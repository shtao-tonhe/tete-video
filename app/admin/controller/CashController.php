<?php

/**
 * 提现
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class CashController extends AdminbaseController {
    protected function getStatus($k=''){
        $status=array(
            '0'=>$Think.\lang('UNHANDLE'),
            '1'=>$Think.\lang('ADMIN_CASH_INDEX_WITHDRAWAL_SUCCEED'),
            '2'=>$Think.\lang('ADMIN_CASH_INDEX_REFUSE_WITHDRAWAL'),
        );
        if($k===''){
            return $status;
        }
        
        return isset($status[$k]) ? $status[$k]: '';
    }
    
    protected function getTypes($k=''){
        $type=array(
            '1'=>$Think.\lang('ALIPAY'),
            '2'=>$Think.\lang('WECHAT'),
            '3'=>$Think.\lang('BANK_CARD'),
        );
        if($k===''){
            return $type;
        }
        
        return isset($type[$k]) ? $type[$k]: '';
    }
    
    function index(){
        $data = $this->request->param();
        $map=[];
		
        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
            $cash['type']=1;
        }
        
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
            $lianguid=getLianguser($uid);
            if($lianguid){
                $map[]=['uid',['=',$uid],['in',$lianguid],'or'];
            }else{
                $map[]=['uid','=',$uid];
            }
        }
        
        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['orderno|trade_no','like',"%".$keyword."%"];
        }
        
    	$lists = DB::name("user_cashrecord")
            ->where($map)
            ->order('id desc')
            ->paginate(20);
        
        $lists->each(function($v,$k){
            $v['userinfo']=getUserInfo($v['uid']);
            return $v;
        });
        
        $lists->appends($data);
        $page = $lists->render();

        $cashrecord_total = DB::name("user_cashrecord")->where($map)->sum("money");

        if($status==''){

            $success=$map;
            $success[]=['status','=',1];
            $fail=$map;
            $fail[]=['status','=',2];
            $wait=$map;
            $wait[]=['status','=',0];
            $cashrecord_success = DB::name("user_cashrecord")->where($success)->sum("money");
            $cashrecord_fail = DB::name("user_cashrecord")->where($fail)->sum("money");
            $cashrecord_wait = DB::name("user_cashrecord")->where($wait)->sum("money");
            $cash['success']=$cashrecord_success;
            $cash['fail']=$cashrecord_fail;
            $cash['wait']=$cashrecord_wait;
            $cash['type']=0;

        }

        $cash['total']=$cashrecord_total;
            
    	$this->assign('cash', $cash);
        
    	$this->assign('lists', $lists);

    	$this->assign('type', $this->getTypes());
    	$this->assign('status', $this->getStatus());
    	$this->assign("page", $page);
    	
    	return $this->fetch();
    }
		
    function del(){
        $id = $this->request->param('id', 0, 'intval');
        if($id){
            $result=DB::name("user_cashrecord")->delete($id);				
            if($result){
               
                $this->success($Think.\lang('DELETE_SUCCESS'));
             }else{
                $this->error($Think.\lang('DELETE_FAILED'));
             }
        }else{				
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }				
    }		

	function edit(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('user_cashrecord')
            ->where("id={$id}")
            ->find();

        if(!$data){
            $this->error($Think.\lang('INFORMATION_ERROR'));
        }

        $data['userinfo']=getUserInfo($data['uid']);

        $this->assign('type', $this->getTypes());
        $this->assign('status', $this->getStatus());
        $configpub=getConfigPub();
        $this->assign('name_coin',$configpub['name_coin']);
 
        $this->assign('cash', $data);
        return $this->fetch();
	}
    
    function edit_post(){
		if ($this->request->isPost()) {
            
            $data = $this->request->param();
            
			$status=$data['status'];
			$uid=$data['uid'];
			$votes=$data['votes'];
			$id=$data['id'];

			if($status=='0'){
				$this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
			}

            
            $data['uptime']=time();


            
			$rs = DB::name('user_cashrecord')->update($data);
            if($rs===false){
                $this->error($Think.\lang('UPDATE_FAILED'));
            }
            
            if($status=='2'){
                 DB::name("user")->where(["id"=>$uid])->setInc("votes",$votes);
                
            }
            
            $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
		}
	}
    
    function export(){

        $data = $this->request->param();
        $map=[];
		
        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
            $cash['type']=1;
        }
        
        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';
        
        if($start_time!=""){
           $map[]=['addtime','>=',strtotime($start_time)];
        }

        if($end_time!=""){
           $map[]=['addtime','<=',strtotime($end_time) + 60*60*24];
        }
        
        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['uid|orderno|trade_no','like',"%".$keyword."%"];
        }
        
        $xlsName  = "提现";
        
        $xlsData=DB::name("user_cashrecord")
            ->where($map)
            ->order('id desc')
            ->select()
            ->toArray();

        foreach ($xlsData as $k => $v){

            $userinfo=getUserInfo($v['uid']);
            $xlsData[$k]['user_nicename']= $userinfo['user_nicename']."(".$v['uid'].")";
            $xlsData[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']); 
            $xlsData[$k]['uptime']=date("Y-m-d H:i:s",$v['uptime']); 
            $xlsData[$k]['status']=$this->getStatus($v['status']);
            if($v['type']==1){ //支付宝
                $xlsData[$k]['account']=$this->getTypes($v['type']).'|'.$v['name'].'|'.$v['account'];
            }else if($v['type']==2){ //微信
                $xlsData[$k]['account']=$this->getTypes($v['type']).'|'.$v['account'];
            }else{
                $xlsData[$k]['account']=$this->getTypes($v['type']).'|'.$v['name'].'|'.$v['account'].'|'.$v['account_bank'];
            }
            
        }

        $cellName = array('A','B','C','D','E','F','G','H','I');
        $xlsCell  = array(
            array('id','序号'),
            array('user_nicename','主播名称'),
			array('votes','金币'),
            array('money','提现金额'),
            array('account','提现账号'),
            array('trade_no','第三方支付订单号'),
            array('status','状态'),
            array('addtime','提交时间'),
            array('uptime','处理时间'),
        );
        exportExcel($xlsName,$xlsCell,$xlsData,$cellName);
    }
    
}
