<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function _initialize()
    {
        //导航
        $nav = Db::table("nav")->order("sort desc")->select();
        $this->assign("nav", $nav);

        //热门文章
        $hot=Db::table("news")->order("looknum desc")->limit(4)->select();
        $this->assign("hot",$hot);

    }

    public function index()
    {
        $cid=input("cid");
        $this->assign("at", "博客");
        $type=Db::table("newstype")->order("id desc")->select();
        $this->assign("type",$type);
        if ($cid){
            $data=Db::table("news")->field("news.*,newstype.name")->join("newstype","news.typeid=newstype.id")->where("typeid","$cid")->order("id desc")->paginate(3, false, ["query" => request()->param()]);
            $this->assign("data",$data);
        }else{
            $data=Db::table("news")->field("news.*,newstype.name")->join("newstype","news.typeid=newstype.id")->order("id desc")->paginate(3, false, ["query" => request()->param()]);
            $this->assign("data",$data);
        }
        $lost=Db::table("admin")->select();
        return view();
    }

    public function details()
    {
        $id=input("id");
        $this->assign("at", "博客");

        //详情内容
        $data=Db::table("news")->where("id","$id")->order("id desc")->select();
        $this->assign("data",$data);

        //博客分类
        $type=Db::table("newstype")->order("id desc")->select();
        $this->assign("type",$type);

        $corre=Db::table("news")->find($id);

        //相关文章
        $typeid=$corre['typeid'];
        $exe=Db::table("news")->where("typeid","$typeid")->select();
        $this->assign("exe",$exe);

        //浏览次数
        $num=$corre['looknum'];
        $arr= [
            "id"=>$id,
            "looknum"=>$num+1
        ];
        Db::table("news")->update($arr);
        return view();
    }

    //美食
    public function first(){
        $typeid=input("typeid");
        return view();
    }

    public function about_me()
    {
        return view();
    }

    public function table()
    {
        return view();
    }

    public function page(){


        $page=input("get.page");
        $star=($page-1)*4;
        $data=Db::table("news")->field("news.*,newstype.name")->join("newstype","newstype.id=news.typeid")->order("time desc" )->limit($star,3)->select();
        foreach ($data as $k=>$v){
            $data[$k]['time']=date("Y-m-d H:i:s",$v['time']);
        }
        if ($data){

            $lis=json_encode($data);
            echo $lis;
        }else{
            echo 0;
        }
    }

}
