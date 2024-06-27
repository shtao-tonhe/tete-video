<?php
/**
 * 房间管理
 */
class Api_Livemanage extends PhalApi_Api {

	public function getRules() {
		return array(
            'getManageList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
			),
            
            'cancelManage' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'desc' => '要解除的用户ID'),
			),
            
            'getRoomList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
			),
            
            'getShutList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '主播ID'),
			),

            'cancelShut' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '主播ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'desc' => '要解除的用户ID'),
			),
            
            'getKickList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '主播ID'),
			),
            
            'cancelKick' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '主播ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'desc' => '要解除的用户ID'),
			),
            
		);
	}
	

	/**
	 * 获取主播的管理员列表
	 * @desc 用于获取主播的管理员列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].nums 管理员数量
	 * @return string info[0].total 总数
	 * @return array info[0].list
	 * @return string info[0].list[].uid 用户id
	 * @return string info[0].list[].user_nicename 用户昵称
	 * @return string info[0].list[].avatar 用户头像
	 * @return string info[0].list[].avatar_thumb 用户小头像
	 * @return string info[0].list[].sex 用户性别 1 男 2 女 0 保密
	 * @return string msg 提示信息
	 */
	public function getManageList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }
		
		$domain = new Domain_Livemanage();
		$info = $domain->getManageList($uid);

		
		$rs['info'][0]=$info;
		return $rs;			
	}
    
    
	/**
	 * 解除管理
	 * @desc 用于解除用户管理
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function cancelManage() {
		$rs = array('code' => 0, 'msg' => '解除成功', 'info' => array());
		
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $touid=checkNull($this->touid);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }
		
		$domain = new Domain_Livemanage();
		$res = $domain->cancelManage($uid,$touid);

		if($res==1001){
			$rs['code']=1001;
			$rs['msg']="用户不是该主播的管理员";
			return $rs;
		}


		return $rs;			
	}


	/**
	 * 作为管理员 获取我的房间列表
	 * @desc 用于获取用户作为管理员的直播间列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return int info[].liveuid 主播ID
	 * @return string info[].user_nicename 主播昵称
	 * @return string info[].avatar 主播头像
	 * @return string info[].avatar_thumb 主播小头像
	 * @return int info[].sex 主播性别 1 男 2 女 0 保密
	 * @return string msg 提示信息
	 */
	public function getRoomList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }
		
		$domain = new Domain_Livemanage();
		$list = $domain->getRoomList($uid);

		
		$rs['info']=$list;
		return $rs;			
	}


	/**
	 * 获取房间禁言用户列表
	 * @desc 用于获取房间禁言用户列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].uid 用户id
	 * @return string msg 提示信息
	 */
	public function getShutList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $liveuid=checkNull($this->liveuid);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }
        
        $uidtype = isAdmin($uid,$liveuid);

		if($uidtype==30 ){
			$rs["code"]=1001;
			$rs["msg"]='您不是该直播间的管理员，无权操作';
			return $rs;									
		}

		
		$domain = new Domain_Livemanage();
		$list = $domain->getShutList($liveuid);

		
		$rs['info']=$list;
		return $rs;			
	}


	/**
	 * 解除禁言
	 * @desc 用于解除用户禁言
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function cancelShut() {
		$rs = array('code' => 0, 'msg' => '解除成功', 'info' => array());
		
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $liveuid=checkNull($this->liveuid);
        $touid=checkNull($this->touid);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }
        
        $uidtype = isAdmin($uid,$liveuid);

		if($uidtype==30 ){
			$rs["code"]=1001;
			$rs["msg"]='您不是该直播间的管理员，无权操作';
			return $rs;									
		}
		
		$domain = new Domain_Livemanage();
		$res = $domain->cancelShut($liveuid,$touid);

		if($res==1001){
			$rs["code"]=1001;
			$rs["msg"]='该用户未被禁言';
			return $rs;
		}

		return $rs;			
	}


	/**
	 * 获取主播踢出用户列表
	 * @desc 用于获取主播踢出用户列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return int info[].uid 用户id
	 * @return string info[].user_nicename 用户昵称
	 * @return string info[].avatar 用户头像
	 * @return string info[].avatar_thumb 用户小头像
	 * @return string info[].sex 用户性别 1 男 2 女 0 保密
	 * @return string msg 提示信息
	 */
	public function getKickList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $liveuid=checkNull($this->liveuid);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }
        
        $uidtype = isAdmin($uid,$liveuid);

		if($uidtype==30 ){
			$rs["code"]=1001;
			$rs["msg"]='您不是该直播间的管理员，无权操作';
			return $rs;									
		}

		$domain = new Domain_Livemanage();
		$list = $domain->getKickList($liveuid);

		
		$rs['info']=$list;
		return $rs;			
	}
	
	/**
	 * 解除踢出
	 * @desc 用于解除用户踢出
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function cancelKick() {
		$rs = array('code' => 0, 'msg' => '解除成功', 'info' => array());
		
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $liveuid=checkNull($this->liveuid);
        $touid=checkNull($this->touid);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }
        
        $uidtype = isAdmin($uid,$liveuid);

		if($uidtype==30 ){
			$rs["code"]=1001;
			$rs["msg"]='您不是该直播间的管理员，无权操作';
			return $rs;									
		}
		
		$domain = new Domain_Livemanage();
		$res = $domain->cancelKick($liveuid,$touid);

		if($res==1001){
			$rs["code"]=1001;
			$rs["msg"]='该用户未被踢出';
			return $rs;
		}
        
		return $rs;			
	}
}
