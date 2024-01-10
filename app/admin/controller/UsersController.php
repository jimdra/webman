<?php

namespace app\admin\controller;
use support\Request;
use Webman\Captcha\CaptchaBuilder;
use Webman\Captcha\PhraseBuilder;

class UsersController
{
    public function code(Request $request)
    {
        // 验证码长度
        $length = 4;
        // 包含哪些字符
        $chars = '0123456789abcefghijklmnopqrstuvwxyz';
        $builder = new PhraseBuilder($length, $chars);
        $captcha = new CaptchaBuilder(null, $builder);
        // 生成验证码
        $builder = $captcha->build(100);
        // 将验证码的值存储到session中
        //$request->session()->set('captcha', strtolower($builder->getPhrase()));
        // 获得验证码图片二进制数据
        $img_content = $builder->inline();
        // 输出验证码二进制数据
        return json(['code' => 0, 'msg' => '验证码', 'data' => $img_content]);
    }
}