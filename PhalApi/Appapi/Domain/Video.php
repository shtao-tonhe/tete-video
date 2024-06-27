<?php
 
class Domain_Video {
	public function setVideo($data,$music_id) {
		$rs = array();

		$model = new Model_Video();
		$rs = $model->setVideo($data,$music_id);

		return $rs;
	}
	
    public function setComment($data) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->setComment($data);

        return $rs;
    }
    public function addView($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->addView($uid,$videoid);

        return $rs;
    }
    public function addLike($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->addLike($uid,$videoid);

        return $rs;
    }


    public function addShare($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->addShare($uid,$videoid);

        return $rs;
    }


    public function addCommentLike($uid,$commentid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->addCommentLike($uid,$commentid);

        return $rs;
    }
	public function getVideoList($uid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getVideoList($uid,$p);

        return $rs;
    }
	public function getAttentionVideo($uid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getAttentionVideo($uid,$p);

        return $rs;
    }
	public function getVideo($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getVideo($uid,$videoid);

        return $rs;
    }
	public function getComments($uid,$videoid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getComments($uid,$videoid,$p);

        return $rs;
    }

	public function getReplys($uid,$commentid,$last_replyid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getReplys($uid,$commentid,$last_replyid,$p);

        return $rs;
    }
	
	public function delComments($uid,$videoid,$commentid,$commentuid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->delComments($uid,$videoid,$commentid,$commentuid);

        return $rs;
    }

	public function getMyVideo($uid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getMyVideo($uid,$p);

        return $rs;
    }
	
	public function del($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->del($uid,$videoid);

        return $rs;
    }
 
	public function getHomeVideo($uid,$touid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getHomeVideo($uid,$touid,$p);

        return $rs;
    }
 
    public function report($data) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->report($data);

        return $rs;
    }

    public function getRecommendVideos($uid,$p,$isstart,$mobileid){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getRecommendVideos($uid,$p,$isstart,$mobileid);

        return $rs;
    }

   
	
	
	public function getNearby($uid,$lng,$lat,$city,$p){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getNearby($uid,$lng,$lat,$city,$p);
        
        return $rs;
    }

    public function getReportContentlist() {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getReportContentlist();

        return $rs;
    }

    public function setConversion($videoid){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->setConversion($videoid);

        return $rs;
    }

    public function getLabelVideoList($uid,$labelid,$p){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getLabelVideoList($uid,$labelid,$p);

        return $rs;
    }

    public function getViewRecord($uid,$p){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getViewRecord($uid,$p);

        return $rs;
    }

    public function getClassLists(){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getClassLists();

        return $rs;
    }


    public function searchClassLists($keywords){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->searchClassLists($keywords);

        return $rs;
    }

    public function getVideoListByClass($classid,$p){
        $rs=array();
        $model=new Model_Video();
        $rs=$model->getVideoListByClass($classid,$p);
        return $rs;
    }

    public function setVideoPay($uid,$videoid){
        $rs=array();
        $model=new Model_Video();
        $rs=$model->setVideoPay($uid,$videoid);
        return $rs;
    }

    public function getVideoListByMusic($uid,$musicid,$p){
        $rs=array();
        $model=new Model_Video();
        $rs=$model->getVideoListByMusic($uid,$musicid,$p);
        return $rs;
    }

    public function getMusicInfo($musicid){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getMusicInfo($musicid);

        return $rs;
    }

    public function videoSendGift($data,$gift_info,$ispack){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->videoSendGift($data,$gift_info,$ispack);

        return $rs;
    }

    public function deltViewRecord($uid,$videoid_arr){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->deltViewRecord($uid,$videoid_arr);

        return $rs;
    }
}
