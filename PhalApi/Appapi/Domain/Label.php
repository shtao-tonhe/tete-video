<?php

class Domain_Label {
    
	public function getList() {
        $rs = array();
                
        $model = new Model_Label();
        $rs = $model->getList();

        return $rs;
    }

	public function searchLabel($key,$p) {
        $rs = array();
                
        $model = new Model_Label();
        $rs = $model->searchLabel($key,$p);

        return $rs;
    }

	public function getLabel($id) {
        $rs = array();
                
        $model = new Model_Label();
        $rs = $model->getLabel($id);

        return $rs;
    }

	public function getVideos($labelid) {
        $rs = array();
                
        $model = new Model_Label();
        $rs = $model->getVideos($labelid);

        return $rs;
    }

}
