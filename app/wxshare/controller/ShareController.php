<?php

namespace app\wxshare\controller;

use cmf\controller\HomeBaseController;
use think\Db;

if (!session_status()) {
  session_start();
}
class ShareController extends HomebaseController
{

  public function index()
  {

    $list = Db::name('user_live')->field("uid,title,city,stream,pull,thumb")->where(["islive" => 1])->order("starttime desc")->limit(0, 20)->select()->toArray();

    foreach ($list as $k => $v) {
      $userinfo = getUserInfo($v['uid']);
      $v['avatar'] = $userinfo['avatar'];
      $v['avatar_thumb'] = $userinfo['avatar_thumb'];
      $v['user_nicename'] = $userinfo['user_nicename'];
      $v['thumb'] = get_upload_path($v['thumb']);
      if (!$v['thumb']) {
        $v['thumb'] = $v['avatar'];
      }
      $list[$k] = $v;
    }

    $this->assign('list', $list);

    /* session('uid',null);
		session('token',null);
		session('openid',null);
		session('unionid',null);
		session('userinfo',null); */

    return $this->fetch();
  }

  public function show()
  {

    $roomnum   = $this->request->param('roomnum', 0, 'intval');

    $liveinfo = array();
    $configpri = getConfigPri();
    $this->assign('configpri', $configpri);

    $config = getConfigPub();
    $this->assign('config', $config);

    $anchor = getUserInfo($roomnum);

    $liveinfo = Db::name("user_live")->field("uid,islive,stream,pull,isvideo")->where(['uid' => $roomnum, 'islive' => 1])->find();


    if (!$liveinfo) {
      $liveinfo['uid'] = $roomnum;
      $liveinfo['type'] = 0;
      $liveinfo['islive'] = '0';
      $liveinfo['pull'] = '';
      $liveinfo['isvideo'] = 1;
      $liveinfo['stream'] = '';
    }

    if ($liveinfo['islive'] == 0) {
      $liveinfo['type'] = 0;
      $liveinfo['pull'] = '';
    }

    $liveinfo['user_nicename'] = $anchor['user_nicename'];
    $liveinfo['avatar'] = $anchor['avatar'];
    $liveinfo['avatar_thumb'] = $anchor['avatar_thumb'];

    if ($liveinfo['isvideo'] == 1) {
      $hls = $liveinfo['pull'];
    } else {
      $hls = '';
      if ($liveinfo['islive'] && $liveinfo['type'] == 0) {
        if ($configpri['cdn_switch'] == 5) {
          $hls = $liveinfo['pull'];
        } else {
          $hls = PrivateKeyA('http', $liveinfo['stream'] . '.m3u8', 0);
        }
      }
    }


    $this->assign('livetype', $liveinfo['type']);
    $this->assign('hls', $hls);
    $this->assign('liveinfo', $liveinfo);

    $isattention = 0;

    // session("uid",'18576');
    // session("token",'06adca42219ef55f6d2e705603168720');
    $uid = (int)session("uid");

    //判断用户是否存在
    $isexist = checkUser([['id', '=', $uid], ['user_type', '=', '2']]);

    if (!$isexist) {
      session('uid', null);
      $uid = 0;
    }

    if ($uid == $anchor['id']) {
      $this->assign('reason', '不能进入自己的直播间');
      return $this->fetch('error');
      exit;
    }
    $userinfo = [];
    //$uid=18576;
    if ($uid) {

      $res = $this->checkShut($uid, $anchor['id']);
      if ($res == 0) {
        $this->assign('reason', '您已被踢出房间');
        return $this->fetch('error');
      }
      $userinfo = getUserInfo($uid);

      $isexist = Db::name('user_attention')->where(['uid' => $uid, 'touid' => $liveinfo['uid']])->find();
      if ($isexist) {
        $isattention = 1;
      }
    }
    $this->assign('isattention', $isattention);
    $this->assign('userinfo', $userinfo);
    if ($userinfo) {
      $this->assign('userinfoj', json_encode($userinfo));
    } else {
      $this->assign('userinfoj', 'null');
    }


    $sensitive_words = str_replace(array("\r\n", "\r", "\n"), "", $configpri['sensitive_words']);
    $words_a = explode(',', $sensitive_words);

    $this->assign("words_j", json_encode($words_a));

    return $this->fetch();
  }

  public function wxLogin()
  {

    $roomnum   = $this->request->param('roomnum', 0, 'intval');

    $configpri = getConfigPri();

    $AppID = $configpri['login_wx_appid'];
    $callback  = get_upload_path('/wxshare/Share/wxLoginCallback?roomnum=' . $roomnum); //回调地址
    //微信登录
    if (!session_id()) {

      session_start();
    }
    //-------生成唯一随机串防CSRF攻击
    $state  = md5(uniqid(rand(), TRUE));
    $_SESSION["wx_state"]    = $state; //存到SESSION
    $callback = urlencode($callback);
    //snsapi_base 静默  snsapi_userinfo 授权
    $wxurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$AppID}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect ";

    header("Location: $wxurl");
  }

  public function wxLoginCallback()
  {

    $roomnum   = $this->request->param('roomnum', 0, 'intval');
    $code   = $this->request->param('code');

    if ($code) {
      $configpri = getConfigPri();

      $AppID = $configpri['login_wx_appid'];
      $AppSecret = $configpri['login_wx_appsecret'];
      /* 获取token */
      $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$AppID}&secret={$AppSecret}&code={$code}&grant_type=authorization_code";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_URL, $url);
      $json =  curl_exec($ch);
      curl_close($ch);
      $arr = json_decode($json, 1);

      if (isset($arr['errcode'])) {
        $this->assign('reason', $arr['errmsg']);
        return $this->fetch('error');
      }

      /* 刷新token 有效期为30天 */
      $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$AppID}&grant_type=refresh_token&refresh_token={$arr['refresh_token']}";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_URL, $url);
      $json =  curl_exec($ch);
      curl_close($ch);

      $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$arr['access_token']}&openid={$arr['openid']}&lang=zh_CN";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_URL, $url);
      $json =  curl_exec($ch);
      curl_close($ch);
      $wxuser = json_decode($json, 1);

      /* 公众号绑定到 开放平台 才有 unionid  否则 用 openid  */
      $openid = $wxuser['unionid'];
      if (!$openid) {
        $this->assign('reason', '公众号未绑定到开放平台');
        return $this->fetch('error');
      }
      $type = 'wx';
      $userinfo = DB::name("user")->field("id,user_nicename,avatar,avatar_thumb,sex,signature,coin,consumption,votestotal,province,city,birthday,user_status,login_type,last_login_time")->where("openid!='' and openid='{$openid}' and login_type='{$type}'")->find();
      $nowtime = time();
      if (empty($userinfo)) {
        if ($openid == "") {
          $this->assign('reason', '登录错误');
          return $this->fetch('error');
        }

        $user_login = $type . '_' . time() . rand(100, 999);
        $user_pass = cmf_password('123456');

        $reg_reward = $configpri['reg_reward'];

        $data = array(
          'openid'   => $openid,
          'user_login'  => $user_login,
          'user_pass'    => $user_pass,
          'user_nicename'  => $wxuser['nickname'],
          'sex' => $wxuser['sex'],
          'avatar' => $wxuser['headimgurl'],
          'avatar_thumb'  => $wxuser['headimgurl'],
          'login_type' => $type,
          'last_login_ip' => get_client_ip(0, true),
          'create_time' => $nowtime,
          'last_login_time' => $nowtime,
          'coin' => $reg_reward,
          'user_status' => 1,
          "user_type" => 2, //会员
          'signature' => '这家伙很懒，什么都没留下',
        );
        $userid = DB::name("user")->insertGetId($data);

        $userinfo = DB::name("user")->field("id,user_nicename,avatar,avatar_thumb,sex,signature,coin,consumption,votestotal,province,city,birthday,user_status,login_type,last_login_time")->where(['id' => $userid])->find();
      }
      if (!$userinfo) {
        $this->assign('reason', '登录错误');
        return $this->fetch('error');
      }

      $token = md5(md5($userinfo['id'] . time()));
      $expiretime = time() + 60 * 60 * 24 * 300;

      $isok = DB::name("user_token")
        ->where("user_id={$userinfo['id']}")
        ->update(array("token" => $token, "expire_time" => $expiretime, "create_time" => $nowtime));
      if (!$isok) {
        DB::name("user_token")
          ->insert(array("user_id" => $userinfo['id'], "token" => $token, "expire_time" => $expiretime, "create_time" => $nowtime));
      }

      if ($userinfo['birthday']) {
        $userinfo['birthday'] = date('Y-m-d', $userinfo['birthday']);
      } else {
        $userinfo['birthday'] = '';
      }

      $userinfo['token'] = $token;

      delcache("token_" . $userinfo['id']);


      session('uid', $userinfo['id']);
      session('token', $userinfo['token']);
      session('openid', $wxuser['openid']);
      session('unionid', $wxuser['unionid']);
      session('userinfo', $userinfo);

      $href = '/wxshare/Share/show?roomnum=' . $roomnum;

      header("Location: $href");
    } else {
    }
  }


  /* 手机验证码 */
  public function getCode()
  {

    $config = getConfigPri();

    $mobile = $this->request->param('mobile');

    /* $where="user_login='{$mobile}'";
        
		$checkuser = checkUser($where);	
        
        if($checkuser){
            $rs['errno']=1006;
            $rs['errmsg']='该手机号已注册，请登录';
            echo json_encode($rs);
            exit;
        } */

    if (isset($_SESSION['mobile']) && $_SESSION['mobile'] == $mobile && isset($_SESSION['mobile_expiretime']) && $_SESSION['mobile_expiretime'] > time()) {
      $rs['errno'] = 1007;
      $rs['errmsg'] = '验证码5分钟有效，勿多发';
      echo json_encode($rs);
      exit;
    }


    $limit = ip_limit();
    if ($limit == 1) {
      $rs['errno'] = 1003;
      $rs['errmsg'] = '您已当日发送次数过多';
      echo json_encode($rs);
      exit;
    }
    $mobile_code = random(6, 1);

    $result = sendCode($mobile, $mobile_code);

    if ($result['code'] == 0) {

      $_SESSION['mobile'] = $mobile;
      $_SESSION['mobile_code'] = $mobile_code;
      $_SESSION['mobile_expiretime'] = time() + 60 * 5;
    } else if ($result['code'] == 667) {

      $_SESSION['mobile'] = $mobile;
      $_SESSION['mobile_code'] = $result['msg'];
      $_SESSION['mobile_expiretime'] = time() + 60 * 5;

      $rs['errno'] = 0;
      $rs['errmsg'] = "验证码为：{$result['msg']}";
      echo json_encode($rs);
      exit;
    } else {
      $rs['errno'] = 1004;
      $rs['errmsg'] = $result['msg'];
      echo json_encode($rs);
      exit;
    }

    $rs = array(
      'errno' => 0,
      'data' => array(),
      'errmsg' => '验证码已送',
    );

    echo json_encode($rs);
    exit;
  }

  /* 登录 */
  /* 	$user_login!=$_SESSION['mobile'] */
  public function userLogin()
  {
    $mobile = $this->request->param('mobile');
    $code = $this->request->param('code');
    $user_login = checkNull($mobile);
    $code = checkNull($code);


    $rs = array('errno' => 0, 'data' => array(), 'errmsg' => '');

    /*var_dump($_SESSION['mobile']);
		

		var_dump($_SESSION['mobile_code']);
		die;*/

    if (!isset($_SESSION['mobile']) || !isset($_SESSION['mobile_code'])) {
      $rs['errno'] = 1120;
      $rs['errmsg'] = '请先获取验证码';
      echo json_encode($rs);
      exit;
    }

    if ($user_login != $_SESSION['mobile']) {
      $rs['errno'] = 1120;
      $rs['errmsg'] = '手机号码不一致';
      echo json_encode($rs);
      exit;
    }

    if ($code != $_SESSION['mobile_code']) {
      $rs['errno'] = 1120;
      $rs['errmsg'] = '验证码错误';
      echo json_encode($rs);
      exit;
    }

    $nowtime = time();

    $userinfo = Db::name("user")->field("id,user_login,user_nicename,avatar,avatar_thumb,sex,signature,consumption,votestotal,province,city,coin,votes,birthday,issuper,user_status")->where("user_login='{$user_login}' and user_type='2'")->find();

    if (!$userinfo) {
      $pass = 'yunbaokj';
      $user_pass = cmf_password($pass);

      $configpri = getConfigPri();
      $reg_reward = $configpri['reg_reward'];

      /* 无信息 进行注册 */
      $data = array(
        'user_login' => $user_login,
        'user_email' => '',
        'mobile' => $user_login,
        'user_nicename' => '请设置昵称',
        'user_pass' => $user_pass,
        'signature' => '这家伙很懒，什么都没留下',
        'avatar' => '/default.jpg',
        'avatar_thumb' => '/default_thumb.jpg',
        'last_login_ip' => get_client_ip(0, true),
        'create_time' => $nowtime,
        'last_login_time' => $nowtime,
        'coin' => $reg_reward,
        'user_status' => 1,
        "user_type" => 2, //会员
      );
      $userid = Db::name("user")->insertGetId($data);
      $userinfo = array(
        'id' => $userid,
        'user_login' => $data['user_login'],
        'user_nicename' => $data['user_nicename'],
        'avatar' => $data['avatar'],
        'avatar_thumb' => $data['avatar_thumb'],
        'sex' => '2',
        'signature' => $data['signature'],
        'consumption' => 0,
        'votestotal' => 0,
        'province' => '',
        'city' => '',
        'coin' => $reg_reward,
        'votes' => 0,
        'birthday' => '',
        'issuper' => 0,
        'user_status' => 1,
      );
    }

    if ($userinfo['user_status'] == 0) {
      $rs['errno'] = 1002;
      $rs['errmsg'] = '账号已被禁用';
      echo json_encode($rs);
      exit;
    }


    $token = md5(md5($userinfo['id'] . $userinfo['user_login'] . time()));
    $expiretime = time() + 60 * 60 * 24 * 300;

    $isok = DB::name("user_token")
      ->where("user_id={$userinfo['id']}")
      ->update(array("token" => $token, "expire_time" => $expiretime, "create_time" => $nowtime));
    if (!$isok) {
      DB::name("user_token")
        ->insert(array("user_id" => $userinfo['id'], "token" => $token, "expire_time" => $expiretime, "create_time" => $nowtime));
    }

    $userinfo['token'] = $token;



    delcache("token_" . $userinfo['id']);


    session('uid', $userinfo['id']);
    session('token', $userinfo['token']);
    session('user', $userinfo);

    echo json_encode($rs);
    exit;
    exit;
  }


  /* 用户进入 写缓存 */
  public function setNodeInfo()
  {

    /* 当前用户信息 */
    $uid = (int)session("uid");
    $token = session("token");

    $liveuid = $this->request->param('liveuid', 0, 'intval');

    if ($uid > 0) {
      $info = getUserInfo($uid);
      $info['liveuid'] = $liveuid;
      $info['token'] = $token;
      $info['contribution'] = '0';
      $info['usertype'] = getIsAdmin($uid, $liveuid);
      $info['level'] = '0';

      //获取用户的守护类型
      $guard_info = Db::name("guard_user")->where("uid={$uid} and liveuid={$liveuid}")->find();
      if ($guard_info) {
        $now = time();
        if ($guard_info['endtime'] <= $now) {
          Db::name("guard_user")->where("id={$guard_info['id']}")->delete();
          $info['guard_type'] = '0';
        } else {
          $info['guard_type'] = (string)$guard_info['type'];
        }
      } else {
        $info['guard_type'] = '0';
      }

      /* 等级+100 保证等级位置位数相同，最后拼接1 防止末尾出现0 */
      $info['sign'] = $info['contribution'] . '.' . ($info['level'] + 100) . '1';
    } else {
      /* 游客 */
      $sign = mt_rand(1000, 9999);
      $info['id'] = '-' . $sign;
      $info['user_nicename'] = '游客' . $sign;
      $info['avatar'] = '';
      $info['avatar_thumb'] = '';
      $info['sex'] = '0';
      $info['signature'] = '0';
      $info['consumption'] = '0';
      $info['votestotal'] = '0';
      $info['province'] = '';
      $info['city'] = '';
      $info['token'] = md5($liveuid . '_' . $sign);
      $info['liveuid'] = $liveuid;
      $info['usertype'] = 30;
      $info['contribution'] = '0';
      $info['vip'] = array('type' => '0');
      $info['level'] = '0';
      $info['guard_type'] = '0';

      /* 等级+100 保证等级位置位数相同，最后拼接1 防止末尾出现0 */
      $info['sign'] = $info['contribution'] . '.' . ($info['level'] + 100) . '1';
      $token = $info['token'];
    }

    setcaches($token, $info);

    $data = array(
      'error' => 0,
      'userinfo' => $info,
    );
    echo  json_encode($data);
  }

  protected function checkShut($uid, $liveuid)
  {
    $where = [];
    $where['uid'] = $uid;
    $where['liveuid'] = $liveuid;

    $isexist = Db::name('user_live_kick')
      ->field("id")
      ->where($where)
      ->find();
    if ($isexist) {
      return 0;
    }

    $isexist = Db::name('user_live_shut')
      ->where($where)
      ->find();
    if ($isexist) {
      hSet($liveuid . 'shutup', $uid, 1);
    } else {
      hDel($liveuid . 'shutup', $uid);
    }

    return 1;
  }


  public function getGift()
  {
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $gift = DB::name('gift')->field("id,type,giftname,needcoin,gifticon,swftime")->where('type!=2')->order("orderno asc")->select();
    foreach ($gift as $k => $v) {
      $v['gifticon'] = get_upload_path($v['gifticon']);
      $gift[$k] = $v;
    }
    $rs['info'] = $gift;
    echo json_encode($rs);
    exit;
  }

  /* 关注 */
  public function follow()
  {

    $uid = (int)session("uid");
    $touid = $this->request->param('touid', 0, 'intval');
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    if ($uid < 1 || $touid < 1) {
      $rs = array(
        'code' => 1001,
        'msg' => '关注失败',
        'info' => array()
      );
      echo json_encode($rs);
      exit;
    }


    $data = array(
      "uid" => $uid,
      "touid" => $touid,
    );
    $result = DB::name('user_attention')->insert($data);
    if (!$result) {
      $rs = array(
        'code' => 1001,
        'msg' => '关注失败',
        'info' => array()
      );
    }
    echo json_encode($rs);
    exit;
  }

  /* 送礼物 */
  public function sendGift()
  {

    //$uid=(int)session("uid");
    $uid = 18576;
    $token = $this->request->param('token');
    $touid = $this->request->param('touid', 0, 'intval');
    $stream = $this->request->param('stream');
    $giftid = $this->request->param('giftid', 0, 'intval');

    $token = checkNull($token);
    $touid = checkNull($touid);
    $stream = checkNull($stream);
    $giftid = checkNull($giftid);
    $giftcount = 1;

    //礼物信息
    $giftinfo = Db::name("gift")->field("giftname,gifticon,needcoin,type,swftype,swf,swftime")->where("id='{$giftid}'")->find();
    if (!$giftinfo) {
      echo '{"errno":"1001","data":"","msg":"礼物信息错误"}';
      exit;
    }

    //判断是否是手绘礼物
    if ($giftinfo['type'] == 2) {
      echo '{"errno":"1001","data":"","msg":"此礼物为手绘礼物,请前去下载app体验~"}';
      exit;
    }

    $total = $giftinfo['needcoin'] * $giftcount;
    $addtime = time();

    $where3['uid'] = $touid;
    $liveinfo = Db::name("user_live")->where("islive=1")->where($where3)->find();
    $showid = 0;
    if ($liveinfo) {
      $showid = $liveinfo['starttime'];
    }

    //更新用户余额 消费
    $ifok = Db::name("user")
      ->where([['id', '=', $uid], ['coin', '>=', $total]])
      ->dec('coin', $total)
      ->inc('consumption', $total)
      ->update();
    if (!$ifok) {
      /* 余额不足 */
      echo '{"errno":"1001","data":"","msg":"余额不足"}';
      exit;
    }


    $anthor_total = $total;


    // 更新直播 映票 累计映票
    Db::name("user")
      ->where([['id', '=', $touid]])
      ->inc('votes', $anthor_total)
      ->inc('votestotal', $total)
      ->update();

    if ($anthor_total) {
      $insert_votes = [

        'action' => '5',
        'uid' => $touid,
        'votes' => $anthor_total,
        'addtime' => $addtime,
      ];
      Db::name('votes_record')->insert($insert_votes);
    }

    $action = 'sendgift';

    //写入消费记录

    $data = array(
      'type' => 'expend',
      'action' => $action,
      'uid' => $uid,
      'touid' => $touid,
      'giftid' => $giftid,
      'giftcount' => $giftcount,
      'totalcoin' => $total,
      'showid' => $showid,
      'addtime' => $addtime

    );

    Db::name("user_coinrecord")->insert($data);


    /* 清除缓存 */
    delCache("userinfo_" . $uid);
    delCache("userinfo_" . $touid);


    $userinfo3 = Db::name('user')->field("votestotal,user_nicename")->where("id='{$touid}'")->find();

    $gifttoken = md5(md5($action . $uid . $touid . $giftid . $giftcount . $total . $showid . $addtime . rand(100, 999)));

    $swf = $giftinfo['swf'] ? get_upload_path($giftinfo['swf']) : '';


    $userinfo2 = Db::name('user')->field("consumption,coin,votestotal")->where("id='{$uid}'")->find();

    $result = array(
      'uid' => (int)$uid,
      'giftid' => (int)$giftid,
      'type' => $giftinfo['type'],
      'giftcount' => (int)$giftcount,
      'totalcoin' => $total,
      'giftname' => $giftinfo['giftname'],
      'gifticon' => get_upload_path($giftinfo['gifticon']),
      'swftime' => $giftinfo['swftime'],
      'swftype' => $giftinfo['swftype'],
      'swf' => $swf,
      'coin' => $userinfo2['coin'],
      'votestotal' => $userinfo3['votestotal'],

    );

    setcaches($gifttoken, $result);

    if ($liveinfo) {
      zIncrBy('user_' . $liveinfo['stream'], $total, $uid);
    }

    echo '{"errno":"0","uid":"' . $uid . '","type":"' . $giftinfo['type'] . '","coin":"' . $userinfo2['coin'] . '","gifttoken":"' . $gifttoken . '","livename":"' . $userinfo3['user_nicename'] . '","msg":"赠送成功"}';
    exit;
  }

  /* 支付页面  */
  public function pay()
  {

    $uid = (int)session("uid");

    $userinfo = Db::name("user")->field("id,user_nicename,avatar_thumb,coin")->where("id='{$uid}'")->find();
    $this->assign('userinfo', $userinfo);

    $chargelist = Db::name('charge_rules')->field('id,coin,money,money_ios,product_id,give')->order('list_order asc')->select();

    $this->assign('chargelist', $chargelist);

    return $this->fetch();
  }
  /* 获取订单号 */
  public function getOrderId()
  {
    $uid = (int)session("uid");
    $chargeid = $this->request->param('chargeid', 0, 'intval');

    $rs = array(
      'code' => 0,
      'data' => array(),
      'msg' => '',
    );
    $charge = Db::name('charge_rules')->where("id={$chargeid}")->find();
    if (!$charge) {
      $rs['code'] = 1002;
      $rs['msg'] = '订单信息错误';
      echo json_encode($rs);
      exit;
    }

    $orderid = $uid . '_' . date('YmdHis') . rand(100, 999);
    $orderinfo = array(
      "uid" => $uid,
      "touid" => $uid,
      "money" => $charge['money'],
      "coin" => $charge['coin'],
      "coin_give" => $charge['give'],
      "orderno" => $orderid,
      "type" => '2',
      "ambient" => '1',
      "status" => 0,
      "addtime" => time()
    );
    $result = Db::name('charge_user')->insert($orderinfo);
    if (!$result) {
      $rs['code'] = 1001;
      $rs['msg'] = '订单生成失败';
      echo json_encode($rs);
      exit;
    }

    $rs['data']['uid'] = $uid;
    $rs['data']['money'] = $charge['money'];
    $rs['data']['orderid'] = $orderid;



    echo json_encode($rs);
    exit;
  }
}
