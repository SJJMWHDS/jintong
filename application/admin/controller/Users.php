<?php

namespace app\admin\Controller;

use think\Controller;
use think\Db;

class Users extends Lock
{
    public function index()
    {
        $search = input("get.search");
        $tot = Db::table("users")->where("username like '%$search%'")->count();
        $this->assign("tot", $tot);
        $data = Db::table("users")->order("id desc")->where("username like '%$search%'")->whereOr("phone like '%$search%'")->whereOr("email like '%$search%'")->whereOr("status like '%$search%'")->paginate(5, false, ["query" => request()->param()]);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_toggle()
    {
        $status = input("get.status");
        $id = input("get.id");
        $arr = [
            "status" => $status,
            "id" => $id
        ];
        $res = Db::table("users")->update($arr);
        if ($res) {
            echo json_encode($arr);
        } else {
            echo 0;
        }
    }
}