<?php
namespace app\index\controller;
use think\Db;
use think\Controller;

class Zhiban extends Controller
{
    public function index()
    {
    	$nums=db('ban')->select();
    	$al=db('time')->alias('a')->field('b.id,COUNT(a.bid) as nums')->join('ban b','a.bid=b.id')->group('a.bid')->select();

    	$this->assign('al',$al);
    	$this->assign('nums',$nums);
        return view();
    }

    public function add()
    {
    	$ban=input('param.');
    	db('ban')->where('id',1)->update(['nums'=>$ban['zao']]);
    	db('ban')->where('id',2)->update(['nums'=>$ban['zhong']]);
    	db('ban')->where('id',3)->update(['nums'=>$ban['wan']]);
    	echo 1;
    }

}
