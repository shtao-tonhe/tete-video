<?php

class Domain_Vipcharge {
	public function getOrderId($chargeid,$orderinfo) {
		$rs = array();

		$model = new Model_Vipcharge();
		$rs = $model->getOrderId($chargeid,$orderinfo);

		return $rs;
	}

	//余额支付
	public function balancePay($chargeid,$orderinfo){
		$rs = array();

		$model = new Model_Vipcharge();
		$rs = $model->balancePay($chargeid,$orderinfo);

		return $rs;
	}
	
}
