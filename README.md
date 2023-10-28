比赛排名系统
===============

> 使用ThinkPHP6.0框架，运行环境要求PHP7.2+，兼容PHP8.1

## 安装

使用指令
~~~
composer install
~~~

接着请初始化环境变量
~~~
cp .example.env .env
~~~

接着打开.env文件，修改相关设置，主要修改数据库连接，站点相关设置可稍后在管理页面修改。

接下来，请将网站运行目录设置为./public，并把nginx.txt文件中的内容粘贴的nginx的配置文件中。

然后，请导入网站文件夹根目录下的 rankupdate.sql ，数据库初始化后，即可使用系统。

## 文档

请按照上述步骤初始化系统后，访问网站，右上角进入全局管理页面，默认管理员信息为：账号 `superadmin` 密码 `12345678` 。

## 版权信息

本项目遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2023-present by 张开昕。

All rights reserved。

ThinkPHP® 商标和著作权所有者为上海顶想信息科技有限公司。

更多细节参阅 [LICENSE.txt](LICENSE.txt)
