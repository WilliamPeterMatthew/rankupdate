<?php
namespace app\model;
use think\Model;

use app\model\GlobalSettings;

class Contests extends Model
{
    protected $pk = 'cid';
    public function enable($cid)
    {
        $contests=Contests::select()->toArray();
        $flag=false;
        foreach($contests as $c)
        {
            if($c['cid']==$cid)
            {
                if($c['enable'])
                    $flag=true;
            }
        }
        return $flag;
    }
    public function addContest($cid,$cname,$ccolor,$caccent_color,$enable,$clang)
    {
        return Contests::insert([
            'cid'=>$cid,
            'cname'=>$cname,
            'ccolor'=>$ccolor,
            'caccent_color'=>$caccent_color,
            'enable'=>$enable,
            'clang'=>$clang
        ]);
    }
    public function delContest($cid)
    {
        return Contests::where('cid',$cid)->delete();
    }
    public function getContest($cid)
    {
        return Contests::where('cid',$cid)->find()->toArray();
    }
    public function updateContest($cid,$cname,$ccolor,$caccent_color,$enable,$clang)
    {
        return Contests::where('cid',$cid)->update([
            'cname'=>$cname,
            'ccolor'=>$ccolor,
            'caccent_color'=>$caccent_color,
            'enable'=>$enable,
            'clang'=>$clang
        ]);
    }
    public function getContests()
    {
        return Contests::order('cid','desc')->select()->toArray();
    }
    public function getContestsOrdered($contests_order_key,$contests_order_by)
    {
        return Contests::order($contests_order_key,$contests_order_by)->select()->toArray();
    }
    public function getCids()
    {
        return Contests::column('cid');
    }
    public function getEnableContests()
    {
        return Contests::where('enable',1)->order('cid','desc')->select()->toArray();
    }
    public function getEnableContestsOrdered($contests_order_key,$contests_order_by)
    {
        return Contests::where('enable',1)->order($contests_order_key,$contests_order_by)->select()->toArray();
    }
    public function getEnableCids()
    {
        return Contests::where('enable',1)->column('cid');
    }
    public function getSetting($cid,$setting_name)
    {
        $ret = Contests::where('cid', $cid)->value('c'.$setting_name);
        if(is_null($ret)||strlen($ret)==0){
            $dbGS = new GlobalSettings();
            $ret=$dbGS->getSetting($setting_name);
        }
        return $ret;
    }
    public function getContestSetting($cid,$setting_name)
    {
        return $ret = Contests::where('cid', $cid)->value($setting_name);
    }
    public function setContestSetting($cid,$setting_name,$setting_value)
    {
        $ret=Contests::where('cid', $cid)->update([
            $setting_name=>$setting_value
        ]);
        return true;
    }
}