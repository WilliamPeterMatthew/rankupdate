<?php
namespace app\model;
use think\Model;

use app\model\ContestProblems;
use app\model\ContestRecords;
use app\model\ExportData;

class ContestScores extends Model
{
    protected $pk = ['cid', 'loginname', 'problemid'];
    public function addScore($cid,$loginname,$problemid,$score,$scoretime,$status,$firstblood)
    {
        return ContestScores::insert([
            'cid'=>$cid,
            'loginname'=>$loginname,
            'problemid'=>$problemid,
            'score'=>$score,
            'scoretime'=>$scoretime,
            'status'=>$status,
            'firstblood'=>$firstblood
        ]);
    }
    public function delScore($cid,$loginname,$problemid)
    {
        return ContestScores::where('cid',$cid)->where('loginname',$loginname)->where('problemid',$problemid)->delete();
    }
    public function delContestLoginnameScores($cid,$loginname)
    {
        return ContestScores::where('cid',$cid)->where('loginname',$loginname)->delete();
    }
    public function delContestProblemidScores($cid,$problemid)
    {
        return ContestScores::where('cid',$cid)->where('problemid',$problemid)->delete();
    }
    public function delContestScores($cid)
    {
        return ContestScores::where('cid',$cid)->delete();
    }
    public function getScore($cid,$loginname,$problemid)
    {
        return ContestScores::where('cid',$cid)->where('loginname',$loginname)->where('problemid',$problemid)->select()->toArray();
    }
    public function updateScore($cid,$loginname,$problemid,$score,$scoretime,$status,$firstblood)
    {
        return ContestScores::where('cid',$cid)->where('loginname',$loginname)->where('problemid',$problemid)->update([
            'score'=>$score,
            'scoretime'=>$scoretime,
            'status'=>$status,
            'firstblood'=>$firstblood
        ]);
    }
    public function getClassPassScores($cid,$class)
    {
        return ContestScores::where('cid', $cid)->where('status', 'pass')->whereLike('loginname',$class.'%')->order('scoretime','desc')->select()->toArray();
    }
    public function getPassScores($cid)
    {
        return ContestScores::where('cid', $cid)->where('status', 'pass')->order('scoretime','desc')->select()->toArray();
    }
    public function getScores($cid)
    {
        return ContestScores::where('cid', $cid)->select()->toArray();
    }
    public function getScoresOrdered($cid,$scores_order_key,$scores_order_by)
    {
        return ContestScores::where('cid', $cid)->order($scores_order_key,$scores_order_by)->select()->toArray();
    }
    public function getScoresOrdered2($cid,$scores_order_key,$scores_order_by,$scores_order_key2,$scores_order_by2)
    {
        return ContestScores::where('cid', $cid)->order([
            $scores_order_key=>$scores_order_by,
            $scores_order_key2=>$scores_order_by2
        ])->select()->toArray();
    }
    public function calcScores($cid)
    {
        $ret=ContestScores::where('cid',$cid)->delete();
        $dbCR = new ContestRecords();
        $records = $dbCR->getRecordsOrdered3($cid,'loginname','asc','problemid','asc','sendtime','asc');
        if(is_null($records))
            return 0;
        
        $loginnames=array();
        $problemids=array();
        $scores=array();
        $scoretimes=array();
        $statuses=array();
        $lenr=count($records);
        
        $nwloginname=$records[0]['loginname'];
        $nwproblemid=$records[0]['problemid'];
        
        $numi=1;
        $numj=array();
        $numj[1]=1;
        $loginnames[1]=$records[0]['loginname'];
        $problemids[1][1]=$records[0]['problemid'];
        $scores[1][1]=$records[0]['dscore'];
        $scoretimes[1][1]=$records[0]['sendtime'];
        $statuses[1][1]=$records[0]['status'];
        
        for($i=1;$i<$lenr;$i++)
        {
            if($nwloginname!=$records[$i]['loginname'])
            {
                $nwloginname=$records[$i]['loginname'];
                $nwproblemid=$records[$i]['problemid'];
                $numi++;
                $numj[$numi]=1;
                $loginnames[$numi]=$records[$i]['loginname'];
                $problemids[$numi][$numj[$numi]]=$records[$i]['problemid'];
                $scores[$numi][$numj[$numi]]=$records[$i]['dscore'];
                $scoretimes[$numi][$numj[$numi]]=$records[$i]['sendtime'];
                $statuses[$numi][$numj[$numi]]=$records[$i]['status'];
            }
            else if($nwproblemid!=$records[$i]['problemid'])
            {
                $nwloginname=$records[$i]['loginname'];
                $nwproblemid=$records[$i]['problemid'];
                $numj[$numi]++;
                $problemids[$numi][$numj[$numi]]=$records[$i]['problemid'];
                $scores[$numi][$numj[$numi]]=$records[$i]['dscore'];
                $scoretimes[$numi][$numj[$numi]]=$records[$i]['sendtime'];
                $statuses[$numi][$numj[$numi]]=$records[$i]['status'];
            }
            else
            {
                if($scores[$numi][$numj[$numi]]<$records[$i]['dscore'])
                {
                    $scores[$numi][$numj[$numi]]=$records[$i]['dscore'];
                    $scoretimes[$numi][$numj[$numi]]=$records[$i]['sendtime'];
                    $statuses[$numi][$numj[$numi]]=$records[$i]['status'];
                }
            }
        }
        
        for($i=1;$i<=$numi;$i++)
        {
            for($j=1;$j<=$numj[$i];$j++)
            {
                ContestScores::insert([
                    'cid'=>$cid,
                    'loginname'=>$loginnames[$i],
                    'problemid'=>$problemids[$i][$j],
                    'score'=>$scores[$i][$j],
                    'scoretime'=>$scoretimes[$i][$j],
                    'status'=>$statuses[$i][$j],
                    'firstblood'=>0
                ]);
            }
        }
        
        $dbCP = new ContestProblems();
        $problems=$dbCP->getProblems($cid);
        $seqs=array();
        $fbs=array();
        $lenp=count($problems);
        for($i=0;$i<$lenp;$i++)
        {
            $seqs[$problems[$i]['problemid']]=$problems[$i]['seq'];
            $fbs[$problems[$i]['seq']]=0;
        }
        
        $scores=ContestScores::where('cid',$cid)->where('status','pass')->order('scoretime','asc')->select()->toArray();
        $lens=count($scores);
        for($i=0;$i<$lens;$i++)
        {
            if($fbs[$seqs[$scores[$i]['problemid']]]==0)
            {
                $fbs[$seqs[$scores[$i]['problemid']]]=1;
                $scores[$i]['firstblood']=1;
            }
        }
        foreach($scores as $v)
            $ret = ContestScores::where('cid',$cid)->where('loginname',$v['loginname'])->where('problemid',$v['problemid'])->update(['firstblood'=>$v['firstblood']]);
        return true;
    }
    function exportScores($cid)
    {
        $ED=new ExportData();
        $scores=ContestScores::where('cid', $cid)->order(['loginname'=>'asc','problemid'=>'asc'])->select()->toArray();
        foreach($scores as &$s)
        {
            unset($s['cid']);
        }
        unset($s);
        return $ED->export_excel('contest'.$cid.'-scores.csv',['登录名称','题目名称','单题得分','单题用时','通过状态','是否一血'],$scores);
    }
}