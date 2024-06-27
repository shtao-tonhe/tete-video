<?php

/**
 * 直播监控
 */
namespace app\admin\controller;
use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class MonitorController extends AdminbaseController {

    public function index(){

        $lists = Db::name('user_live')
            ->where(function (Query $query) {
                $data = $this->request->param();
                
                $query->where('islive', 'eq' , '1');
                $query->where('isvideo', 'eq' , '0');

                $data = $this->request->param();

                $start_time=isset($data['start_time']) ? $data['start_time']: '';
                $end_time=isset($data['end_time']) ? $data['end_time']: '';
                
                if (!empty($start_time)) {
                    $query->where('starttime', 'gt' , strtotime($start_time));
                }
                if (!empty($end_time)) {
                    $query->where('starttime', 'lt' ,strtotime($end_time));
                }
                
                if (!empty($start_time) && !empty($end_time)) {
                    $query->where('starttime', 'between' , [strtotime($start_time),strtotime($end_time)]);
                }

                $keyword=isset($data['keyword']) ? $data['keyword']: '';
                
                if (!empty($keyword)) {
                    $query->where('uid', 'like', "%$keyword%");
                }

            })
            ->order("starttime DESC")
            ->paginate(6);


        $lists->each(function($v,$k){
            $userinfo=getUserInfo($v['uid']);
            $auth_url=urldecode(PrivateKeyA('http',$v['stream'],0));
            $v['userinfo']= $userinfo;
            $v['url']= $auth_url;
            //$v['url']= $v['pull']; //测试用
            
            return $v;
            
        });

        //分页-->筛选条件参数
        $data = $this->request->param();
        $lists->appends($data); 
            
        // 获取分页显示
        $page = $lists->render();

        $configpri=getConfigPri();
			
    	$this->assign('lists', $lists);
    	$this->assign("page", $page);
        $this->assign("time", time());
        $this->assign('configpri', $configpri);
    	
    	return $this->fetch();
    }

    public function full(){
        $uid=$this->request->param('uid',0,'intval');
        $live=Db::name("user_live")->where(['islive'=>1,'uid'=>$uid])->find();
        if($live['title']==""){
            $live['title']=$Think.\lang('LIVE_MONITORING_BACKGROUND');
        }
        $pull=urldecode(PrivateKeyA('http',$live['stream'],0));
        $live['pull']=$pull;
        $configpri=getConfigPri();

        $this->assign('configpri',$configpri);
        $this->assign('live',$live);
        return $this->fetch();
    }
		
	public function stopRoom(){

        $uid=$this->request->param('uid',0,'intval');
        if(!$uid){
            echo  json_encode( array("status"=>'0','info'=>$Thin.lang('PARAMENTER_ERROT')) );
            exit;
        }
        $where['islive']=1;
        $where['uid']=$uid;
        $liveinfo=Db::name("user_live")->field("uid,showid,starttime,title,province,city,stream")->where($where)->find();

        Db::name("user_live")->where(" uid='{$uid}'")->delete();

        if($liveinfo){

            $liveinfo['endtime']=time();
            $liveinfo['time']=date("Y-m-d",$liveinfo['showid']);

            $where2=[];
            $where2['touid']=$uid;
            $where2['showid']=$liveinfo['showid'];
            
            $votes=Db::name("user_coinrecord")
                ->where($where2)
                ->sum('totalcoin');

            $liveinfo['votes']=0;
            if($votes){
                $liveinfo['votes']=$votes;
            }

            $stream=$liveinfo['stream'];
            $nums=zSize('user_'.$stream);
            $liveinfo['nums']=$nums;

            hDel("livelist",$uid);
            delcache($uid.'_zombie');
            delcache($uid.'_zombie_uid');
            delcache('attention_'.$uid);
            delcache('user_'.$stream);

            Db::name("user_liverecord")->insert($liveinfo);
        }

        echo  json_encode( array("status"=>'1','info'=>'') );
        exit;								  			
	}		


    
}
