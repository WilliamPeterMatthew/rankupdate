<?php
namespace app\model;
use think\Model;

use app\model\ExportData;

class ContestProblems extends Model
{
    protected $pk = ['cid', 'problemid'];
    public function addProblem($cid,$seq,$lang,$problemscore,$problemid)
    {
        return ContestProblems::insert([
            'cid'=>$cid,
            'seq'=>$seq,
            'lang'=>$lang,
            'problemscore'=>$problemscore,
            'problemid'=>$problemid
        ]);
    }
    public function delProblem($cid,$problemid)
    {
        return ContestProblems::where('cid',$cid)->where('problemid',$problemid)->delete();
    }
    public function delContestProblems($cid)
    {
        return ContestProblems::where('cid',$cid)->delete();
    }
    public function getProblem($cid,$problemid)
    {
        return ContestProblems::where('cid',$cid)->where('problemid',$problemid)->find()->toArray();
    }
    public function updateProblem($cid,$seq,$lang,$problemscore,$problemid)
    {
        return ContestProblems::where('cid',$cid)->where('problemid',$problemid)->update([
            'seq'=>$seq,
            'lang'=>$lang,
            'problemscore'=>$problemscore
        ]);
    }
    public function getProblems($cid)
    {
        return ContestProblems::where('cid', $cid)->select()->toArray();
    }
    public function getProblemsOrdered($cid,$problems_order_key,$problems_order_by)
    {
        return ContestProblems::where('cid', $cid)->order($problems_order_key,$problems_order_by)->select()->toArray();
    }
    function exportProblems($cid)
    {
        $ED=new ExportData();
        $problems=ContestProblems::where('cid', $cid)->order('seq','asc')->select()->toArray();
        foreach($problems as &$p)
        {
            unset($p['cid']);
        }
        unset($p);
        return $ED->export_excel('contest'.$cid.'-problems.csv',['题目排号','题目语言','题目分数','题目序号'],$problems);
    }
}