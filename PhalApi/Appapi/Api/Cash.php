<?php

class Api_Cash extends PhalApi_Api {  

	public function getRules() {
		return array(
			'getAccountList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
			),

            'setAccount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'int', 'desc' => '账号类型，1支付宝，2微信，3银行卡'),
                'account_bank' => array('name' => 'account_bank', 'type' => 'string', 'default' => '', 'desc' => '银行名称'),
                'account' => array('name' => 'account', 'type' => 'string', 'desc' => '账号'),
                'name' => array('name' => 'name', 'type' => 'string', 'default' => '', 'desc' => '姓名'),
			),
            
            'delAccount' => array(
				'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
                'id' => array('name' => 'id', 'type' => 'int','desc' => '账号ID'),
			),
				
			'getProfit' => array(
				'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
			),
			
			'setCash' => array(
				'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
				'accountid' => array('name' => 'accountid', 'type' => 'int','desc' => '账号ID'),
				'money' => array('name' => 'money', 'type' => 'int', 'desc' => '提现金额'),
			),
		);
	}

	/**
	 * 获取用户提现账号 
	 * @desc 用于获取用户提现账号
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].id 账号ID
	 * @return string info[].type 账号类型
	 * @return string info[].account_bank 银行名称
	 * @return string info[].account 账号
	 * @return string info[].name 姓名
	 * @return string msg 提示信息
	 */
	public function getAccountList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        if($uid<1 || $token==''){
            $rs['code'] = 1001;
			$rs['msg'] = $Think.\lang('INFORMATION_ERROR');
			return $rs;
        }

        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
    

        $domain = new Domain_Cash();
        $info = $domain->getAccountList($uid);

		$rs['info']=$info;

		return $rs;
	}	

	/**
	 * 用户添加提现账号 
	 * @desc 用于用户添加提现账号
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function setAccount() {
		$rs = array('code' => 0, 'msg' => $Think.\lang('ADD_SUCCESS'), 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        if($uid<1 || $token==''){
            $rs['code'] = 1001;
			$rs['msg'] = $Think.\lang('INFORMATION_ERROR');
			return $rs;
        }

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
        
        $type=checkNull($this->type);
        $account_bank=checkNull($this->account_bank);
        $account=checkNull($this->account);
        $name=checkNull($this->name);

        if($type==3){
            if($account_bank==''){
                $rs['code'] = 1001;
                $rs['msg'] = '银行名称不能为空';
                return $rs;
            }
        }
        
        if($account==''){
            $rs['code'] = 1002;
            $rs['msg'] = '账号不能为空';
            return $rs;
        }
        if($type!=2){
            if($name==''){
                $rs['code'] = 1003;
                $rs['msg'] = '姓名不能为空';
                return $rs;
            }
        }
              
        
        $data=array(
            'uid'=>$uid,
            'type'=>$type,
            'account_bank'=>$account_bank,
            'account'=>$account,
            'name'=>$name,
            'addtime'=>time(),
        );
        
        $domain = new Domain_Cash();
        $result = $domain->setAccount($data);

        if(!$result){
            $rs['code'] = 1003;
            $rs['msg'] = '添加失败，请重试';
            return $rs;
        }
        
        $rs['info'][0]=$result;

		return $rs;
	}	


	/**
	 * 用户删除提现账号 
	 * @desc 用于用户删除提现账号
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function delAccount() {
		$rs = array('code' => 0, 'msg' => $Think.\lang('DELETE_SUCCESS'), 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        if($uid<1 || $token==''){
            $rs['code'] = 1001;
			$rs['msg'] = $Think.\lang('INFORMATION_ERROR');
			return $rs;
        }
        
        $id=checkNull($this->id);
        
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}        
        
        $data=array(
            'uid'=>$uid,
            'id'=>$id,
        );
        
        $domain = new Domain_Cash();
        $result = $domain->delAccount($data);

        if(!$result){
            $rs['code'] = 1003;
            $rs['msg'] = '删除失败，请重试';
            return $rs;
        }

		return $rs;
	}
    
	/**
	 * 获取用户收益，包括可提现金额，今日可提现金额
	 * @desc 用于获取用户收益，包括可提现金额，今日可提现金额
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].votes 余额
	 * @return string info[0].total 可提现金额
	 * @return string info[0].cash_prop 去除平台抽成的提现所得，用户输入的提现金额数直接乘以该数就能得到用户实际或的金额
	 * @return string info[0].tips 提示语
	 * @return string msg 提示信息
	 */
	public function getProfit() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        if($uid<1 || $token==''){
            $rs['code'] = 1001;
			$rs['msg'] = $Think.\lang('INFORMATION_ERROR');
			return $rs;
        }
        
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		} 
		
		$domain = new Domain_Cash();
		$info = $domain->getProfit($uid);
	 
		$rs['info'][0]=$info;
		return $rs;
	}	
	
	/**
	 * 用户提现
	 * @desc 用于用户进行提现
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 提现成功信息
	 * @return string msg 提示信息
	 */
	public function setCash() {
		$rs = array('code' => 0, 'msg' => $Think.\lang('ADMIN_CASH_INDEX_WITHDRAWAL_SUCCEED'), 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        if($uid<1 || $token==''){
            $rs['code'] = 1001;
			$rs['msg'] = $Think.\lang('INFORMATION_ERROR');
			return $rs;
        }
        
        $accountid=checkNull($this->accountid);		
        $money=checkNull($this->money);		
        
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
        
        if(!$accountid){
            $rs['code'] = 1001;
			$rs['msg'] = '请选择提现账户';
			return $rs;
        }
        
        if($money<=0){
            $rs['code'] = 1002;
			$rs['msg'] = '请输入有效的提现金额';
			return $rs;
        }
		
        $data=array(
            'uid'=>$uid,
            'accountid'=>$accountid,
            'money'=>$money,
        );
        
        $config=getConfigPri();
        
		$domain = new Domain_Cash();
		$info = $domain->setCash($data);
        
		if($info==1001){
			$rs['code'] = 1001;
			$rs['msg'] = '余额不足';
			return $rs;
		}else if(!$info){
			$rs['code'] = 1002;
			$rs['msg'] = '提现失败，请重试';
			return $rs;
		}else if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = '每月只可提现'.$config['cash_max_times'].'次,已达上限';
			return $rs;
		}else if($info==1004){
			$rs['code'] = 1004;
			$rs['msg'] = '提现最低额度为'.$config['cash_min'].'元';
			return $rs;
		}else if($info==1005){
			$rs['code'] = 1005;
			$rs['msg'] = '提现金额超过可提现金额';
			return $rs;
		}else if($info==1006){
			$rs['code'] = 1006;
			$rs['msg'] = '不在提现期限内，不能提现';
			return $rs;
		}else if($info==1007){
			$rs['code'] = 1007;
			$rs['msg'] = '提现账号信息不正确';
			return $rs;
		}else if($info==1008){
			$rs['code'] = 1008;
			$rs['msg'] = '提现比例不正确';
			return $rs;
		}
	 
		$rs['info'][0]['msg']=$Think.\lang('ADMIN_CASH_INDEX_WITHDRAWAL_SUCCEED');
		return $rs;
	}
} 
