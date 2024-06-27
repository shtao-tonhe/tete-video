<?php

/*
 * 分享邀请
 */
class Api_Agent extends PhalApi_Api {

	public function getRules() {
		return array(

			'checkAgent'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
			),

            'setViewLength' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
                'length' => array('name' => 'length', 'type' => 'int', 'desc' => '时长（秒）'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'string' => '签名'),
			),

			'setAgent'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'agentcode' => array('name' => 'agentcode', 'type' => 'string', 'require' => true, 'desc' => '邀请码'),
			),

			'getCode' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
			),


		);
	}


	/**
	 * 获取邀请开关和邀请码必填开关以及用户是否设置了邀请码
	 * @desc 用于获取邀请开关和邀请码必填开关以及用户是否设置了邀请码
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return int info[0]. agent_switch 邀请开关 1打开 0关闭
	 * @return int info[0]. agent_must 邀请码是否必填 1是 0否
	 * @return int info[0]. has_agent 是否已经设置过邀请码 1是 0否
	 * @return int info[0]. is_firstlogin 是否新注册用户
	 * @return string msg 提示信息
	 */
	public function checkAgent(){

		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}


		$configpri=getConfigPri();

		$info[0]['agent_switch']=$configpri['agent_switch'];
		$info[0]['agent_must']=$configpri['agent_must'];  //此参数结合用户登录接口返回的isreg,如果agent_must=0时，只有在isreg=1时app端才会弹窗显示邀请码
		$info[0]['has_agent']=(string)checkAgentIsExist($uid);
		$info[0]['openinstall_switch']=$configpri['openinstall_switch'];
		$userinfo=getUserInfo($uid);
		$info[0]['is_firstlogin']=$userinfo['is_firstlogin']; //iOS专用

		$domain=new Domain_Agent();
		$domain->checkAgent($uid);


		$rs['info']=$info;

		return $rs;
	}
	
    
	/**
	 * 观看视频奖励
	 * @desc 用于 观看视频奖励
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function setViewLength() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $length=checkNull($this->length);
        $sign=checkNull($this->sign);
        
        if($uid<1 || $token=='' || $length<1 || $sign=='' ){
            $rs['code'] = 1001;
			$rs['msg'] =$Think.\lang('INFORMATION_ERROR');
			return $rs;
        }

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
        
        $checkdata=array(
            'uid'=>$uid,
            'token'=>$token,
            'length'=>$length,
        );
        
        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
			$rs['msg']='签名错误';
			return $rs;	
        }
        
		$domain = new Domain_Agent();
		$info = $domain->setViewLength($uid,$length);

		return $rs;			
	}		
	

	/**
	 * 通过邀请码建立上下级关系
	 * @desc 通过邀请码建立上下级关系
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function setAgent(){

		$rs = array('code' => 0, 'msg' => '设置邀请码成功', 'info' => array());
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$agentcode=checkNull($this->agentcode);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$configpri=getConfigPri();

		if($configpri['agent_switch']){
			if(!$agentcode){
				$rs['code'] = 1001;
				$rs['msg'] = '请填写邀请码';
				return $rs;
			}

			$domain=new Domain_Agent();
			$res=$domain->setAgent($uid,$agentcode);

			if($res==1001){
				$rs['code'] = 1001;
				$rs['msg'] = '已经填写过邀请码';
				return $rs;
			}

			if($res==1002){
				$rs['code'] = 1002;
				$rs['msg'] = '邀请码不存在';
				return $rs;
			}

			if($res==1003){
				$rs['code'] = 1003;
				$rs['msg'] =$Think.\lang('NOT_FILL_IN_YOUR_CODE');
				return $rs;
			}

			if($res==1004){
				$rs['code'] = 1004;
				$rs['msg'] = '该用户已经是你的下级';
				return $rs;
			}

		}

		return $rs;

	}

	/**
	 * 个人中心获取分享名片信息
	 * @desc 用于个人中心获取分享名片信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].code 邀请码
	 * @return string info[0].href 二维码链接
	 * @return string info[0].qr 二维码图片链接
	 * @return string msg 提示信息
	 */
	public function getCode() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        
        $checkToken = checkToken($uid,$token);
		if($checkToken==700){
			$rs['code']=700;
			$rs['msg']=$Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain = new Domain_Agent();
		$info = $domain->getCode($uid);
        
        if(!$info){
            $rs['code']=1001;
			$rs['msg']=$Think.\lang('INFORMATION_ERROR');
			return $rs;
        }

		$href=get_upload_path('/portal/index/scanqr');
		$info['href']=$href;
        $qr=scerweima($href);
        $info['qr']=get_upload_path($qr);
        
		$rs['info'][0]=$info;
		return $rs;			
	}
}
