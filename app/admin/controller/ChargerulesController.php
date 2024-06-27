<?php

/**
 * 钻石充值记录
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;


class ChargerulesController extends AdminbaseController {

	//列表
    public function index(){

		$lists=Db::name("charge_rules")
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
			$configpub=getConfigPub();

			$name=trim($data['name']);
			$money=$data['money'];
			$orderno=$data['orderno'];
			$coin=$data['coin'];
			$coin_ios=$data['coin_ios'];
			$product_id=$data['product_id'];
			$give=$data['give'];
			$coin_paypal=$data['coin_paypal'];

			if($name==""){
				$this->error($Think.\lang('FILL_IN_NAME_OF_RECHARGE_RULE'));
			}

			if(!$money){
                $this->error($Think.\lang('FILL_IN_THE_PRICE'));
            }

            if(!is_numeric($money)){
                $this->error($Think.\lang('PRICE_MUST_BE_NUMERIC'));
            }

            if($money<=0||$money>99999999){
                $this->error($Think.\lang('THE_PRICE_IN'));
            }

            $data['money']=round($money,2);


			if(!is_numeric($orderno)){
				$this->error($Think.\lang('FILL_IN_THE_NUM_OF_SORTING_NUM'));
			}

			if($orderno<0){
				$this->error($Think.\lang('THE_SORT_MUST_GREATER_ZERO'));
			}
			
			if(!$coin){
                $this->error($Think.\lang('PLEASE_FILL_IN').$configpub['name_coin']);
            }

            if(!is_numeric($coin)){
                $this->error($configpub['name_coin'].$Think.\lang('MUST_BE_NUMERIC'));
            }

            if($coin<1||$coin>99999999){
                $this->error($configpub['name_coin'].$Think.\lang('BETWEEN_ONE_AND'));
            }

            if(floor($coin)!=$coin){
                $this->error($configpub['name_coin'].$Think.\lang('MUST_BE_AN_INTEGER'));
            }

            if(!$coin_ios){
                $this->error($Think.\lang('PLEASE_FILL_IN_APPLE_PAYMENT').$configpub['name_coin']);
            }

            if(!is_numeric($coin_ios)){
                $this->error($Think.\lang('APPLE_PAY').$configpub['name_coin'].$Think.\lang('MUST_BE_NUMERIC'));
            }

            if($coin_ios<1||$coin_ios>99999999){
                $this->error($Think.\lang('APPLE_PAY').$configpub['name_coin'].$Think.\lang('BETWEEN_ONE_AND'));
            }

            if(floor($coin_ios)!=$coin_ios){
                $this->error($Think.\lang('APPLE_PAY').$configpub['name_coin'].$Think.\lang('MUST_BE_AN_INTEGER'));
            }

            if($product_id==''){
                $this->error($Think.\lang('APPLE_ID_NOT_ENPTY'));
            }

			if($give==''){
               $this->error($Think.\lang('GIVE').$configpub['name_coin'].$Think.\lang('CONNOT_BE_EMPTY'));
            }

            if(!is_numeric($give)){
                $this->error($Think.\lang('GIVE').$configpub['name_coin'].$Think.\lang('MUST_BE_NUMERIC'));
            }

            if($give<0||$give>99999999){
                $this->error($Think.\lang('GIVE').$configpub['name_coin']."在0-99999999之间");
            }

            if(floor($give)!=$give){
                $this->error($Think.\lang('GIVE').$configpub['name_coin'].$Think.\lang('MUST_BE_AN_INTEGER'));
            }

            if($coin_paypal==''){
               $this->error($Think.\lang('PAYPAL_PAYMENT').$configpub['name_coin'].$Think.\lang('CONNOT_BE_EMPTY'));
            }

            if(!is_numeric($coin_paypal)){
                $this->error($Think.\lang('PAYPAL_PAYMENT').$configpub['name_coin'].$Think.\lang('MUST_BE_NUMERIC'));
            }

            if($coin_paypal<1||$coin_paypal>99999999){
                $this->error($Think.\lang('PAYPAL_PAYMENT').$configpub['name_coin'].$Think.\lang('BETWEEN_ONE_AND'));
            }

            if(floor($coin_paypal)!=$coin_paypal){
                $this->error($Think.\lang('PAYPAL_PAYMENT').$configpub['name_coin'].$Think.\lang('MUST_BE_AN_INTEGER'));
            }
			
			$isexit=Db::name("charge_rules")
				->where("name='{$name}'")
				->find();	
			if($isexit){
				$this->error('该规则名称已存在');
			}
			
			$data['name']=$name;
			$data['addtime']=time();
			
			$result=Db::name("charge_rules")->insert($data);

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
			$result=Db::name("charge_rules")
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
			$info=Db::name("charge_rules")
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
			$configpub=getConfigPub();
			
			$id=$data["id"];
			$name=$data["name"];
			$money=$data['money'];
			$orderno=$data["orderno"];
			$coin=$data['coin'];
			$product_id=$data['product_id'];
			$give=$data['give'];
            $coin_paypal=$data['coin_paypal'];
            $coin_ios=$data['coin_ios'];

			if(!trim($name)){
				$this->error($Think.\lang('CATEGORY_TITLE_NOT_EMPTY'));
			}

			$isexit=Db::name("charge_rules")
				->where("id!={$id} and name='{$name}'")
				->find();
			if($isexit){
				$this->error($Think.\lang('THE_NAME_ALREADY_EXISTS'));
			}

			if(!$money){
                $this->error($Think.\lang('FILL_IN_THE_PRICE'));
            }

            if(!is_numeric($money)){
                $this->error($Think.\lang('PRICE_MUST_BE_NUMERIC'));
            }

            if($money<=0||$money>99999999){
                $this->error($Think.\lang('THE_PRICE_IN'));
            }

            $data['money']=round($money,2);

			if(!is_numeric($orderno)){
				$this->error($Think.\lang('FILL_IN_THE_NUM_OF_SORTING_NUM'));
			}

			if($orderno<0){
				$this->error($Think.\lang('THE_SORT_MUST_GREATER_ZERO'));
			}

			if(!$coin){
                $this->error($Think.\lang('PLEASE_FILL_IN').$configpub['name_coin']);
            }

            if(!is_numeric($coin)){
                $this->error($configpub['name_coin'].$Think.\lang('MUST_BE_NUMERIC'));
            }

            if($coin<1||$coin>99999999){
                $this->error($configpub['name_coin'].$Think.\lang('BETWEEN_ONE_AND'));
            }

            if(floor($coin)!=$coin){
                $this->error($configpub['name_coin'].$Think.\lang('MUST_BE_AN_INTEGER'));
            }

            if($product_id==''){
                $this->error($Think.\lang('APPLE_ID_NOT_ENPTY'));
            }

            if($give==''){
               $this->error($Think.\lang('GIVE').$configpub['name_coin'].$Think.\lang('CONNOT_BE_EMPTY'));
            }

            if(!is_numeric($give)){
                $this->error($Think.\lang('GIVE').$configpub['name_coin'].$Think.\lang('MUST_BE_NUMERIC'));
            }

            if($give<0||$give>99999999){
                $this->error($Think.\lang('GIVE').$configpub['name_coin']."在0-99999999之间");
            }

            if(floor($give)!=$give){
                $this->error($Think.\lang('GIVE').$configpub['name_coin'].$Think.\lang('MUST_BE_AN_INTEGER'));
            }

            if(!$coin_ios){
                $this->error($Think.\lang('PLEASE_FILL_IN_APPLE_PAYMENT').$configpub['name_coin']);
            }

            if(!is_numeric($coin_ios)){
                $this->error($Think.\lang('APPLE_PAY').$configpub['name_coin'].$Think.\lang('MUST_BE_NUMERIC'));
            }

            if($coin_ios<1||$coin_ios>99999999){
                $this->error($Think.\lang('APPLE_PAY').$configpub['name_coin'].$Think.\lang('BETWEEN_ONE_AND'));
            }

            if(floor($coin_ios)!=$coin_ios){
                $this->error($Think.\lang('APPLE_PAY').$configpub['name_coin'].$Think.\lang('MUST_BE_AN_INTEGER'));
            }

            if($coin_paypal==''){
               $this->error($Think.\lang('PAYPAL_PAYMENT').$configpub['name_coin'].$Think.\lang('CONNOT_BE_EMPTY'));
            }

            if(!is_numeric($coin_paypal)){
                $this->error($Think.\lang('PAYPAL_PAYMENT').$configpub['name_coin'].$Think.\lang('MUST_BE_NUMERIC'));
            }

            if($coin_paypal<1||$coin_paypal>99999999){
                $this->error($Think.\lang('PAYPAL_PAYMENT').$configpub['name_coin'].$Think.\lang('BETWEEN_ONE_AND'));
            }

            if(floor($coin_paypal)!=$coin_paypal){
                $this->error($Think.\lang('PAYPAL_PAYMENT').$configpub['name_coin'].$Think.\lang('MUST_BE_AN_INTEGER'));
            }
		
			
			
			$result=Db::name("charge_rules")
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
            Db::name("charge_rules")->where(array('id' => $key))->update($data);
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
		$key='getChargeRules';
        $rules= Db::name("charge_rules")
            ->field('id,coin,coin_ios,money,product_id,give,coin_paypal')
            ->order('orderno asc')
            ->select();
        setcaches($key,$rules);
        return 1;
	}
	

}
