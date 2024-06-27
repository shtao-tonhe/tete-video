<?php

/**
 * 上热门
 */
class Api_Popular extends PhalApi_Api {

	public function getRules() {
		return array(
			'getInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户Token'),
			),
            
            'balancePay' => array( 
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户Token'),
				'videoid' => array('name' => 'videoid', 'type' => 'int', 'desc' => '视频ID'),
				'length' => array('name' => 'length', 'type' => 'int', 'desc' => '时长(小时)'),
				'money' => array('name' => 'money', 'type' => 'string', 'desc' => '金额'),
			),
            
            'getPutin'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string',  'desc' => 'token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1','desc' => '页码'),
            ),

            'getOrderList'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string',  'desc' => 'token'),
            	'type' => array('name' => 'type', 'type' => 'int','default'=>'1',  'desc' => '订单类型 0 未完成 1已完成'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1','desc' => '页码'),
            ),
		);
	}
    
	/**
	 * 获取上热门信息
	 * @desc 用于获取上热门价格
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].coin 余额
	 * @return string info[0].base 基数（1元一小时多少个）
	 * @return string info[0].tips 提示内容
	 * @return array info[0].moneylist 价格列表
	 * @return string info[0].moneylist[] 价格
	 * @return array info[0].lengthlist 时长列表
	 * @return string info[0].lengthlist[] 时长
	 * @return array info[0].paylist 支付列表
	 * @return string info[0].paylist[].id 
	 * @return string info[0].paylist[].name 名称
	 * @return string info[0].paylist[].thumb 图标
	 * @return string info[0].paylist[].href 
	 * @return string info[0].aliapp_partner 支付宝合作者身份ID
	 * @return string info[0].aliapp_seller_id 支付宝帐号	
	 * @return string info[0].aliapp_key 支付宝密钥
	 * @return string info[0].wx_appid 开放平台账号AppID
	 * @return string msg 提示信息
	 */	
	public function getInfo() {
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
        
        
		$domain = new Domain_Popular();
		$info=array();

		$coin=getUserCoin($uid);
		$info['coin']=$coin;
        
        $configpri=getConfigPri();
		
        $info['base']=$configpri['popular_base'];
        $info['tips']=$configpri['popular_tips'];
        
        $moneylist=['100','500','1000','5000','10000'];
        
        
        
        $info['moneylist']=$moneylist;
        
        $length_list=['6','12','24'];
        
		$info['length_list']=$length_list;
        
        $paylist=[];
        
        $info['paylist'] =$paylist;
        
		$rs['info'][0]=$info;
		return $rs;
	}
	/* 获取订单号 */
	protected function getOrderid($uid){
		$orderid=$uid.'_'.date('YmdHis').rand(100,999);
		return $orderid;
	}


	/**
	 * 余额支付
	 * @desc 用于余额支付
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].orderid 订单号
	 * @return string msg 提示信息
	 */
	public function balancePay() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
		$length=checkNull($this->length);
		$money=checkNull($this->money);
        
        if($uid<1 || $token=='' || $videoid<1 || $length<1 || $money<1){
            $rs['code'] = 1001;
			$rs['msg'] = $Think.\lang('INFORMATION_ERROR');
			return $rs;
        }

        if(floor($money)!=$money){
        	$rs['code'] = 1016;
			$rs['msg'] = '金额请填写大于1的整数';
			return $rs;
        }

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
        
        $domain = new Domain_Popular();
        $video_rs = $domain->checkVideo($uid,$videoid);
        if($video_rs==1){
            $rs['code'] = 1011;
			$rs['msg'] = '视频不存在';
			return $rs;
        }
        /*
        因需求可以帮助别人视频上热门所有注释
         if($video_rs==2){
            $rs['code'] = 1012;
			$rs['msg'] = '不是您发布的视频';
			return $rs;
        }*/
        if($video_rs==3){
            $rs['code'] = 1013;
			$rs['msg'] = '视频未审核通过';
			return $rs;
        }
        if($video_rs==4){
            $rs['code'] = 1014;
			$rs['msg'] = '视频已被下架';
			return $rs;
        }
        if($video_rs==5){
            $rs['code'] = 1015;
			$rs['msg'] = '视频已上热门,暂时不能付费';
			return $rs;
        }
		
		$configpri=getConfigPri();
		
		$base=$configpri['popular_base'];
        
        if($base<1){
            $rs['code'] = 1004;
			$rs['msg'] = '配置错误';
			return $rs;	
        }
        
        $nums=$money * $length * $base;
		
		$orderinfo=array(
			"uid"=>$uid,
			"videoid"=>$videoid,
			"money"=>$money,
			"length"=>$length,
			"nums"=>$nums,
		);
		
		$info = $domain->balancePay($orderinfo);

		return $info;
	}

	/**
	 * 投放订单
	 * @desc 用于获取投放订单
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].coin 余额
	 * @return array info[0].list 列表
	 * @return string info[0].list[].money 价格
	 * @return string info[0].list[].paytime 下单时间
	 * @return string msg 提示信息
	 */
    public function getPutin() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$p=checkNull($this->p);
		
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
        
        $domain = new Domain_Popular();
		$coin=getUserCoin($uid);
        
		$list = $domain->getPutin($uid,$p);
        
        
		$rs['info'][0]['coin']=$coin;
		$rs['info'][0]['list']=$list;
		return $rs;
	}

	/**
	 * 获取上热门订单列表
	 * @desc 用于获取上热门订单列表
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 * @return int info[].id 上热门订单的ID
	 * @return int info[].uid 上热门订单用户的ID
	 * @return int info[].touid 上热门订单视频发布者的ID
	 * @return int info[].videoid 上热门订单视频ID
	 * @return float info[].money 上热门订单金额
	 * @return int info[].length 上热门订单时长
	 * @return int info[].nums 上热门订单预计播放量
	 * @return int info[].type 上热门订单支付类型,0余额1支付宝2微信3苹果
	 * @return string info[].addtime 上热门订单添加时间
	 * @return int info[].status 上热门订单状态，0未支付，1已支付
	 * @return string info[].status 上热门订单订单号
	 * @return string info[].trade_no 上热门订单商户流水号
	 * @return int info[].refund_status 上热门订单退款状态 0 待退款 1 已退款
	 * @return string info[].video_thumb 上热门订单视频封面
	 * @return int info[].real_play_num 上热门视频实际播放量
	 * @return string info[].real_length 上热门视频实际播放时长
	 * @return int info[].return_coin 上热门视频返回钻石数
	 */
	public function getOrderList(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$type=checkNull($this->type);
		$p=checkNull($this->p);
		
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

		$domain = new Domain_Popular();
		$list=$domain->getOrderList($uid,$type,$p);

		$rs['info']=$list;
		return $rs;
	}
}
