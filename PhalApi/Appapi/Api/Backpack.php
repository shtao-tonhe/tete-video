<?php
/**
 * 背包
 */
class Api_Backpack extends PhalApi_Api {

	public function getRules() {
		return array(
            'getBackpack' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
			),
		);
	}
	

	/**
	 * 获取用户的背包礼物
	 * @desc 用于 获取用户的背包礼物
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].nums 数量
	 * @return string msg 提示信息
	 */
	public function getBackpack() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        if($uid<0 || $token=='' ){
            $rs['code'] = 1000;
			$rs['msg'] =$Think.\lang('INFORMATION_ERROR');
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

		$domain = new Domain_Backpack();
		$giftlist = $domain->getBackpack($uid);
		
		$domain2 = new Domain_User();
		$coin=$domain2->getBalance($uid);

		
		$rs['info'][0]['giftlist']=$giftlist;
		$rs['info'][0]['coin']=$coin['coin'];
		
		return $rs;			
	}		
	

}
