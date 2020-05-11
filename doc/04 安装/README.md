环境要求：PHP 7+, MySQL 5.5+

Linux下Nginx的配置是最常遇到的问题，解决办法见：

https://my.oschina.net/u/2525829/blog/532614

在Nginx下还需要配置好 `PathInfo`

参见：http://www.nginx.cn/426.html
最简单的方法是：php.ini中设置 `cgi.fix_pathinfo=1`

`如果实在是搞不定Nginx，可以用LNMPA：` https://lnmp.org/lnmpa.html

开发环境使用的是：XAMPP
