<?php

/**
 * 店铺申请
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class ShopbondController extends AdminbaseController {
    protected function getStatus($k=''){
        $status=[
            '-1'=>$Think.\lang('DEDUCTER'),
            '0'=>$Think.\lang('RETURNED'),
            '1'=>$Think.\lang('PAID'),
        ];
        if($k==''){
            return $status;
        }
        return isset($status[$k])?$status[$k]:'';
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
        
        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
        }
        
        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
            
            $map[]=['uid','=',$uid];
            
        }
			

    	$lists = Db::name("shop_bond")
                ->where($map)
                ->order("id DESC")
                ->paginate(20);
        $lists->each(function($v,$k){
			$v['userinfo']=getUserInfo($v['uid']);
            return $v;           
        });
        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
        $this->assign('status', $this->getStatus());
    	
    	return $this->fetch();
    }
		
    function setstatus(){
        
        $id = $this->request->param('id', 0, 'intval');
        $status = $this->request->param('status', 0, 'intval');
        
        $info = DB::name('shop_bond')->where("id={$id}")->find();
        if(!$info){
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }
        
        if($info['status']!=1){
            $this->error($Think.\lang('NOT_DO_OPERATE_TIMES'));
        }
        
        $rs=DB::name("shop_bond")->where("id='{$id}' and status=1")->setField('status',$status);
        if($rs===false){
            $this->error($Think.\lang('OPERATION_FAILED'));
        }
        
		
		
        if($status==0){
            /* 退回 */
            $uid=$info['uid'];
            $total=$info['bond'];
            
            DB::name('user')->where("id={$uid}")->inc('coin',$total)->update();
            
            DB::name("user_coinrecord")->insert(array("type"=>'1',"action"=>'15',"uid"=>$uid,"touid"=>$uid,"giftid"=>0,"giftcount"=>1,"totalcoin"=>$total,"addtime"=>time() ));
        }
		
		
		$status_name=$status==0?$Think.\lang('ADMIN_GOODSCLASS_RETURN'):$Think.\lang('ADMIN_GOODSCLASS_DEDUCTION');
		$action=$status_name.$Think.\lang('STORE_DEPOSIT_ID').$info['uid'];
		
		setAdminLog($action);
        
        $this->success($Think.\lang('OPERATION_SUCCESSFUL'));
    }

		
}
