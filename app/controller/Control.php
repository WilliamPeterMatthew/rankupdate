<?php
namespace app\controller;
use think\facade\View;
use think\facade\Request;
use \think\facade\Filesystem;

use app\model\Admins;
use app\model\Contests;
use app\model\ContestProblems;
use app\model\ContestRanks;
use app\model\ContestScores;
use app\model\ContestRecords;
use app\model\GlobalSettings;
use app\model\CookieToSession;
use app\model\SessionToMsg;

class Control
{
    public function index($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if($isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/settings');
        return redirect('/rankupdate/contest'.$cid.'/admin/login');
    }
    
    public function error($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/control');
        if(!$isA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','管理错误');
        
        if($isA)
        {
            $aid = $CTS->getAid();
            View::assign([
                'usertype'=>'admin',
                'aid'=>$aid
            ]);
        }
        
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
    public function errorapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/control');
        if(!$isA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'GET')
        {
            if($action=='logout')
            {
                if($isA)
                    $ret = $CTS->delAid();
                return redirect('/rankupdate/contest'.$cid.'/admin/login');
            }
        }
    }
    
    public function login($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if($isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/control');
        View::assign('page_title','管理登录');
        
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
    public function loginapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if($isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/control');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='login')
            {
                $dbA = new Admins();
                $ret = $dbA->login($all['loginname'],$all['password']);
                if($ret)
                {
                    //$msg=$dbSTM->setMsg('adminlogin',0,'登录成功','登录成功');
                    // return redirect('/rankupdate/contest'.$cid.'/admin');
                    return $dbSTM->retMsg(0,'登录成功','登录成功');
                }
                else
                {
                    $msg=$dbSTM->setMsg('adminlogin',1,'登录错误','请输入正确的登录名称和登录密码');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/login');
                    return $dbSTM->retMsg(1,'登录错误','请输入正确的登录名称和登录密码');
                }
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminlogin');
            }
        }
        if(Request::method() == 'GET')
        {
            if($action=='login')
            {
                $dbA = new Admins();
                $ret = $dbA->login($all['loginname'],$all['password']);
                if($ret)
                {
                    //$msg=$dbSTM->setMsg('adminlogin',0,'登录成功','登录成功');
                    return redirect('/rankupdate/contest'.$cid.'/admin');
                }
                else
                {
                    $msg=$dbSTM->setMsg('adminlogin',1,'登录错误','请输入正确的登录名称和登录密码');
                    return redirect('/rankupdate/contest'.$cid.'/admin/login');
                }
            }
        }
    }
    
    public function logout($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','管理退出登录');
        
        if($isCA)
        {
            $aid = $CTS->getAid();
            View::assign([
                'usertype'=>'admin',
                'aid'=>$aid
            ]);
        }
        
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function logoutapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='logout')
            {
                $ret = $CTS->delAid();
                //$msg=$dbSTM->setMsg('adminlogout',0,'注销成功','注销成功');
                // return redirect('/rankupdate/contest'.$cid.'/admin/login');
                return $dbSTM->retMsg(0,'注销成功','注销成功');
            }
        }
        if(Request::method() == 'GET')
        {
            if($action=='logout')
            {
                $ret = $CTS->delAid();
                return redirect('/rankupdate/contest'.$cid.'/admin/login');
            }
        }
    }
    
    public function profile($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','管理员档案');
        
        $aid = $CTS->getAid();
        $dbA = new Admins();
        $admin = $dbA->getProfile($aid);
        View::assign([
            'aname'=>$admin['aname'],
            'loginname'=>$admin['loginname'],
            'permission'=>$admin['permission']
        ]);
        
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'profile'=>true,
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function profileapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        $aid = $CTS->getAid();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='aname')
            {
                $dbA = new Admins();
                $ret = $dbA->modAname($aid,$all['aname']);
                if($ret)
                {
                    $msg=$dbSTM->setMsg('adminprofile',1,'修改成功','管理员名称已更新');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/profile');
                    return $dbSTM->retMsg(1,'修改成功','管理员名称已更新');
                }
                else
                {
                    $msg=$dbSTM->setMsg('adminprofile',2,'修改错误','请输入正确的新管理员名称');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','请输入正确的新管理员名称');
                }
            }
            if($action=='loginname')
            {
                $dbA = new Admins();
                $ret = $dbA->tryLoginname($aid,$all['loginname']);
                if(!$ret)
                {
                    $msg=$dbSTM->setMsg('adminprofile',2,'修改错误','新的登录名称与其他管理员冲突');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','新的登录名称与其他管理员冲突');
                }
                $ret = $dbA->modLoginname($aid,$all['loginname']);
                if($ret)
                {
                    $msg=$dbSTM->setMsg('adminprofile',1,'修改成功','登录名称已更新');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/profile');
                    return $dbSTM->retMsg(1,'修改成功','登录名称已更新');
                }
                else
                {
                    $msg=$dbSTM->setMsg('adminprofile',2,'修改错误','请输入由数字、大小写字母和下划线“_”组成的新的登录名称');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','请输入由数字、大小写字母和下划线“_”组成的新的登录名称');
                }
            }
            if($action=='password')
            {
                $dbA = new Admins();
                $ret = $dbA->tryPassword($aid,$all['passwordpre']);
                if(!$ret)
                {
                    $msg=$dbSTM->setMsg('adminprofile',2,'修改错误','请输入正确的旧登录密码');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','请输入正确的旧登录密码');
                }
                $ret = $dbA->modPassword($aid,$all['password']);
                if($ret)
                {
                    $msg=$dbSTM->setMsg('adminprofile',1,'修改成功','登录密码已更新');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/profile');
                    return $dbSTM->retMsg(1,'修改成功','登录密码已更新');
                }
                else
                {
                    $msg=$dbSTM->setMsg('adminprofile',2,'修改错误','请输入正确的新登录密码');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','请输入正确的新登录密码');
                }
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminprofile');
            }
        }
    }
    
    public function problems($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','题目列表');
        
        $problems_order_key=$CTS->getProblemsOrderKey($cid);
        $problems_order_by=$CTS->getProblemsOrderBy($cid);
        $dbCP=new ContestProblems();
        $problems=$dbCP->getProblemsOrdered($cid,$problems_order_key,$problems_order_by);
        View::assign([
            'problems'=>$problems,
            'problems_order_key'=>$problems_order_key,
            'problems_order_by'=>$problems_order_by
        ]);
        
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'tabbar'=>'problems',
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function problemsapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='del')
            {
                $dbCP=new ContestProblems();
                $dellist=explode(',',$all['dellist']);
                foreach($dellist as $v)
                {
                    $ret=$dbCP->delProblem($cid,$v);
                }
                $msg=$dbSTM->setMsg('adminproblems',1,'删除成功','列表已更新');
                // return redirect('/rankupdate/contest'.$cid.'/admin/problems');
                return $dbSTM->retMsg(1,'删除成功','列表已更新');
            }
            if($action=='order')
            {
                $ret=$CTS->setProblemsOrder($cid,$all['problems_order_key'],$all['problems_order_by']);
                // return redirect('/rankupdate/contest'.$cid.'/admin/problems');
                return $dbSTM->retMsg(1,'修改成功','排序方式已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminproblems');
            }
        }
        if(Request::method() == 'GET')
        {
            if($action=='export')
            {
                $dbCP=new ContestProblems();
                return $dbCP->exportProblems($cid);
            }
        }
    }
    public function problemadd($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','添加题目');
        
        $contest_lang=explode(',',$dbC->getContestSetting($cid,'clang'));
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'tabbar'=>'problems',
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color'),
            'contest_lang'=>$contest_lang
        ]);
        return View::fetch();
    }
    public function problemaddapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='add')
            {
                $problemid=$all['problemid'];
                $dbCP=new ContestProblems();
                $problems=$dbCP->getProblemsOrdered($cid,'problemid','desc');
                if(is_null($problemid)||strlen($problemid)==0)
                {
                    $msg=$dbSTM->setMsg('adminproblemadd',2,'添加失败','需要输入题目序号');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/problemadd');
                    return $dbSTM->retMsg(2,'添加失败','需要输入题目序号');
                }
                else
                {
                    foreach($problems as $v)
                    {
                        if($v['problemid']==$problemid)
                        {
                            $msg=$dbSTM->setMsg('adminproblemadd',2,'添加失败','题目序号已存在');
                            // return redirect('/rankupdate/contest'.$cid.'/admin/problemadd');
                            return $dbSTM->retMsg(2,'添加失败','题目序号已存在');
                        }
                    }
                }
                $ret=$dbCP->addProblem($cid,$all['seq'],$all['lang'],$all['problemscore'],$problemid);
                $msg=$dbSTM->setMsg('adminproblems',1,'添加成功','题目列表已更新');
                // return redirect('/rankupdate/contest'.$cid.'/admin/problems');
                return $dbSTM->retMsg(1,'添加成功','题目列表已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminproblemadd');
            }
        }
    }
    public function problemeditre($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        return redirect('/rankupdate/contest'.$cid.'/admin/problems');
    }
    public function problemedit($cid,$problemid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','修改题目');
        
        $dbCP=new ContestProblems();
        $problem=$dbCP->getProblem($cid,$problemid);
        View::assign([
            'seq'=>$problem['seq'],
            'plang'=>$problem['lang'],
            'problemscore'=>$problem['problemscore'],
            'problemid'=>$problemid
        ]);
        
        $problem_lang=explode(',',$problem['lang']);
        $contest_lang=explode(',',$dbC->getContestSetting($cid,'clang'));
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'tabbar'=>'problems',
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color'),
            'problem_lang'=>$problem_lang,
            'contest_lang'=>$contest_lang
        ]);
        return View::fetch();
    }
    public function problemeditapi($cid,$action,$problemid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='edit')
            {
                $dbCP=new ContestProblems();
                $ret=$dbCP->updateProblem($cid,$all['seq'],$all['lang'],$all['problemscore'],$problemid);
                $msg=$dbSTM->setMsg('adminproblems',1,'修改成功','题目列表已更新');
                // return redirect('/rankupdate/contest'.$cid.'/admin/problems');
                return $dbSTM->retMsg(1,'修改成功','题目列表已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminproblemedit'.$problemid);
            }
        }
    }
    
    public function ranks($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','选手得分');
        
        $ranks_order_key=$CTS->getRanksOrderKey($cid);
        $ranks_order_by=$CTS->getRanksOrderBy($cid);
        $dbCR=new ContestRanks();
        $ranks=$dbCR->getRanksOrdered($cid,$ranks_order_key,$ranks_order_by);
        View::assign([
            'ranks'=>$ranks,
            'ranks_order_key'=>$ranks_order_key,
            'ranks_order_by'=>$ranks_order_by
        ]);
        
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'tabbar'=>'ranks',
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function ranksapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='calc')
            {
                $dbCR=new ContestRanks();
                $ret = $dbCR->calcRanks($cid);
                $msg=$dbSTM->setMsg('adminranks',1,'计算成功','数据已更新');
                // return redirect('/contest'.$cid.'/admin/ranks');
                return $dbSTM->retMsg(1,'计算成功','数据已更新');
            }
            if($action=='order')
            {
                $ret=$CTS->setRanksOrder($cid,$all['ranks_order_key'],$all['ranks_order_by']);
                // return redirect('/rankupdate/contest'.$cid.'/admin/ranks');
                return $dbSTM->retMsg(1,'修改成功','排序方式已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminranks');
            }
        }
        if(Request::method() == 'GET')
        {
            if($action=='export')
            {
                $dbCR=new ContestRanks();
                return $dbCR->exportRanks($cid);
            }
        }
    }
    
    public function scores($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','选手得分');
        
        $scores_order_key=$CTS->getScoresOrderKey($cid);
        $scores_order_by=$CTS->getScoresOrderBy($cid);
        $dbCS=new ContestScores();
        $scores=$dbCS->getScoresOrdered($cid,$scores_order_key,$scores_order_by);
        View::assign([
            'scores'=>$scores,
            'scores_order_key'=>$scores_order_key,
            'scores_order_by'=>$scores_order_by
        ]);
        
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'tabbar'=>'scores',
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function scoresapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='calc')
            {
                $dbCR=new ContestRanks();
                $ret = $dbCR->calcRanks($cid);
                $msg=$dbSTM->setMsg('adminscores',1,'计算成功','数据已更新');
                // return redirect('/contest'.$cid.'/admin/scores');
                return $dbSTM->retMsg(1,'计算成功','数据已更新');
            }
            if($action=='order')
            {
                $ret=$CTS->setScoresOrder($cid,$all['scores_order_key'],$all['scores_order_by']);
                // return redirect('/rankupdate/contest'.$cid.'/admin/scores');
                return $dbSTM->retMsg(1,'修改成功','排序方式已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminscores');
            }
        }
        if(Request::method() == 'GET')
        {
            if($action=='export')
            {
                $dbCS=new ContestScores();
                return $dbCS->exportScores($cid);
            }
        }
    }
    
    public function records($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','提交记录');
        
        $records_order_key=$CTS->getRecordsOrderKey($cid);
        $records_order_by=$CTS->getRecordsOrderBy($cid);
        $dbCRs=new ContestRecords();
        $records=$dbCRs->getRecordsOrdered($cid,$records_order_key,$records_order_by);
        View::assign([
            'records'=>$records,
            'records_order_key'=>$records_order_key,
            'records_order_by'=>$records_order_by
        ]);
        
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'tabbar'=>'records',
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function recordsapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='forcedel')
            {
                $dbCRs=new ContestRecords();
                $ret=$dbCRs->delContestRecords($cid);
                return $dbSTM->retMsg(1,'删除成功','列表已清除');
            }
            if($action=='del')
            {
                $dbCRs=new ContestRecords();
                $dellist=explode(',',$all['dellist']);
                foreach($dellist as $v)
                {
                    $ret=$dbCRs->delRecord($cid,$v);
                }
                $msg=$dbSTM->setMsg('adminrecords',1,'删除成功','列表已更新');
                // return redirect('/rankupdate/contest'.$cid.'/admin/records');
                return $dbSTM->retMsg(1,'删除成功','列表已更新');
            }
            if($action=='order')
            {
                $ret=$CTS->setRecordsOrder($cid,$all['records_order_key'],$all['records_order_by']);
                // return redirect('/rankupdate/contest'.$cid.'/admin/records');
                return $dbSTM->retMsg(1,'修改成功','排序方式已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminrecords');
            }
        }
        if(Request::method() == 'GET')
        {
            if($action=='export')
            {
                $dbCRs=new ContestRecords();
                return $dbCRs->exportRecords($cid);
            }
        }
    }
    public function recordadd($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','添加记录');
        
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'tabbar'=>'records',
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function recordaddapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='add')
            {
                $data_rid=$all['data_rid'];
                $dbCRs=new ContestRecords();
                $records=$dbCRs->getRecordsOrdered($cid,'data_rid','desc');
                if(is_null($data_rid)||strlen($data_rid)==0)
                {
                    $msg=$dbSTM->setMsg('adminrecordadd',2,'添加失败','需要输入记录特征值');
                    // return redirect('/rankupdate/contest'.$cid.'/admin/recordadd');
                    return $dbSTM->retMsg(2,'添加失败','需要输入记录特征值');
                }
                else
                {
                    foreach($records as $v)
                    {
                        if($v['data_rid']==$data_rid)
                        {
                            $msg=$dbSTM->setMsg('adminrecordadd',2,'添加失败','记录特征值已存在');
                            // return redirect('/rankupdate/contest'.$cid.'/admin/recordadd');
                            return $dbSTM->retMsg(2,'添加失败','记录特征值已存在');
                        }
                    }
                }
                $ret=$dbCRs->addRecord($cid,$data_rid,$all['status'],$all['dscore'],$all['problemid'],$all['loginname'],$all['sendtime']);
                $msg=$dbSTM->setMsg('adminrecords',1,'添加成功','提交记录已更新');
                // return redirect('/rankupdate/contest'.$cid.'/admin/records');
                return $dbSTM->retMsg(1,'添加成功','提交已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminrecordadd');
            }
        }
    }
    public function recordeditre($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        return redirect('/rankupdate/contest'.$cid.'/admin/records');
    }
    public function recordedit($cid,$data_rid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','修改记录');
        
        $dbCRs=new ContestRecords();
        $record=$dbCRs->getRecord($cid,$data_rid);
        View::assign([
            'data_rid'=>$data_rid,
            'status'=>$record['status'],
            'dscore'=>$record['dscore'],
            'problemid'=>$record['problemid'],
            'loginname'=>$record['loginname'],
            'sendtime'=>$record['sendtime']
        ]);
        
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'tabbar'=>'records',
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function recordeditapi($cid,$action,$data_rid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='edit')
            {
                $dbCRs=new ContestRecords();
                $ret=$dbCRs->updateRecord($cid,$data_rid,$all['status'],$all['dscore'],$all['problemid'],$all['loginname'],$all['sendtime']);
                $msg=$dbSTM->setMsg('adminrecords',1,'修改成功','提交记录已更新');
                // return redirect('/rankupdate/contest'.$cid.'/admin/records');
                return $dbSTM->retMsg(1,'修改成功','提交记录已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminrecordedit'.$data_rid);
            }
        }
    }
    
    public function settings($cid)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        View::assign('page_title','比赛设置');
        
        $dbGS=new GlobalSettings();
        View::assign([
            'cname'=>$dbC->getContestSetting($cid,'cname'),
            'cfavicon'=>$dbC->getContestSetting($cid,'cfavicon'),
            'clogo'=>$dbC->getContestSetting($cid,'clogo'),
            'ccolor'=>$dbC->getContestSetting($cid,'ccolor'),
            'caccent_color'=>$dbC->getContestSetting($cid,'caccent_color'),
            'enable'=>$dbC->getContestSetting($cid,'enable'),
            'clang'=>$dbC->getContestSetting($cid,'clang')
        ]);
        View::assign([
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        $color_list=['red','pink','purple','deep-purple','indigo','blue','light-blue','cyan','teal','green','light-green','lime','yellow','amber','orange','deep-orange','brown','grey','blue-grey'];
        $accent_color_list=['red','pink','purple','deep-purple','indigo','blue','light-blue','cyan','teal','green','light-green','lime','yellow','amber','orange','deep-orange'];
        $lang_list=['Cpp','C','Python'];
        $contest_lang=explode(',',$dbC->getContestSetting($cid,'clang'));
        View::assign([
            'color_list'=>$color_list,
            'accent_color_list'=>$accent_color_list,
            'lang_list'=>$lang_list,
            'contest_lang'=>$contest_lang
        ]);
        
        View::assign([
            'cid'=>$cid,
            'logined'=>true,
            'tabbar'=>'settings',
            'contest_name'=>$dbC->getSetting($cid,'name'),
            'contest_favicon'=>$dbC->getSetting($cid,'favicon'),
            'contest_logo'=>$dbC->getSetting($cid,'logo'),
            'contest_color'=>$dbC->getSetting($cid,'color'),
            'contest_accent_color'=>$dbC->getSetting($cid,'accent_color')
        ]);
        return View::fetch();
    }
    public function settingsapi($cid,$action)
    {
        $dbC=new Contests();
        $visitable=$dbC->enable($cid);
        if(!$visitable)return redirect('/rankupdate/visitwrong');
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isCA = $CTS->isContestAdmin($cid);
        if($isA&&(!$isCA))
            return redirect('/rankupdate/contest'.$cid.'/admin/error');
        if(!$isCA)
            return redirect('/rankupdate/contest'.$cid.'/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='edit')
            {
                $cname=$all['cname'];
                $clang=$all['clang'];
                $ccolor=$all['ccolor'];
                $caccent_color=$all['caccent_color'];
                
                $ret=$dbC->setContestSetting($cid,'cname',$cname);
                $ret=$dbC->setContestSetting($cid,'clang',$clang);
                $ret=$dbC->setContestSetting($cid,'ccolor',$ccolor);
                $ret=$dbC->setContestSetting($cid,'caccent_color',$caccent_color);
                $msg=$dbSTM->setMsg('adminsettings',1,'修改成功','比赛设置已更新');
                // return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                return $dbSTM->retMsg(1,'修改成功','比赛设置已更新');
            }
            if($action=='uploadfavicon')
            {
                $file = request()->file('imagefavicon');
                if(empty($file)){
                    $msg=$dbSTM->setMsg('adminsettings',2,'上传失败','未选择任何文件');
                    return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','未选择任何文件');
                }
                $extension=$file->extension();
                if(!in_array($extension,array('ico','jpeg','jpg','png'))){
                    $msg=$dbSTM->setMsg('adminsettings',2,'上传失败','比赛网标的文件扩展名仅能为ico、jpeg、jpg和png中的一个');
                    return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','比赛网标的文件扩展名仅能为ico、jpeg、jpg和png中的一个');
                }
                if(filesize($file)/1024/1024>1){
                    $msg=$dbSTM->setMsg('adminsettings',2,'上传失败','比赛网标的文件大小在1MB以内');
                    return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','比赛网标的文件大小在1MB以内');
                }
                $saveName = Filesystem::disk('picture')->putFile('favicon',$file,'md5');
                if (!empty($saveName)) {
                    $saveName = "/rankupdate/uploads/".str_replace('\\', "/", $saveName);
                    $ret=$dbC->setContestSetting($cid,'cfavicon',$saveName);
                    $msg=$dbSTM->setMsg('adminsettings',1,'上传成功','可能需要清除缓存后才能更新');
                    return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                    // return $dbSTM->retMsg(1,'上传成功','可能需要清除缓存后才能更新');
                }
            }
            if($action=='usefavicon')
            {
                $ret=$dbC->setContestSetting($cid,'cfavicon','');
                $msg=$dbSTM->setMsg('adminsettings',1,'修改成功','可能需要清除缓存后才能更新');
                // return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                return $dbSTM->retMsg(1,'修改成功','可能需要清除缓存后才能更新');
            }
            if($action=='uploadlogo')
            {
                $file = request()->file('imagelogo');
                if(empty($file)){
                    $msg=$dbSTM->setMsg('adminsettings',2,'上传失败','未选择任何文件');
                    return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','未选择任何文件');
                }
                $extension=$file->extension();
                if(!in_array($extension,array('jpeg','jpg','png'))){
                    $msg=$dbSTM->setMsg('adminsettings',2,'上传失败','比赛图标的文件扩展名仅能为jpeg、jpg和png中的一个');
                    return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','比赛图标的文件扩展名仅能为jpeg、jpg和png中的一个');
                }
                if(filesize($file)/1024/1024>1){
                    $msg=$dbSTM->setMsg('adminsettings',2,'上传失败','比赛图标的文件大小在1MB以内');
                    return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','比赛图标的文件大小在1MB以内');
                }
                $saveName = Filesystem::disk('picture')->putFile('logo',$file,'md5');
                if (!empty($saveName)) {
                    $saveName = "/rankupdate/uploads/".str_replace('\\', "/", $saveName);
                    $ret=$dbC->setContestSetting($cid,'clogo',$saveName);
                    $msg=$dbSTM->setMsg('adminsettings',1,'上传成功','可能需要清除缓存后才能更新');
                    return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                    // return $dbSTM->retMsg(1,'上传成功','可能需要清除缓存后才能更新');
                }
            }
            if($action=='uselogo')
            {
                $ret=$dbC->setContestSetting($cid,'clogo','');
                $msg=$dbSTM->setMsg('adminsettings',1,'修改成功','可能需要清除缓存后才能更新');
                // return redirect('/rankupdate/contest'.$cid.'/admin/settings');
                return $dbSTM->retMsg(1,'修改成功','可能需要清除缓存后才能更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('adminsettings');
            }
        }
    }
}
