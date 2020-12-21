<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use PHPExcel;
use PHPExcel_IOFactory;

class Index extends Controller
{
    public function index()
    {
        return view();
    }

    public function add()
    {
    	if(!request()->isPost()){
            $ban=db('ban')->select();
            $this->assign('band',$ban);
    		return $this->fetch();
    	}
    	else{
    	   $user=input('param.');
           $user['name']=$user['fname'];
           unset($user['fname']);
           $dd=db('pepole')->where($user)->select();
           if($dd){
                echo 3;
           }
           else{
                db('pepole')->insert($user);
                echo 1;
           }
    	}
    }

    public function lst()
    {
        $users=db('pepole')->alias('a')->field('a.id,a.name,b.class as interest,c.class')->join('ban b','a.interest=b.id')->join('ban c','a.class=c.id','left')->paginate(10);
        $this->assign('users',$users);
        return $this->fetch();
    }

    public function edit()
    {
      if(!request()->isPost()){
            $id=input('param.id');
            $man=db('pepole')->alias('a')->join('ban b','a.interest=b.id')->where('a.id',$id)->find();
            $ban=db('ban')->select();
            $this->assign('band',$ban);
            $this->assign('man',$man);
        return $this->fetch();
      }
      else{
         $user=input('param.');

         $arr=array(
          'name'=>$user['name'],
          'interest'=>$user['interest']
         );
         $dd=db('pepole')->where('id',$user['id'])->update($arr);

         echo 1;
      }
    }

    public function del($id)
    {
      db('pepole')->where('id',$id)->delete();
      echo 1;
    }

    public function upload()
    {
        $type=explode(',',input('param.ftype'))[1];

        $file = request()->file('ftxt');
        $up=ROOT_PATH . 'public' . DS . 'uploads';
        $info = $file->move($up);

        if($info){
          $f=$info->getSaveName();
          $up=$up.DS.$f;
          if($type=='txt'){
            $this->reload($up);
          }
          else{
            $this->rlxls($up);
          }
        }
    }

    // 读取文件
    public function reload($up)
    {
        header('Content-type: text/html; charset=UTF8');
        $file = fopen($up, "r");
        $data=array();
        $i=0;
        //输出文本中所有的行，直到文件结束为止。
        while(! feof($file))
        {
            $data[$i]= mb_convert_encoding(fgets($file),'utf8','gb2312');//fgets()函数从文件指针中读取一行
            $i++;
        }
        fclose($file);
        $data=array_filter($data);
        $arr=array();

        foreach($data as $k=>$v){
            $dd=explode(' ',str_replace(array("\r\n", "\r", "\n"),'',$v));
            array_push($arr,$dd);
        }
        $brr=json_encode($arr);
        echo $brr;
    }

    public function rlxls($up)
    {
      header('Content-type: text/html; charset=UTF8');
      $inputFileName=$up;
      vendor('PHPExcel.PHPExcel');
      $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
      $objReader = PHPExcel_IOFactory::createReader($inputFileType);
      $objPHPExcel = $objReader->load($inputFileName);

      $sheet = $objPHPExcel->getSheet(0);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn();

      $n=1;
      for ($row = 2; $row <= $highestRow; $row++){
      // Read a row of data into an array
          $rowData = $sheet->rangeToArray('A' . $row . ':' . 'B' . $row, NULL, TRUE, FALSE);

          // 添加员工
          $arr['name']=$rowData[0][0];
          $arr['interest']=$rowData[0][1];

          $u=db('pepole')->where('name',$arr['name'])->find();
          if(count($u)>0){
            $n=2;
          }
          else{
            $arr['class']=0;
            $ban=db('ban')->where('class',$arr['interest'])->find();
            $arr['interest']=$ban['id'];
            db('pepole')->insert($arr);
          }
      }
      echo $n;
    }

    // 导入文件添加员工
    public function fileadd()
    {
      $user=input('param.');
      $us=db('pepole')->where('name',$user['name'])->find();
      if(count($us)>0){
        echo 2;
      }
      else{
        $user['class']=0;
        $ban=db('ban')->where('class',$user['interest'])->find();
        $user['interest']=$ban['id'];
        db('pepole')->insert($user);
        echo 1;
      }
    }
}
