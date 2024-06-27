<?php

/**
 * vip充值规则
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;


class VipchargerulesController extends AdminbaseController {

	//列表
    public function index(){
		$lists=Db::name("vip_charge_rules")
            ->where(function (Query $query) {
                

            })
            ->order("orderno asc")
            ->paginate(20);
			
			
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);	

    	 // 获取分页显示
        $page = $lists->render();
		
        $this->assign('lists', $lists);
        $this->assign('page', $page);
		
		
		return $this->fetch();
	}

	/*添加*/
	public function add(){
		$configPub=getConfigPub();
    	$this->assign("name_coin",$configPub['name_coin']);
		return $this->fetch();
	}


	/*添加提交*/
	public function add_post(){

		if($this->request->isPost()) {
			
			$data = $this->request->param();
			
			$name=trim($data['name']);
			$days=$data['days'];
			$orderno=$data['orderno'];

			if($name==""){
				$this->error($Think.\lang('FILL_IN_NAME_OF_RECHARGE_RULE'));
			}


			if(!is_numeric($days)){
				$this->error($Think.\lang('FILL_IN_NUM_OF_VIP_DAYS'));
			}

			if($days<0){
				$this->error($Think.\lang('VIP_DAYS_MUST_GREATER_ZERO'));
			}

			if(!is_numeric($orderno)){
				$this->error($Think.\lang('FILL_IN_NUM_FOR_SORTING_RULES'));
			}

			if($orderno<0){
				$this->error($Think.\lang('CONLLATION_MUST_GREATER_ZERO'));
			}
			
			
			$isexit=Db::name("vip_charge_rules")
				->where("name='{$name}' or days='{$days}'")
				->find();	
			if($isexit){
				$this->error($Think.\lang('VIP_RULE_ALREADY_EXISTS'));
			}
			
			$data['name']=$name;
			$data['addtime']=time();
			
			$result=Db::name("vip_charge_rules")->insert($data);

			if($result){
				$this->resetcache();
				$this->success($Think.\lang('ADD_SUCCESS'));
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}
		}

	}


    /*删除*/
	public function del(){

		$id = $this->request->param('id');
		if($id){
			$result=Db::name("vip_charge_rules")
				->where("id={$id}")
				->delete();

			if($result){
				$this->resetcache();
				$this->success($Think.\lang('DELETE_SUCCESS'));
			}else{
				$this->error($Think.\lang('DELETE_FAILED'));
			}			
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
	}

	/*分类编辑*/
	public function edit(){
		$id = $this->request->param('id');
		if($id){
			$info=Db::name("vip_charge_rules")
				->where("id={$id}")
				->find();
			$configPub=getConfigPub();
    		$this->assign("name_coin",$configPub['name_coin']);
			$this->assign("info",$info);
		}else{
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
		
		return $this->fetch();
	}

	/*分类编辑提交*/
	public function edit_post(){

		if($this->request->isPost()) {
			
			$data = $this->request->param();	

			
			$id=$data["id"];
			$name=$data["name"];
			$orderno=$data["orderno"];
			$days=$data["days"];

			if(!trim($name)){
				$this->error($Think.\lang('CATEGORY_TITLE_NOT_EMPTY'));
			}

			if(!is_numeric($orderno)){
				$this->error($Think.\lang('FILL_IN_THE_NUM_OF_SORTING_NUM'));
			}

			if($orderno<0){
				$this->error($Think.\lang('THE_SORT_MUST_GREATER_ZERO'));
			}

			if(!is_numeric($days)){
				$this->error($Think.\lang('FILL_IN_NUM_OF_RECHARGE_DAYS'));
			}

			if($days<0){
				$this->error($Think.\lang('RECHARGE_DAYS_MUST_GREATER_ZERO'));
			}
		
			$isexit=Db::name("vip_charge_rules")
				->where("id!={$id} and (name='{$name}' or days='{$days}')")
				->find();
			if($isexit){
				$this->error($Think.\lang('VIP_RULE_ALREADY_EXISTS'));
			}
			
			$result=Db::name("vip_charge_rules")
				->update($data);
				
			
			if($result!==false){
				$this->resetcache();
				$this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
			}else{
				$this->error($Think.\lang('UPDATE_FAILED'));
			}
		}

	}


	public function listorderset(){
		$ids=$this->request->param('listorders');

        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name("vip_charge_rules")->where(array('id' => $key))->update($data);
        }
                
        $status = true;
        if ($status) {

            $this->resetcache();
            $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
        } else {
            $this->error($Think.\lang('SORTING_UPDATE_FAILED'));
        }
	}

	public function resetcache(){
		$key='getVipChargeRules';
        $rules= Db::name("vip_charge_rules")
            ->field('id,name,money,days,coin')
            ->order('orderno asc')
            ->select();
        setcaches($key,$rules);
        return 1;
	}
	

}
