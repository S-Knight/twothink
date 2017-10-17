<?php

namespace app\home\controller;

class Index extends Home
{
    public function index()
    {
        return view('Index/index');
    }
}