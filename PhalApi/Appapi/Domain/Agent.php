<?php

class Domain_Agent {
    
	
	public function setAgent($uid,$code) {
		$rs = array();

		$model = new Model_Agent();
		$rs = $model->setAgent($uid,$code);

		return $rs;
	}

	public function setViewLength($uid,$length) {
		$rs = array();

		$model = new Model_Agent();
		$rs = $model->setViewLength($uid,$length);

		return $rs;
	}

	public function getCode($uid) {
		$rs = array();

		$model = new Model_Agent();
		$rs = $model->getCode($uid);

		return $rs;
	}

	public function checkAgent($uid){
		$rs = array();

		$model = new Model_Agent();
		$rs = $model->checkAgent($uid);

		return $rs;
	}
	
}
