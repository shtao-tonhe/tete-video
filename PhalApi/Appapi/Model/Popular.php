<?php

class Model_Popular extends PhalApi_Model_NotORM {


	/* 检测视频 */
	public function checkVideo($uid,$videoid) {
		
        $nowtime=time();
        
		$isexist= DI()->notorm->user_video
                ->select('id,uid,status,isdel,p_expire')
                ->where('id=?',$videoid)
                ->fetchOne();

        if(!$isexist){
            return '1';
        }

        /*if($isexist['uid']!=$uid){
            return '2';
        }*/
        
        if($isexist['status']!=1){
            return '3';
        }
        
        if($isexist['isdel']!=0){
            return '4';
        }
        
        if($isexist['p_expire']> $nowtime){
            return '5';
        }

		return '0';
	}

	/* 订单号 */
	public function setOrder($data) {
		
		$result= DI()->notorm->popular_orders->insert($data);

		return $result;
	}

	/* 余额购买 */
	public function balancePay($data) {
		$rs = array('code' => 0, 'msg' => '支付成功', 'info' => array());
        
        $uid=$data['uid'];
        $videoid=$data['videoid'];
        $money=$data['money'];
        $length=$data['length'];
        $nums=$data['nums'];

        $ifok=changeUserCoin($uid,$money);

        if(!$ifok){
            $rs['code'] = 1005;
			$rs['msg'] = '余额不足';
			return $rs;	
        }

        $nowtime=time();

        $video_info=DI()->notorm->user_video->select("uid")->where("id={$videoid}")->fetchOne();

        //写入钻石消费记录
        $data=array(
            'type'=>'expend',
            'action'=>'uppop',
            'uid'=>$uid,
            'touid'=>$video_info['uid'],
            'videoid'=>$videoid,
            'totalcoin'=>$money,
            'addtime'=>$nowtime
        );

        //写入钻石消费记录
        setCoinRecord($data);

        
        $expire=$nowtime + $length*60*60;

        
        DI()->notorm->user_video->where("id={$videoid}")->update(array("p_nums"=>$nums,"p_expire"=>$expire,"p_add"=>$nowtime));
      
        $data2=[
            'uid'=>$uid,
            'touid'=>$video_info['uid'],
            'videoid'=>$videoid,
            'money'=>$money,
            'length'=>$length,
            'nums'=>$nums,
            'type'=>0, //类型,0余额1支付宝2微信3苹果
            'addtime'=>$nowtime,
            'status'=>1,
        ];
        DI()->notorm->popular_orders->insert($data2);

        if($uid!=$video_info['uid']){ //帮别人视频上热门
            //给视频所有者发@信息
            $data3=array(
                "uid"=>$uid,
                "touid"=>$video_info['uid'],
                "videoid"=>$videoid,
                "addtime"=>time(),
                "type"=>1 //帮助别人视频上热门
                
            );

            DI()->notorm->user_video_comments_at_messages->insert($data3);

            jMessageIM("@通知",$video_info['uid'],"dsp_at");
        }

		return $rs;
	}

    // 投放视频列表
    public function getPutin($uid,$p){
        
        if($p<1){
            $p=1;
        }
        
        $nums=50;
        $start=($p-1)*$nums;
        
        $list=DI()->notorm->popular_orders
                ->select("uid,videoid,money,addtime")
                ->where('uid=? and status=1',$uid)
                ->order("id desc")
                ->limit($start,$nums)
                ->fetchAll();

        //敏感词树        
        $tree=trieTreeBasic();

        foreach($list as $k=>$v){
            $videoinfo=DI()->notorm->user_video
                ->select("*")
                ->where('id=?',$v['videoid'])
                ->fetchOne();
            
            $videoinfo=handleVideo($uid,$videoinfo,$tree);
            
            $videoinfo['money']=$v['money'];
            $videoinfo['paytime']=date('Y-m-d',$v['addtime']);
            
            $list[$k]=$videoinfo;
        }
        
        return $list;
        
    }

    //获取上热门订单列表
    public function getOrderList($uid,$type,$p){
        if($p<1){
            $p=1;
        }
        
        $nums=50;
        $start=($p-1)*$nums;

        $list=DI()->notorm->popular_orders
                ->select("*")
                ->where('uid=? and status=1 and refund_status=?',$uid,$type)
                ->order("addtime desc")
                ->limit($start,$nums)
                ->fetchAll();

        $now=time();

        foreach ($list as $k => $v) {
            $videoinfo=DI()->notorm->user_video
                ->select("thumb,p_expire")
                ->where('id=?',$v['videoid'])
                ->fetchOne();

            if(!$videoinfo){
                $videoinfo['thumb']='/default.png';
                $videoinfo['p_expire']=0;
                $list[$k]['videoid']='0';
            }

            $list[$k]['video_thumb']=get_upload_path($videoinfo['thumb']);
            if($v['refund_status']==1){
                $list[$k]['real_play_num']=(string)((int)$v['nums']-(int)$v['end_nums']);
            }else{
                $list[$k]['real_play_num']=(string)((int)$v['nums']-(int)$videoinfo['p_nums']);
            }
            $list[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
            $list[$k]['length']=$v['length'].'小时';
            $v['expiretime']=$v['addtime']+$v['length']*60*60;

            
            if($v['refund_status']==1){

                $seconds=$v['expiretime']-$v['addtime'];
                if($seconds<0){
                    $seconds=0;
                }

                $list[$k]['real_length']=getSeconds($seconds);
                $coin_record=DI()->notorm->user_coinrecord
                    ->where("action='pop_refund' and type='income' and videoid=? and uid=?",$v['videoid'],$uid)
                    ->fetchOne();
                if($coin_record){
                    $list[$k]['return_coin']=$coin_record['totalcoin'];
                }else{
                    $list[$k]['return_coin']='0'; 
                }
                
            }else{

                $seconds=$now-$v['addtime'];
                if($seconds<0){
                    $seconds=0;
                }

                $list[$k]['real_length']=getSeconds($seconds);
                $list[$k]['return_coin']='0';
            }
            
            unset($list[$k]['status']);
        }

        return $list;

    }
}
