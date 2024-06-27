<?php

class Domain_User {

	public function getBaseInfo($userId) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getBaseInfo($userId);

			return $rs;
	}
	
	public function checkName($uid,$name) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->checkName($uid,$name);

			return $rs;
	}
	
	public function userUpdate($uid,$fields) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->userUpdate($uid,$fields);

			return $rs;
	}
	
	
	public function getChargeRules() {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getChargeRules();

			return $rs;
	}

	
	public function setAttent($uid,$touid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setAttent($uid,$touid);

			return $rs;
	}
	
	public function setBlack($uid,$touid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setBlack($uid,$touid);

			return $rs;
	}
	
	public function getFollowsList($uid,$touid,$p,$key) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getFollowsList($uid,$touid,$p,$key);

			return $rs;
	}
	
	public function getFansList($uid,$touid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getFansList($uid,$touid,$p);

			return $rs;
	}

	public function getBlackList($uid,$touid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getBlackList($uid,$touid,$p);

			return $rs;
	}

	
	public function getUserHome($uid,$touid) {
		$rs = array();

		$model = new Model_User();
		$rs = $model->getUserHome($uid,$touid);
		return $rs;
	}			
	
	public function checkMobile($uid,$mobile) {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->checkMobile($uid,$mobile);

        return $rs;
    }

    public function getLikeVideos($uid,$touid,$p){
    	$rs = array();
                
        $model = new Model_User();
        $rs = $model->getLikeVideos($uid,$touid,$p);

        return $rs;
    }

    public function getBalance($uid) {
    	
		$rs = array();

		$model = new Model_User();
		$rs = $model->getBalance($uid);

		return $rs;
	}

	public function getVipRules() {
		$rs = array();

		$model = new Model_User();
		$rs = $model->getVipRules();

		return $rs;
	}

	public function checkLiveVipStatus($uid) {
		$rs = array();

		$model = new Model_User();
		$rs = $model->checkLiveVipStatus($uid);

		return $rs;
	}
	
	public function seeDailyTasks($uid){
		$rs = array();

		$model = new Model_User();
		$rs = $model->seeDailyTasks($uid);

		return $rs;
	}

	public function receiveTaskReward($uid,$taskid){
		$rs = array();

		$model = new Model_User();
		$rs = $model->receiveTaskReward($uid,$taskid);

		return $rs;
	}

	public function setBeautyParams($uid,$params){
		$rs = array();

		$model = new Model_User();
		$rs = $model->setBeautyParams($uid,$params);

		return $rs;
	}

	public function getBeautyParams($uid){
		$rs = array();

		$model = new Model_User();
		$rs = $model->getBeautyParams($uid);

		return $rs;
	}
	
	//用户申请店铺余额提现
	public function setShopCash($data){
		$rs = array();

		$model = new Model_User();
		$rs = $model->setShopCash($data);

		return $rs;
	}
	//判断用户是否第一次关注对方
	public function isFirstAttent($uid,$touid){
		$rs = array();

		$model = new Model_User();
		$rs = $model->isFirstAttent($uid,$touid);

		return $rs;
	}

	public function BraintreeCallback($uid,$orderno,$ordertype,$nonce,$money){
		$rs = array();

		$model = new Model_User();
		$rs = $model->BraintreeCallback($uid,$orderno,$ordertype,$nonce,$money);

		return $rs;
	}

}
