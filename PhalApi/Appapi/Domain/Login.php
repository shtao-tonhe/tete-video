<?php

class Domain_Login {

    public function userLogin($user_login,$source,$mobileid) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->userLogin($user_login,$source,$mobileid);

        return $rs;
    }

	

    public function userLoginByThird($openid,$type,$nickname,$avatar,$source,$mobileid) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->userLoginByThird($openid,$type,$nickname,$avatar,$source,$mobileid);

        return $rs;
    }

    public function upUserPush($uid,$pushid) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->upUserPush($uid,$pushid);

        return $rs;
    }

    public function getCancelCondition($uid){
        $rs = array();

        $model = new Model_Login();
        $rs = $model->getCancelCondition($uid);

        return $rs;  
    }

    public function cancelAccount($uid){
        $rs = array();

        $model = new Model_Login();
        $rs = $model->cancelAccount($uid);

        return $rs;
    }	

}
