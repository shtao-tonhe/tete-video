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

use app\admin\model\RouteModel;
use cmf\controller\AdminBaseController;

use think\Db;
use think\facade\Cache;

/**
 * Class SettingController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'设置',
 *     'action' =>'default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 0,
 *     'icon'   =>'cogs',
 *     'remark' =>'系统设置入口'
 * )
 */
class SettingController extends AdminBaseController
{

    /**
     * 网站信息
     * @adminMenu(
     *     'name'   => '网站信息',
     *     'parent' => 'default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 0,
     *     'icon'   => '',
     *     'remark' => '网站信息',
     *     'param'  => ''
     * )
     */
    public function site()
    {
        $content = hook_one('admin_setting_site_view');

        if (!empty($content)) {
            return $content;
        }

        $noNeedDirs     = [".", "..", ".svn", 'fonts'];
        $adminThemesDir = WEB_ROOT . config('template.cmf_admin_theme_path') . config('template.cmf_admin_default_theme') . '/public/assets/themes/';
        $adminStyles    = cmf_scan_dir($adminThemesDir . '*', GLOB_ONLYDIR);
        $adminStyles    = array_diff($adminStyles, $noNeedDirs);
        $cdnSettings    = cmf_get_option('cdn_settings');
        $cmfSettings    = cmf_get_option('cmf_settings');
        $adminSettings  = cmf_get_option('admin_settings');

        $adminThemes = [];
        $themes      = cmf_scan_dir(WEB_ROOT . config('template.cmf_admin_theme_path') . '/*', GLOB_ONLYDIR);

        foreach ($themes as $theme) {
            if (strpos($theme, 'admin_') === 0) {
                array_push($adminThemes, $theme);
            }
        }

        if (APP_DEBUG && false) { // TODO 没确定要不要可以设置默认应用
            $apps = cmf_scan_dir(APP_PATH . '*', GLOB_ONLYDIR);
            $apps = array_diff($apps, $noNeedDirs);
            $this->assign('apps', $apps);
        }

        $this->assign('site_info', cmf_get_option('site_info'));
        $this->assign("admin_styles", $adminStyles);
        $this->assign("templates", []);
        $this->assign("admin_themes", $adminThemes);
        $this->assign("cdn_settings", $cdnSettings);
        $this->assign("admin_settings", $adminSettings);
        $this->assign("cmf_settings", $cmfSettings);

        return $this->fetch();
    }

    /**
     * 网站信息设置提交
     * @adminMenu(
     *     'name'   => '网站信息设置提交',
     *     'parent' => 'site',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '网站信息设置提交',
     *     'param'  => ''
     * )
     */
    public function sitePost()
    {
        if ($this->request->isPost()) {
            $result = $this->validate($this->request->param(), 'SettingSite');
			
            if ($result !== true) {
                $this->error($result);
            }

            $oldconfig=cmf_get_option('site_info');

            $options = $this->request->param('options/a');

            $login_type=isset($options['login_type'])?$options['login_type']:'';
            $share_type=isset($options['share_type'])?$options['share_type']:'';

            $options['login_type']='';
            $options['share_type']='';

            if($login_type){
                $options['login_type']=implode(',',$login_type);
            }

            if($share_type){
                $options['share_type']=implode(',',$share_type);
            }

            $brightness=$options['brightness'];
            if(!is_numeric($brightness) || $brightness<0 || $brightness>100 || floor($brightness)!=$brightness || strpos($brightness, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_BRIGHTNESS'));
            }

            $skin_whiting=$options['skin_whiting'];
            if(!is_numeric($skin_whiting) || $skin_whiting<0 || $skin_whiting>9 || floor($skin_whiting)!=$skin_whiting || strpos($skin_whiting, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_WHITENING'));
            }

            $skin_smooth=$options['skin_smooth'];
            if(!is_numeric($skin_smooth) || $skin_smooth<0 || $skin_smooth>9 || floor($skin_smooth)!=$skin_smooth || strpos($skin_smooth, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_SKIN_GRINDING'));
            }

            $skin_tenderness=$options['skin_tenderness'];
            if(!is_numeric($skin_tenderness) || $skin_tenderness<0 || $skin_tenderness>9 || floor($skin_tenderness)!=$skin_tenderness || strpos($skin_tenderness, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_RUDDY'));
            }

            $eye_brow=$options['eye_brow'];
            if(!is_numeric($eye_brow) || $eye_brow<0 || $eye_brow>100 || floor($eye_brow)!=$eye_brow || strpos($eye_brow, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_EYEBROWS'));
            }

            $big_eye=$options['big_eye'];
            if(!is_numeric($big_eye) || $big_eye<0 || $big_eye>100 || floor($big_eye)!=$big_eye || strpos($big_eye, '.')>0){
                $this->error($Think.\lang('BEAUTUFUL_AND_BIG_EYE'));
            }

            $eye_length=$options['eye_length'];
            if(!is_numeric($eye_length) || $eye_length<0 || $eye_length>100 || floor($eye_length)!=$eye_length || strpos($eye_length, '.')>0){
                $this->error($Think.\lang('BEAUTIFUL_EYE_DISTANCE'));
            }

            $eye_corner=$options['eye_corner'];
            if(!is_numeric($eye_corner) || $eye_corner<0 || $eye_corner>100 || floor($eye_corner)!=$eye_corner || strpos($eye_corner, '.')>0){
                $this->error($Think.\lang('BEAUTIFUL_AND_CORNER_OF_EYE'));
            }

            $eye_alat=$options['eye_alat'];
            if(!is_numeric($eye_alat) || $eye_alat<0 || $eye_alat>100 || floor($eye_alat)!=$eye_alat || strpos($eye_alat, '.')>0){
                $this->error($Think.\lang('BEAUTIFUL_AND_OPEN_EYE'));
            }

            $face_lift=$options['face_lift'];
            if(!is_numeric($face_lift) || $face_lift<0 || $face_lift>100 || floor($face_lift)!=$face_lift || strpos($face_lift, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_THIN_FACE'));
            }

            $face_shave=$options['face_shave'];
            if(!is_numeric($face_shave) || $face_shave<0 || $face_shave>100 || floor($face_shave)!=$face_shave || strpos($face_shave, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_CUT_FACE'));
            }

            $mouse_lift=$options['mouse_lift'];
            if(!is_numeric($mouse_lift) || $mouse_lift<0 || $mouse_lift>100 || floor($mouse_lift)!=$mouse_lift || strpos($mouse_lift, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_MOUTH_TYPE'));
            }

            $nose_lift=$options['nose_lift'];
            if(!is_numeric($nose_lift) || $nose_lift<0 || $nose_lift>100 || floor($nose_lift)!=$nose_lift || strpos($nose_lift, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_HTIN_NOSE'));
            }

            $chin_lift=$options['chin_lift'];
            if(!is_numeric($chin_lift) || $chin_lift<0 || $chin_lift>100 || floor($chin_lift)!=$chin_lift || strpos($chin_lift, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_CHIN'));
            }

            $forehead_lift=$options['forehead_lift'];
            if(!is_numeric($forehead_lift) || $forehead_lift<0 || $forehead_lift>100 || floor($forehead_lift)!=$forehead_lift || strpos($forehead_lift, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_FOREHEAD'));
            }

            $lengthen_noseLift=$options['lengthen_noseLift'];
            if(!is_numeric($lengthen_noseLift) || $lengthen_noseLift<0 || $lengthen_noseLift>100 || floor($lengthen_noseLift)!=$lengthen_noseLift || strpos($lengthen_noseLift, '.')>0){
                $this->error($Think.\lang('BEAUTY_AND_LONG_NOSE'));
            }

            cmf_set_option('site_info', $options);

            $action=$Think.\lang('MODIFY_SITE_CONFIGURATION');

            if($options['company_name'] !=$oldconfig['company_name']){
                $action.=$Think.\lang('THE_NAME_OF_COMPANY').$oldconfig['company_name'].$Think.\lang('CHANGE_TO').$options['company_name'].' ;';
            }

            if($options['company_desc'] !=$oldconfig['company_desc']){
                $action.=$Think.\lang('ADMIN_SETTING_SITE_COMPANY_INTRO');
            }

            if($options['app_name'] !=$oldconfig['app_name']){
                $action.=$Think.\lang('APP_NAME_BY').$oldconfig['app_name'].$Think.\lang('CHANGE_TO').$options['app_name'].' ;';
            }

            if($options['sitename'] !=$oldconfig['sitename']){
                $action.=$Think.\lang('WEBSITE_TITLE_BY').$oldconfig['sitename'].$Think.\lang('CHANGE_TO').$options['sitename'].' ;';
            }

            if($options['site'] !=$oldconfig['site']){
                $action.=$Think.\lang('WEBSITE_DOMAIN_NAME_BY').$oldconfig['site'].$Think.\lang('CHANGE_TO').$options['site'].' ;';
            }

            if($options['name_coin'] !=$oldconfig['name_coin']){
                $action.=$Think.\lang('DIAMOND_NAME_BY').$oldconfig['name_coin'].$Think.\lang('CHANGE_TO').$options['name_coin'].' ;';
            }

            if($options['name_votes'] !=$oldconfig['name_votes']){
                $action.=$Think.\lang('GOLD_COIN_BY').$oldconfig['name_votes'].$Think.\lang('CHANGE_TO').$options['name_votes'].' ;';
            }

            if($options['copyright'] !=$oldconfig['copyright']){
                $action.=$Think.\lang('COPYRIGHT_INFORMATION_BY').$oldconfig['copyright'].$Think.\lang('CHANGE_TO').$options['copyright'].' ;';
            }
            
            if($options['qq'] !=$oldconfig['qq']){
                $action.=$Think.\lang('CUSTOMER_SERVICE_QQ').$oldconfig['qq'].$Think.\lang('CHANGE_TO').$options['qq'].' ;';
            }

            if($options['mobile'] !=$oldconfig['mobile']){
                $action.=$Think.\lang('CUSTOMER_SERVICE_TELEPHONE_PROVIDE_BY').$oldconfig['mobile'].$Think.\lang('CHANGE_TO').$options['mobile'].' ;';
            }

            if($options['watermark'] !=$oldconfig['watermark']){
                $action.=$Think.\lang('WATERMARK_IMAGE_BY').$oldconfig['watermark'].$Think.\lang('CHANGE_TO').$options['watermark'].' ;';
            }
            
            if($options['qr_url'] !=$oldconfig['qr_url']){
                $action.=$Think.\lang('DOWNLOAD_QR_CODE_ANDROID').$oldconfig['qr_url'].$Think.\lang('CHANGE_TO').$options['qr_url'].' ;';
            }

            if($options['qr_url_ios'] !=$oldconfig['qr_url_ios']){
                $action.=$Think.\lang('DOWNLOAD_QR_CODE_IOS').$oldconfig['qr_url_ios'].$Think.\lang('CHANGE_TO').$options['qr_url_ios'].' ;';
            }

            if($options['login_type'] !=$oldconfig['login_type']){
                $action.=$Think.\lang('LOGIN_BY').$oldconfig['login_type'].$Think.\lang('CHANGE_TO').$options['login_type'].' ;';
            }
            
            if($options['share_type'] !=$oldconfig['share_type']){
                $action.=$Think.\lang('SHARE_BY').$oldconfig['share_type'].$Think.\lang('CHANGE_TO').$options['share_type'].' ;';
            }
            
            if($options['apk_ver'] !=$oldconfig['apk_ver']){
                $action.=$Think.\lang('APK_VERSION').$oldconfig['apk_ver'].$Think.\lang('CHANGE_TO').$options['apk_ver'].' ;';
            }

            if($options['apk_url'] !=$oldconfig['apk_url']){
                $action.=$Think.\lang('APK_DOWNLOAD_LINK').$oldconfig['apk_url'].$Think.\lang('CHANGE_TO').$options['apk_url'].' ;';
            }

            if($options['apk_des'] !=$oldconfig['apk_des']){
                $action.=$Think.\lang('APK_UPDATED_DESCRIPTION').$oldconfig['apk_des'].$Think.\lang('CHANGE_TO').$options['apk_des'].' ;';
            }

            if($options['ipa_ver'] !=$oldconfig['ipa_ver']){
                $action.=$Think.\lang('IPA_VERSION').$oldconfig['ipa_ver'].$Think.\lang('CHANGE_TO').$options['ipa_ver'].' ;';
            }

            if($options['ios_shelves'] !=$oldconfig['ios_shelves']){
                $action.=$Think.\lang('IPA_VERSION_NUM').$oldconfig['ios_shelves'].$Think.\lang('CHANGE_TO').$options['ios_shelves'].' ;';
            }

            if($options['ipa_url'] !=$oldconfig['ipa_url']){
                $action.=$Think.\lang('IPA_DOWNLOAD_LINK').$oldconfig['ipa_url'].$Think.\lang('CHANGE_TO').$options['ipa_url'].' ;';
            }

            if($options['ipa_des'] !=$oldconfig['ipa_des']){
                $action.=$Think.\lang('IPA_UPDATE_DESCRIPTION').$oldconfig['ipa_des'].$Think.\lang('CHANGE_TO').$options['ipa_des'].' ;';
            }

            if($options['app_android'] !=$oldconfig['app_android']){
                $action.=$Think.\lang('ANDROID_APP_DOWNLOAD_LINK').$oldconfig['app_android'].$Think.\lang('CHANGE_TO').$options['app_android'].' ;';
            }

            if($options['app_ios'] !=$oldconfig['app_ios']){
                $action.=$Think.\lang('IOSAPP_DOWNLOAD_LINK').$oldconfig['app_ios'].$Think.\lang('CHANGE_TO').$options['app_ios'].' ;';
            }

            if($options['video_share_title'] !=$oldconfig['video_share_title']){
                $action.=$Think.\lang('SHORT_VIDEO_SHARING_TITLE').$oldconfig['video_share_title'].$Think.\lang('CHANGE_TO').$options['video_share_title'].' ;';
            }

            if($options['video_share_des'] !=$oldconfig['video_share_des']){
                $action.=$Think.\lang('SHORT_VIDEO_SHARING_SCRIPT').$oldconfig['video_share_des'].$Think.\lang('CHANGE_TO').$options['video_share_des'].' ;';
            }

            if($options['agent_share_title'] !=$oldconfig['agent_share_title']){
                $action.=$Think.\lang('INVITE_FRIENDS_TO_SHARE').$oldconfig['agent_share_title'].$Think.\lang('CHANGE_TO').$options['agent_share_title'].' ;';
            }

            if($options['agent_share_des'] !=$oldconfig['agent_share_des']){
                $action.=$Think.\lang('INVITE_FRIENDS_TO_SHARE_SCRIPT').$oldconfig['agent_share_des'].$Think.\lang('CHANGE_TO').$options['agent_share_des'].' ;';
            }

            if($options['wx_siteurl'] !=$oldconfig['wx_siteurl']){
                $action.=$Think.\lang('WECHAT_PROMOTION_DOMAIN_NAME').$oldconfig['wx_siteurl'].$Think.\lang('CHANGE_TO').$options['wx_siteurl'].' ;';
            }

            if($options['share_title'] !=$oldconfig['share_title']){
                $action.=$Think.\lang('LIVE_SHARING_TITLE_BY').$oldconfig['share_title'].$Think.\lang('CHANGE_TO').$options['share_title'].' ;';
            }

            if($options['share_des'] !=$oldconfig['share_des']){
                $action.=$Think.\lang('LIVE_SHARING_SCRIPT').$oldconfig['share_des'].$Think.\lang('CHANGE_TO').$options['share_des'].' ;';
            }

            if($options['sprout_key'] !=$oldconfig['sprout_key']){
                $action.=$Think.\lang('MENG_YAN_AUTHORIZATION_CODE_ADROID').$oldconfig['sprout_key'].$Think.\lang('CHANGE_TO').$options['sprout_key'].' ;';
            }

            if($options['sprout_key_ios'] !=$oldconfig['sprout_key_ios']){
                $action.=$Think.\lang('MENG_YAN_AUTHORIZATION_CODE_IOS').$oldconfig['sprout_key_ios'].$Think.\lang('CHANGE_TO').$options['sprout_key_ios'].' ;';
            }

            if($options['brightness'] !=$oldconfig['brightness']){
                $action.=$Think.\lang('BEAUTY_BRIGHTNESS').$oldconfig['brightness'].$Think.\lang('CHANGE_TO').$options['brightness'].' ;';
            }

            if($options['skin_whiting'] !=$oldconfig['skin_whiting']){
                $action.=$Think.\lang('BEAUTY_WHITENING').$oldconfig['skin_whiting'].$Think.\lang('CHANGE_TO').$options['skin_whiting'].' ;';
            }
            
            if($options['skin_smooth'] !=$oldconfig['skin_smooth']){
                $action.=$Think.\lang('BEAUTY_SKIN_FRINDING').$oldconfig['skin_smooth'].$Think.\lang('CHANGE_TO').$options['skin_smooth'].' ;';
            }

            if($options['skin_tenderness'] !=$oldconfig['skin_tenderness']){
                $action.=$Think.\lang('BEAUTY_RUDDY').$oldconfig['skin_tenderness'].$Think.\lang('CHANGE_TO').$options['skin_tenderness'].' ;';
            }

            if($options['eye_brow'] !=$oldconfig['eye_brow']){
                $action.=$Think.\lang('BEAUTY_YEYBROWS_FROM').$oldconfig['eye_brow'].$Think.\lang('CHANGE_TO').$options['eye_brow'].' ;';
            }

            if($options['big_eye'] !=$oldconfig['big_eye']){
                $action.=$Think.\lang('BEAUTY_BIG_EYES').$oldconfig['big_eye'].$Think.\lang('CHANGE_TO').$options['big_eye'].' ;';
            }

            if($options['eye_length'] !=$oldconfig['eye_length']){
                $action.=$Think.\lang('BEAUTY_DISTANCE_BETWEEN_EYES').$oldconfig['eye_length'].$Think.\lang('CHANGE_TO').$options['eye_length'].' ;';
            }

            if($options['eye_corner'] !=$oldconfig['eye_corner']){
                $action.=$Think.\lang('BEAUTY_CORNER_OF_EYE').$oldconfig['eye_corner'].$Think.\lang('CHANGE_TO').$options['eye_corner'].' ;';
            }

            if($options['eye_alat'] !=$oldconfig['eye_alat']){
                $action.=$Think.\lang('BEAUTY_OPEN_EYES').$oldconfig['eye_alat'].$Think.\lang('CHANGE_TO').$options['eye_alat'].' ;';
            }

            if($options['face_lift'] !=$oldconfig['face_lift']){
                $action.=$Think.\lang('BEAUTY_THIN_FACE').$oldconfig['face_lift'].$Think.\lang('CHANGE_TO').$options['face_lift'].' ;';
            }

            if($options['face_shave'] !=$oldconfig['face_shave']){
                $action.=$Think.\lang('BEAUTY_FACE_CUTTING').$oldconfig['face_shave'].$Think.\lang('CHANGE_TO').$options['face_shave'].' ;';
            }

            if($options['mouse_lift'] !=$oldconfig['mouse_lift']){
                $action.=$Think.\lang('BEAUTY_MOUTH_SHAPE').$oldconfig['mouse_lift'].$Think.\lang('CHANGE_TO').$options['mouse_lift'].' ;';
            }

            if($options['nose_lift'] !=$oldconfig['nose_lift']){
                $action.=$Think.\lang('BEAUTY_THIN_NOSE').$oldconfig['nose_lift'].$Think.\lang('CHANGE_TO').$options['nose_lift'].' ;';
            }

            if($options['chin_lift'] !=$oldconfig['chin_lift']){
                $action.=$Think.\lang('BEAUTY_CHIN_BY').$oldconfig['chin_lift'].$Think.\lang('CHANGE_TO').$options['chin_lift'].' ;';
            }

            if($options['forehead_lift'] !=$oldconfig['forehead_lift']){
                $action.=$Think.\lang('BEAUTY_FOREHEAD').$oldconfig['forehead_lift'].$Think.\lang('CHANGE_TO').$options['forehead_lift'].' ;';
            }

            if($options['lengthen_noseLift'] !=$oldconfig['lengthen_noseLift']){
                $action.=$Think.\lang('BEAUTY_LONG_NOSE').$oldconfig['lengthen_noseLift'].$Think.\lang('CHANGE_TO').$options['lengthen_noseLift'].' ;';
            }

            if($options['login_alert_title'] !=$oldconfig['login_alert_title']){
                $action.=$Think.\lang('PKP_UP_TITLE').$oldconfig['login_alert_title'].$Think.\lang('CHANGE_TO').$options['login_alert_title'].' ;';
            }

            if($options['login_alert_content'] !=$oldconfig['login_alert_content']){
                $action.=$Think.\lang('POP_UP_CONTENT');
            }

            if($options['login_clause_title'] !=$oldconfig['login_clause_title']){
                $action.=$Think.\lang('AGREEMENT_TITLE').$oldconfig['login_clause_title'].$Think.\lang('CHANGE_TO').$options['login_clause_title'].' ;';
            }

            if($options['login_private_title'] !=$oldconfig['login_private_title']){
                $action.=$Think.\lang('PRIVACY_POLICY_NAME').$oldconfig['login_private_title'].$Think.\lang('CHANGE_TO').$options['login_private_title'].' ;';
            }

            if($options['login_private_url'] !=$oldconfig['login_private_url']){
                $action.=$Think.\lang('PRIVACY_POLICY_LINK').$oldconfig['login_private_url'].$Think.\lang('CHANGE_TO').$options['login_private_url'].' ;';
            }

            if($options['login_service_title'] !=$oldconfig['login_service_title']){
                $action.=$Think.\lang('SERVICE_AGREEMENT_NAME').$oldconfig['login_service_title'].$Think.\lang('CHANGE_TO').$options['login_service_title'].' ;';
            }

            if($options['login_service_url'] !=$oldconfig['login_service_url']){
                $action.=$Think.\lang('SERVICE_AGREEMENT_LINK').$oldconfig['login_service_url'].$Think.\lang('CHANGE_TO').$options['login_service_url'].' ;';
            }
            

            if($action!=$Think.\lang('MODIFY_SITE_CONFIGURATION')){
                setAdminLog($action);
            }
			

            $cmfSettings = $this->request->param('cmf_settings/a');

            $bannedUsernames                 = preg_replace("/[^0-9A-Za-z_\\x{4e00}-\\x{9fa5}-]/u", ",", $cmfSettings['banned_usernames']);
            $cmfSettings['banned_usernames'] = $bannedUsernames;
            cmf_set_option('cmf_settings', $cmfSettings);

            $cdnSettings = $this->request->param('cdn_settings/a');
            cmf_set_option('cdn_settings', $cdnSettings);

            $adminSettings = $this->request->param('admin_settings/a');

            $routeModel = new RouteModel();
            if (!empty($adminSettings['admin_password'])) {
                $routeModel->setRoute($adminSettings['admin_password'] . '$', 'admin/Index/index', [], 2, 5000);
            } else {
                $routeModel->deleteRoute('admin/Index/index', []);
            }

            $routeModel->getRoutes(true);

            if (!empty($adminSettings['admin_theme'])) {
                $result = cmf_set_dynamic_config([
                    'template' => [
                        'cmf_admin_default_theme' => $adminSettings['admin_theme']
                    ]
                ]);

                if ($result === false) {
                    $this->error($Think.\lang('CONFIGURATION_WRITE_FAILED'));
                }
            }

            cmf_set_option('admin_settings', $adminSettings);

            $config= Db::name("option")
                    ->where("option_name='site_info'")
                    ->value("option_value");
            $config=json_decode($config,true);
            setcaches("getConfigPub",$config);
			
			$this->resetcache('getConfigPub',$options);
            $this->success($Think.\lang('EDIT_SUCCESS'), '');

        }
    }

    /**
     * 密码修改
     * @adminMenu(
     *     'name'   => '密码修改',
     *     'parent' => 'default',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '密码修改',
     *     'param'  => ''
     * )
     */
    public function password()
    {
        return $this->fetch();
    }

    /**
     * 密码修改提交
     * @adminMenu(
     *     'name'   => '密码修改提交',
     *     'parent' => 'password',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '密码修改提交',
     *     'param'  => ''
     * )
     */
    public function passwordPost()
    {
        if ($this->request->isPost()) {

            $data = $this->request->param();
            if (empty($data['old_password'])) {
                $this->error($Think.\lang('ORIGINAL_PASSWORD_NOT_EMPTY'));
            }
            if (empty($data['password'])) {
                $this->error($Think.\lang('NEW_PASSWORD_NOT_EMPTY'));
            }

            $userId = cmf_get_current_admin_id();

            $admin = Db::name('user')->where("id", $userId)->find();

            $oldPassword = $data['old_password'];
            $password    = $data['password'];
            $rePassword  = $data['re_password'];

            if (cmf_compare_password($oldPassword, $admin['user_pass'])) {
                if ($password == $rePassword) {

                    if (cmf_compare_password($password, $admin['user_pass'])) {
                        $this->error($Think.\lang('NEW_AND_OLD_NOT_SAME'));
                    } else {
                        Db::name('user')->where('id', $userId)->update(['user_pass' => cmf_password($password)]);
                        $this->success($Think.\lang('PASSWORD_MODIFIED_SUCCESSFULLY'));
                    }
                } else {
                    $this->error($Think.\lang('INCONSISTENT_PASSWORD_INPUT'));
                }

            } else {
                $this->error($Think.\lang('OLD_PASSWORD_IS_INCORRECT'));
            }
        }
    }

    /**
     * 上传限制设置界面
     * @adminMenu(
     *     'name'   => '上传设置',
     *     'parent' => 'default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '上传设置',
     *     'param'  => ''
     * )
     */
    public function upload()
    {
        $uploadSetting = cmf_get_upload_setting();
        $this->assign('upload_setting', $uploadSetting);
        return $this->fetch();
    }

    /**
     * 上传限制设置界面提交
     * @adminMenu(
     *     'name'   => '上传设置提交',
     *     'parent' => 'upload',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '上传设置提交',
     *     'param'  => ''
     * )
     */
    public function uploadPost()
    {
        if ($this->request->isPost()) {
            //TODO 非空验证
            $uploadSetting = $this->request->post();

            cmf_set_option('upload_setting', $uploadSetting);
            $this->success($Think.\lang('EDIT_SUCCESS'));
        }

    }

    /**
     * 清除缓存
     * @adminMenu(
     *     'name'   => '清除缓存',
     *     'parent' => 'default',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '清除缓存',
     *     'param'  => ''
     * )
     */
    public function clearCache()
    {
        $content = hook_one('admin_setting_clear_cache_view');

        if (!empty($content)) {
            return $content;
        }

        cmf_clear_cache();
        return $this->fetch();
    }
	
	
	
	
	 /**
     * 私密设置
     */
    public function configpri(){

        $siteinfo=cmf_get_option('site_info');
        $name_coin=$siteinfo['name_coin'];
        $name_votes=$siteinfo['name_votes'];
        $this->assign('config', cmf_get_option('configpri'));
        $this->assign("name_coin",$name_coin);
        $this->assign("name_votes",$name_votes);
        return $this->fetch();
    }

    /**
     * 私密设置提交
     */
    public function configpriPost(){
        
        if ($this->request->isPost()) {

            
            
            $options = $this->request->param('options');

            $oldconfigpri=cmf_get_option('configpri');
            
            $login_type=$_POST['login_type'];
            $share_type=$_POST['share_type'];
            
            $options['login_type']='';
            $options['share_type']='';
            
            if($login_type){
                $options['login_type']=implode(',',$login_type);
            }
            
            if($share_type){
                $options['share_type']=implode(',',$share_type);
            }


            $cash_rate=$options['cash_rate'];
            if(!is_numeric($cash_rate)){
                $this->error($Think.\lang('WITHDRAWAL_PPROPORTION_MUST_NUM'));
            }

            if($cash_rate<0){
                $this->error($Think.\lang('WITHDRAWAL_PPROPORTION_NOT_NEGATIVE'));
            }

            if(floor($cash_rate)!=$cash_rate){
                $this->error($Think.\lang('WITHDRAWAL_PPROPORTION_NOT_DECIMAL'));
            }

            $cash_take=$options['cash_take'];
            if(!is_numeric($cash_take)){
                $this->error($Think.\lang('WITHDRAWAL_AMOUT_NUST_NUM'));
            }

            if($cash_take<0){
                $this->error($Think.\lang('WITHDRAWAL_AMOUT_NOT_NEGATIVE'));
            }

            if(floor($cash_take)!=$cash_take){
                $this->error($Think.\lang('WITHDRAWAL_AMOUT_NOT_DECIMAL'));
            }

            $cash_min=$options['cash_min'];
            if(!is_numeric($cash_min)){
                $this->error($Think.\lang('WITHDRAWAL_CONFIGURATION_MUST_NUM'));
            }
            if($cash_min<0){
                $this->error($Think.\lang('WITHDRAWAL_CONFIGURATION_NOT_NEGATIVE'));
            }
            $cash_start=$options['cash_start'];
            $cash_end=$options['cash_end'];

            if(!is_numeric($cash_start)){
                $this->error($Think.\lang('WITHDRAWAL_DATE_MUST_NUM'));
            }
            if($cash_start<1){
                $this->error($Think.\lang('WITHDRAWAL_DATE_MUST_GREATER_ZERO'));
            }
            if(floor($cash_start)!=$cash_start){
                $this->error($Think.\lang('WITHDRAWAL_START_DATE_GREATER_ZERO'));
            }

            if(!is_numeric($cash_end)){
                $this->error($Think.\lang('WITHDRAWAL_END_DATE_MUST_NUM'));
            }
            if($cash_end<1){
                $this->error($Think.\lang('WITHDRAWAL_END_DATE_GREATER_ZERO'));
            }
            if(floor($cash_end)!=$cash_end){
                $this->error($Think.\lang('THE_END_DATE_MONTHLY_GREATER_ZERO'));
            }
            if($cash_end<$cash_start){
                $this->error($Think.\lang('THE_END_GREATER_START'));
            }

            $cash_max_times=$options['cash_max_times'];
            if(!is_numeric($cash_max_times)){
                $this->error($Think.\lang('WITHDRAWAL_TIME_MUST_NUM'));
            }
            if($cash_max_times<0){
                $this->error($Think.\lang('WITHDRAWAL_TIMES_GREATE_ZERO'));
            }
            if(floor($cash_max_times)!=$cash_max_times){
                $this->error($Think.\lang('WITHDRAWAL_TIMES_GREATE_ZERO'));
            }

            cmf_set_option('configpri', $options);

            $config= Db::name("option")
                    ->where("option_name='configpri'")
                    ->value("option_value");
            $config=json_decode($config,true);
            setcaches("getConfigPri",$config);

            $this->resetcache('getConfigPri',$options);

            $action=$Think.\lang('MODIFY_PRIVACY_CONFIG');

            if($options['cache_switch'] !=$oldconfigpri['cache_switch']){
                $cache_switch=$options['cache_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_CACHE_SWITCH').$cache_switch.';';
            }

            if($options['cache_time'] !=$oldconfigpri['cache_time']){
                $action.=$Think.\lang('CACHE_TIME_BY').$oldconfigpri['cache_time'].$Think.\lang('CHANGE_TO').$options['cache_time'].' ;';
            }

            if($options['auth_islimit'] !=$oldconfigpri['auth_islimit']){
                $auth_islimit=$options['auth_islimit']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_AUTH_LIMIT').$auth_islimit.';';
            }

            if($options['private_letter_switch'] !=$oldconfigpri['private_letter_switch']){
                $private_letter_switch=$options['private_letter_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_NOT_ATTENTION_SEND_MSG_SWITCH').$private_letter_switch.';';
            }

            if($options['private_letter_nums'] !=$oldconfigpri['private_letter_nums']){
                $action.=$Think.\lang('NUM_OF_MESSAGES_SENT_ATTENTION').$oldconfigpri['private_letter_nums'].$Think.\lang('CHANGE_TO').$options['private_letter_nums'].' ;';
            }
            if($options['video_audit_switch'] !=$oldconfigpri['video_audit_switch']){
                $video_audit_switch=$options['video_audit_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_VEDIO_CHECK_SWITCH').$video_audit_switch.';';
            }

            if($options['login_wx_appid'] !=$oldconfigpri['login_wx_appid']){
                $action.=$Think.\lang('WECHAT_ADDID_PROVIDED_BY').$oldconfigpri['login_wx_appid'].$Think.\lang('CHANGE_TO').$options['login_wx_appid'].' ;';
            }

            if($options['login_wx_appsecret'] !=$oldconfigpri['login_wx_appsecret']){
                $action.=$Think.\lang('WECHAT_PLATFOR_APPSECRET').$oldconfigpri['login_wx_appsecret'].$Think.\lang('CHANGE_TO').$options['login_wx_appsecret'].' ;';
            }

            if($options['sendcode_switch'] !=$oldconfigpri['sendcode_switch']){
                $sendcode_switch=$options['sendcode_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_SHORT_MSG_AUTH_COED_SEITCH').$sendcode_switch.';';
            }

            if($options['code_switch'] !=$oldconfigpri['code_switch']){
                $code_switch=$options['code_switch']==1?'阿里云':'容联云';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_SHORT_MSG_PORT_PLATFORM').$code_switch.';';
            }
            
            if($options['ccp_sid'] !=$oldconfigpri['ccp_sid']){
                $action.=$Think.\lang('RONG_ACCOUNT_SID').$oldconfigpri['ccp_sid'].$Think.\lang('CHANGE_TO').$options['ccp_sid'].' ;';
            }

            if($options['ccp_token'] !=$oldconfigpri['ccp_token']){
                $action.=$Think.\lang('RONG_ACCOUNT_TOKEN').$oldconfigpri['ccp_token'].$Think.\lang('CHANGE_TO').$options['ccp_token'].' ;';
            }

            if($options['ccp_appid'] !=$oldconfigpri['ccp_appid']){
                $action.=$Think.\lang('RONG_APPLICATION_APPID').$oldconfigpri['ccp_appid'].$Think.\lang('CHANGE_TO').$options['ccp_appid'].' ;';
            }

            if($options['ccp_tempid'] !=$oldconfigpri['ccp_tempid']){
                $action.=$Think.\lang('RONG_SMS_TEMPLATE_ID').$oldconfigpri['ccp_tempid'].$Think.\lang('CHANGE_TO').$options['ccp_tempid'].' ;';
            }

            if($options['aly_keyid'] !=$oldconfigpri['aly_keyid']){
                $action.=$Think.\lang('ALICALOUD_ACCESSKEY_ID').$oldconfigpri['aly_keyid'].$Think.\lang('CHANGE_TO').$options['aly_keyid'].' ;';
            }

            if($options['aly_secret'] !=$oldconfigpri['aly_secret']){
                $action.=$Think.\lang('ALIBAB_ACCESSKEY_ID').$oldconfigpri['aly_secret'].$Think.\lang('CHANGE_TO').$options['aly_secret'].' ;';
            }

            if($options['aly_signName'] !=$oldconfigpri['aly_signName']){
                $action.=$Think.\lang('ALIBABA_SMS_SIGNED').$oldconfigpri['aly_signName'].$Think.\lang('CHANGE_TO').$options['aly_signName'].' ;';
            }

            if($options['aly_templateCode'] !=$oldconfigpri['aly_templateCode']){
                $action.=$Think.\lang('ALIBABA_SMS_TEMPLATE_ID').$oldconfigpri['aly_templateCode'].$Think.\lang('CHANGE_TO').$options['aly_templateCode'].' ;';
            }
            

            if($options['iplimit_switch'] !=$oldconfigpri['iplimit_switch']){
                $iplimit_switch=$options['iplimit_switch']?'开':'关';
                $action.=$Think.\lang('SMS_VERIFICATION_CODE').$iplimit_switch.';';
            }
            
            if($options['iplimit_times'] !=$oldconfigpri['iplimit_times']){
                $action.=$Think.\lang('SMS_IP_TIMES').$oldconfigpri['iplimit_times'].$Think.\lang('CHANGE_TO').$options['iplimit_times'].' ;';
            }
            
            if($options['same_device_ip_regnums'] !=$oldconfigpri['same_device_ip_regnums']){
                $action.=$Think.\lang('NUM_OF_USER_SAME_IP').$oldconfigpri['same_device_ip_regnums'].$Think.\lang('CHANGE_TO').$options['same_device_ip_regnums'].' ;';
            }
            
            if($options['jpush_switch'] !=$oldconfigpri['jpush_switch']){
                $jpush_switch=$options['jpush_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_AURORA_SEND_SWITCH').$jpush_switch.';';
            }
            
            if($options['jpush_sandbox'] !=$oldconfigpri['jpush_sandbox']){
                $jpush_sandbox=$options['jpush_sandbox']?'生产':'开发';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_AURORA_SEND_SCHEMA').$jpush_sandbox.';';
            }

            if($options['jpush_key'] !=$oldconfigpri['jpush_key']){
                $action.=$Think.\lang('AURORA_APP_KEY').$oldconfigpri['jpush_key'].$Think.\lang('CHANGE_TO').$options['jpush_key'].' ;';
            }
            
            if($options['jpush_secret'] !=$oldconfigpri['jpush_secret']){
                $action.=$Think.\lang('AURORA_MASTER_SECRET_BY').$oldconfigpri['jpush_secret'].$Think.\lang('CHANGE_TO').$options['jpush_secret'].' ;';
            }
            
            if($options['video_showtype'] !=$oldconfigpri['video_showtype']){
                $video_showtype=$options['video_showtype']?'按曝光值':'随机';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_RECOMMEND_VEDIO_SHOW_WAY').$video_showtype.';';
            }
            
            if($options['ad_video_switch'] !=$oldconfigpri['ad_video_switch']){
                $ad_video_switch=$options['ad_video_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_ADVERTISING_VEDIO_SWITCH').$ad_video_switch.';';
            }
            
            if($options['ad_video_loop'] !=$oldconfigpri['ad_video_loop']){
                $ad_video_loop=$options['ad_video_loop']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_ADVERTISING_POLLING_SWITCH').$ad_video_loop.';';
            }

            if($options['video_ad_num'] !=$oldconfigpri['video_ad_num']){
                $action.=$Think.\lang('SLIDE_SERVAL_VIDEOS_APPEAR').$oldconfigpri['video_ad_num'].$Think.\lang('CHANGE_TO').$options['video_ad_num'].' ;';
            }
            
            if($options['comment_weight'] !=$oldconfigpri['comment_weight']){
                $action.=$Think.\lang('COMMENT_WEIGHT_VALUE').$oldconfigpri['comment_weight'].$Think.\lang('CHANGE_TO').$options['comment_weight'].' ;';
            }
            
            if($options['like_weight'] !=$oldconfigpri['like_weight']){
                $action.=$Think.\lang('LIKE_WEIGHT_VALUE_DETERMINED').$oldconfigpri['like_weight'].$Think.\lang('CHANGE_TO').$options['like_weight'].' ;';
            }

            if($options['share_weight'] !=$oldconfigpri['share_weight']){
                $action.=$Think.\lang('SHARE_WEIGHT_VALUE').$oldconfigpri['share_weight'].$Think.\lang('CHANGE_TO').$options['share_weight'].' ;';
            }
            
            if($options['show_val'] !=$oldconfigpri['show_val']){
                $action.=$Think.\lang('INITIAL_EXPOSURE_VALUE').$oldconfigpri['show_val'].$Think.\lang('CHANGE_TO').$options['show_val'].' ;';
            }
            
            if($options['hour_minus_val'] !=$oldconfigpri['hour_minus_val']){
                $action.=$Think.\lang('DEDUCT_EXPOSURE_VALUE').$oldconfigpri['hour_minus_val'].$Think.\lang('CHANGE_TO').$options['hour_minus_val'].' ;';
            }

            if($options['um_apikey'] !=$oldconfigpri['um_apikey']){
                $action.=$Think.\lang('YOUMENG_OPENAPI_APIKEY').$oldconfigpri['um_apikey'].$Think.\lang('CHANGE_TO').$options['um_apikey'].' ;';
            }

            if($options['um_apisecurity'] !=$oldconfigpri['um_apisecurity']){
                $action.=$Think.\lang('YOUMENG_OPENAPI_SECURITY').$oldconfigpri['um_apisecurity'].$Think.\lang('CHANGE_TO').$options['um_apisecurity'].' ;';
            }

            if($options['um_appkey_android'] !=$oldconfigpri['um_appkey_android']){
                $action.=$Think.\lang('YOUMENG_ANDROID_APPKEY').$oldconfigpri['um_appkey_android'].$Think.\lang('CHANGE_TO').$options['um_appkey_android'].' ;';
            }

            if($options['um_appkey_ios'] !=$oldconfigpri['um_appkey_ios']){
                $action.=$Think.\lang('YOUMENG_IOS_APPLICATION_APPKEY').$oldconfigpri['um_appkey_ios'].$Think.\lang('CHANGE_TO').$options['um_appkey_ios'].' ;';
            }
            
            if($options['shop_fans'] !=$oldconfigpri['shop_fans']){
                $action.=$Think.\lang('NUM_OF_FANS_FOR_APPLICATION_SHOP').$oldconfigpri['shop_fans'].$Think.\lang('CHANGE_TO').$options['shop_fans'].' ;';
            }
            
            if($options['shop_videos'] !=$oldconfigpri['shop_videos']){
                $action.=$Think.\lang('NUM_OF_VIDEOS_FOR_APPLICATION_SHOP').$oldconfigpri['shop_videos'].$Think.\lang('CHANGE_TO').$options['shop_videos'].' ;';
            }

            if($options['show_switch'] !=$oldconfigpri['show_switch']){
                $show_switch=$options['show_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_SHOP_CHECK_SHOP').$show_switch.';';
            }

            if($options['agent_switch'] !=$oldconfigpri['agent_switch']){
                $agent_switch=$options['agent_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_INVITE_SWITCH').$agent_switch.';';
            }

            if($options['agent_must'] !=$oldconfigpri['agent_must']){
                $agent_must=$options['agent_must']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_INVITE_COED_NEED').$agent_must.';';
            }
            
            if($options['agent_reward'] !=$oldconfigpri['agent_reward']){
                $action.=$Think.\lang('REWARDED_BY_NEW_PERSON').$oldconfigpri['agent_reward'].$Think.\lang('CHANGE_TO').$options['agent_reward'].' ;';
            }

            if($options['agent_v_l'] !=$oldconfigpri['agent_v_l']){
                $action.=$Think.\lang('LENGTH_OF_VIDEO').$oldconfigpri['agent_v_l'].$Think.\lang('CHANGE_TO').$options['agent_v_l'].' ;';
            }

            if($options['agent_v_a'] !=$oldconfigpri['agent_v_a']){
                $action.=$Think.\lang('VIDEO_TIME_EVERY_DAY').$oldconfigpri['agent_v_a'].$Think.\lang('CHANGE_TO').$options['agent_v_a'].' ;';
            }

            if($options['agent_a'] !=$oldconfigpri['agent_a']){
                $action.=$Think.\lang('SUPERIOR_USER_DETERMINED').$oldconfigpri['agent_a'].$Think.\lang('CHANGE_TO').$options['agent_a'].' ;';
            }

            if($options['aliapp_partner'] !=$oldconfigpri['aliapp_partner']){
                $action.=$Think.\lang('APLIPAY_PARNER_ID_IS').$oldconfigpri['aliapp_partner'].$Think.\lang('CHANGE_TO').$options['aliapp_partner'].' ;';
            }

            if($options['aliapp_seller_id'] !=$oldconfigpri['aliapp_seller_id']){
                $action.=$Think.\lang('APLIPAY_ACCOUNT_NUM_IS').$oldconfigpri['aliapp_seller_id'].$Think.\lang('CHANGE_TO').$options['aliapp_seller_id'].' ;';
            }
            
            if($options['aliapp_key'] !=$oldconfigpri['aliapp_key']){
                $action.=$Think.\lang('PAY_FOR_ANDROID_KEY_MODIFICATION');
            }
            
            if($options['aliapp_key_ios'] !=$oldconfigpri['aliapp_key_ios']){
                $action.=$Think.\lang('APLIPAY_IOS_KEY_MODIFICATION');
            }

            if($options['wx_appid'] !=$oldconfigpri['wx_appid']){
                 $action.=$Think.\lang('WECHAT_OPEN_MOBILE_APPID').$oldconfigpri['wx_appid'].$Think.\lang('CHANGE_TO').$options['wx_appid'].' ;';
            }
            
            if($options['wx_appsecret'] !=$oldconfigpri['wx_appsecret']){
                 $action.=$Think.\lang('WECHAT_OPEN_APP_SECRET').$oldconfigpri['wx_appsecret'].$Think.\lang('CHANGE_TO').$options['wx_appsecret'].' ;';
            }

            if($options['wx_mchid'] !=$oldconfigpri['wx_mchid']){
                 $action.=$Think.\lang('WECHAT_MERCHANT_ID_MCHID').$oldconfigpri['wx_mchid'].$Think.\lang('CHANGE_TO').$options['wx_mchid'].' ;';
            }

            if($options['wx_key'] !=$oldconfigpri['wx_key']){
                 $action.=$Think.\lang('WECAHT_KEY').$oldconfigpri['wx_key'].$Think.\lang('CHANGE_TO').$options['wx_key'].' ;';
            }

            if($options['aliapp_switch'] !=$oldconfigpri['aliapp_switch']){
                $aliapp_switch=$options['aliapp_switch']?'开':'关';
                $action.=$Think.\lang('APLIPAY_PAYMENT_SUITCH').$aliapp_switch.';';
            }

            if($options['ios_switch'] !=$oldconfigpri['ios_switch']){
                $ios_switch=$options['ios_switch']?'开':'关';
                $action.=$Think.\lang('APPLE_PAYMENT_SWITCH').$ios_switch.';';
            }
            
            if($options['ios_switch'] !=$oldconfigpri['ios_switch']){
                $ios_switch=$options['ios_switch']?'开':'关';
                $action.=$Think.\lang('APPLE_PAYMENT_SWITCH').$ios_switch.';';
            }

            if($options['wx_switch'] !=$oldconfigpri['wx_switch']){
                $wx_switch=$options['wx_switch']?'开':'关';
                $action.=$Think.\lang('WECHAT_PAYMENT_SWITCH').$wx_switch.';';
            }

            if($options['vip_aliapp_switch'] !=$oldconfigpri['vip_aliapp_switch']){
                $vip_aliapp_switch=$options['vip_aliapp_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_VIP_ZFB_PAY_SWITCH').$vip_aliapp_switch.';';
            }

            if($options['vip_wx_switch'] !=$oldconfigpri['vip_wx_switch']){
                $vip_wx_switch=$options['vip_wx_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_VIP_WECHAT_PAY_SWITCH').$vip_wx_switch.';';
            }

            if($options['vip_coin_switch'] !=$oldconfigpri['vip_coin_switch']){
                $vip_coin_switch=$options['vip_coin_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_YIP_BLANCE_PAY_SWITCH').$vip_coin_switch.';';
            }
            
            if($options['cash_rate'] !=$oldconfigpri['cash_rate']){
                 $action.=$Think.\lang('WITHDRAWAL_PROPORTION_DETERMINED').$oldconfigpri['cash_rate'].$Think.\lang('CHANGE_TO').$options['cash_rate'].' ;';
            }

            if($options['cash_min'] !=$oldconfigpri['cash_min']){
                 $action.=$Think.\lang('MINIMUM_WITHFRAWAL_AMOUNT').$oldconfigpri['cash_min'].$Think.\lang('CHANGE_TO').$options['cash_min'].' ;';
            }
            
            if($options['popular_interval'] !=$oldconfigpri['popular_interval']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_HOT_VEDIO_ADD_GAP').$oldconfigpri['popular_interval'].$Think.\lang('CHANGE_TO').$options['popular_interval'].' ;';
            }
            
            if($options['popular_base'] !=$oldconfigpri['popular_base']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_HOT_BASE_NUMBER').$oldconfigpri['popular_base'].$Think.\lang('CHANGE_TO').$options['popular_base'].' ;';
            }

            if($options['popular_tips'] !=$oldconfigpri['popular_tips']){
                $action.=$Think.\lang('TOP_TIPS_ON');
            }
            
            if($options['vip_switch'] !=$oldconfigpri['vip_switch']){
                $vip_switch=$options['vip_switch']?'开':'关';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_VIP_SWITCH').$vip_switch.';';
            }

            if($options['nonvip_upload_nums'] !=$oldconfigpri['nonvip_upload_nums']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_NOT_VIP_UPLOAD_VEDIO_NUMBER_EACHDAY').$oldconfigpri['nonvip_upload_nums'].$Think.\lang('CHANGE_TO').$options['nonvip_upload_nums'].' ;';
            }
            
            if($options['watch_video_type'] !=$oldconfigpri['watch_video_type']){
                $vip_switch=$options['watch_video_type']?'内容限制模式':'次数限制模式';
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_SHORT_VEDIO_WATCH_MODEL').$vip_switch.';';
            }

            if($options['nonvip_watch_nums'] !=$oldconfigpri['nonvip_watch_nums']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_FREE_WATCH_VEDIO_NUMBER').$oldconfigpri['nonvip_watch_nums'].$Think.\lang('CHANGE_TO').$options['nonvip_watch_nums'].' ;';
            }
            
            if($options['cloudtype'] !=$oldconfigpri['cloudtype']){
                $cloudtype=$options['cloudtype']==1?'七牛云存储':'腾讯云存储';
                $action.=$Think.\lang('CLOUD_STORAGE').$cloudtype.';';
            }
            
            if($options['qiniu_zone'] !=$oldconfigpri['qiniu_zone']){
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_QI_NIU_CLOUD_SAVE_AREA');
            }

            if($options['qiniu_protocol'] !=$oldconfigpri['qiniu_protocol']){
                $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_QI_NIU_SAVE_DN');
            }
            
            if($options['qiniu_accesskey'] !=$oldconfigpri['qiniu_accesskey']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_QI_NIU_CLOUD_SAVE_ACCESSKEY').$oldconfigpri['qiniu_accesskey'].$Think.\lang('CHANGE_TO').$options['qiniu_accesskey'].' ;';
            }

            if($options['qiniu_secretkey'] !=$oldconfigpri['qiniu_secretkey']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_QI_NIU_CLOUD_SAVE_SECRETKEY').$oldconfigpri['qiniu_secretkey'].$Think.\lang('CHANGE_TO').$options['qiniu_secretkey'].' ;';
            }

            if($options['qiniu_bucket'] !=$oldconfigpri['qiniu_bucket']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_QI_NIU_CLOUD_SAVE_BUCKET').$oldconfigpri['qiniu_bucket'].$Think.\lang('CHANGE_TO').$options['qiniu_bucket'].' ;';
            }

            if($options['qiniu_domain'] !=$oldconfigpri['qiniu_domain']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_QI_NIU_CLOUD_SAVE_SPACE_DN').$oldconfigpri['qiniu_domain'].$Think.\lang('CHANGE_TO').$options['qiniu_domain'].' ;';
            }
            
            if($options['tx_private_signature'] !=$oldconfigpri['tx_private_signature']){
                $tx_private_signature=$options['tx_private_signature']?'开':'关';
                $action.=$Think.\lang('TENCENT_SIGNATURE_VERIFICATION').$tx_private_signature.';';
            }

            if($options['txcloud_appid'] !=$oldconfigpri['txcloud_appid']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_TENGXUN_YUN_SAVE_APPID').$oldconfigpri['txcloud_appid'].$Think.\lang('CHANGE_TO').$options['txcloud_appid'].' ;';
            }

            if($options['txcloud_secret_id'] !=$oldconfigpri['txcloud_secret_id']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_TENGXUN_YUN_SAVE_SECRET_ID').$oldconfigpri['txcloud_secret_id'].$Think.\lang('CHANGE_TO').$options['txcloud_secret_id'].' ;';
            }

            if($options['txcloud_secret_key'] !=$oldconfigpri['txcloud_secret_key']){
                 $action.=$Think.\lang('TENCENT_SECRET_KEY').$oldconfigpri['txcloud_secret_key'].$Think.\lang('CHANGE_TO').$options['txcloud_secret_key'].' ;';
            }

            if($options['txcloud_region'] !=$oldconfigpri['txcloud_region']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_TENGXUN_YUN_SAVE_REGION').$oldconfigpri['txcloud_region'].$Think.\lang('CHANGE_TO').$options['txcloud_region'].' ;';
            }

            if($options['txcloud_bucket'] !=$oldconfigpri['txcloud_bucket']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_TENGXUN_YUN_SAVE_BUCKET').$oldconfigpri['txcloud_bucket'].$Think.\lang('CHANGE_TO').$options['txcloud_bucket'].' ;';
            }
            
            if($options['tx_domain_url'] !=$oldconfigpri['tx_domain_url']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_TENGXUN_YUN_SAVE_SPACE_URL').$oldconfigpri['tx_domain_url'].$Think.\lang('CHANGE_TO').$options['tx_domain_url'].' ;';
            }

            if($options['live_videos'] !=$oldconfigpri['live_videos']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_DRIECT_NEED_UP_VEDIO_NUMBER').$oldconfigpri['live_videos'].$Think.\lang('CHANGE_TO').$options['live_videos'].' ;';
            }

            if($options['live_fans'] !=$oldconfigpri['live_fans']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_DRIECT_NEED_FANS_NUMBER').$oldconfigpri['live_fans'].$Think.\lang('CHANGE_TO').$options['live_fans'].' ;';
            }

            if($options['tx_appid'] !=$oldconfigpri['tx_appid']){
                 $action.=$Think.\lang('TENCENT_PUSH_PULL_APPID').$oldconfigpri['tx_appid'].$Think.\lang('CHANGE_TO').$options['tx_appid'].' ;';
            }

            if($options['tx_bizid'] !=$oldconfigpri['tx_bizid']){
                 $action.=$Think.\lang('TENCENT_PUSH_PULL_BIZID').$oldconfigpri['tx_bizid'].$Think.\lang('CHANGE_TO').$options['tx_bizid'].' ;';
            }

            if($options['tx_push_key'] !=$oldconfigpri['tx_push_key']){
                 $action.=$Think.\lang('TENCENT_PUSH_PULL_CHAIN_KEY').$oldconfigpri['tx_push_key'].$Think.\lang('CHANGE_TO').$options['tx_push_key'].' ;';
            }
            
            if($options['tx_api_key'] !=$oldconfigpri['tx_api_key']){
                 $action.=$Think.\lang('TENCENT_PUSH_PULL_API_AUTHENTICATION_KEY').$oldconfigpri['tx_api_key'].$Think.\lang('CHANGE_TO').$options['tx_api_key'].' ;';
            }

            if($options['tx_push'] !=$oldconfigpri['tx_push']){
                 $action.=$Think.\lang('TENCENT_STREAMING_DOMAIN_NAME').$oldconfigpri['tx_push'].$Think.\lang('CHANGE_TO').$options['tx_push'].' ;';
            }

            if($options['tx_pull'] !=$oldconfigpri['tx_pull']){
                 $action.=$Think.\lang('TENCENT_PUSH_PULL_DOMAIN_NAME').$oldconfigpri['tx_pull'].$Think.\lang('CHANGE_TO').$options['tx_pull'].' ;';
            }

            if($options['live_txcloud_secret_id'] !=$oldconfigpri['live_txcloud_secret_id']){
                 $action.=$Think.\lang('TENCENT_PUSH_PULL_SECRETID').$oldconfigpri['live_txcloud_secret_id'].$Think.\lang('CHANGE_TO').$options['live_txcloud_secret_id'].' ;';
            }

            if($options['live_txcloud_secret_key'] !=$oldconfigpri['live_txcloud_secret_key']){
                 $action.=$Think.\lang('TENCENT_PUSH_PULL_SECRETKEY').$oldconfigpri['live_txcloud_secret_key'].$Think.\lang('CHANGE_TO').$options['live_txcloud_secret_key'].' ;';
            }

            if($options['userlist_time'] !=$oldconfigpri['userlist_time']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_S_DRIECT_ROOM_USER_LIST_REFRESH_GAP').$oldconfigpri['userlist_time'].$Think.\lang('CHANGE_TO').$options['userlist_time'].' ;';
            }

            if($options['chatserver'] !=$oldconfigpri['chatserver']){
                 $action.=$Think.\lang('CHAT_SERVER_ADDRESS').$oldconfigpri['chatserver'].$Think.\lang('CHANGE_TO').$options['chatserver'].' ;';
            }
            
            if($options['sensitive_words'] !=$oldconfigpri['sensitive_words']){
                 $action.=$Think.\lang('ADMIN_SETTING_CONFIGPRI_SENSITIVE_WORD');
            }
            

            if($action!=$Think.\lang('MODIFY_PRIVACY_CONFIG')){
                setAdminLog($action);
            }
            
            $this->success($Think.\lang('EDIT_SUCCESS'), '');

        }
    }

    protected function resetcache($key='',$info=[]){
        if($key!='' && $info){
            delcache($key);
            hMSet($key,$info);
        }
    }

    public function setlang()
    {
        $lang = $this->request->param('lang');
        $langConfig = [
            1 => 'en-us',
            2 => 'zh-cn',
        ];
        $think_var = isset($langConfig[$lang]) ? $langConfig[$lang] : 'zh-cn';

        //cookie
        setcookie('think_var', $think_var, 3600 * 24 * 365 * 20);

        echo $think_var;
    }
}