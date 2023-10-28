<?php
namespace app\controller;
use think\facade\View;
use think\facade\Request;
use \think\facade\Filesystem;

use app\model\Contests;
use app\model\ContestRanks;
use app\model\GlobalSettings;

class Show
{
    public function index($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        
        View::assign('page_title','排名页面');
        
        $dbCR=new ContestRanks();
        $ranks=$dbCR->getRanksOrdered($cid,'rank','asc');
        View::assign([
            'ranks'=>$ranks
        ]);
        
        View::assign([
            'cid'=>$cid,
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function indexauto($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        
        View::assign('page_title','排名页面');
        
        $dbCR=new ContestRanks();
        $ranks=$dbCR->getRanksOrdered($cid,'rank','asc');
        View::assign([
            'ranks'=>$ranks
        ]);
        
        View::assign([
            'cid'=>$cid,
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function indexloginname($cid,$loginname)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        
        View::assign('page_title',$loginname.' - 排名页面');
        
        $dbCR=new ContestRanks();
        $rank=$dbCR->getRank($cid,$loginname);
        if(count($rank)==0){$rank=['rank'=>'无搜索结果','loginname'=>'-','pscore'=>'-','ptime'=>'-'];}
        else $rank=$rank[0];
        $ranks=$dbCR->getRanksOrdered($cid,'rank','asc');
        View::assign([
            'loginname'=>$loginname,
            'rank'=>$rank,
            'ranks'=>$ranks
        ]);
        
        View::assign([
            'cid'=>$cid,
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function indexloginnameauto($cid,$loginname)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        
        View::assign('page_title',$loginname.' - 排名页面');
        
        $dbCR=new ContestRanks();
        $rank=$dbCR->getRank($cid,$loginname);
        if(count($rank)==0){$rank=['rank'=>'无搜索结果','loginname'=>'-','pscore'=>'-','ptime'=>'-'];}
        else $rank=$rank[0];
        $ranks=$dbCR->getRanksOrdered($cid,'rank','asc');
        View::assign([
            'loginname'=>$loginname,
            'rank'=>$rank,
            'ranks'=>$ranks
        ]);
        
        View::assign([
            'cid'=>$cid,
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
}
