<?php

class Domain_Live {

    public function getRecommendLists($p){
        $rs = array();

        $model = new Model_Live();
        $rs = $model->getRecommendLists($p);
        return $rs;
    }

	public function getGiftList() {
        
        $key='getGiftList';
        $list=getcaches($key);

        //$list=false;

        if(!$list){
            $model = new Model_Live();
            $list = $model->getGiftList();
            if($list){
                setcaches($key,$list);
            }
        }

        $configpub=getConfigPub();
        
        foreach($list as $k=>$v){
            $list[$k]['gifticon']=get_upload_path($v['gifticon']);
            $list[$k]['swf']=get_upload_path($v['swf']);
            $list[$k]['needcoin_name']=$v['needcoin'].$configpub['name_coin'];
        }   
        
        return $list;
    }


    public function checkBan($uid){
        $rs = array();

        $model = new Model_Live();
        $rs = $model->checkBan($uid);
        return $rs;
    }

    public function createRoom($uid,$data) {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->createRoom($uid,$data);
        return $rs;
    }

    public function changeLive($uid,$stream,$status) {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->changeLive($uid,$stream,$status);
        return $rs;
    }

    public function stopRoom($uid,$stream) {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->stopRoom($uid,$stream);
        return $rs;
    }

    public function stopInfo($stream) {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->stopInfo($stream);
        return $rs;
    }

    public function checkLive($uid,$liveuid,$stream) {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->checkLive($uid,$liveuid,$stream);
        return $rs;
    }

    public function checkShut($uid,$liveuid) {
        $rs = array();
        $model = new Model_Live();
        $rs = $model->checkShut($uid,$liveuid);
        return $rs;
    }

    public function getContribut($uid,$liveuid,$showid) {
        $rs = array();
        $model = new Model_Live();
        $rs = $model->getContribut($uid,$liveuid,$showid);
        return $rs;
    }

    public function sendGift($uid,$liveuid,$stream,$giftid,$giftcount,$ispack) {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->sendGift($uid,$liveuid,$stream,$giftid,$giftcount,$ispack);
        return $rs;
    }

    public function setAdmin($liveuid,$touid) {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->setAdmin($liveuid,$touid);
        return $rs;
    }

    public function getReportClass() {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->getReportClass();
        return $rs;
    }

    public function getAdminList($liveuid) {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->getAdminList($liveuid);
        return $rs;
    }

    public function setReport($uid,$touid,$content) {
        $rs = array();

        $model = new Model_Live();
        $rs = $model->setReport($uid,$touid,$content);
        return $rs;
    }

    public function setShutUp($uid,$liveuid,$touid,$showid) {
        $rs = array();
        $model = new Model_Live();
        $rs = $model->setShutUp($uid,$liveuid,$touid,$showid);
        return $rs;
    }

    public function kicking($uid,$liveuid,$touid) {
        $rs = array();
        $model = new Model_Live();
        $rs = $model->kicking($uid,$liveuid,$touid);
        return $rs;
    }

    public function superStopRoom($uid,$token,$liveuid,$type) {
        $rs = array();
        $model = new Model_Live();
        $rs = $model->superStopRoom($uid,$token,$liveuid,$type);
        return $rs;
    }

    public function checkLiveing($uid,$stream) {
        $rs = array();
        $model = new Model_Live();
        $rs = $model->checkLiveing($uid,$stream);
        return $rs;
    }  

	public function getLiveShowGoods($liveuid){
		$rs = array();
		$model = new Model_Live();
		$rs = $model->getLiveShowGoods($liveuid);
		return $rs;
	}

    public function setLiveGoodsIsShow($uid,$goodsid){
        $rs = array();
        $model = new Model_Live();
        $rs = $model->setLiveGoodsIsShow($uid,$goodsid);
        return $rs;
    }

    public function getClassLive($liveclassid,$p){
        $rs = array();

        $model = new Model_Live();
        $rs = $model->getClassLive($liveclassid,$p);
                
        return $rs;
    }

}
