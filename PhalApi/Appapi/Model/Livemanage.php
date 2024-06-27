<?php

class Model_Livemanage extends PhalApi_Model_NotORM {
	/* 我的管理员 */
	public function getManageList($uid) {
        
        $rs=[
            'nums'=>'0',
            'total'=>'5',
            'list'=>[],
        ];
		
        $nums=DI()->notorm->user_livemanager
            ->where('liveuid=?',$uid)
            ->count();
            
        $list=DI()->notorm->user_livemanager
            ->select('uid')
            ->where('liveuid=?',$uid)
            ->fetchAll();
            
        foreach($list as $k=>$v){
            
            $userinfo=getUserInfo($v['uid']);
            
            $v['user_nicename']=$userinfo['user_nicename'];
            $v['avatar']=$userinfo['avatar'];
            $v['avatar_thumb']=$userinfo['avatar_thumb'];
            $v['sex']=$userinfo['sex'];
            $v['id']=$v['uid']; //ios专用

            $list[$k]=$v;
        }

        $rs['nums']=(string)$nums;
        $rs['list']=$list;
        
		return $rs;
	}
    
    /* 解除管理 */
	public function cancelManage($uid,$touid) {

        //判断用户是否是该房间管理员
        $info=DI()->notorm->user_livemanager
            ->where('liveuid=? and uid=?',$uid,$touid)
            ->fetchOne();
        if(!$info){
            return 1001;
        }
        
        $rs=DI()->notorm->user_livemanager
            ->where('liveuid=? and uid=?',$uid,$touid)
            ->delete();
            
		return $rs;
	}

	/* 我的房间 */
	public function getRoomList($uid) {

            
        $list=DI()->notorm->user_livemanager
            ->select('liveuid')
            ->where('uid=?',$uid)
            ->fetchAll();
            
        foreach($list as $k=>$v){
            
            $userinfo=getUserInfo($v['liveuid']);
            
            $v['user_nicename']=$userinfo['user_nicename'];
            $v['avatar']=$userinfo['avatar'];
            $v['avatar_thumb']=$userinfo['avatar_thumb'];
            $v['sex']=$userinfo['sex'];

            $list[$k]=$v;
        }

        
		return $list;
	}

	/* 禁言用户 */
	public function getShutList($liveuid) {
        

            
        $list=DI()->notorm->user_live_shut
            ->select('uid')
            ->where('liveuid=?',$liveuid)
            ->order('id desc')
            ->fetchAll();
            
        foreach($list as $k=>$v){
            
            $userinfo=getUserInfo($v['uid']);
            
            $v['user_nicename']=$userinfo['user_nicename'];
            $v['avatar']=$userinfo['avatar'];
            $v['avatar_thumb']=$userinfo['avatar_thumb'];
            $v['sex']=$userinfo['sex'];
            $v['id']=$v['uid']; //ios专用

            $list[$k]=$v;
        }

        
		return $list;
	}

	/* 解除禁言 */
	public function cancelShut($liveuid,$touid) {
        
        //判断该用户是否被禁言
        $info=DI()->notorm->user_live_shut
            ->where('liveuid=? and uid=?',$liveuid,$touid)
            ->fetchOne();

        if(!$info){
            return 1001;
        }

        $rs=DI()->notorm->user_live_shut
            ->where('liveuid=? and uid=?',$liveuid,$touid)
            ->delete();
            
        DI()->redis -> hDel($liveuid . 'shutup',$touid);
        
		return $rs;
	}

	/* 踢人用户 */
	public function getKickList($liveuid) {
        

            
        $list=DI()->notorm->user_live_kick
            ->select('uid')
            ->where('liveuid=?',$liveuid)
            ->order('id desc')
            ->fetchAll();
            
        foreach($list as $k=>$v){
            
            $userinfo=getUserInfo($v['uid']);
            
            $v['user_nicename']=$userinfo['user_nicename'];
            $v['avatar']=$userinfo['avatar'];
            $v['avatar_thumb']=$userinfo['avatar_thumb'];
            $v['sex']=$userinfo['sex'];
            $v['id']=$v['uid']; //ios专用

            $list[$k]=$v;
        }

        
		return $list;
	}
    
	/* 解除踢人 */
	public function cancelKick($liveuid,$touid) {

        //判断该用户是否被踢出状态
        $info=DI()->notorm->user_live_kick
            ->where('liveuid=? and uid=?',$liveuid,$touid)
            ->fetchOne();
        if(!$info){
            return 1001;
        }
        
        $rs=DI()->notorm->user_live_kick
            ->where('liveuid=? and uid=?',$liveuid,$touid)
            ->delete();
            
		return $rs;
	}

}
