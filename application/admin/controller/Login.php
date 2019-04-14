<?php

namespace app\admin\Controller;

use think\Controller;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        return view();
    }

    public function check()
    {
        $username = input("post.username");
        $password = input("post.password");
        $code = input("post.code");
        if ($username) {
            if ($password) {
                if (captcha_check($code)) {
                    $data = Db::table("admin")->where("username='$username' and password='$password' and status='1'")->find();
                    if ($data) {
                        session("blog_admin_name", $data['username']);
                        session("blog_admin_id", $data['id']);
                        $arr = [
                            "id" => $data['id'],
                            "logintime" => time()
                        ];
                        Db::table("admin")->update($arr);
                        $this->success("登录成功", "index/index", "", "1");
                    } else {
                        $this->error("登录失败", "login/index", "", "1");
                    }
                } else {
                    $this->error("验证码输入有误", "login/index", "", "1");
                }
            } else {
                $this->error("请输入密码", "login/index", "", "1");
            }
        } else {
            $this->error("请输入用户名", "login/index", "", "1");
        }
    }

    public function logout()
    {
        session(null);
        $this->success("退出成功", "login/index", "", "1");
    }

}