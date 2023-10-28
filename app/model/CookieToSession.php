<?php
namespace app\model;
use think\Model;
use think\facade\Cookie;
use think\facade\Session;

use app\model\Admins;
use app\model\Contests;

class CookieToSession extends Model
{
    protected $name = 'Contests';//只是占位用的
    protected $pk = 'cid';
    public function isAdmin()
    {
        if(Session::has('aid'))return true;
        if(Cookie::has('aid'))
        {
            Session::set('aid',Cookie::get('aid'));
            return true;
        }
        return false;
    }
    public function getAid()
    {
        if(Session::has('aid'))return Session::get('aid');
        if(Cookie::has('aid'))
        {
            Session::set('aid',Cookie::get('aid'));
            return Session::get('aid');
        }
        return false;
    }
    public function setAid($aid)
    {
        Cookie::set('aid',$aid);
        Session::set('aid',$aid);
        return true;
    }
    public function delAid()
    {
        Cookie::delete('aid');
        Session::delete('aid');
        $dbC = new Contests();
        $Cids = $dbC->getCids();
        foreach($Cids as $c)
        {
            Cookie::delete('problems_order_key'.$c);
            Session::delete('problems_order_key'.$c);
            Cookie::delete('problems_order_by'.$c);
            Session::delete('problems_order_by'.$c);
            Cookie::delete('records_order_key'.$c);
            Session::delete('records_order_key'.$c);
            Cookie::delete('records_order_by'.$c);
            Session::delete('records_order_by'.$c);
            Cookie::delete('scores_order_key'.$c);
            Session::delete('scores_order_key'.$c);
            Cookie::delete('scores_order_by'.$c);
            Session::delete('scores_order_by'.$c);
            Cookie::delete('ranks_order_key'.$c);
            Session::delete('ranks_order_key'.$c);
            Cookie::delete('ranks_order_by'.$c);
            Session::delete('ranks_order_by'.$c);
        }
        Cookie::delete('admins_order_key');
        Session::delete('admins_order_key');
        Cookie::delete('admins_order_by');
        Session::delete('admins_order_by');
        Cookie::delete('contests_order_key');
        Session::delete('contests_order_key');
        Cookie::delete('contests_order_by');
        Session::delete('contests_order_by');
        return true;
    }
    public function isGlobalAdmin()
    {
        if(Session::has('aid'))
        {
            $aid = Session::get('aid');
            $dbA = new Admins();
            $ret = $dbA->pdGlobalPermission($aid);
            return $ret;
        }
        if(Cookie::has('aid'))
        {
            Session::set('aid',Cookie::get('aid'));
            $aid = Session::get('aid');
            $dbA = new Admins();
            $ret = $dbA->pdGlobalPermission($aid);
            return $ret;
        }
        return false;
    }
    public function isContestAdmin($cid)
    {
        if(Session::has('aid'))
        {
            $aid = Session::get('aid');
            $dbA = new Admins();
            $ret = $dbA->pdPermission($aid,$cid);
            return $ret;
        }
        if(Cookie::has('aid'))
        {
            Session::set('aid',Cookie::get('aid'));
            Session::set('problems_order_key'.$cid,Cookie::get('problems_order_key'.$cid));
            Session::set('problems_order_by'.$cid,Cookie::get('problems_order_by'.$cid));
            Session::set('records_order_key'.$cid,Cookie::get('records_order_key'.$cid));
            Session::set('records_order_by'.$cid,Cookie::get('records_order_by'.$cid));
            Session::set('scores_order_key'.$cid,Cookie::get('scores_order_key'.$cid));
            Session::set('scores_order_by'.$cid,Cookie::get('scores_order_by'.$cid));
            Session::set('ranks_order_key'.$cid,Cookie::get('ranks_order_key'.$cid));
            Session::set('ranks_order_by'.$cid,Cookie::get('ranks_order_by'.$cid));
            Session::set('admins_order_key',Cookie::get('admins_order_key'));
            Session::set('admins_order_by',Cookie::get('admins_order_by'));
            Session::set('contests_order_key',Cookie::get('contests_order_key'));
            Session::set('contests_order_by',Cookie::get('contests_order_by'));
            $aid = Session::get('aid');
            $dbA = new Admins();
            $ret = $dbA->pdPermission($aid,$cid);
            return $ret;
        }
        return false;
    }
    public function getProblemsOrderKey($cid)
    {
        if(Session::has('problems_order_key'.$cid))return Session::get('problems_order_key'.$cid);
        else if(Cookie::has('problems_order_key'.$cid))
        {
            Session::set('problems_order_key'.$cid,Cookie::get('problems_order_key'.$cid));
            Session::set('problems_order_by'.$cid,Cookie::get('problems_order_by'.$cid));
            return Session::get('problems_order_key'.$cid);
        }
        else
        {
            Cookie::set('problems_order_key'.$cid,'seq');
            Session::set('problems_order_key'.$cid,'seq');
            Cookie::set('problems_order_by'.$cid,'asc');
            Session::set('problems_order_by'.$cid,'asc');
            return Session::get('problems_order_key'.$cid);
        }
    }
    public function getProblemsOrderBy($cid)
    {
        if(Session::has('problems_order_by'.$cid))return Session::get('problems_order_by'.$cid);
        else if(Cookie::has('problems_order_by'.$cid))
        {
            Session::set('problems_order_key'.$cid,Cookie::get('problems_order_key'.$cid));
            Session::set('problems_order_by'.$cid,Cookie::get('problems_order_by'.$cid));
            return Session::get('problems_order_by'.$cid);
        }
        else
        {
            Cookie::set('problems_order_key'.$cid,'seq');
            Session::set('problems_order_key'.$cid,'seq');
            Cookie::set('problems_order_by'.$cid,'asc');
            Session::set('problems_order_by'.$cid,'asc');
            return Session::get('problems_order_by'.$cid);
        }
    }
    public function setProblemsOrder($cid,$problems_order_key,$problems_order_by)
    {
        Cookie::set('problems_order_key'.$cid,$problems_order_key);
        Session::set('problems_order_key'.$cid,$problems_order_key);
        Cookie::set('problems_order_by'.$cid,$problems_order_by);
        Session::set('problems_order_by'.$cid,$problems_order_by);
        return true;
    }
    public function getRecordsOrderKey($cid)
    {
        if(Session::has('records_order_key'.$cid))return Session::get('records_order_key'.$cid);
        else if(Cookie::has('records_order_key'.$cid))
        {
            Session::set('records_order_key'.$cid,Cookie::get('records_order_key'.$cid));
            Session::set('records_order_by'.$cid,Cookie::get('records_order_by'.$cid));
            return Session::get('records_order_key'.$cid);
        }
        else
        {
            Cookie::set('records_order_key'.$cid,'data_rid');
            Session::set('records_order_key'.$cid,'data_rid');
            Cookie::set('records_order_by'.$cid,'asc');
            Session::set('records_order_by'.$cid,'asc');
            return Session::get('records_order_key'.$cid);
        }
    }
    public function getRecordsOrderBy($cid)
    {
        if(Session::has('records_order_by'.$cid))return Session::get('records_order_by'.$cid);
        else if(Cookie::has('records_order_by'.$cid))
        {
            Session::set('records_order_key'.$cid,Cookie::get('records_order_key'.$cid));
            Session::set('records_order_by'.$cid,Cookie::get('records_order_by'.$cid));
            return Session::get('records_order_by'.$cid);
        }
        else
        {
            Cookie::set('records_order_key'.$cid,'data_rid');
            Session::set('records_order_key'.$cid,'data_rid');
            Cookie::set('records_order_by'.$cid,'asc');
            Session::set('records_order_by'.$cid,'asc');
            return Session::get('records_order_by'.$cid);
        }
    }
    public function setRecordsOrder($cid,$records_order_key,$records_order_by)
    {
        Cookie::set('records_order_key'.$cid,$records_order_key);
        Session::set('records_order_key'.$cid,$records_order_key);
        Cookie::set('records_order_by'.$cid,$records_order_by);
        Session::set('records_order_by'.$cid,$records_order_by);
        return true;
    }
    public function getScoresOrderKey($cid)
    {
        if(Session::has('scores_order_key'.$cid))return Session::get('scores_order_key'.$cid);
        else if(Cookie::has('scores_order_key'.$cid))
        {
            Session::set('scores_order_key'.$cid,Cookie::get('scores_order_key'.$cid));
            Session::set('scores_order_by'.$cid,Cookie::get('scores_order_by'.$cid));
            return Session::get('scores_order_key'.$cid);
        }
        else
        {
            Cookie::set('scores_order_key'.$cid,'loginname');
            Session::set('scores_order_key'.$cid,'loginname');
            Cookie::set('scores_order_by'.$cid,'asc');
            Session::set('scores_order_by'.$cid,'asc');
            return Session::get('scores_order_key'.$cid);
        }
    }
    public function getScoresOrderBy($cid)
    {
        if(Session::has('scores_order_by'.$cid))return Session::get('scores_order_by'.$cid);
        else if(Cookie::has('scores_order_by'.$cid))
        {
            Session::set('scores_order_key'.$cid,Cookie::get('scores_order_key'.$cid));
            Session::set('scores_order_by'.$cid,Cookie::get('scores_order_by'.$cid));
            return Session::get('scores_order_by'.$cid);
        }
        else
        {
            Cookie::set('scores_order_key'.$cid,'loginname');
            Session::set('scores_order_key'.$cid,'loginname');
            Cookie::set('scores_order_by'.$cid,'asc');
            Session::set('scores_order_by'.$cid,'asc');
            return Session::get('scores_order_by'.$cid);
        }
    }
    public function setScoresOrder($cid,$scores_order_key,$scores_order_by)
    {
        Cookie::set('scores_order_key'.$cid,$scores_order_key);
        Session::set('scores_order_key'.$cid,$scores_order_key);
        Cookie::set('scores_order_by'.$cid,$scores_order_by);
        Session::set('scores_order_by'.$cid,$scores_order_by);
        return true;
    }
    public function getRanksOrderKey($cid)
    {
        if(Session::has('ranks_order_key'.$cid))return Session::get('ranks_order_key'.$cid);
        else if(Cookie::has('ranks_order_key'.$cid))
        {
            Session::set('ranks_order_key'.$cid,Cookie::get('ranks_order_key'.$cid));
            Session::set('ranks_order_by'.$cid,Cookie::get('ranks_order_by'.$cid));
            return Session::get('ranks_order_key'.$cid);
        }
        else
        {
            Cookie::set('ranks_order_key'.$cid,'loginname');
            Session::set('ranks_order_key'.$cid,'loginname');
            Cookie::set('ranks_order_by'.$cid,'asc');
            Session::set('ranks_order_by'.$cid,'asc');
            return Session::get('ranks_order_key'.$cid);
        }
    }
    public function getRanksOrderBy($cid)
    {
        if(Session::has('ranks_order_by'.$cid))return Session::get('ranks_order_by'.$cid);
        else if(Cookie::has('ranks_order_by'.$cid))
        {
            Session::set('ranks_order_key'.$cid,Cookie::get('ranks_order_key'.$cid));
            Session::set('ranks_order_by'.$cid,Cookie::get('ranks_order_by'.$cid));
            return Session::get('ranks_order_by'.$cid);
        }
        else
        {
            Cookie::set('ranks_order_key'.$cid,'loginname');
            Session::set('ranks_order_key'.$cid,'loginname');
            Cookie::set('ranks_order_by'.$cid,'asc');
            Session::set('ranks_order_by'.$cid,'asc');
            return Session::get('ranks_order_by'.$cid);
        }
    }
    public function setRanksOrder($cid,$ranks_order_key,$ranks_order_by)
    {
        Cookie::set('ranks_order_key'.$cid,$ranks_order_key);
        Session::set('ranks_order_key'.$cid,$ranks_order_key);
        Cookie::set('ranks_order_by'.$cid,$ranks_order_by);
        Session::set('ranks_order_by'.$cid,$ranks_order_by);
        return true;
    }
    public function getAdminsOrderKey()
    {
        if(Session::has('admins_order_key'))return Session::get('admins_order_key');
        else if(Cookie::has('admins_order_key'))
        {
            Session::set('admins_order_key',Cookie::get('admins_order_key'));
            Session::set('admins_order_by',Cookie::get('admins_order_by'));
            return Session::get('admins_order_key');
        }
        else
        {
            Cookie::set('admins_order_key','aid');
            Session::set('admins_order_key','aid');
            Cookie::set('admins_order_by','asc');
            Session::set('admins_order_by','asc');
            return Session::get('admins_order_key');
        }
    }
    public function getAdminsOrderBy()
    {
        if(Session::has('admins_order_by'))return Session::get('admins_order_by');
        else if(Cookie::has('admins_order_by'))
        {
            Session::set('admins_order_key',Cookie::get('admins_order_key'));
            Session::set('admins_order_by',Cookie::get('admins_order_by'));
            return Session::get('admins_order_by');
        }
        else
        {
            Cookie::set('admins_order_key','aid');
            Session::set('admins_order_key','aid');
            Cookie::set('admins_order_by','asc');
            Session::set('admins_order_by','asc');
            return Session::get('admins_order_by');
        }
    }
    public function setAdminsOrder($admins_order_key,$admins_order_by)
    {
        Cookie::set('admins_order_key',$admins_order_key);
        Session::set('admins_order_key',$admins_order_key);
        Cookie::set('admins_order_by',$admins_order_by);
        Session::set('admins_order_by',$admins_order_by);
        return true;
    }
    public function getContestsOrderKey()
    {
        if(Session::has('contests_order_key'))return Session::get('contests_order_key');
        else if(Cookie::has('contests_order_key'))
        {
            Session::set('contests_order_key',Cookie::get('contests_order_key'));
            Session::set('contests_order_by',Cookie::get('contests_order_by'));
            return Session::get('contests_order_key');
        }
        else
        {
            Cookie::set('contests_order_key','cid');
            Session::set('contests_order_key','cid');
            Cookie::set('contests_order_by','asc');
            Session::set('contests_order_by','asc');
            return Session::get('contests_order_key');
        }
    }
    public function getContestsOrderBy()
    {
        if(Session::has('contests_order_by'))return Session::get('contests_order_by');
        else if(Cookie::has('contests_order_by'))
        {
            Session::set('contests_order_key',Cookie::get('contests_order_key'));
            Session::set('contests_order_by',Cookie::get('contests_order_by'));
            return Session::get('contests_order_by');
        }
        else
        {
            Cookie::set('contests_order_key','cid');
            Session::set('contests_order_key','cid');
            Cookie::set('contests_order_by','asc');
            Session::set('contests_order_by','asc');
            return Session::get('contests_order_by');
        }
    }
    public function setContestsOrder($contests_order_key,$contests_order_by)
    {
        Cookie::set('contests_order_key',$contests_order_key);
        Session::set('contests_order_key',$contests_order_key);
        Cookie::set('contests_order_by',$contests_order_by);
        Session::set('contests_order_by',$contests_order_by);
        return true;
    }
}