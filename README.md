yaf
===

编写yaf已经有整整四天了，基本的雏形已经出来：

集成好了smarty和twig模板引擎，后来没有用，暂时注释

使用了ORM框架Medoo

开启yaf的Session管理

使用了memcached和php的memcached扩展，添加session


yaf+yar+msgpack+opcache for mvc+orm to api



```
可以按照以下步骤来部署和运行程序:
1.请确保机器yantze@localhost已经安装了Yaf框架, 并且已经加载入PHP;
2.把y目录Copy到Webserver的DocumentRoot目录下;
3.需要在php.ini里面启用如下配置，生产的代码才能正确运行：
	yaf.environ="product"
4.重启Webserver;
5.访问http://yourhost/,出现Hellow Word!, 表示运行成功,否则请查看php错误日志;
```


yaf 的一些资源：http://www.laruence.com/2012/07/06/2649.html

yaf学习的一些思路：http://achun.iteye.com/blog/1473126

YafUse项目给了我很大的帮助：https://www.github.com/melonwool/YafUse/
