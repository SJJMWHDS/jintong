<?php

namespace app\admin\Controller;

use think\Controller;
use think\Db;

class Nav extends Lock
{
    public function index()
    {
        $search = input("get.search");
        $tot = Db::table("nav")->where("name like '%$search%'")->whereOr("keywords like '%$search%'")->whereOr("description like '%$search%'")->count();
        $this->assign("tot", $tot);
        $data = Db::table("nav")->order("sort desc")->where("name like '%$search%'")->whereOr("keywords like '%$search%'")->whereOr("description like '%$search%'")->paginate(3, false, ["query" => request()->param()]);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_add()
    {
        $str = input("post.");
        $res = Db::table("nav")->insert($str);
        if ($res) {
            $data = [
                "code" => "200",
                "info" => "添加成功",
            ];
        } else {
            $data = [
                "code" => "400",
                "info" => "添加失败"
            ];
        }
        return $data;
    }

    public function ajax_sort()
    {
        $arr = [
            "id" => input("get.id"),
            "sort" => input("get.sort")
        ];
        $res = Db::table("nav")->update($arr);
        if ($res) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ajax_delAll()
    {
        $id = $_GET['str'];
        $res = Db::table("nav")->delete($id);
        if ($res) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ajax_tk()
    {
        $id = $_GET['id'];
        $data = Db::table("nav")->find($id);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_save()
    {
        $arr = input("post.");
        $res = Db::table("nav")->update($arr);
        if ($res) {
            echo 1;
        } else {
            echo 0;
        }
    }
}