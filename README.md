tinywork - 基于MVC的php框架

> - 作者： iProg
> - 日期： 2015-01-12
> - 版本： 1.1.2
> - 邮箱： zxwmyemail@126.com
> - 描述： It is a PHP framework based on the MVC design pattern!


框架相关说明 - 轻量级、易使用

一、系统目录和主要文件如下：
```php
  | — web                       web入口文件和静态资源所在文件夹
       | —— index.php               web入口文件
       | —— resource                静态资源所在文件夹
            | ——— css                   css文件存放位置 
            | ——— js                    js文件存放位置
            | ——— images                images文件存放位置
            | ——— font                  font文件存放位置
  | — config                    存放配置文件  
      | —— const.config.php         系统预定义常量 
      | —— params.config.php        系统参数配置文件
  | — log                       日志文件夹
      | —— sys_log                  系统日志
      | —— app_log                  程序日志
  | — mvc                       框架的mvc层
      | —— home                     默认为home模块
          | ——— controller              控制器文件  
          | ——— model                   模型文件  
          | ——— view                    视图文件     
  | — extend                    存放扩展类库  
  | — system                    系统目录 
      | —— core                     系统核心类，例如控制层父类，model层父类、路由类
      | —— framework                第三方框架，例如smarty引擎
      | —— library                  系统类库
  | — README.md                 框架说明文件，即本文件
```

二、系统文件名命名规则：
```html
  所有自定义类，文件名和类名必须一样，如：类名为MyTest，则文件名也应为MyTest
```

三、系统集成了smarty引擎，在控制层中使用方法如下：
```php
  $this->smarty->assign('name','zxw');
  $this->smarty->display('home.html');
```
四、文件夹mvc/view/视图层下面，建立文件夹的规则和控制层类的对应关系举例如下：
```php
  1.该框架支持多个模块，比如有网站前台（module名为home）和网站后台管理（module名为backend）
    两个系统（一个系统对应一个模块），所以要有两个控制层，先在mvc/controller下面建立两个文件夹，
    规则如下：
    网站前台：mvc/controller/homeModule/
    网站后台：mvc/controller/backendModule/

  2.对应的视图层也要建立和上面一样的两个文件夹，建立规则如下：
    网站前台：mvc/view/homeModule/
    网站后台：mvc/view/backendModule/

  3.无论哪个模块，如果在控制层建立了一个控制层类，对应的，在视图层要先建立文件夹，比如：
    在网站前台的控制层：mvc/controller/homeModule/下建立了一个控制层类文件homeController.php
    则需在对应的视图层：mvc/view/homeModule/下先建立home文件夹，然后把html页面放在home下面

  4.路由访问，有两种:
    (1) http://localhost/web/index.php?m=home&r=home.index&id=2
    (2) http://localhost/web/index.php/home/controller/action/?id=2

    说明，默认的系统模块为home（见config/params.config.php中关于默认路由配置），
    所以可以不写，即为：
    (1) http://localhost/web/index.php?r=home.index&id=2
    (2) http://localhost/web/index.php/controller/action/?id=2  
```
