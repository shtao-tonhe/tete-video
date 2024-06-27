<?php
session_start();
class Model_Home extends PhalApi_Model_NotORM {

		/* 搜索 */
    public function search($uid,$key,$p) {
		$pnum=50;
		$start=($p-1)*$pnum;
		$where=' user_type="2" and user_status=1 and ( id=? or user_nicename like ?) and id!=?';


		if($p!=1){
			$id=$_SESSION['search'];
			$where.=" and id < {$id}";
		}

		
		$result=DI()->notorm->user
				->select("id,user_nicename,avatar,avatar_thumb,sex,signature,province,city,birthday,age")
				->where($where,$key,'%'.$key.'%',$uid)
				->order("id desc")
				->limit($start,$pnum)
				->fetchAll();


		foreach($result as $k=>$v){

			$result[$k]['isattention']=(string)isAttention($uid,$v['id']);
			$result[$k]['avatar']=get_upload_path($v['avatar']);
			$result[$k]['avatar_thumb']=get_upload_path($v['avatar_thumb']);
			if($v['age']<0){
				$result[$k]['age']="年龄未填写";
			}else{
				$result[$k]['age'].="岁";
			}

			if($v['city']==""){
				$result[$k]['city']="城市未填写";
			}

			$result[$k]['praise']=getPraises($v['id']);
			$result[$k]['fans']=getFans($v['id']);					
			$result[$k]['follows']=getFollows($v['id']);
			$result[$k]['coin']="0";


			unset($result[$k]['consumption']);
		}

		if($result){
			$last=array_slice($result,-1,1);

			$_SESSION['search']=$last[0]['id'];
		}

		
		return $result;
    }
	



    public function videoSearch($uid,$key,$p) {
		$pnum=50;
		$start=($p-1)*$pnum;

		$where="v.isdel=0 and v.status=1 and v.is_ad=0";

		$where.=" and (v.title like '%".$key."%' or u.user_nicename like '%".$key."%')";
		/*if($p!=1){
			$id=$_SESSION['videosearch'];
			$where.=" and v.id < {$id}";
		}*/

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		$result=DI()->notorm->user_video
				->queryAll("select v.*,u.user_nicename,u.avatar from {$prefix}user_video v left join {$prefix}user u on v.uid=u.id where {$where} order by v.addtime desc limit {$start},{$pnum}");

		/*if($result){
			$last=array_slice($result,-1,1);
			$_SESSION['videosearch']=$last['id'];
		}*/

		//敏感词树        
        $tree=trieTreeBasic();

		foreach ($result as $k => $v) {
            
            $v=handleVideo($uid,$v,$tree);
            
            $result[$k]=$v;

		}
        
        

		
		return $result;
    }

    //获取本周内累计观众人数前20名的主播榜单
    public function getWeekShowLists(){
    	$nowtime=time();

    	$w=date('w',$nowtime); 
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天 
        $first=1;
        //周一
        $week=date('Y-m-d H:i:s',strtotime( date("Ymd")."-".($w ? $w - $first : 6).' days')); 
        $week_start=strtotime( date("Ymd")."-".($w ? $w - $first : 6).' days'); 

        //本周结束日期 
        //周天
        $week_end=strtotime("{$week} +1 week");

        $list_week=DI()->notorm->user_liverecord->select("uid,sum(nums) as total")->where("starttime >={$week_start} and starttime < {$week_end} ")->group("uid")->order("total desc,uid asc")->limit(20)->fetchAll();

        foreach ($list_week as $k => $v) {
        	$userinfo=getUserInfo($v['uid']);
        	$v['user_nicename']=$userinfo['user_nicename'];
        	$v['avatar']=$userinfo['avatar'];
        	$v['total']=NumberFormat($v['total']);
        	//判断用户是否在直播
        	$live_info=getLiveInfo($v['uid']);
        	$v['stream']=$live_info['stream'];
        	$v['islive']=$live_info['islive'];
        	$list_week[$k]=$v;
        }

        return $list_week;

    }


}
