<?php

class Model_Vipcharge extends PhalApi_Model_NotORM {
	/* 订单号 */
	public function getOrderId($chargeid,$orderinfo) {
		
		$charge=DI()->notorm->vip_charge_rules->select('*')->where('id=?',$chargeid)->fetchOne();
		
		if(!$charge || $charge['money']!=$orderinfo['money'] || ($charge['days']!=$orderinfo['days']  && $charge['money_ios']!=$orderinfo['money'] )){
			return 1003;
		}
				

		$result= DI()->notorm->user_vip_charge->insert($orderinfo);

		return $result;
	}

	//余额支付
	public function balancePay($chargeid,$orderinfo){

		//判断充值规则
		$charge=DI()->notorm->vip_charge_rules->select('*')->where('id=?',$chargeid)->fetchOne();
		if(!$charge || $charge['coin']!=$orderinfo['coin'] || $charge['days']!=$orderinfo['days']){
			return 1001;
		}

		//判断用户的余额是否足够支付

		$coin=DI()->notorm->user->where("id=?",$orderinfo['uid'])->fetchOne("coin");

		//用户钻石余额不足
		if($coin<$charge['coin']){
			return 1002;
		}

		//扣除用户余额
		$result=changeUserCoin($orderinfo['uid'],$charge['coin']);

		if(!$result){
			return 1003;
		}

		//向vip支付订单里写入记录
		DI()->notorm->user_vip_charge->insert($orderinfo);

		$now=time();

		//向消费记录表中写入记录
		$data=array(
			'type'=>'expend',
			'action'=>'buyvip',
			'uid'=>$orderinfo['uid'],
			'touid'=>$orderinfo['uid'],
			'totalcoin'=>$charge['coin'],
			'addtime'=>$now
		);

		DI()->notorm->user_coinrecord->insert($data);

		//给用户增加vip天数
		$vipinfo=getUserVipInfo($orderinfo['uid']);

		$days=$charge['days']*24*60*60;

		if($vipinfo['isvip']==0){

			$endtime=$now+$days;
			
		}else{

			$endtime=DI()->notorm->user->where("id=?",$orderinfo['uid'])->fetchOne("vip_endtime");
			$endtime=$endtime+$days;
		}

		DI()->notorm->user->where("id=?",$orderinfo['uid'])->update(array('vip_endtime'=>$endtime));

		$vipinfo=getUserVipInfo($orderinfo['uid']);

		return $vipinfo;
		
	}			

}
