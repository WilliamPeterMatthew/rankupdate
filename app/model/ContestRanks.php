<?php
namespace app\model;
use think\Model;

use app\model\ContestScores;
use app\model\ExportData;

class ContestRanks extends Model
{
    protected $pk = ['cid', 'loginname'];
    public function addRank($cid,$loginname,$rank,$pscore,$ptime)
    {
        return ContestRanks::insert([
            'cid'=>$cid,
            'loginname'=>$loginname,
            'rank'=>$rank,
            'pscore'=>$pscore,
            'ptime'=>$ptime
        ]);
    }
    public function delRank($cid,$loginname)
    {
        return ContestRanks::where('cid',$cid)->where('loginname',$loginname)->delete();
    }
    public function delContestRanks($cid)
    {
        return ContestRanks::where('cid',$cid)->delete();
    }
    public function getRank($cid,$loginname)
    {
        return ContestRanks::where('cid',$cid)->where('loginname',$loginname)->select()->toArray();
    }
    public function updateRank($cid,$loginname,$rank,$pscore,$ptime)
    {
        return ContestRanks::where('cid',$cid)->where('loginname',$loginname)->update([
            'rank'=>$rank,
            'pscore'=>$pscore,
            'ptime'=>$ptime
        ]);
    }
    public function getRanks($cid)
    {
        return ContestRanks::where('cid', $cid)->select()->toArray();
    }
    public function getRanksOrdered($cid,$ranks_order_key,$ranks_order_by)
    {
        return ContestRanks::where('cid', $cid)->order($ranks_order_key,$ranks_order_by)->select()->toArray();
    }
    public function getRanksOrdered2($cid,$ranks_order_key,$ranks_order_by,$ranks_order_key2,$ranks_order_by2)
    {
        return ContestRanks::where('cid', $cid)->order([
            $ranks_order_key=>$ranks_order_by,
            $ranks_order_key2=>$ranks_order_by2
        ])->select()->toArray();
    }
    public function calcRanks($cid)
    {
        $ret=ContestRanks::where('cid',$cid)->delete();
        $dbCS = new ContestScores();
        $res=$dbCS->calcScores($cid);
        if(!$res)
            return 0;
        $scores = $dbCS->getScoresOrdered2($cid,'loginname','asc','problemid','asc');
        if(is_null($scores))
            return 0;
        
        $loginnames=array();
        $ranks=array();
        $pscores=array();
        $ptimes=array();
        $lens=count($scores);
        
        $nwloginname=$scores[0]['loginname'];
        
        $numi=1;
        $loginnames[1]=$scores[0]['loginname'];
        $pscores[1]=$scores[0]['score'];
        $ptimes[1]=$scores[0]['scoretime'];
        
        for($i=1;$i<$lens;$i++)
        {
            if($nwloginname!=$scores[$i]['loginname'])
            {
                $nwloginname=$scores[$i]['loginname'];
                $numi++;
                $loginnames[$numi]=$scores[$i]['loginname'];
                $pscores[$numi]=$scores[$i]['score'];
                $ptimes[$numi]=$scores[$i]['scoretime'];
            }
            else
            {
                $pscores[$numi]+=$scores[$i]['score'];
                $ptimes[$numi]+=$scores[$i]['scoretime'];
            }
        }
        
        for($i=1;$i<=$numi;$i++)
        {
            ContestRanks::insert([
                'cid'=>$cid,
                'loginname'=>$loginnames[$i],
                'rank'=>0,
                'pscore'=>$pscores[$i],
                'ptime'=>$ptimes[$i],
            ]);
        }
        
        $ranks=ContestRanks::where('cid', $cid)->order([
            'pscore'=>'desc',
            'ptime'=>'asc'
        ])->select()->toArray();
        
        $lenr=count($ranks);
        $ranks[0]['rank']=1;
        for($i=1;$i<$lenr;$i++)
        {
            if($ranks[$i]['pscore']==$ranks[$i-1]['pscore']&&$ranks[$i]['ptime']==$ranks[$i-1]['ptime'])
                $ranks[$i]['rank']=$ranks[$i-1]['rank'];
            else
                $ranks[$i]['rank']=$i+1;
        }
        foreach($ranks as $v)
            $ret = ContestRanks::where('cid',$cid)->where('loginname',$v['loginname'])->update(['rank'=>$v['rank']]);
        return true;
    }
    function exportRanks($cid)
    {
        $ED=new ExportData();
        $ranks=ContestRanks::where('cid', $cid)->order('rank','asc')->select()->toArray();
        foreach($ranks as &$r)
        {
            unset($r['cid']);
        }
        unset($r);
        return $ED->export_excel('contest'.$cid.'-ranks.csv',['登录名称','选手排名','选手总分','选手总用时'],$ranks);
    }
}