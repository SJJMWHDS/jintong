<?php

namespace app\admin\Controller;

use think\Controller;
use think\Db;

class Banner extends Lock
{
    public function index()
    {
        $search = input("get.search");
        $tot = Db::table("banner")->where("name like '%$search%'")->whereOr("img like '%$search%'")->count();
        $this->assign("tot", $tot);
        $data = Db::table("banner")->order("sort desc")->where("name like '%$search%'")->whereOr("img like '%$search%'")->paginate(3, false, ["query" => request()->param()]);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_add()
    {
        $arr = input("post.");
        $file = request()->file('img');
        if ($file) {
            $info = $file->move(ROOT_PATH . "public/upload/banner");
            if ($info) {
                $arr['img'] = $info->getSaveName();
                $res = Db::table("banner")->insert($arr);
                if ($res) {
                    $this->redirect("banner/index");
                } else {
                    $this->redirect("banner/index");
                }
            } else {
                $file->getError();
            }
        }
    }

    public function ajax_sort()
    {
        $arr = [
            "id" => input("get.id"),
            "sort" => input("get.sort")
        ];
        $res = Db::table("banner")->update($arr);
        if ($res) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ajax_delAll()
    {
        $idarr = input("get.idarr");
        $imgarr = input("get.imgarr");
        $img = explode(",", $imgarr);
        $res = Db::table("banner")->delete($idarr);
        if ($res) {
            foreach ($img as $k => $v) {
                unlink("upload/banner/" . $v);
            }
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ajax_tk()
    {
        $id = $_GET['id'];
        $data = Db::table("banner")->find($id);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_save()
    {
        $arr = input("post.");
        $file = request()->file('img');
        if ($file) {
            unlink("upload/banner/" . $arr['oldimg']);
            unset($arr['oldimg']);
            $info = $file->move(ROOT_PATH . "public/upload/banner");
            if ($info) {
                $arr['img'] = $info->getSaveName();
                $res = Db::table("banner")->update($arr);
                if ($res) {
                    $this->redirect("banner/index");
                } else {
                    $this->redirect("banner/index");
                }
            } else {
                $file->getError();
            }
        } else {
            unset($arr['oldimg']);
            $res = Db::table("banner")->update($arr);
            if ($res) {
                $this->redirect("banner/index");
            } else {
                $this->redirect("banner/index");
            }
        }
    }

}