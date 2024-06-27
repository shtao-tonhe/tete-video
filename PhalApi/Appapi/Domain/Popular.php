<?php

class Domain_Popular {

    
    public function checkVideo($uid,$videoid) {
		$rs = array();

		$model = new Model_Popular();
		$rs = $model->checkVideo($uid,$videoid);

		return $rs;
	}

	public function setOrder($data) {
		$rs = array();

		$model = new Model_Popular();
		$rs = $model->setOrder($data);

		return $rs;
	}

	public function balancePay($data) {
		$rs = array();

		$model = new Model_Popular();
		$rs = $model->balancePay($data);

		return $rs;
	}

	public function getPutin($uid,$p) {
		$rs = array();

		$model = new Model_Popular();
		$rs = $model->getPutin($uid,$p);

		return $rs;
	}

	public function getOrderList($uid,$type,$p){
		$rs = array();

		$model = new Model_Popular();
		$rs = $model->getOrderList($uid,$type,$p);

		return $rs;
	}
	
}
