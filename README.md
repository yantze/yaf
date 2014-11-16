购物车网站

===
有用户购买的基本操作，有网站后台的基本操作

使用了Yaf框架和medoo数据库orm

前端用bootstrap3和yeti主题编写

demo: http://cartbyyaf.sinaapp.com/
sae的路由没有做好，只能浏览首页。

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
![home](http://github.com/yantze/yaf/raw/master/docs/homepage-Screenshot 2014-11-16 04.24.04.png)
![backpabe](http://github.com/yantze/yaf/raw/master/docs/backpage-Screenshot 2014-11-16 04.24.42.png)
