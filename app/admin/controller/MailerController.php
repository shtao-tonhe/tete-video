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
use think\Validate;

class MailerController extends AdminBaseController
{

    /**
     * 邮箱配置
     * @adminMenu(
     *     'name'   => '邮箱配置',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10,
     *     'icon'   => '',
     *     'remark' => '邮箱配置',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $emailSetting = cmf_get_option('smtp_setting');
        $this->assign($emailSetting);
        return $this->fetch();
    }

    /**
     * 邮箱配置
     * @adminMenu(
     *     'name'   => '邮箱配置提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '邮箱配置提交保存',
     *     'param'  => ''
     * )
     */
    public function indexPost()
    {
        $post = array_map('trim', $this->request->param());

        if (in_array('', $post) && !empty($post['smtpsecure'])) {
            $this->error($Think.\lang('CANNOT_LEAVE_VLANK'));
        }

        cmf_set_option('smtp_setting', $post);

        $this->success($Think.\lang('EDIT_SUCCESS'));
    }

    /**
     * 邮件模板
     * @adminMenu(
     *     'name'   => '邮件模板',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '邮件模板',
     *     'param'  => ''
     * )
     */
    public function template()
    {
        $allowedTemplateKeys = ['verification_code'];
        $templateKey         = $this->request->param('template_key');

        if (empty($templateKey) || !in_array($templateKey, $allowedTemplateKeys)) {
            $this->error($Think.\lang('ILLEGAL_REQUEST'));
        }

        $template = cmf_get_option('email_template_' . $templateKey);
        $this->assign($template);
        return $this->fetch('template_verification_code');
    }

    /**
     * 邮件模板提交
     * @adminMenu(
     *     'name'   => '邮件模板提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '邮件模板提交',
     *     'param'  => ''
     * )
     */
    public function templatePost()
    {
        $allowedTemplateKeys = ['verification_code'];
        $templateKey         = $this->request->param('template_key');

        if (empty($templateKey) || !in_array($templateKey, $allowedTemplateKeys)) {
            $this->error($Think.\lang('ILLEGAL_REQUEST'));
        }

        $data = $this->request->param();

        unset($data['template_key']);

        cmf_set_option('email_template_' . $templateKey, $data);

        $this->success($Think.\lang('EDIT_SUCCESS'));
    }

    /**
     * 邮件发送测试
     * @adminMenu(
     *     'name'   => '邮件发送测试',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '邮件发送测试',
     *     'param'  => ''
     * )
     */
    public function test()
    {
        if ($this->request->isPost()) {

            $validate = new Validate([
                'to'      => 'require|email',
                'subject' => 'require',
                'content' => 'require',
            ]);
            $validate->message([
                'to.require'      => $Think.\lang('INBOX_CANNOT_EMPTY'),
                'to.email'        => $Think.\lang('INCORRECT_INVOX_FORMAT'),
                'subject.require' => $Think.\lang('TITLE_CANNOT_EMPTY'),
                'content.require' => $Think.\lang('CONTENT_CANNOT_EMPTY'),
            ]);

            $data = $this->request->param();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            $result = cmf_send_email($data['to'], $data['subject'], $data['content']);
            if ($result && empty($result['error'])) {
                $this->success($Think.\lang('SENT_SUCCESSFULLY'));
            } else {
                $this->error($Think.\lang('FAIL_IN_SEND') . $result['message']);
            }

        } else {
            return $this->fetch();
        }

    }


}

