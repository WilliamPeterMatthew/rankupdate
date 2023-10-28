<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
Route::pattern([
    'aid'   => '\d+',
    'cid'   => '\w+',
    'problemid'   => '\w+',
    'data_rid'   => '\w+',
    'loginname'   => '\w+',
    'class'   => '\w+',
    'action'=> '[a-zA-Z]+',
]);

Route::rule('contest:cid/admin/settingsapi:action', '/control/settingsapi');
Route::rule('contest:cid/admin/settings', '/control/settings');
Route::rule('contest:cid/admin/problemeditapi:action/:problemid', '/control/problemeditapi');
Route::rule('contest:cid/admin/problemedit:problemid', '/control/problemedit');
Route::rule('contest:cid/admin/problemedit', '/control/problemeditre');//redirect
Route::rule('contest:cid/admin/problemaddapi:action', '/control/problemaddapi');
Route::rule('contest:cid/admin/problemadd', '/control/problemadd');
Route::rule('contest:cid/admin/problemsapi:action', '/control/problemsapi');
Route::rule('contest:cid/admin/problems', '/control/problems');
Route::rule('contest:cid/admin/ranksapi:action', '/control/ranksapi');
Route::rule('contest:cid/admin/ranks', '/control/ranks');
Route::rule('contest:cid/admin/scoresapi:action', '/control/scoresapi');
Route::rule('contest:cid/admin/scores', '/control/scores');
Route::rule('contest:cid/admin/recordeditapi:action/:data_rid', '/control/recordeditapi');
Route::rule('contest:cid/admin/recordedit:data_rid', '/control/recordedit');
Route::rule('contest:cid/admin/recordedit', '/control/recordeditre');//redirect
Route::rule('contest:cid/admin/recordaddapi:action', '/control/recordaddapi');
Route::rule('contest:cid/admin/recordadd', '/control/recordadd');
Route::rule('contest:cid/admin/recordsapi:action', '/control/recordsapi');
Route::rule('contest:cid/admin/records', '/control/records');
Route::rule('contest:cid/admin/profileapi:action', '/control/profileapi');
Route::rule('contest:cid/admin/profile', '/control/profile');
Route::rule('contest:cid/admin/logoutapi:action', '/control/logoutapi');
Route::rule('contest:cid/admin/logout', '/control/logout');
Route::rule('contest:cid/admin/loginapi:action', '/control/loginapi');
Route::rule('contest:cid/admin/login', '/control/login');
Route::rule('contest:cid/admin/errorapi:action', '/control/errorapi');
Route::rule('contest:cid/admin/error', '/control/error');
Route::rule('contest:cid/admin', '/control/index');

Route::rule('contest:cid/showauto/:loginname', '/show/indexloginnameauto');
Route::rule('contest:cid/showauto', '/show/indexauto');
Route::rule('contest:cid/show/:loginname', '/show/indexloginname');
Route::rule('contest:cid/show', '/show/index');

Route::rule('contest:cid/volunteerauto/:class', '/volunteer/indexclassauto');
Route::rule('contest:cid/volunteerauto', '/volunteer/indexauto');
Route::rule('contest:cid/volunteer/:class', '/volunteer/indexclass');
Route::rule('contest:cid/volunteer', '/volunteer/index');

Route::rule('contest:cid/player/contentapi:action', '/player/contentapi');
Route::rule('contest:cid/player/content', '/player/content');
Route::rule('contest:cid/player/logoutapi:action', '/player/logoutapi');
Route::rule('contest:cid/player/logout', '/player/logout');
Route::rule('contest:cid/player/loginapi:action', '/player/loginapi');
Route::rule('contest:cid/player/login', '/player/login');
Route::rule('contest:cid/player/errorapi:action', '/player/errorapi');
Route::rule('contest:cid/player/error', '/player/error');
Route::rule('contest:cid/player', '/player/index');

Route::rule('contest:cid', '/index/contest');
Route::rule('contest', '/index/contestre');//redirect

Route::rule('admin/settingsapi:action', '/admin/settingsapi');
// Route::rule('admin/settings', '/admin/settings');
Route::rule('admin/contesteditapi:action/:cid', '/admin/contesteditapi');
Route::rule('admin/contestedit:cid', '/admin/contestedit');
Route::rule('admin/contestedit', '/admin/contesteditre');//redirect
Route::rule('admin/contestaddapi:action', '/admin/contestaddapi');
// Route::rule('admin/contestadd', '/admin/contestadd');
Route::rule('admin/contestsapi:action', '/admin/contestsapi');
// Route::rule('admin/contests', '/admin/contests');
Route::rule('admin/admineditapi:action/:aid', '/admin/admineditapi');
Route::rule('admin/adminedit:aid', '/admin/adminedit');
Route::rule('admin/adminedit', '/admin/admineditre');//redirect
Route::rule('admin/adminaddapi:action', '/admin/adminaddapi');
// Route::rule('admin/adminadd', '/admin/adminadd');
Route::rule('admin/adminsapi:action', '/admin/adminsapi');
// Route::rule('admin/admins', '/admin/admins');
Route::rule('admin/profileapi:action', '/admin/profileapi');
// Route::rule('admin/profile', '/admin/profile');
Route::rule('admin/logoutapi:action', '/admin/logoutapi');
// Route::rule('admin/logout', '/admin/logout');
Route::rule('admin/loginapi:action', '/admin/loginapi');
// Route::rule('admin/login', '/admin/login');
Route::rule('admin/errorapi:action', '/admin/errorapi');
// Route::rule('admin/error', '/admin/error');
// Route::rule('admin/index', '/admin/index');

Route::rule('visitwrong', '/index/visitwrong');
// Route::rule('index/index', '/index/index');
