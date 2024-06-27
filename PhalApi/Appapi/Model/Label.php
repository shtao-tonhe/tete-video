<?php

class Model_Label extends PhalApi_Model_NotORM {
    
    /* 标签列表 */
    public function getList(){
        
        $list=DI()->notorm->label
                ->select("id,name")
                ->order("orderno asc")
                ->fetchAll();
                
        return $list;
    }


    /* 标签列表 */
    public function searchLabel($key,$p){
        
        $list=DI()->notorm->label
                ->select("id,name")
                ->where('name like ?','%'.$key.'%')
                ->order("orderno asc")
                ->fetchAll();
                
        return $list;
    }

    /* 标签信息 */
    public function getLabel($id){
        
        $info=DI()->notorm->label
                ->select("id,name,des,thumb")
                ->where('id=?',$id)
                ->fetchOne();
        if($info){
            $info['thumb']=get_upload_path($info['thumb']);
        }
                
        return $info;
    }

    /* 标签下视频数量 */
    public function getVideos($labelid){
        
        $info=DI()->notorm->user_video
                ->where('labelid=?',$labelid)
                ->count();
                
        return $info;
    }

}
