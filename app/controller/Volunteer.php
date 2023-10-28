<?php
namespace app\controller;
use think\facade\View;
use think\facade\Request;
use \think\facade\Filesystem;

use app\model\Contests;
use app\model\ContestScores;
use app\model\GlobalSettings;

class Volunteer
{
    public function index($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        
        View::assign('page_title','志愿者页面');
        
        $dbCS=new ContestScores();
        $scores=$dbCS->getPassScores($cid);
        View::assign([
            'scores'=>$scores
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
        
        View::assign('page_title','志愿者页面');
        
        $dbCS=new ContestScores();
        $scores=$dbCS->getPassScores($cid);
        View::assign([
            'scores'=>$scores
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
    public function indexclass($cid,$class)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        
        View::assign('page_title',$class.' - 志愿者页面');
        
        $dbCS=new ContestScores();
        $scores=$dbCS->getClassPassScores($cid,$class);
        View::assign([
            'class'=>$class,
            'scores'=>$scores
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
    public function indexclassauto($cid,$class)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        
        View::assign('page_title',$class.' - 志愿者页面');
        
        $dbCS=new ContestScores();
        $scores=$dbCS->getClassPassScores($cid,$class);
        View::assign([
            'class'=>$class,
            'scores'=>$scores
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
