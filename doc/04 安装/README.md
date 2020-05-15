# 安装

---

运行环境要求：PHP 7+ ，MySQL 5.5+

操作系统：生产环境推荐使用Linux

Web服务器：Apache、Nginx

## 安装步骤概述
1、新建MySQL数据库，并导入 [/doc/99 SQL]( https://gitee.com/crm8000/PSI/tree/master/doc/99%20SQL ) 中的SQL语句
`01CreateTables.sql` 和 `02InsertInitData.sql`。
(`99psi_demo_data.sql`是创建演示数据，在正式使用的时候不用导入)

2、修改数据库连接配置，这个是标准的ThinkPHP数据库连接配置。配置文件位于：
[ /web/Application/Common/Conf/config.php ]( https://gitee.com/crm8000/PSI/blob/master/web/Application/Common/Conf/config.php )

3、发布PHP源代码

默认的登录用户名是`admin` 登录密码也是`admin`

admin用户是系统的超级管理员，具有所有的权限。在正式使用中建议不要直接使用admin来操作业务。

## 参考资料

https://my.oschina.net/u/2525829/blog/532614

在Nginx下还需要配置好 `PathInfo`

参见：http://www.nginx.cn/426.html
最简单的方法是：php.ini中设置 `cgi.fix_pathinfo=1`

`如果实在是搞不定Nginx，可以用LNMPA：` https://lnmp.org/lnmpa.html

开发环境使用的是：XAMPP
