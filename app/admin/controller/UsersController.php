<?php

namespace app\admin\controller;
use support\Request;
use support\Redis;
use Webman\Captcha\CaptchaBuilder;
use Webman\Captcha\PhraseBuilder;

class UsersController
{

    public function login(Request $request)
    {
        $uniqueId = $request->cookie('unique_id');
        $code = Redis::get($uniqueId);
        return json(['code' => 0, 'message' => '登录成功']);
    }
    public function code(Request $request)
    {
        $response = response();
        // 验证码长度
        $length = 4;
        // 包含哪些字符
        $chars = '0123456789abcefghijklmnopqrstuvwxyz';
        $builder = new PhraseBuilder($length, $chars);
        $captcha = new CaptchaBuilder(null, $builder);
        // 生成验证码
        $builder = $captcha->build(100);
        $unique_id = bin2hex(pack('d', microtime(true)).pack('N', mt_rand()));
        $response->cookie('unique_id', $unique_id, 120, '/', '', false, false,true);
        // 将验证码的值存储到redis中
        Redis::setex($unique_id, 120, strtolower($builder->getPhrase()));
        // 获得验证码图片二进制数据
        $img_content = $builder->inline();
        $response->header('Content-Type', 'application/json');
        // 输出验证码二进制数据
        $response->withBody(json_encode(['code' => 0, 'msg' => '验证码', 'data' => $img_content],JSON_UNESCAPED_UNICODE));
        return $response;
    }
}