<?php

namespace app\admin\controller;

use support\Db;
use support\Request;
use support\Redis;
use Webman\Captcha\CaptchaBuilder;
use Webman\Captcha\PhraseBuilder;

class UsersController
{

    public function login(Request $request)
    {
        $json = $request->rawBody();
        $post = json_decode($json, true);
        var_export($post);
        $uniqueId = $request->cookie('unique_id');
        $code = Redis::get($uniqueId);
        if ($code !== strtolower($post['captcha'])) {
            return json(['code' => 1, 'message' => '验证码错误']);
        }
        $user = Db::table('users')->where('username', $post['username'])->first();
        if (!$user || $user['password'] !== md5($post['password'])) {
            return json(['code' => 1, 'message' => '用户名或密码错误']);
        }
        // 验证码正确，执行登录操作
        return json(['code' => 0, 'message' => '登录成功', 'data' => $user]);
    }

    public function info(Request $request)
    {
        $user = $request->user();
        return json(['code' => 0, 'message' => '获取用户信息成功', 'data' => $user]);
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
        $unique_id = bin2hex(pack('d', microtime(true)) . pack('N', mt_rand()));
        $response->cookie('unique_id', $unique_id, 120, '', '', true, false, 'None');
        // 将验证码的值存储到redis中
        Redis::setex($unique_id, 120, strtolower($builder->getPhrase()));
        // 获得验证码图片二进制数据
        $img_content = $builder->inline();
        $response->header('Content-Type', 'application/json');
        // 输出验证码二进制数据
        $response->withBody(json_encode(['code' => 0, 'msg' => '验证码', 'data' => $img_content], JSON_UNESCAPED_UNICODE));
        return $response;
    }
}