<?php

namespace app\admin\Controller;

use think\Controller;

class Lock extends Controller
{
    public function _initialize()
    {
        if (session("blog_admin_name") && session("blog_admin_id")) {

        } else {
            $this->error("请登录", "login/index");
        }
    }
}