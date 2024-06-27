<?php

class Model_Charge extends PhalApi_Model_NotORM {
	/* 订单号 */
	public function getOrderId($chargeid,$orderinfo) {
		
		$charge=DI()->notorm->charge_rules->select('*')->where('id=?',$chargeid)->fetchOne();
		
		if(!$charge || $charge['money']!=$orderinfo['money'] || ($charge['coin']!=$orderinfo['coin']  && $charge['coin_ios']!=$orderinfo['coin'] && $charge['coin_paypal']!=$orderinfo['coin'])){
			return 1003;
		}
		
		$orderinfo['coin_give']=$charge['give'];
		

		$result= DI()->notorm->user_charge->insert($orderinfo);

		return $result;
	}			

}
