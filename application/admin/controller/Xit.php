<?php

namespace app\admin\Controller;

use think\Controller;
use think\Db;

class Xit extends Lock
{
    public function index()
    {
        return view();
    }

    public function save()
    {
        $data = input("post.");
        $con = config("webConfig");
        $arr = array_merge($con, $data);
        $file = request()->file('logo');
        if ($file) {
            $info = $file->move(ROOT_PATH . "public/upload");
            if ($info) {
                $arr['logo'] = $info->getSaveName();
            } else {
                $file->getError();
            }
        }
        $str = var_export($arr, true);
        if (file_put_contents("../application/extra/webConfig.php", "<?php return " . $str . ";")) {
            unlink("upload/" . $con['logo']);
            $this->redirect("xit/index");
        } else {
            $this->redirect("xit/index");
        }
    }
}