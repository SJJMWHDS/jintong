<?php

namespace app\admin\Controller;

use think\Controller;
use think\Db;

class Ping extends Lock
{
    public function index()
    {
        $search = input("get.search");
        $tot = Db::table("pinglun")->field("pinglun.*,users.username,news.title,news.img")->join("users", "users.id=uid")->join("news", "news.id=nid")->where("users.username like '%$search%'")->whereOr("news.title like '%$search%'")->whereOr("pinglun.text like '%$search%'")->count();
        $data = Db::table("pinglun")->field("pinglun.*,users.username,news.title,news.img")->join("users", "users.id=uid")->join("news", "news.id=nid")->where("users.username like '%$search%'")->whereOr("news.title like '%$search%'")->whereOr("pinglun.text like '%$search%'")->order("pinglun.id")->paginate(3, false, ["query" => request()->param()]);
        $this->assign("tot", $tot);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_toggle()
    {
        $arr = input("get.");
        $res = Db::table("pinglun")->update($arr);
        if ($res) {
            $data = [
                "code" => 200,
                "info" => "状态修改成功"
            ];
        } else {
            $data = [
                "code" => 400,
                "info" => "状态修改失败"
            ];
        }
        return $data;
    }
}