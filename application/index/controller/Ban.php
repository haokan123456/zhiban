<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use PHPExcel;
use PHPExcel_IOFactory;

class Ban extends Controller
{
    public function index()
    {
        return view();
    }

    public function add()
    {
      if(!request()->isPost()){
        $users=db('pepole')->order('id')->select();
        $ban=db('ban')->order('id')->select();
        $this->assign('users',$users);
        $this->assign('ban',$ban);
        return $this->fetch();
      }
      else{
        $ban=input('param.');
        $name=$ban['fname'];
        $ban['date']=strtotime($ban['date']);
        $ban['date2']=strtotime($ban['date2']);

        $uu=db::query("select * from time
                      where uid=".$ban['fname']." and ".$ban['date']." between date and date2");

        $date2=strtotime('-1day',$ban['date2']);
        $u2=db::query("select a.bid,count(a.bid) as sums,b.nums
                      from time a
                      inner join ban b on a.bid=b.id
                      where ".$ban['date']." between date and date2 and a.bid=".$ban['paiban']."
                      group by a.bid
                      order by b.id");

        if(count($u2)>0){
          if($u2[0]['sums']>=$u2[0]['nums']){
            echo 5;
          }
          else if(count($uu)>0){
            echo 4;
          }
          else{
            $ban['uid']=$ban['fname'];
            $ban['bid']=$ban['paiban'];
            unset($ban['fname'],$ban['paiban']);
            $time=db('time')->insert($ban);
            echo 1;
          }
        }
        else if(count($uu)>0){
          echo 4;
        }
        else{
          $ban['uid']=$ban['fname'];
          $ban['bid']=$ban['paiban'];
          unset($ban['fname'],$ban['paiban']);
          $time=db('time')->insert($ban);
          echo 1;
        }
      }
    }

    public function lst()
    {
      $users=db('time')->alias('a')->field('a.id,b.`name`,c.class,a.date,a.date2')->join('pepole b','a.uid=b.id')->join('ban c','a.bid=c.id')->order('id')->paginate(10);

      $this->assign('users',$users);
      return $this->fetch();
    }

    public function export()
    {
      vendor('PHPExcel.PHPExcel');
      $objPHPExcel=new PHPExcel();
      $objSheet=$objPHPExcel->getActiveSheet();
      $objSheet->setTitle('排班表');
      $objSheet->setCellValue('A1','姓名')->setCellValue('B1','排班')->setCellValue('C1','起始日期')->setCellValue('D1','终止日期')->setCellValue('E1','休息日期');

      $paiban=db('time')->alias('a')->field('c.id,c.`name`,b.class,a.date,a.date2')->join('ban b','a.bid=b.id')->join('pepole c','a.uid=c.id')->select();

      $daochu=array([]);

      for($i=0;$i<count($paiban);$i++){
        $kaishi=date('Y-m-d',$paiban[$i]['date']);
        $jieshu=date('Y-m-d',strtotime('-1day',$paiban[$i]['date2']));
        $xiuxi=date('Y-m-d',$paiban[$i]['date2']);
        array_push($daochu,array($paiban[$i]['name'],$paiban[$i]['class'],$kaishi,$jieshu,$xiuxi));
      }
      $objSheet->fromArray($daochu);

      $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="排班.xlsx"');
      header('Cache-Control: max-age=0');
      $objWriter->save("php://output");
    }

    public function edit()
    {
      if(!request()->isPost()){
        $id=input('param.id');
        $user=db('time')->where('id',$id)->find();
        $yonghu=db('pepole')->order('id')->select();
        $ban=db('ban')->order('id')->select();

        $user['date']=date('Y-m-d',$user['date']);
        $user['date2']=date('Y-m-d',$user['date2']);

        $this->assign(array(
          'user'=>$user,
          'yonghu'=>$yonghu,
          'ban'=>$ban,
          'pid'=>$id
        ));

        return $this->fetch();
      }
      else{
        // $user=input('param.');
        // $arr['date']=strtotime($user['date']);
        // $arr['date2']=strtotime($user['date2']);

        // $pid=$user['pid'];
        // unset($user['pid']);

        // $arr['uid']=$user['fname'];
        // $arr['bid']=$user['paiban'];

        // db('time')->where('id',$pid)->update($arr);
        // echo 1;


        $ban=input('param.');
        $name=$ban['fname'];
        $ban['date']=strtotime($ban['date']);
        $ban['date2']=strtotime($ban['date2']);

        $uu=db::query("select * from time
                      where uid=".$ban['fname']." and ".$ban['date']." between date and date2");

        $date2=strtotime('-1day',$ban['date2']);
        $u2=db::query("select a.bid,count(a.bid) as sums,b.nums
                      from time a
                      inner join ban b on a.bid=b.id
                      where ".$ban['date']." between date and date2 and a.bid=".$ban['paiban']."
                      group by a.bid
                      order by b.id");

        if(count($u2)>0){
          if($u2[0]['sums']>=$u2[0]['nums']){
            echo 5;
          }
          else if(count($uu)>0){
            echo 4;
          }
          else{
            $ban['uid']=$ban['fname'];
            $ban['bid']=$ban['paiban'];
            unset($ban['fname'],$ban['paiban']);
            $time=db('time')->insert($ban);
            echo 1;
          }
        }
        else if(count($uu)>0){
          echo 4;
        }
        else{
          $ban['uid']=$ban['fname'];
          $ban['bid']=$ban['paiban'];
          $pid=$ban['pid'];
          unset($ban['fname'],$ban['paiban'],$ban['pid']);
          db('time')->where('id',$pid)->update($ban);
          echo 1;
        }

      }
    }

    public function del($id)
    {
      db('time')->where('id',$id)->delete();
      echo 1;
    }
}
