<?php

class Model_Cash extends PhalApi_Model_NotORM {
    
    /* 提现账号列表 */
    public function getAccountList($uid){
        
        $list=DI()->notorm->user_cash_account
                ->select("*")
                ->where('uid=?',$uid)
                ->order("addtime desc")
                ->fetchAll();
                
        return $list;
    }

    /* 设置提账号 */
    public function setAccount($data){
        
        $rs=DI()->notorm->user_cash_account
                ->insert($data);
                
        return $rs;
    }

    /* 删除提账号 */
    public function delAccount($data){
        
        $rs=DI()->notorm->user_cash_account
                ->where($data)
                ->delete();
                
        return $rs;
    }    

	/* 我的收益 */
	public function getProfit($uid){
		$info= DI()->notorm->user
				->select("votes")
				->where('id=?',$uid)
				->fetchOne();

		$config=getConfigPri();
		
		//提现比例
		$cash_rate=$config['cash_rate'];
		$cash_start=$config['cash_start'];
		$cash_end=$config['cash_end'];
		$cash_max_times=$config['cash_max_times'];
		$cash_take=$config['cash_take'];
		//剩余票数
		$votes=$info['votes'];

		$cash_prop=round((1-$cash_take*0.01),2);
        
		//总可提现数
		if(!$cash_rate){
			$total='0';
		}else{
			$total=(string)floor($votes/$cash_rate); //平台抽成之前的总金额
		}

		if($cash_max_times){
			$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，每月只可提现'.$cash_max_times.'次';
		}else{
			$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请';
		}
        
		$rs=array(
			"votes"=>$votes,
			"total"=>$total,
			"cash_prop"=>(string)$cash_prop, //用户输入的提现金额直接乘以该数换算即可
			"tips"=>$tips
		);
		return $rs;
	}
    
	/* 提现  */
	public function setCash($data){
        
        $nowtime=time();
        
        $uid=$data['uid'];
        $accountid=$data['accountid'];
        $money=$data['money'];
        
        $config=getConfigPri();
        
        /* 钱包信息 */
		$accountinfo=DI()->notorm->user_cash_account
				->select("*")
				->where('id=?',$accountid)
				->fetchOne();

        /*if(!$accountinfo){
            return 1007;
        }*/
        

		//提现比例
		$cash_rate=$config['cash_rate'];
		$cash_take=$config['cash_take'];
		$cash_start=$config['cash_start'];
        $cash_end=$config['cash_end'];
        $cash_max_times=$config['cash_max_times'];

		if(!$cash_rate){
			return 1008;
		}

		//获取用户的可提现金额
		$info= DI()->notorm->user
				->select("votes")
				->where('id=?',$uid)
				->fetchOne();

		$votes=$info['votes'];
        
		//总可提现金额数
		$total=(string)floor($votes/$cash_rate);	
		
		/* 最低额度 */
		$cash_min=$config['cash_min'];
        
        if($money < $cash_min){
			return 1004;
		}

		if($money>$total){
			return 1005;
		}

		$day=(int)date("d",$nowtime);

        
        if($day < $cash_start || $day > $cash_end){
            return 1006;
        }

        //本月第一天
        $month=date('Y-m-d',strtotime(date("Ym",$nowtime).'01'));
        $month_start=strtotime(date("Ym",$nowtime).'01');

        //本月最后一天
        $month_end=strtotime("{$month} +1 month");

        if($cash_max_times){
            $isexist=DI()->notorm->user_cashrecord
                    ->where('uid=? and addtime > ? and addtime < ?',$uid,$month_start,$month_end)
                    ->count();
            if($isexist >= $cash_max_times){
                return 1003;
            }
        }
		
		//提现映票数
		$cashvotes=$money*$cash_rate;

        $ifok=changeUserVotes($uid,$cashvotes);

        if(!$ifok){
            return 1001;
        }
		
		$money_take=$money*(1-$cash_take*0.01);
		$real_money=number_format($money_take,2,".","");
		
		$data=array(
			"uid"=>$uid,
			"money"=>$real_money,
			"votes"=>$cashvotes,
			"orderno"=>$uid.'_'.$nowtime.rand(100,999),
			"status"=>0,
			"addtime"=>$nowtime,
			"uptime"=>$nowtime,
			"type"=>$accountinfo['type'],
			"account_bank"=>$accountinfo['account_bank'],
			"account"=>$accountinfo['account'],
			"name"=>$accountinfo['name'],
			'cash_money'=>$money,
			'cash_take'=>$cash_take
		);
		
		$rs=DI()->notorm->user_cashrecord->insert($data);
		if(!$rs){
            return 1002;
		}	        
        
		return $rs;
	}

}
