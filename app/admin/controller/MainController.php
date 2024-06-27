<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\Menu;
use think\Lang;

class MainController extends AdminBaseController{

    /**
     *  后台欢迎页
     */
    public function index(){
        $config=getConfigPub();
        $this->assign("config",$config);
        
        $nowtime=time();
        //当天0点
        $today=date("Y-m-d",$nowtime);
        $today_start=strtotime($today);
        //当天 23:59:59
        $today_end=strtotime("{$today} + 1 day");
        
        /* 总注册数 */
        $users_total=Db::name("user")->where("user_type=2")->count();
        $this->assign("users_total",number_format($users_total));
        
        /* 基础数据 */
        $congifpri=getConfigPri();

        if($congifpri['um_appkey_android']){
            $appkey=$congifpri['um_appkey_android'];
            $basic_today_android=$this->getDailyData($appkey,$today);
        }
        
        if($congifpri['um_appkey_ios']){
            $appkey=$congifpri['um_appkey_ios'];
            $basic_today_ios=$this->getDailyData($appkey,$today);
        }

        $basic_today['newUsers']=number_format($basic_today_android['newUsers']+$basic_today_ios['newUsers']);
        $basic_today['totalUsers']=number_format($basic_today_android['totalUsers']+$basic_today_ios['totalUsers']);
        $basic_today['activityUsers']=number_format($basic_today_android['activityUsers']+$basic_today_ios['activityUsers']);
        $basic_today['launches']=number_format($basic_today_android['launches']+$basic_today_ios['launches']);
        

        $data_basic=$this->getBasic($today_start,$today_end,1);
        $this->assign("basic_today",$basic_today);
        $this->assign("data_basicj",json_encode($data_basic));
        
    	//设备终端
        $android_nums=Db::name("user")
                ->where("user_type=2 and source='android'")
                ->count();
                
        $ios_nums=Db::name("user")
                ->where("user_type=2 and source='ios'")
                ->count();

        $data_source=[
            'name'=>[],
            'v_n'=>[],
        ];
        
        $data_source['v_n'][]=['value'=>$android_nums,'name'=>$Think.\lang('Android'),'itemStyle'=>['color'=>'#4da2ff']];
        $data_source['name'][]=$Think.\lang('Android');
        
        $data_source['v_n'][]=['value'=>$ios_nums,'name'=>'IOS','itemStyle'=>['color'=>'#fecc2d']];
        $data_source['name'][]='IOS';

        
        $this->assign("data_sourcej",json_encode($data_source));
        /* 注册渠道 */
        $qq_nums=Db::name("user")
                ->where("user_type=2 and login_type='qq'")
                ->count();  
                
        $wx_nums=Db::name("user")
                ->where("user_type=2 and login_type='wx'")
                ->count();   
        $phone_nums=Db::name("user")
                ->where("user_type=2 and login_type='phone'")
                ->count();      
        $other_nums=Db::name("user")
                ->where("user_type=2 and login_type!='qq' and login_type!='wx' and login_type!='phone'")
                ->count();     
        $login_nums_totoal=$qq_nums+$wx_nums+$phone_nums+$other_nums;
        $data_type=[
            'name'=>[],
            'nums'=>[],
            'nums_per'=>[],
            'color'=>[],
        ];
        
        $data_type['name'][]='QQ';
        $data_type['nums'][]=$qq_nums;
        $data_type['color'][]='#ff7a8b';
        $data_type['nums_per'][]=$login_nums_totoal!=0? round($qq_nums*100/$login_nums_totoal) : '0';
        
        $data_type['name'][]=$Think.\lang('ADMIN_SETTING_SITE_LOGIN_WECHAT');
        $data_type['nums'][]=$wx_nums;
        $data_type['color'][]='#fecc2d';
        $data_type['nums_per'][]=$login_nums_totoal!=0? round($wx_nums*100/$login_nums_totoal) : '0';
        
        $data_type['name'][]=$Think.\lang('MOBILE_PHONE');
        $data_type['nums'][]=$phone_nums;
        $data_type['color'][]='#4da2ff';
        $data_type['nums_per'][]=$login_nums_totoal!=0? round($phone_nums*100/$login_nums_totoal) : '0';
        
        $data_type['name'][]=$Think.\lang('OTHER');
        $data_type['nums'][]=$other_nums;
        $data_type['color'][]='#83d688';
        $data_type['nums_per'][]=$login_nums_totoal!=0? round($other_nums*100/$login_nums_totoal) : '0';
        
        $this->assign("data_typej",json_encode($data_type));
        
        /* 七天数据 */
        $time_7=$today_start-60*60*24*7;
        $data_week=array(
            'date'=>[],
            'value'=>[],
            'fans'=>[],
            'likes'=>[],
        );
        for($i=$time_7;$i<$today_start;){
            $end=$i+60*60*24;
            $videonums=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=0 and addtime > {$i} and addtime <= {$end}")->count();
            $likes=Db::name('user_video_like')->where("addtime > {$i} and addtime <= {$end}")->count();
            $fans=Db::name('user_attention')->where("addtime > {$i} and addtime <= {$end}")->count();
            
            $data_week['date'][]=date("Y-m-d",$i);
            $data_week['value'][]=$videonums;
            $data_week['fans'][]=$fans;
            $data_week['likes'][]=$likes;
            $i=$end;
        }
        
        $this->assign("data_weekj",json_encode($data_week));
        
        
        
        /* 广告数量 */
        $data_ad=array(
            'date'=>[],
            'value'=>[],
            'videoviews'=>[],
        );
        $data_ad['date'][]='00';
        $data_ad['value'][]='0';
        $data_ad['videoviews'][]='0';
        for($i2=$today_start;$i2<$today_end;){
            $end=$i2+60*60;
            $videonums=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=1 and addtime > {$i2} and addtime <= {$end}")->count();
            $videoviews=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=1 and addtime > {$i2} and addtime <= {$end}")->sum('views');
            if(!$videoviews){
                $videoviews=0;
            }
            if($end==$today_end){
                $data_ad['date'][]='24';
            }else{
                $data_ad['date'][]=date("H",$end);
            }
            
            $data_ad['value'][]=$videonums;
            $data_ad['videoviews'][]=$videoviews;
            $i2=$end;
        }
        
        $this->assign("data_adj",json_encode($data_ad));
        
        
        /* 平台数据 */
        $data_plat=array(
            'fans'=>0,
            'commnets'=>0,
            'release'=>0,
            'likes'=>0,
            'shares'=>0,
            'attents'=>0,
            'video_total'=>0,
            'video_add'=>0,
            'commnets_30'=>0,
        );
        /* 30天前的时间 */
        $time_30=$today_start-60*60*24*29;
        /* 粉丝数 */
        $fans=Db::name('user_attention')->count();
        $data_plat['fans']=NumberFormat($fans);
        /* 评论数 */
        $commnets=Db::name('user_video_comments')->count();
        $data_plat['commnets']=NumberFormat($commnets);
        /* 近30天发布视频 */
        $release=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=0 and addtime > {$time_30}")->count();
        $data_plat['release']=NumberFormat($release);
        /* 近30天点赞数 */
        $likes=Db::name('user_video_like')->where("addtime > {$time_30}")->count();
        $data_plat['likes']=NumberFormat($likes);
        /* 近30天分享数 */
        //$likes=Db::name('user_video_like')->where("addtime > {$time_30}")->count();
        /* 近30天关注增量 */
        $attents=Db::name('user_attention')->where("addtime > {$time_30}")->count();
        $data_plat['attents']=NumberFormat($attents);
        /* 视频总数 */
        $video_total=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=0")->count();
        $data_plat['video_total']=NumberFormat($video_total);
        /* 近30天留言 */
        $commnets_30=Db::name('user_video_comments')->where("addtime > {$time_30}")->count();
        $data_plat['commnets_30']=NumberFormat($commnets_30);
        
        $this->assign("data_plat",$data_plat);
		
		
		
		$stayinfo=[];
		//店铺待审核数量
		$stayinfo['shopapply_count'] = Db::name('shop_apply')->where("status=0")->count();
		
		//视频待审核数量
		$stayinfo['video_count'] = Db::name('user_video')->where("isdel=0 and status=0")->count();
		
		//视频举报数量
		$stayinfo['videorepot_count'] = Db::name('user_video_report')->where("status=0")->count();
		
		
		//直播间举报数量
		$stayinfo['livereport_count'] = Db::name('user_live_report')->where("status=0")->count();
		
		//用户认证待审核数量
		$stayinfo['auth_count'] = Db::name('user_auth')->where("status=0")->count();
		
		$this->assign("stayinfo",$stayinfo);

        return $this->fetch();
    }
	
	
	
	public function getdata(){

		
		$data = $this->request->param();
		
		

        $action=$data['action'];
        $start_time=$data['start_time'];
        $end_time=$data['end_time'];
        
        $nowtime=time();
        //当天0点
        $today=date("Y-m-d",$nowtime);
        $today_start=strtotime($today);
        //当天 23:59:59
        $today_end=strtotime("{$today} + 1 day");
        
        $start=$today_start;
        $end=$today_end;

        if($start_time){
            $start=strtotime($start_time);
        }
        if($end_time){
          $end=strtotime($end_time) + 60*60*24;  
        }
        
		

        switch($action){
            case '1':
                $info=$this->getBasic($start,$end);
                break;
            case '2':
                $info=$this->getUsers($start,$end);
                break;
            case '3':
                $info=$this->getAds($start,$end);
                break;
        }
        
        $this->success($info);
    }
    
    /* 基础数据 */
    public function getBasic($starttime,$endtime){
        $rs=[
            'name'=>[],
        ];
        
        $start=date("Y-m-d",$starttime);
        $end=date("Y-m-d",($endtime - 60*60*24));
        $congifpri=getConfigPri();

        $periodType='daily';
        for($i=$starttime;$i<$endtime;$i+=60*60*24){
            $rs['name'][]=date("Y-m-d",$i);
        }

        
        if($congifpri['um_appkey_android']){
            $appkey=$congifpri['um_appkey_android'];

            $newusers_android=$this->getNewUsers($appkey,$start,$end,$periodType);
            $launches_android=$this->getLaunches($appkey,$start,$end,$periodType);
            $durations_android=$this->getDurations($appkey,$start,$end,$periodType);
            $activeusers_android=$this->getActiveUsers($appkey,$start,$end,$periodType);
            //$retentions_android=$this->getRetentions($appkey,$start,$end,$periodType);

        }
        
        if($congifpri['um_appkey_ios']){
            $appkey=$congifpri['um_appkey_ios'];
            
            $newusers_ios=$this->getNewUsers($appkey,$start,$end,$periodType);
            $launches_ios=$this->getLaunches($appkey,$start,$end,$periodType);
            $durations_ios=$this->getDurations($appkey,$start,$end,$periodType);
            $activeusers_ios=$this->getActiveUsers($appkey,$start,$end,$periodType);
            //$retentions_ios=$this->getRetentions($appkey,$start,$end,$periodType);

        }

        $newusers=0;
        $launches=0;
        $durations=0;
        $activeusers=0;
        /* value */
        foreach($rs['name'] as $k=>$v){
			$newusers_a=isset($newusers_android[$k]['value'])?$newusers_android[$k]['value']:0;
			$newusers_i=isset($newusers_ios[$k]['value'])?$newusers_ios[$k]['value']:0;
			if($newusers_a||$newusers_i){
                $newusers+=$newusers_a + $newusers_i;
            }
            
        }

        foreach($rs['name'] as $k=>$v){
			$launches_a=isset($launches_android[$k]['value'])?$launches_android[$k]['value']:0;
			$launches_i=isset($launches_ios[$k]['value'])?$launches_ios[$k]['value']:0;
			if($launches_a||$launches_i){
                $launches+=$launches_a + $launches_i;
            }
            
        }

        foreach($rs['name'] as $k=>$v){
			
			$duration_a=isset($durations_android[$k])?$durations_android[$k]:0;
            $duration_i=isset($durations_ios[$k])?$durations_ios[$k]:0;
			if($duration_a||$duration_i){
                $durations+= floor( ($duration_a + $duration_i)/60);
            }
            
        }

        foreach($rs['name'] as $k=>$v){
			$activeusers_a=isset($activeusers_android[$k]['value'])?$activeusers_android[$k]['value']:0;
			$activeusers_i=isset($activeusers_ios[$k]['value'])?$activeusers_ios[$k]['value']:0;
			if($activeusers_a||$activeusers_i){
                $activeusers+=$activeusers_a + $activeusers_i;
            }
            
        }

        $data=[
            'newusers'=>$newusers,
            'launches'=>$launches,
            'durations'=>$durations,
            'activeusers'=>$activeusers,
        ];

        return $data;
        
    }
	
	
	/* 获取某天总数 */
    public function getDailyData($appkey,$start){
			
			$res=[
                'newUsers'=>0,
                'totalUsers'=>0,
                'activityUsers'=>0,
                'launches'=>0,
            ];
		
            $data=[
                'appkey'=>$appkey,
                'date'=>$start,
            ];
            
            $urlPath='param2/1/com.umeng.uapp/umeng.uapp.getDailyData/';
            
            $rs=$this->getUmengData($urlPath,$data);
			
			
            
            return isset($rs['dailyData'])?$rs['dailyData']:$res;
    }
	
	
	/* 获取App新增用户数 */
    public function getNewUsers($appkey,$start,$end,$periodType){
            $data=[
                'appkey'=>$appkey,
                'startDate'=>$start,
                'endDate'=>$end,
                'periodType'=>$periodType,
            ];
            
            $urlPath='param2/1/com.umeng.uapp/umeng.uapp.getNewUsers/';
            
            $rs=$this->getUmengData($urlPath,$data);
			
			return isset($rs['newUserInfo'])?$rs['newUserInfo']:[];

    }
    /* 获取App启动次数 */
    public function getLaunches($appkey,$start,$end,$periodType){

            $data=[
                'appkey'=>$appkey,
                'startDate'=>$start,
                'endDate'=>$end,
                'periodType'=>$periodType,
            ];
            
            $urlPath='param2/1/com.umeng.uapp/umeng.uapp.getLaunches/';
            
            $rs=$this->getUmengData($urlPath,$data);
            
            return isset($rs['launchInfo'])?$rs['launchInfo']:[];
    }
    /* 获取App使用时长 */
    public function getDurations($appkey,$start,$end,$periodType){
            
            $urlPath='param2/1/com.umeng.uapp/umeng.uapp.getDurations/';
            $info=[];
            
            $start_time=strtotime($start);
            $end_time=strtotime($end);
            for($i=$start_time;$i<=$end_time;$i+=60*60*24){
                $date=date("Y-m-d",$i);
                $data=[
                    'appkey'=>$appkey,
                    'date'=>$date,
                    'statType'=>'daily',
                ];

                $rs=$this->getUmengData($urlPath,$data);
                
                $info[]=isset($rs['average'])?$rs['average']:[];
            }
            return $info;
    }
    /* 活跃用户数 */
    public function getActiveUsers($appkey,$start,$end,$periodType){

            $data=[
                'appkey'=>$appkey,
                'startDate'=>$start,
                'endDate'=>$end,
                'periodType'=>$periodType,
            ];
            
            $urlPath='param2/1/com.umeng.uapp/umeng.uapp.getActiveUsers/';
            
            $rs=$this->getUmengData($urlPath,$data);
            
            return isset($rs['activeUserInfo'])?$rs['activeUserInfo']:[];
    }
    /* 留存用户数 */
    public function getRetentions($appkey,$start,$end,$periodType){

            $data=[
                'appkey'=>$appkey,
                'startDate'=>$start,
                'endDate'=>$end,
                'periodType'=>$periodType,
            ];
            
            $urlPath='param2/1/com.umeng.uapp/umeng.uapp.getRetentions/';
            
            $rs=$this->getUmengData($urlPath,$data);
            
             return isset($rs['retentionInfo'])?$rs['retentionInfo']:[];
    }
    
    
    public function getUmengData($urlPath,$data){
        $congifpri=getConfigPri();
        
        $url='https://gateway.open.umeng.com/openapi/';
        
        $appkey=$congifpri['um_apikey'];
        $apiSecurity=$congifpri['um_apisecurity'];
        
        $urlPath.=$appkey;
        
        ksort($data);
        $param='';
        foreach($data as $k=>$v){
            $param.=$k.$v;
        }
        $s=$urlPath.$param;
        $Signature=strtoupper ( bin2hex ( hash_hmac("sha1", $s, $apiSecurity, true) )  );
        
        $url.=$urlPath;
        
        $query=http_build_query($data);
        
        $query.='&_aop_signature='.$Signature;

        $rs=$this->Post($query,$url);

        return json_decode($rs,true);
        
    }
    public function Post($curlPost,$url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
    }
    
    /* 广告数据 */
    public function getAds($start,$end){
        $data_ad=array(
            'date'=>[],
            'value'=>[],
            'videoviews'=>[],
        );
        
        
        $start_time=$start;
        $end_time=$end;
        
        if($end_time - $start_time == 60*60*24){
            $data_ad['date'][]='00';
            $data_ad['value'][]='0';
            $data_ad['videoviews'][]='0';
            for($i2=$start_time;$i2<$end_time;){
                $end=$i2+60*60;
                $videonums=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=1 and addtime > {$i2} and addtime <= {$end}")->count();
                $videoviews=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=1 and addtime > {$i2} and addtime <= {$end}")->sum('views');
                if(!$videoviews){
                    $videoviews=0;
                }
                if($end==$end_time){
                    $data_ad['date'][]='24';
                }else{
                    $data_ad['date'][]=date("H",$end);
                }
                
                $data_ad['value'][]=$videonums;
                $data_ad['videoviews'][]=$videoviews;
                $i2=$end;
            }
        }else{
            
            for($i2=$start_time;$i2<$end_time;){
                $end=$i2+60*60*24;
                $videonums=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=1 and addtime > {$i2} and addtime <= {$end}")->count();
                $videoviews=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=1 and addtime > {$i2} and addtime <= {$end}")->sum('views');
                if(!$videoviews){
                    $videoviews=0;
                }

                $data_ad['date'][]=date("Y-m-d",$i2);
                $data_ad['value'][]=$videonums;
                $data_ad['videoviews'][]=$videoviews;
                $i2=$end;
            }
        }
        
        return $data_ad;
    }


    
    /* 导出 */
    public function export(){
		
		
		$data = $this->request->param();

        $action=isset($data['action']) ? $data['action']: '';
        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';

        $nowtime=time();
        //当天0点
        $today=date("Y-m-d",$nowtime);
        $today_start=strtotime($today);
        //当天 23:59:59
        $today_end=strtotime("{$today} + 1 day");
        
        $start=$today_start;
        $end=$today_end;

        if($start_time){
            $start=strtotime($start_time);
        }
        if($end_time){
            $end=strtotime($end_time) + 60*60*24;  
        }


        $xlsData=[];
        switch($action){
            case '1':
                $result=$this->getBasic($start,$end);
				
                $xlsName  = $Think.\lang('BASIC_INDEX_EXPORT');
                $cellName = array('A','B','C','D','E');
                $xlsCell  = array('newusers','launches','duration','activityUsers','users_total');
                
                $xlsData[]=[
                    'newusers'=>$Think.\lang('BASIC_INDICATORS'),
                    'launches'=>'',
                    'duration'=>'',
                    'activityUsers'=>'',
                    'users_total'=>'',
                    'ismerge'=>'1',
                ];
                
                $date=date("Y-m-d",$start).'至'.date("Y-m-d",($end-1));
                if($end - $start == 60*60*24){
                    $date=date("Y-m-d",$start);
                }
                $xlsData[]=[
                    'newusers'=>$date,
                    'launches'=>'',
                    'duration'=>'',
                    'activityUsers'=>'',
                    'users_total'=>'',
                    'ismerge'=>'1',
                ];
                

                $xlsData[]=[
                    'newusers'=>$Think.\lang('NUM_OF_NEW_USERS'),
                    'launches'=>$Think.\lang('NUM_OF_STARTS'),
                    'duration'=>$Think.\lang('DURATION_OF_USE'),
                    'activityUsers'=>$Think.\lang('ACTIVE_USERS'),
                    'users_total'=>$Think.\lang('TOTAL_REGISTRATION'),
                    'ismerge'=>'0',
                ];
                
                $users_total=Db::name("user")->where("user_type=2")->count();
                
                $xlsData[]=[
                    'newusers'=>$result['newusers'],
                    'launches'=>$result['launches'],
                    'duration'=>$result['durations'],
                    'activityUsers'=>$result['activeusers'],
                    'users_total'=>$users_total,
                    'ismerge'=>'0',
                ];

                break;
            case '2':
                
                /* 七天数据 */
                $time_7=$today_start-60*60*24*7;
                $data_week=array(
                    'date'=>[],
                    'value'=>[],
                    'fans'=>[],
                    'likes'=>[],
                );
                for($i=$time_7;$i<$today_start;){
                    $end=$i+60*60*24;
                    $videonums=Db::name('user_video')->where("isdel=0 and status=1 and is_ad=0 and addtime > {$i} and addtime <= {$end}")->count();
                    $likes=Db::name('user_video_like')->where("addtime > {$i} and addtime <= {$end}")->count();
                    $fans=Db::name('user_attention')->where("addtime > {$i} and addtime <= {$end}")->count();
                    
                    $data_week['date'][]=date("Y-m-d",$i);
                    $data_week['value'][]=$videonums;
                    $data_week['fans'][]=$fans;
                    $data_week['likes'][]=$likes;
                    $i=$end;
                }
                
                $xlsName  = $Think.\lang('SEVEN_DAY_DATE');
                $cellName = array('A','B','C','D');
                $xlsCell  = array('date','value','fans','likes');
                
                $xlsData[]=[
                    'date'=>$Think.\lang('SEVEN_DAY_DATE'),
                    'value'=>'',
                    'fans'=>'',
                    'likes'=>'',
                    'ismerge'=>'1',
                ];
                
                $date=date("Y-m-d",$time_7).'至'.date("Y-m-d",($today_start-1));

                $xlsData[]=[
                    'date'=>$date,
                    'value'=>'',
                    'fans'=>'',
                    'likes'=>'',
                    'ismerge'=>'1',
                ];
                
                $xlsData[]=[
                    'date'=>$Think.\lang('TIME'),
                    'value'=>$Think.\lang('NUM_OF_VIDEOS'),
                    'fans'=>$Think.\lang('NUM_OF_FANS'),
                    'likes'=>$Think.\lang('NUM_OF_LIKES'),
                    'ismerge'=>'0',
                ];
                foreach($data_week['date'] as $k=>$v){
                    $info=[];
                    $info['date']=$v;
                    $info['value']=$data_week['value'][$k];
                    $info['fans']=$data_week['fans'][$k];
                    $info['likes']=$data_week['likes'][$k];
                    $info['ismerge']='0';
                    $xlsData[]=$info; 
                }
                break;
            case '3':
                $result=$this->getAds($start,$end);
                
                $xlsName  = $Think.\lang('ADVERTISING_DATA');
                $cellName = array('A','B','C');
                $xlsCell  = array('date','value','videoviews');
                
                $xlsData[]=[
                    'date'=>$Think.\lang('ADVERTISING_DATA'),
                    'value'=>'',
                    'videoviews'=>'',
                    'ismerge'=>'1',
                ];
                
                $date=date("Y-m-d",$start).'至'.date("Y-m-d",($end-1));
                if($end - $start == 60*60*24){
                    $date=date("Y-m-d",$start);
                }
                $xlsData[]=[
                    'date'=>$date,
                    'value'=>'',
                    'videoviews'=>'',
                    'ismerge'=>'1',
                ];
                
                $xlsData[]=[
                    'date'=>$Think.\lang('TIME'),
                    'value'=>$Think.\lang('NUM_OF_ADVERTISEMENTS'),
                    'videoviews'=>$Think.\lang('NUM_OF_VIEWS'),
                    'ismerge'=>'0',
                ];
                foreach($result['date'] as $k=>$v){
                    $info=[];
                    $info['date']=$v;
                    $info['value']=$result['value'][$k];
                    $info['videoviews']=$result['videoviews'][$k];
                    $info['ismerge']='0';
                    $xlsData[]=$info;
                    
                }
                break;
        }
        
        $this->exportExcel($xlsName,$xlsCell,$xlsData,$cellName);    
    }
	
	
	/**导出Excel 表格
   * @param $expTitle 名称
   * @param $expCellName 参数
   * @param $expTableData 内容
   * @throws \PHPExcel_Exception
   * @throws \PHPExcel_Reader_Exception
   */
	public function exportExcel($xlsName,$expCellName,$expTableData,$cellName){
		$xlsTitle = iconv('utf-8', 'gb2312', $xlsName);//文件名称
		$fileName = $xlsTitle.'_'.date('YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);
	
		require_once CMF_ROOT.'sdk/PHPExcel/PHPExcel.php';
		
		$objPHPExcel = new \PHPExcel();
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);


		for($i=0;$i<$dataNum;$i++){
            $cellinfo=$expTableData[$i];
            if($cellinfo['ismerge']==1){
                $objPHPExcel->getActiveSheet()->mergeCells('A'.($i+1).':'.end($cellName).($i+1));//合并单元格（如果要拆分单元格是需要先合并再拆分的，否则程序会报错）
                
                $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+1), $cellinfo[$expCellName[0]]);
            }else{
                for($j=0;$j<$cellNum;$j++){
                    $key=$expCellName[$j];
                    $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+1), $cellinfo[$key]);
                }
            }
			
		}
		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
		header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//Excel5为xls格式，excel2007为xlsx格式
		$objWriter->save('php://output');
		exit;
	}    
	
	
    public function dashboardWidget()
    {
        $dashboardWidgets = [];
        $widgets          = $this->request->param('widgets/a');
        if (!empty($widgets)) {
            foreach ($widgets as $widget) {
                if ($widget['is_system']) {
                    array_push($dashboardWidgets, ['name' => $widget['name'], 'is_system' => 1]);
                } else {
                    array_push($dashboardWidgets, ['name' => $widget['name'], 'is_system' => 0]);
                }
            }
        }

        cmf_set_option('admin_dashboard_widgets', $dashboardWidgets, true);

        $this->success($Think.\lang('UPDATE_SUCCESSFUL'));

    }

}
