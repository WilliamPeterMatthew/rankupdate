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

class Admin
{
    public function index()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if($isGA)
            return redirect('/rankupdate/admin/settings');
        return redirect('/rankupdate/admin/login');
    }
    
    public function error()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isGA)
            return redirect('/rankupdate/admin/settings');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','管理错误');
        
        if($isA)
        {
            $aid = $CTS->getAid();
            View::assign([
                'usertype'=>'admin',
                'aid'=>$aid
            ]);
        }
        
        $dbGS=new GlobalSettings();
        View::assign([
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function errorapi($action)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isGA)
            return redirect('/rankupdate/admin/settings');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'GET')
        {
            if($action=='logout')
            {
                if($isA)
                    $ret = $CTS->delAid();
                return redirect('/rankupdate/admin/login');
            }
        }
    }
    
    public function login()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if($isGA)
            return redirect('/rankupdate/admin/settings');
        View::assign('page_title','管理登录');
        
        $dbGS=new GlobalSettings();
        View::assign([
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function loginapi($action)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if($isGA)
            return redirect('/rankupdate/admin/settings');
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
                    //$msg=$dbSTM->setMsg('globallogin',0,'登录成功','登录成功');
                    // return redirect('/rankupdate/admin');
                    return $dbSTM->retMsg(0,'登录成功','登录成功');
                }
                else
                {
                    $msg=$dbSTM->setMsg('globallogin',1,'登录错误','请输入正确的登录名称和登录密码');
                    // return redirect('/rankupdate/admin/login');
                    return $dbSTM->retMsg(1,'登录错误','请输入正确的登录名称和登录密码');
                }
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('globallogin');
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
                    //$msg=$dbSTM->setMsg('globallogin',0,'登录成功','登录成功');
                    return redirect('/rankupdate/admin');
                }
                else
                {
                    $msg=$dbSTM->setMsg('globallogin',1,'登录错误','请输入正确的登录名称和登录密码');
                    return redirect('/rankupdate/admin/login');
                }
            }
        }
    }
    
    public function logout()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','管理退出登录');
        
        if($isGA)
        {
            $aid = $CTS->getAid();
            View::assign([
                'usertype'=>'admin',
                'aid'=>$aid
            ]);
        }
        
        $dbGS=new GlobalSettings();
        View::assign([
            'logined'=>true,
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function logoutapi($action)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'GET')
        {
            if($action=='logout')
            {
                $ret = $CTS->delAid();
                return redirect('/rankupdate/admin/login');
            }
        }
    }
    
    public function profile()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','管理员档案');
        
        $aid = $CTS->getAid();
        $dbA = new Admins();
        $admin = $dbA->getProfile($aid);
        View::assign([
            'aname'=>$admin['aname'],
            'loginname'=>$admin['loginname'],
            'permission'=>$admin['permission']
        ]);
        
        $dbGS=new GlobalSettings();
        View::assign([
            'logined'=>true,
            'profile'=>true,
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function profileapi($action)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
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
                    $msg=$dbSTM->setMsg('globalprofile',1,'修改成功','管理员名称已更新');
                    // return redirect('/rankupdate/admin/profile');
                    return $dbSTM->retMsg(1,'修改成功','管理员名称已更新');
                }
                else
                {
                    $msg=$dbSTM->setMsg('globalprofile',2,'修改错误','请输入正确的新管理员名称');
                    // return redirect('/rankupdate/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','请输入正确的新管理员名称');
                }
            }
            if($action=='loginname')
            {
                $dbA = new Admins();
                $ret = $dbA->tryLoginname($aid,$all['loginname']);
                if(!$ret)
                {
                    $msg=$dbSTM->setMsg('globalprofile',2,'修改错误','新的登录名称与其他管理员冲突');
                    // return redirect('/rankupdate/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','新的登录名称与其他管理员冲突');
                }
                $ret = $dbA->modLoginname($aid,$all['loginname']);
                if($ret)
                {
                    $msg=$dbSTM->setMsg('globalprofile',1,'修改成功','登录名称已更新');
                    // return redirect('/rankupdate/admin/profile');
                    return $dbSTM->retMsg(1,'修改成功','登录名称已更新');
                }
                else
                {
                    $msg=$dbSTM->setMsg('globalprofile',2,'修改错误','请输入由数字、大小写字母和下划线“_”组成的新的登录名称');
                    // return redirect('/rankupdate/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','请输入由数字、大小写字母和下划线“_”组成的新的登录名称');
                }
            }
            if($action=='password')
            {
                $dbA = new Admins();
                $ret = $dbA->tryPassword($aid,$all['passwordpre']);
                if(!$ret)
                {
                    $msg=$dbSTM->setMsg('globalprofile',2,'修改错误','请输入正确的旧登录密码');
                    // return redirect('/rankupdate/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','请输入正确的旧登录密码');
                }
                $ret = $dbA->modPassword($aid,$all['password']);
                if($ret)
                {
                    $msg=$dbSTM->setMsg('globalprofile',1,'修改成功','登录密码已更新');
                    // return redirect('/rankupdate/admin/profile');
                    return $dbSTM->retMsg(1,'修改成功','登录密码已更新');
                }
                else
                {
                    $msg=$dbSTM->setMsg('globalprofile',2,'修改错误','请输入正确的新登录密码');
                    // return redirect('/rankupdate/admin/profile');
                    return $dbSTM->retMsg(2,'修改错误','请输入正确的新登录密码');
                }
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('globalprofile');
            }
        }
    }
    
    public function admins()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','管理员列表');
        
        $admins_order_key=$CTS->getAdminsOrderKey();
        $admins_order_by=$CTS->getAdminsOrderBy();
        $dbA=new Admins();
        $admins=$dbA->getAdminsOrdered($admins_order_key,$admins_order_by);
        View::assign([
            'admins'=>$admins,
            'admins_order_key'=>$admins_order_key,
            'admins_order_by'=>$admins_order_by
        ]);
        
        $dbGS=new GlobalSettings();
        View::assign([
            'logined'=>true,
            'tabbar'=>'admins',
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function adminsapi($action)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='del')
            {
                $dbA=new Admins();
                $dellist=json_decode($all['dellist']);
                foreach($dellist as $v)
                {
                    $ret=$dbA->delAdmin($v);
                }
                $msg=$dbSTM->setMsg('globaladmins',1,'删除成功','列表已更新');
                // return redirect('/rankupdate/admin/admins');
                return $dbSTM->retMsg(1,'删除成功','列表已更新');
            }
            if($action=='order')
            {
                $ret=$CTS->setAdminsOrder($all['admins_order_key'],$all['admins_order_by']);
                // return redirect('/rankupdate/admin/admins');
                return $dbSTM->retMsg(1,'修改成功','排序方式已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('globaladmins');
            }
        }
    }
    public function adminadd()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','添加管理员');
        
        $dbGS=new GlobalSettings();
        View::assign([
            'logined'=>true,
            'tabbar'=>'admins',
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function adminaddapi($action)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='add')
            {
                $aid=$all['aid'];
                $loginname=$all['loginname'];
                $password=$all['password'];
                $dbA=new Admins();
                $admins=$dbA->getAdminsOrdered('aid','desc');
                if(is_null($aid)||strlen($aid)==0)
                {
                    if(count($admins)==0)
                        $aid=1;
                    else
                        $aid=$admins[0]['aid']+1;
                }
                else
                {
                    foreach($admins as $v)
                    {
                        if($v['aid']==$aid)
                        {
                            $msg=$dbSTM->setMsg('globaladminadd',2,'添加失败','管理员序号已存在');
                            // return redirect('/rankupdate/admin/adminadd');
                            return $dbSTM->retMsg(2,'添加失败','管理员序号已存在');
                        }
                    }
                }
                $admins=$dbA->getAdminsOrdered('loginname','desc');
                if(is_null($loginname)||strlen($loginname)==0)
                {
                    $flag=true;
                    while($flag)
                    {
                        $loginname=$dbA->genLoginname();
                        $flag=false;
                        foreach($admins as $v)
                        {
                            if($v['loginname']==$loginname)
                            {
                                $flag=true;
                                break;
                            }
                        }
                    }
                }
                else
                {
                    foreach($admins as $v)
                    {
                        if($v['loginname']==$loginname)
                        {
                            $msg=$dbSTM->setMsg('globaladminadd',2,'添加失败','登录码已存在');
                            // return redirect('/rankupdate/admin/adminadd');
                            return $dbSTM->retMsg(2,'添加失败','登录码已存在');
                        }
                    }
                }
                if(is_null($password)||strlen($password)==0)
                {
                    $password='12345678';
                }
                $ret=$dbA->addAdmin($aid,$all['aname'],$loginname,$all['enable'],$all['permission']);
                $ret=$dbA->modPassword($aid,$password);
                $msg=$dbSTM->setMsg('globaladmins',1,'添加成功','管理员列表已更新');
                // return redirect('/rankupdate/admin/admins');
                return $dbSTM->retMsg(1,'添加成功','管理员列表已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('globaladminadd');
            }
        }
    }
    public function admineditre()
    {
        return redirect('/rankupdate/admin/admins');
    }
    public function adminedit($aid)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','修改管理员');
        
        $dbA=new Admins();
        $admin=$dbA->getAdmin($aid);
        View::assign([
            'aid'=>$aid,
            'loginname'=>$admin['loginname'],
            'aname'=>$admin['aname'],
            'enable'=>$admin['enable'],
            'permission'=>$admin['permission']
        ]);
        
        $dbGS=new GlobalSettings();
        View::assign([
            'logined'=>true,
            'tabbar'=>'admins',
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function admineditapi($action,$aid)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='edit')
            {
                $aid=(int)$aid;
                $loginname=$all['loginname'];
                $password=$all['password'];
                $dbA=new Admins();
                $admins=$dbA->getAdminsOrdered('loginname','desc');
                foreach($admins as $v)
                {
                    if($v['loginname']==$loginname&&$v['aid']!=$aid)
                    {
                        $msg=$dbSTM->setMsg('globaladminedit/'.$aid,2,'修改失败','登录码已存在');
                        // return redirect('/rankupdate/admin/adminedit/'.$aid);
                        return $dbSTM->retMsg(2,'修改失败','登录码已存在');
                    }
                }
                $ret=$dbA->updateAdmin($aid,$all['aname'],$loginname,$all['enable'],$all['permission']);
                if(!is_null($password)&&strlen($password)!=0)
                {
                    $ret=$dbA->modPassword($aid,$password);
                }
                $msg=$dbSTM->setMsg('globaladmins',1,'修改成功','管理员列表已更新');
                // return redirect('/rankupdate/admin/admins');
                return $dbSTM->retMsg(1,'修改成功','管理员列表已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('globaladminedit/'.$aid);
            }
        }
    }
    
    public function contests()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','比赛列表');
        
        $contests_order_key=$CTS->getContestsOrderKey();
        $contests_order_by=$CTS->getContestsOrderBy();
        $dbC=new Contests();
        $contests=$dbC->getContestsOrdered($contests_order_key,$contests_order_by);
        View::assign([
            'contests'=>$contests,
            'contests_order_key'=>$contests_order_key,
            'contests_order_by'=>$contests_order_by
        ]);
        
        $dbGS=new GlobalSettings();
        View::assign([
            'logined'=>true,
            'tabbar'=>'contests',
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function contestsapi($action)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='del')
            {
                $dbC=new Contests();
                $dbA=new Admins();
                $dbCR=new ContestRanks();
                $dbCS=new ContestScores();
                $dbCRs=new ContestRecords();
                $dbCP=new ContestProblems();
                $dellist=explode(',',$all['dellist']);
                foreach($dellist as $v)
                {
                    $ret=$dbCR->delContestRanks($v);
                    $ret=$dbCS->delContestScores($v);
                    $ret=$dbCRs->delContestRecords($v);
                    $ret=$dbCP->delContestProblems($v);
                    $ret=$dbA->delContestAdmin($v);
                    $ret=$dbC->delContest($v);
                }
                $msg=$dbSTM->setMsg('globalcontests',1,'删除成功','列表已更新');
                // return redirect('/rankupdate/admin/contests');
                return $dbSTM->retMsg(1,'删除成功','列表已更新');
            }
            if($action=='order')
            {
                $ret=$CTS->setContestsOrder($all['contests_order_key'],$all['contests_order_by']);
                // return redirect('/rankupdate/admin/contests');
                return $dbSTM->retMsg(1,'修改成功','排序方式已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('globalcontests');
            }
        }
    }
    public function contestadd()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','添加比赛');
        
        $dbGS=new GlobalSettings();
        View::assign([
            'default_screen_page_pic'=>$dbGS->getSetting('default_screen_page_pic'),
            'default_screen_background_page_pic'=>$dbGS->getSetting('default_screen_background_page_pic')
        ]);
        $color_list=['red','pink','purple','deep-purple','indigo','blue','light-blue','cyan','teal','green','light-green','lime','yellow','amber','orange','deep-orange','brown','grey','blue-grey'];
        $accent_color_list=['red','pink','purple','deep-purple','indigo','blue','light-blue','cyan','teal','green','light-green','lime','yellow','amber','orange','deep-orange'];
        $lang_list=['Cpp','C','Python'];
        View::assign([
            'color_list'=>$color_list,
            'accent_color_list'=>$accent_color_list,
            'lang_list'=>$lang_list
        ]);
        
        $dbGS=new GlobalSettings();
        View::assign([
            'logined'=>true,
            'tabbar'=>'contests',
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function contestaddapi($action)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='add')
            {
                $cid=$all['cid'];
                $dbC=new Contests();
                $contests=$dbC->getContestsOrdered('cid','desc');
                if(is_null($cid)||strlen($cid)==0)
                {
                    $msg=$dbSTM->setMsg('globalcontestadd',2,'添加失败','需要输入比赛序号');
                    // return redirect('/rankupdate/admin/contestadd');
                    return $dbSTM->retMsg(2,'添加失败','需要输入比赛序号');
                }
                else
                {
                    foreach($contests as $v)
                    {
                        if($v['cid']==$cid)
                        {
                            $msg=$dbSTM->setMsg('globalcontestadd',2,'添加失败','比赛序号已存在');
                            // return redirect('/rankupdate/admin/contestadd');
                            return $dbSTM->retMsg(2,'添加失败','比赛序号已存在');
                        }
                    }
                }
                $ret=$dbC->addContest($cid,$all['cname'],$all['ccolor'],$all['caccent_color'],$all['enable'],$all['clang']);
                $msg=$dbSTM->setMsg('globalcontests',1,'修改成功','比赛列表已更新');
                // return redirect('/rankupdate/admin/contests');
                return $dbSTM->retMsg(1,'修改成功','比赛列表已更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('globalcontestadd');
            }
        }
    }
    public function contesteditre()
    {
        return redirect('/rankupdate/admin/contests');
    }
    public function contestedit($cid)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','修改比赛');
        
        $dbC=new Contests();
        $dbGS=new GlobalSettings();
        $contest=$dbC->getContest($cid);
        View::assign([
            'cid'=>$cid,
            'cname'=>$contest['cname'],
            'cfavicon'=>$contest['cfavicon'],
            'clogo'=>$contest['clogo'],
            'ccolor'=>$contest['ccolor'],
            'caccent_color'=>$contest['caccent_color'],
            'enable'=>$contest['enable'],
            'clang'=>$contest['clang']
        ]);
        $color_list=['red','pink','purple','deep-purple','indigo','blue','light-blue','cyan','teal','green','light-green','lime','yellow','amber','orange','deep-orange','brown','grey','blue-grey'];
        $accent_color_list=['red','pink','purple','deep-purple','indigo','blue','light-blue','cyan','teal','green','light-green','lime','yellow','amber','orange','deep-orange'];
        $lang_list=['Cpp','C','Python'];
        $contest_lang=explode(',',$contest['clang']);
        View::assign([
            'color_list'=>$color_list,
            'accent_color_list'=>$accent_color_list,
            'lang_list'=>$lang_list,
            'contest_lang'=>$contest_lang
        ]);
        
        View::assign([
            'logined'=>true,
            'tabbar'=>'contests',
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function contesteditapi($action,$cid)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='edit')
            {
                $dbC=new Contests();
                $ret=$dbC->updateContest($cid,$all['cname'],$all['ccolor'],$all['caccent_color'],$all['enable'],$all['clang']);
                $msg=$dbSTM->setMsg('globalcontests',1,'修改成功','比赛列表已更新');
                // return redirect('/rankupdate/admin/contests');
                return $dbSTM->retMsg(1,'修改成功','比赛列表已更新');
            }
            if($action=='uploadfavicon')
            {
                $file = request()->file('imagefavicon');
                if(empty($file)){
                    $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'上传失败','未选择任何文件');
                    return redirect('/rankupdate/admin/contestedit'.$cid);
                    // return $dbSTM->retMsg(2,'上传失败','未选择任何文件');
                }
                $extension=$file->extension();
                if(!in_array($extension,array('ico','jpeg','jpg','png'))){
                    $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'上传失败','比赛网标的文件扩展名仅能为ico、jpeg、jpg和png中的一个');
                    return redirect('/rankupdate/admin/contestedit'.$cid);
                    // return $dbSTM->retMsg(2,'上传失败','比赛网标的文件扩展名仅能为ico、jpeg、jpg和png中的一个');
                }
                if(filesize($file)/1024/1024>1){
                    $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'上传失败','比赛网标的文件大小在1MB以内');
                    return redirect('/rankupdate/admin/contestedit'.$cid);
                    // return $dbSTM->retMsg(2,'上传失败','比赛网标的文件大小在1MB以内');
                }
                $saveName = Filesystem::disk('picture')->putFile('favicon',$file,'md5');
                if (!empty($saveName)) {
                    $saveName = "/rankupdate/uploads/".str_replace('\\', "/", $saveName);
                    $dbC=new Contests();
                    $ret=$dbC->setContestSetting($cid,'cfavicon',$saveName);
                    $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'上传成功','可能需要清除缓存后才能更新');
                    return redirect('/rankupdate/admin/contestedit'.$cid);
                    // return $dbSTM->retMsg(2,'上传成功','可能需要清除缓存后才能更新');
                }
            }
            if($action=='usefavicon')
            {
                $dbC=new Contests();
                $ret=$dbC->setContestSetting($cid,'cfavicon','');
                $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'修改成功','可能需要清除缓存后才能更新');
                // return redirect('/rankupdate/admin/contestedit'.$cid);
                return $dbSTM->retMsg(2,'修改成功','可能需要清除缓存后才能更新');
            }
            if($action=='uploadlogo')
            {
                $file = request()->file('imagelogo');
                if(empty($file)){
                    $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'上传失败','未选择任何文件');
                    return redirect('/rankupdate/admin/contestedit'.$cid);
                    // return $dbSTM->retMsg(2,'上传失败','未选择任何文件');
                }
                $extension=$file->extension();
                if(!in_array($extension,array('jpeg','jpg','png'))){
                    $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'上传失败','比赛图标的文件扩展名仅能为jpeg、jpg和png中的一个');
                    return redirect('/rankupdate/admin/contestedit'.$cid);
                    // return $dbSTM->retMsg(2,'上传失败','比赛图标的文件扩展名仅能为jpeg、jpg和png中的一个');
                }
                if(filesize($file)/1024/1024>1){
                    $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'上传失败','比赛图标的文件大小在1MB以内');
                    return redirect('/rankupdate/admin/contestedit'.$cid);
                    // return $dbSTM->retMsg(2,'上传失败','比赛图标的文件大小在1MB以内');
                }
                $saveName = Filesystem::disk('picture')->putFile('logo',$file,'md5');
                if (!empty($saveName)) {
                    $saveName = "/rankupdate/uploads/".str_replace('\\', "/", $saveName);
                    $dbC=new Contests();
                    $ret=$dbC->setContestSetting($cid,'clogo',$saveName);
                    $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'上传成功','可能需要清除缓存后才能更新');
                    return redirect('/rankupdate/admin/contestedit'.$cid);
                    // return $dbSTM->retMsg(2,'上传成功','可能需要清除缓存后才能更新');
                }
            }
            if($action=='uselogo')
            {
                $dbC=new Contests();
                $ret=$dbC->setContestSetting($cid,'clogo','');
                $msg=$dbSTM->setMsg('globalcontestedit/'.$cid,2,'修改成功','可能需要清除缓存后才能更新');
                // return redirect('/rankupdate/admin/contestedit'.$cid);
                return $dbSTM->retMsg(2,'修改成功','可能需要清除缓存后才能更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('globalcontestedit/'.$cid);
            }
        }
    }
    
    public function settings()
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        View::assign('page_title','站点设置');
        
        $dbGS=new GlobalSettings();
        View::assign([
            'sname'=>$dbGS->getGlobalSetting('site_name'),
            'surl'=>$dbGS->getGlobalSetting('site_url'),
            'sfavicon'=>$dbGS->getGlobalSetting('site_favicon'),
            'slogo'=>$dbGS->getGlobalSetting('site_logo'),
            'scolor'=>$dbGS->getGlobalSetting('site_color'),
            'saccent_color'=>$dbGS->getGlobalSetting('site_accent_color')
        ]);
        View::assign([
            'env_name'=>$dbGS->getEnvSetting('site.name'),
            'env_url'=>$dbGS->getEnvSetting('site.url'),
            'env_favicon'=>$dbGS->getEnvSetting('site.favicon'),
            'env_logo'=>$dbGS->getEnvSetting('site.logo'),
            'env_color'=>$dbGS->getEnvSetting('site.color'),
            'env_accent_color'=>$dbGS->getEnvSetting('site.accent_color')
        ]);
        $color_list=['red','pink','purple','deep-purple','indigo','blue','light-blue','cyan','teal','green','light-green','lime','yellow','amber','orange','deep-orange','brown','grey','blue-grey'];
        $accent_color_list=['red','pink','purple','deep-purple','indigo','blue','light-blue','cyan','teal','green','light-green','lime','yellow','amber','orange','deep-orange'];
        View::assign([
            'color_list'=>$color_list,
            'accent_color_list'=>$accent_color_list
        ]);
        
        View::assign([
            'logined'=>true,
            'tabbar'=>'settings',
            'site_name'=>$dbGS->getSetting('name'),
            'site_favicon'=>$dbGS->getSetting('favicon'),
            'site_logo'=>$dbGS->getSetting('logo'),
            'site_color'=>$dbGS->getSetting('color'),
            'site_accent_color'=>$dbGS->getSetting('accent_color')
        ]);
        return View::fetch();
    }
    public function settingsapi($action)
    {
        $CTS = new CookieToSession();
        $isA = $CTS->isAdmin();
        $isGA = $CTS->isGlobalAdmin();
        if($isA&&(!$isGA))
            return redirect('/rankupdate/admin/error');
        if(!$isA)
            return redirect('/rankupdate/admin/login');
        $all = Request::param();
        $dbSTM = new SessionToMsg();
        if(Request::method() == 'POST')
        {
            header('Content-Type:application/json');
            if($action=='edit')
            {
                $sname=$all['sname'];
                $surl=$all['surl'];
                $scolor=$all['scolor'];
                $saccent_color=$all['saccent_color'];
                
                $dbGS=new GlobalSettings();
                $ret=$dbGS->setGlobalSetting('site_name',$sname);
                $ret=$dbGS->setGlobalSetting('site_url',$surl);
                $ret=$dbGS->setGlobalSetting('site_color',$scolor);
                $ret=$dbGS->setGlobalSetting('site_accent_color',$saccent_color);
                $msg=$dbSTM->setMsg('globalsettings',1,'修改成功','站点设置已更新');
                // return redirect('/rankupdate/admin/settings');
                return $dbSTM->retMsg(1,'修改成功','站点设置已更新');
            }
            if($action=='uploadfavicon')
            {
                $file = request()->file('imagefavicon');
                if(empty($file)){
                    $msg=$dbSTM->setMsg('globalsettings',2,'上传失败','未选择任何文件');
                    return redirect('/rankupdate/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','未选择任何文件');
                }
                $extension=$file->extension();
                if(!in_array($extension,array('ico','jpeg','jpg','png'))){
                    $msg=$dbSTM->setMsg('globalsettings',2,'上传失败','站点网标的文件扩展名仅能为ico、jpeg、jpg和png中的一个');
                    return redirect('/rankupdate/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','站点网标的文件扩展名仅能为ico、jpeg、jpg和png中的一个');
                }
                if(filesize($file)/1024/1024>1){
                    $msg=$dbSTM->setMsg('globalsettings',2,'上传失败','站点网标的文件大小在1MB以内');
                    return redirect('/rankupdate/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','站点网标的文件大小在1MB以内');
                }
                $saveName = Filesystem::disk('public')->putFile('favicon',$file,'md5');
                if (!empty($saveName)) {
                    $saveName = "/rankupdate/storage/".str_replace('\\', "/", $saveName);
                    $dbGS=new GlobalSettings();
                    $ret=$dbGS->setGlobalSetting('site_favicon',$saveName);
                    $msg=$dbSTM->setMsg('globalsettings',1,'上传成功','可能需要清除缓存后才能更新');
                    return redirect('/rankupdate/admin/settings');
                    // return $dbSTM->retMsg(1,'上传成功','可能需要清除缓存后才能更新');
                }
            }
            if($action=='usefavicon')
            {
                $dbGS=new GlobalSettings();
                $ret=$dbGS->setGlobalSetting('site_favicon','');
                $msg=$dbSTM->setMsg('globalsettings',1,'修改成功','可能需要清除缓存后才能更新');
                // return redirect('/rankupdate/admin/settings');
                return $dbSTM->retMsg(1,'修改成功','可能需要清除缓存后才能更新');
            }
            if($action=='uploadlogo')
            {
                $file = request()->file('imagelogo');
                if(empty($file)){
                    $msg=$dbSTM->setMsg('globalsettings',2,'上传失败','未选择任何文件');
                    return redirect('/rankupdate/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','未选择任何文件');
                }
                $extension=$file->extension();
                if(!in_array($extension,array('jpeg','jpg','png'))){
                    $msg=$dbSTM->setMsg('globalsettings',2,'上传失败','站点图标的文件扩展名仅能为jpeg、jpg和png中的一个');
                    return redirect('/rankupdate/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','站点图标的文件扩展名仅能为jpeg、jpg和png中的一个');
                }
                if(filesize($file)/1024/1024>1){
                    $msg=$dbSTM->setMsg('globalsettings',2,'上传失败','站点图标的文件大小在1MB以内');
                    return redirect('/rankupdate/admin/settings');
                    // return $dbSTM->retMsg(2,'上传失败','站点图标的文件大小在1MB以内');
                }
                $saveName = Filesystem::disk('public')->putFile('logo',$file,'md5');
                if (!empty($saveName)) {
                    $saveName = "/rankupdate/storage/".str_replace('\\', "/", $saveName);
                    $dbGS=new GlobalSettings();
                    $ret=$dbGS->setGlobalSetting('site_logo',$saveName);
                    $msg=$dbSTM->setMsg('globalsettings',1,'上传成功','可能需要清除缓存后才能更新');
                    return redirect('/rankupdate/admin/settings');
                    // return $dbSTM->retMsg(1,'上传成功','可能需要清除缓存后才能更新');
                }
            }
            if($action=='uselogo')
            {
                $dbGS=new GlobalSettings();
                $ret=$dbGS->setGlobalSetting('site_logo','');
                $msg=$dbSTM->setMsg('globalsettings',1,'修改成功','可能需要清除缓存后才能更新');
                // return redirect('/rankupdate/admin/settings');
                return $dbSTM->retMsg(1,'修改成功','可能需要清除缓存后才能更新');
            }
            if($action=='getmsg')
            {
                return $dbSTM->popMsg('globalsettings');
            }
        }
    }
}
