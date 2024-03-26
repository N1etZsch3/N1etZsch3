# Django笔记：



## 一、 Django项目框架：
​	创建Django的命令：django-admin startproject [filename]

```
|-- website
|   |-- __init__.py     [文件包]
|   |-- settings.py     [Django的配置文件，数据库，模板目录等]
|   |-- urls.py         [负责处理URL和相关函数的对应关系]
|   |-- asgi.py         [异步式接受网络请求]
|   `-- wsgi.py         [同步式接收网络请求]
`-- manage.py      [项目的管理、启动、创建、数据管理，需要用到的文件]
```

## 二、 APP概述
​	创建APP的命令：python manage.py startapp [appname]

​	对于一个项目，有多个功能，将这些功能分成不同的小的模块，每个模块就是一个app

```
	例如： - 项目
        	- app、用户管理
        	- app、订单管理
        	- app、后台管理
        	- app、网站
        	- app、数据接口
        	- app、API
        	...
```

​	每个app都可以有独立的HTML模板，CSS文件，表结构，函数等等

## 三、APP结构

```
│——app01
│  │  admin.py      [django默认提供的admin后台管理]
│  │  apps.py       [app启动类]
│  │  models.py     [对数据库进行操作]
│  │  tests.py      [单元测试文件]
│  │  views.py      [视图文件，保存urls.py调用的视图函数]
│  │  __init__.py
│  │
│  └─migrations     [数据库变更记录]
```

## 四、运行项目
#### 1、注册APP
​	在主目录[website]模块下的setting文件的[INSTALLED_APPS]列表中， 加入app的引用路径

例如：

```python
    INSTALLED_APPS = [
    "django.contrib.admin",
    "django.contrib.auth",
    "django.contrib.contenttypes",
    "django.contrib.sessions",
    "django.contrib.messages",
    "django.contrib.staticfiles",
    "app01.apps.App01Config",
    ]
```

#### 2、创建url与视图方法的对应关系	

​	然后在urls.py文件中指定url和视图函数

```python
from django.urls import re_path
from app01 import views

urlpatterns = [
    re_path(r"^$", views.index)
]
```

#### 3、编写视图方法	

​	接着在[app01]模块中的views文件中编写对应的方法

```python
from django.shortcuts import HttpResponse


def index(request):
    page = "这是一个测试网站！"
    return HttpResponse(page)
```

#### 4、启动Django服务器

```commandline
python manage.py runserver ip:port
```

​	ip不写则默认为localhost，为0.0.0.0则为任意主机。port可以任意，但注意不要冲突

## 五、模板和静态文件

​	在django中，HTML文件叫模板，模板需要保存在app项目目录的子目录，模板目录的命名需要和主目录下的setting.py文件中配置的文件名相对应，默认为templates。在模板中需要使用到的如js，jquery，css，image都属于静态文件，静态文件同样需要保存为app项目目录的子目录，静态文件名称同样要和setting文件中的相关配置所对应，一般为static，结构为：

```commondline
├─app01
│  ├─migrations 
│  ├─static
│  │  ├─css		[css文件]
│  │  ├─img		[images文件]
│  │  ├─js		[js文件]
│  │  └─plugins		[需要引用的库文件，如jQuery]
│  └─templates		[HTML模板文件]

```

​	在模板文件中加载静态文件需要用到特殊的语法，例如：

```html
{% load static %}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script href="{% static 'plugins/jquery-3.7.1.min.js' %}"></script>
</head>
<body>
<img src="{% static 'img/Wallpaper.jpg' %}" alt="">
</body>
</html>
```

​	首先要加载静态文件：

```python
{% load static %}
```

​	然后在对应的 例如script标签 中的语法为：

```html
<script href="{% static 'plugins/jquery-3.7.1.min.js' %}"></script>
```

