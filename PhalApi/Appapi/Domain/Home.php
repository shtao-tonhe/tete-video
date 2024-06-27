<?php

class Domain_Home {

	public function search($uid,$key,$p) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->search($uid,$key,$p);
				
        return $rs;
    }
	


    public function videoSearch($uid,$key,$p){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->videoSearch($uid,$key,$p);
                
        return $rs;
    }

    public function getWeekShowLists(){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getWeekShowLists();
                
        return $rs;
    }
	
}
