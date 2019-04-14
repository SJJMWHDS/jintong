<?php

namespace app\admin\Controller;

use think\Controller;
use think\Db;

class Admin extends Lock
{
    public function index()
    {
        $search = input("get.search");
        $tot = Db::table("admin")->where("username like '%$search%'")->whereOr("id like '%$search%'")->whereOr("status like '%$search%'")->count();
        $this->assign("tot", $tot);
        $data = Db::table("admin")->order("id desc")->where("username like '%$search%'")->whereOr("id like '%$search%'")->whereOr("status like '%$search%'")->paginate(5, false, ["query" => request()->param()]);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_username()
    {
        $username = input("get.username");
        $data = Db::table("admin")->where("username", "$username")->find();
        if ($data) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ajax_add()
    {
        $arr["username"] = input("post.username");
        $arr["password"] = input("post.password");
        $repassword = input("post.repassword");
        $arr["status"] = input("post.status");
        $arr["time"] = time();
        if ($arr["username"]) {
            if (strlen($arr["username"]) >= 5 && strlen($arr["username"]) <= 12) {
                if ($arr["password"]) {
                    if (strlen($arr["password"]) >= 5 && strlen($arr["password"]) <= 12) {
                        if ($repassword && $arr["password"] == $repassword) {
                            $data = Db::table("admin")->where("username", "$arr[username]")->find();
                            if (!$data) {
                                $res = Db::table("admin")->insert($arr);
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
                            } else {
                                $data = [
                                    "code" => "400",
                                    "info" => "用户名已存在"
                                ];
                            }
                        } else {
                            $data = [
                                "code" => "400",
                                "info" => "两次密码输入不一致"
                            ];
                        }
                    } else {
                        $data = [
                            "code" => "400",
                            "info" => "密码长度在5到12位之间"
                        ];
                    }
                } else {
                    $data = [
                        "code" => "400",
                        "info" => "请输入密码"
                    ];
                }
            } else {
                $data = [
                    "code" => "400",
                    "info" => "用户名长度在5到12位之间"
                ];
            }
        } else {
            $data = [
                "code" => "400",
                "info" => "请输入用户名"
            ];
        }
        echo json_encode($data);
    }

    public function ajax_toggle()
    {
        $status = input("get.status");
        $id = input("get.id");
        $arr = [
            "status" => $status,
            "id" => $id
        ];
        $res = Db::table("admin")->update($arr);
        if ($res) {
            echo json_encode($arr);
        } else {
            echo 0;
        }
    }

    public function ajax_delAll()
    {
        $id = $_GET['str'];
        $res = Db::table("admin")->delete($id);
        if ($res) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ajax_tk()
    {
        $id = $_GET['id'];
        $data = Db::table("admin")->find($id);
        $this->assign("data", $data);
        return view();
    }

    public function ajax_save()
    {
        $id = input("id");
        $password = input("password");
        $repassword = input("repassword");
        $status = input("status");
        if ($password || $repassword) {
            if (strlen($password) >= 5 && $password <= 12) {
                if ($password == $repassword) {
                    $arr = [
                        "id" => $id,
                        "password" => $password,
                        "status" => $status
                    ];
                } else {
                    $data = [
                        "code" => 400,
                        "info" => "两次密码输入不一致"
                    ];
                    echo json_encode($data);
                    exit();
                }
            } else {
                $data = [
                    "code" => 400,
                    "info" => "密码长度应在5到12位之间"
                ];
                echo json_encode($data);
                exit();
            }
        } else {
            $arr = [
                "id" => $id,
                "status" => $status
            ];
        }
        $res = Db::table("admin")->update($arr);
        if ($res) {
            echo 1;
        } else {
            echo 0;
        }
    }
}