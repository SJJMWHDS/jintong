<?php

namespace app\admin\Controller;

use think\Controller;
use think\Db;

class News extends Lock
{
    public function index()
    {
        $search = input("get.search");
        $this->assign("search", $search);
        $typeid = input("get.type");
        $where = [];
        if ($typeid) {
            $where['typeid'] = $typeid;
        }
        $this->assign("typeid", $typeid);
        $sort = input("get.sort");
        switch ($sort) {
            case "0":
                $str = "id desc";
                break;
            case "1":
                $str = "looknum desc";
                break;
            case "2":
                $str = "zannum desc";
                break;
            case "3":
                $str = "shounum desc";
                break;
            default:
                $str = "id desc";
        }
        $this->assign("sort", $sort);
        $type = Db::table("newstype")->select();
        $this->assign("type", $type);
        $tot = Db::table("news")->where($where)->where("title like '%$search%'")->count();
        $this->assign("tot", $tot);
        $data = Db::table("news")->field("news.*,newstype.name")->join("newstype", "news.typeid=newstype.id")->where($where)->where("title like '%$search%'")->order($str)->paginate(3, false, ["query" => request()->param()]);
        $this->assign("data", $data);
        return view();
    }

    public function add()
    {
        $data = Db::table("newstype")->select();
        $this->assign("data", $data);
        return view();
    }

    public function insert()
    {
        $arr = input("post.");
        $file = request()->file('img');
        if ($file) {
            $info = $file->move(ROOT_PATH . "public/upload/news");
            if ($info) {
                $arr['img'] = $info->getSaveName();
                $arr['text'] = htmlspecialchars(input("post.text"));
                $arr['time'] = time();
                $res = Db::table("news")->insert($arr);
                if ($res) {
                    $this->success("添加成功", "news/index", "", 1);
                } else {
                    $this->error("添加失败", "", "", 1);
                }
            } else {
                $file->getError();
            }
        }
    }

    public function ajax_delAll()
    {
        $arrId = input("get.arrId");
        $arrImg = input("get.arrImg");
        $img = explode(",", $arrImg);
        $res = Db::table("news")->delete($arrId);
        if ($res) {
            foreach ($img as $k => $v) {
                unlink("upload/news/" . $v);
            }
            echo 1;
        } else {
            echo 0;
        }
    }

    public function save()
    {
        $id = input("get.");
        $type = Db::table("newstype")->select();
        $this->assign("type", $type);
        $data = Db::table("news")->find($id);
        $this->assign("data", $data);
        return view();
    }

    public function upload()
    {
        $arr = input("post.");
        $file = request()->file('img');
        if ($file) {
            unlink("upload/news/" . $arr['oldimg']);
            unset($arr['oldimg']);
            $info = $file->move(ROOT_PATH . "public/upload/news");
            if ($info) {
                $arr['img'] = $info->getSaveName();
                $res = Db::table("news")->update($arr);
                if ($res) {
                    $this->redirect("news/index");
                } else {
                    $this->redirect("news/index");
                }
            } else {
                $file->getError();
            }
        } else {
            unset($arr['oldimg']);
            $res = Db::table("news")->update($arr);
            if ($res) {
                $this->redirect("news/index");
            } else {
                $this->redirect("news/index");
            }
        }
    }
}