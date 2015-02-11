##购物车网站
yaf+opcache for mvc+orm to api

用户有购买收藏等基本操作，网站后台有商品增删查改上传图片基本操作.

前端使用AMD规范的requirejs，并且使用yeti主题的bootstrap前端响应式框架，支持手机浏览。


集成好smarty和twig模板引擎，后来没有用，暂时注释

使用ORM框架Medoo

开启yaf的Session管理

使用memcached和php的memcached扩展，添加session

也可以用forp做性能测试，在public/index_forp.php

[注意]使用opcache的时候，它会缓存php为静态，debug的时候，最好关闭



DEMO: http://cartbyyaf.sinaapp.com/


##快速开始



可以按照以下步骤来部署和运行程序(SAE已经内置，不需要自己安装):
```
1.请确保机器localhost已经安装了Yaf扩展框架, 并且已经启动PHP;
2.把这个项目拷贝到Webserver的DocumentRoot目录下;
3.创建php.d/yaf.ini文件,里面启用如下配置,生产的代码才能正确运行：
    extension=yaf.so
4.重启Webserver;
5.访问http://yourhost/,出现商城页面!, 表示运行成功,否则请查看错误日志;

yaf.ini文件详细说明:
[yaf]
yaf.environ = product
yaf.library = NULL
yaf.cache_config = 0
yaf.name_suffix = 1
yaf.name_separator = ""
yaf.forward_limit = 5
yaf.use_namespace = 0     //如果使用类,可以开启
yaf.use_spl_autoload = 0
extension=yaf.so
```


###目录结构

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
本项目的目录说明
```
+ public
  |- index.php //入口文件
  |- index_forp.php //性能测试入库
  |- .htaccess //重写规则
  |- favicon.jpg
  |+ css
  |+ images
  |+ js
+ conf
  |- application.ini //配置文件
  |- *.php           //空白文件,为以后架构配置
+ application
  |+ controllers
     |- Index.php //默认控制器
  |+ views    
     |+ index   //控制器
     |- index.phtml //默认视图
  |- Bootstrap.php //项目的全局配置
  |- yaf_classes.php //yaf框架的函数列表,方便补全
+ modules //其他模块
+ library //本地类库
+ models  //model目录
+ plugins //插件目录
+ tests   //测试目录
+ globals   //插件目录和全局配置
  |+ cache  //模板生成的缓存文件
  |+ composer         //composer下载的lib
     |- composer.json //composer的依赖配置
  |- *.php  //空白文件,为以后配置库
```



###重写规则

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



参考
===
yaf的一些资源：http://www.laruence.com/2012/07/06/2649.html

yaf学习的一些思路：http://achun.iteye.com/blog/1473126

YafUse项目给了我很大的帮助：https://www.github.com/melonwool/YafUse/

yaf的api：http://yaf.laruence.com/manual/index.html

数据库设计上面主要有课本上的一些知识

浅谈数据库设计技巧：http://www.knowsky.com/4937.html

SAE:[sina app engine](http://sae.sina.com.cn)

最终效果：
![home](https://github.com/yantze/yaf/raw/master/docs/homepage-Screenshot 2014-11-16 04.24.04.png)
![backpabe](https://github.com/yantze/yaf/raw/master/docs/backpage-Screenshot 2014-11-16 04.24.42.png)
