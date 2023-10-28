<?php
namespace app\model;
use think\Model;

use app\model\ExportData;

class ContestRecords extends Model
{
    protected $pk = ['cid', 'data_rid'];
    public function addRecord($cid,$data_rid,$status,$dscore,$problemid,$loginname,$sendtime)
    {
        return ContestRecords::insert([
            'cid'=>$cid,
            'data_rid'=>$data_rid,
            'status'=>$status,
            'dscore'=>$dscore,
            'problemid'=>$problemid,
            'loginname'=>$loginname,
            'sendtime'=>$sendtime
        ]);
    }
    public function delRecord($cid,$data_rid)
    {
        return ContestRecords::where('cid',$cid)->where('data_rid',$data_rid)->delete();
    }
    public function delContestRecords($cid)
    {
        return ContestRecords::where('cid',$cid)->delete();
    }
    public function getRecord($cid,$data_rid)
    {
        return ContestRecords::where('cid',$cid)->where('data_rid',$data_rid)->find()->toArray();
    }
    public function updateRecord($cid,$data_rid,$status,$dscore,$problemid,$loginname,$sendtime)
    {
        return ContestRecords::where('cid',$cid)->where('data_rid',$data_rid)->update([
            'status'=>$status,
            'dscore'=>$dscore,
            'problemid'=>$problemid,
            'loginname'=>$loginname,
            'sendtime'=>$sendtime
        ]);
    }
    public function getRecords($cid)
    {
        return ContestRecords::where('cid', $cid)->select()->toArray();
    }
    public function getRecordsOrdered($cid,$records_order_key,$records_order_by)
    {
        return ContestRecords::where('cid', $cid)->order($records_order_key,$records_order_by)->select()->toArray();
    }
    public function getRecordsOrdered2($cid,$records_order_key,$records_order_by,$records_order_key2,$records_order_by2)
    {
        return ContestRecords::where('cid', $cid)->order([
            $records_order_key=>$records_order_by,
            $records_order_key2=>$records_order_by2
        ])->select()->toArray();
    }
    public function getRecordsOrdered3($cid,$records_order_key,$records_order_by,$records_order_key2,$records_order_by2,$records_order_key3,$records_order_by3)
    {
        return ContestRecords::where('cid', $cid)->order([
            $records_order_key=>$records_order_by,
            $records_order_key2=>$records_order_by2,
            $records_order_key3=>$records_order_by3
        ])->select()->toArray();
    }
    function exportRecords($cid)
    {
        $ED=new ExportData();
        $records=ContestRecords::where('cid', $cid)->order('data_rid','asc')->select()->toArray();
        foreach($records as &$r)
        {
            unset($r['cid']);
        }
        unset($r);
        return $ED->export_excel('contest'.$cid.'-records.csv',['记录特征值','通过状态','记录分数','题目序号','登录名称','提交时间'],$records);
    }
}