<?php

class Model_Agent extends PhalApi_Model_NotORM {
	
	
    /* 设置邀请关系 */
	public function setAgent($uid,$code) {
        
        $isexist=checkAgentIsExist($uid);

        if($isexist){
            return 1001;
        }

        //判断邀请码是否存在
        $code=strtoupper($code);
        $agent_userinfo=DI()->notorm->user->select("id")->where("code='{$code}'")->fetchOne();

        if(!$agent_userinfo){
            return 1002;
        }

        //获取该用户的邀请码
        $userinfo=DI()->notorm->user->select("code")->where("id={$uid}")->fetchOne();

        if($userinfo['code']==$code){
            return 1003;
        }

        //获取该用户的下级用户
        $agentlists=DI()->notorm->agent->select("uid")->where("one={$uid}")->fetchAll();
        $isexist=0;


        if($agentlists){

            foreach ($agentlists as $k => $v) {
               $agent=DI()->notorm->user->where("id={$v['uid']} and code='{$code}'")->fetchOne();

               if($agent){
                    $isexist=1;
                    break;
               }
            }

            if($isexist){
                return 1004;
            }
        }
        
        $one_uid=$agent_userinfo['id'];
        
        
        $data=[
            'uid'=>$uid,
            'one'=>$one_uid,
            'addtime'=>time(),
        ];
        
        $result=DI()->notorm->agent
                    ->insert($data);
        /* 邀请奖励 */
        $configPri=getConfigPri();
        $nowtime=time();
        
        $data=[
            'action'=>'1',
            'uid'=>$uid,
            'votes'=>0,
            'addtime'=>$nowtime,
        ];
        

        $agent_reward=$configPri['agent_reward'];

        //更新用户的映票
        changeUserVotes($one_uid,$agent_reward,1);
            
        $data['touid']=$one_uid;
        $data['touid_votes']=$agent_reward;
        
       $rs2=DI()->notorm->agent_profit
            ->where('uid=?',$one_uid)
            ->fetchOne();


        if(!$rs2){
            DI()->notorm->agent_profit
                ->insert(array('uid'=> $one_uid,'one_p'=> $agent_reward));
        }else{
            DI()->notorm->agent_profit
                ->where('uid=?',$one_uid)
                ->update(array('one_p'=> new NotORM_Literal("one_p + {$agent_reward} ")));
        }
        


        $rs3=DI()->notorm->agent_profit
                ->where('uid=?',$uid)
                ->fetchOne();


        if(!$rs3){
            DI()->notorm->agent_profit
                ->insert(array('uid'=> $uid,'one'=> $agent_reward));
        }else{
            DI()->notorm->agent_profit
                ->where('uid=?',$uid)
                ->update(array('one'=> new NotORM_Literal("one + {$agent_reward} ")));
        }

        //写入映票收入记录        
        setVoteRecord($data);
            
		return 1;
	}
    
	/* 观看奖励 */
	public function setViewLength($uid,$length) {
        
        $nowtime=time();
        //当天0点
        $today=date("Ymd",$nowtime);
        $today_start=strtotime($today);
        //当天 23:59:59
        $today_end=strtotime("{$today} + 1 day");
        

        $isexist=DI()->notorm->view_reward
                    ->select('*')
                    ->where('uid=?',$uid)
                    ->fetchOne();
        if($isexist){
            /* 有记录 */
            if($nowtime>$isexist['addtime']){
                /* 新一天 */
                DI()->notorm->view_reward
                    ->where('uid=?',$uid)
                    ->update(['length'=>$length,'addtime'=>$today_end,'status'=>'0']);
                
            }else{
                /* 累计 */
                DI()->notorm->view_reward
                    ->where('uid=?',$uid)
                    ->update(array('length'=> new NotORM_Literal("length + {$length} ")));
            }
        }else{
            /* 无记录 */
            DI()->notorm->view_reward
                    ->insert(['uid'=>$uid,'length'=>$length,'addtime'=>$today_end,'status'=>'0']);
        }
        
        /* 判断是否奖励 */
        $info=DI()->notorm->view_reward
                    ->select('*')
                    ->where('uid=? and status=0',$uid)
                    ->fetchOne();
        if($info){
            $configPri=getConfigPri();
            $agent_v_l=$configPri['agent_v_l'] * 60;
            if($info['length'] >= $agent_v_l){
                
                $rs=DI()->notorm->view_reward
                    ->where('uid=?',$uid)
                    ->update(['status'=>'1']);
                if(!$rs){
                    return '1';
                }    
                /* 添加奖励 */
                $agent_v_a=$configPri['agent_v_a'];

                //更新用户的映票
                changeUserVotes($uid,$agent_v_a,1);
                    
                $data=[
                    'action'=>'2',
                    'uid'=>$uid,
                    'votes'=>$agent_v_a,
                    'addtime'=>$nowtime,
                ];
                
                $agent=DI()->notorm->agent
                            ->select('*')
                            ->where('uid=?',$uid)
                            ->fetchOne();
                if($agent){
                    
                    $one=0;
                    if($agent['one']>0){
                        $agent_a=$configPri['agent_a'];

                        //更新用户的映票
                        changeUserVotes($agent['one'],$agent_a,1);
                            
                        $data['touid']=$agent['one'];
                        $data['touid_votes']=$agent_a;
                        $one=$agent_a;
                        
                        $rs2=DI()->notorm->agent_profit
                            ->where('uid=?',$agent['one'])
                            ->update(array('one_p'=> new NotORM_Literal("one_p + {$agent_a} ")));
                        if(!$rs2){
                            DI()->notorm->agent_profit
                                ->insert(array('uid'=> $agent['one'],'one_p'=> $agent_a));
                        }
                        
                        $rs3=DI()->notorm->agent_profit
                            ->where('uid=?',$uid)
                            ->update(array('one'=> new NotORM_Literal("one + {$agent_a} ")));
                        if(!$rs3){
                            DI()->notorm->agent_profit
                                ->insert(array('uid'=> $uid,'one'=> $agent_a));
                        }
                    }
                    
                    
                    
                }
                
                //写入映票收入记录
                setVoteRecord($data);
            }
        }
            
		return '1';
	}

    public function getCode($uid) {
        
        $agentinfo=DI()->notorm->user
            ->select('code')
            ->where('id=?',$uid)
            ->fetchOne();
            
        return $agentinfo;
    }

    public function checkAgent($uid){

        $is_firstlogin=DI()->notorm->user
            ->where("id=?",$uid)
            ->fetchOne("is_firstlogin");

        if($is_firstlogin){
            DI()->notorm->user
            ->where("id=?",$uid)
            ->update(['is_firstlogin'=>0]);

            $is_firstlogin=0;
        }

        return $is_firstlogin;
    }
}
