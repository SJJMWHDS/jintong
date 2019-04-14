<?php

namespace app\admin\Controller;

use think\Controller;
use think\Db;

class Newstype extends Lock
{
    public function index()
    {
        $search = input("get.search");
        $tot = Db::table("newstype")->where("name like '%$search%'")->count();
        $this->assign("tot", $tot);
        $data = Db::table("newstype")->order("id desc")->where("name like '%$search%'")->paginate(5, false, ["query" => request()->param()]);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_add()
    {
        $str = input("post.");
        $res = Db::table("newstype")->insert($str);
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

    public function ajax_delAll()
    {
        $id = $_GET['str'];
        $idarr = explode(",", $id);
        foreach ($idarr as $k => $v) {
            $data = Db::table("news")->where("typeid", "$v")->find();
        }
        if (!$data) {
            $res = Db::table("newstype")->delete($id);
            if ($res) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 2;
        }
    }

    public function ajax_tk()
    {
        $id = $_GET['id'];
        $data = Db::table("newstype")->find($id);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_save()
    {
        $arr = input("post.");
        $res = Db::table("newstype")->update($arr);
        if ($res) {
            echo 1;
        } else {
            echo 0;
        }
    }
}