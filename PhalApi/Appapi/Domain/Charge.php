<?php

class Domain_Charge {
	public function getOrderId($chargeid,$orderinfo) {
		$rs = array();

		$model = new Model_Charge();
		$rs = $model->getOrderId($chargeid,$orderinfo);

		return $rs;
	}
	
}
