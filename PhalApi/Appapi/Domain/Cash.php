<?php

class Domain_Cash {
    
	public function getAccountList($uid) {
        $rs = array();
                
        $model = new Model_Cash();
        $rs = $model->getAccountList($uid);

        return $rs;
    }

	public function setAccount($data) {
        $rs = array();
                
        $model = new Model_Cash();
        $rs = $model->setAccount($data);

        return $rs;
    }

	public function delAccount($data) {
        $rs = array();
                
        $model = new Model_Cash();
        $rs = $model->delAccount($data);

        return $rs;
    }
    
	public function getProfit($uid) {
        $rs = array();

        $model = new Model_Cash();
        $rs = $model->getProfit($uid);

        return $rs;
	}

	public function setCash($data) {
        $rs = array();

        $model = new Model_Cash();
        $rs = $model->setCash($data);

        return $rs;
	}
	
}
