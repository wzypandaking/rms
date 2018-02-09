<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->redirect("/resume/index.html", null, 301, null);
    }
}
