购物车网站

===
有用户购买的基本操作，有网站后台的基本操作

使用了Yaf框架和medoo数据库orm

前端用bootstrap3和yeti主题编写

demo: http://cartbyyaf.sinaapp.com/

sae不支持视图，导致不能看到购物车的内容.有时间再改吧.


##快速开始
===
####目录结构

对于Yaf的应用, 都应该遵循类似下面的目录结构.

一个典型的目录结构

```
+ public
|- index.php //入口文件
|- .htaccess //重写规则    
|+ css
|+ img
|+ js
+ conf
|- application.ini //配置文件   
+ application
|+ controllers
 |- Index.php //默认控制器
|+ views    
 |+ index   //控制器
  |- index.phtml //默认视图
|+ modules //其他模块
|+ library //本地类库
|+ models  //model目录
|+ plugins //插件目录
```



####入口文件

入口文件是所有请求的入口, 一般都借助于rewrite规则, 把所有的请求都重定向到这个入口文件.


####重写规则

除非我们使用基于query string的路由协议(Yaf_Route_Simple, Yaf_Route_Supervar), 否则我们就需要使用WebServer提供的Rewrite规则, 把所有这个应用的请求, 都定向到上面提到的入口文件.

####Apache的Rewrite (httpd.conf)
.htaccess
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule .* index.php
```
当然也可以写在httpd.conf[option]
```
DocumentRoot "path/public" #需要定位到本项目的public文件夹
<Directory "path/public">
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule .* index.php
</Directory>
```


####Nginx的Rewrite (nginx.conf)
```
root path/public #需要定位到本项目的public文件夹
location / {
    try_files $uri $uri/ /index.php;
}
```


####Lighttpd的Rewrite (lighttpd.conf)
```
$HTTP["host"] =~ "(www.)?domain.com$" {
  url.rewrite = (
     "^/(.+)/?$"  => "/index.php/$1",
  )
}
```


####SAE的Rewrite (config.yaml)
```
name: your_app_name
version: 1
handle:
    - rewrite: if(path ~ "^(?!public/)(.*)") goto "/public/$1"
    - rewrite: if(!is_file()) goto "/public/index.php"
```

或者在SAE面板
appconfig->rewrite->高级设置->直接在大框框下填入下面的内容->保存
```
    - rewrite: if(path ~ "^(?!public/)(.*)") goto "/public/$1"
    - rewrite: if(!is_file()) goto "/public/index.php"
```
[注意]
每种Server要启用Rewrite都需要特别设置, 如果对此有疑问.. RTFM

###运行

在浏览器输入
http://www.yourhostname.com/index.php



yaf
===
yaf+opcache for mvc+orm to api

集成好了smarty和twig模板引擎，后来没有用，暂时注释

使用了ORM框架Medoo

开启yaf的Session管理

使用了memcached和php的memcached扩展，添加session

使用opcache的时候要注意，它会缓存php为静态，debug的时候，最好关闭



可以按照以下步骤来部署和运行程序:
```
1.请确保机器localhost已经安装了Yaf框架, 并且已经加载入PHP;
2.把y目录Copy到Webserver的DocumentRoot目录下;
3.需要在php.ini里面启用如下配置，生产的代码才能正确运行(要先安装yaf扩展)：
    yaf.environ="product"
4.重启Webserver;
5.访问http://yourhost/,出现商城页面!, 表示运行成功,否则请查看php错误日志;
```


参考
===
yaf的一些资源：http://www.laruence.com/2012/07/06/2649.html

yaf学习的一些思路：http://achun.iteye.com/blog/1473126

YafUse项目给了我很大的帮助：https://www.github.com/melonwool/YafUse/

yaf的api：http://yaf.laruence.com/manual/index.html

数据库设计上面主要有课本上的一些知识

浅谈数据库设计技巧：http://www.knowsky.com/4937.html

最终结果：
![home](https://github.com/yantze/yaf/raw/master/docs/homepage-Screenshot 2014-11-16 04.24.04.png)
![backpabe](https://github.com/yantze/yaf/raw/master/docs/backpage-Screenshot 2014-11-16 04.24.42.png)
