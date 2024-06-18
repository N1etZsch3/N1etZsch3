# Django笔记：



## 一、 Django项目框架：

​	创建Django的命令：`django-admin startproject [filename]`

```
|-- website
|   |-- __init__.py     [文件包]
|   |-- settings.py     [Django的配置文件，数据库，模板目录等][经常操作]
|   |-- urls.py         [负责处理URL和相关函数的对应关系][经常操作]
|   |-- asgi.py         [异步式接受网络请求][默认组件不修改]
|   `-- wsgi.py         [同步式接收网络请求][默认组件不修改]
`-- manage.py    [项目的管理、启动、创建、数据管理，需要用到的文件][默认组件不修改]
```

## 二、 APP概述
​	创建APP的命令：`python manage.py startapp [appname]`

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
│  │  admin.py      [django默认提供的admin后台管理] [默认组件不修改]
│  │  apps.py       [app启动类] [默认组件不修改]
│  │  models.py     [对数据库进行操作] 
│  │  tests.py      [单元测试文件] [默认组件不修改]
│  │  views.py      [视图文件，保存urls.py调用的视图函数]
│  │  __init__.py
│  │
│  └─migrations     [数据库变更记录] [默认组件不修改]
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
    re_path(r"^$", views.index) # 只要访问前面的URL路径，就自动执行后面的函数。
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

#### 5、注意问题：

​	如果你选择使用`Pycharm`进行项目开发，建议最好直接使用`Pycharm`创建项目，否则你会遇到一些问题，例如：你在后面使用Djando静态模板语法的时候会显示`意外的标签`，以及在`urls.py`文件中引入`from app01 import views`时，`Pycharm`会报语法错误。但是事实上，这些都属于是`Pycharm`将你的项目理解为原生的python项目，因此报错。其实不影响项目正常运行。

## 五、模板语法和静态文件

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

#### 1、加载静态文件	

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

```django
{% load static %}
```

​	然后在对应的 例如script标签 中的语法为：

```django
<script href="{% static 'plugins/jquery-3.7.1.min.js' %}"></script>
```

​	其他的静态资源也是类似的，例如：

```django
<link href="{% static 'css/main.css' %}" type='text/css'></link>
<img src="{% static 'img/1.png' %}"></img>
```

#### 2、加载动态数据

​	在 Django 中，动态数据的加载主要依赖于视图（views.py 中的函数）与模板（HTML 文件）之间的交互。视图负责处理来自用户的请求，执行业务逻辑，获取或生成数据，然后将这些数据传递给模板，以便动态地渲染 HTML 页面。

**示例：传递和渲染数据**

​	假设我们有一个简单的视图函数，如下所示：

```python
def tpl(request):
    page_name = "tpl页面"
    roles = ["管理员", "CEO", "保安"]
    user_info = {"name": "张三", "gender": "男", "age": 18}

    user_list = [
        {"name": "张三", "gender": "男", "age": 18},
        {"name": "李四", "gender": "女", "age": 21},
        {"name": "王五", "gender": "男", "age": 31},
    ]

    return render(request, "tpl.html", {
        "n1": page_name,
        "n2": roles,
        "n3": user_info,
        "n4": user_list,
    })
```

​	在这个示例中，`render()` 函数是核心，它接受请求对象（`request`）、模板名称（`"tpl.html"`）和一个字典（`context`）作为参数。`context` 字典包含了要传递给模板的所有变量。

**render()函数定义：**

```python
def render(
    request, template_name, context=None, content_type=None, status=None, using=None
):
    """
    Return an HttpResponse whose content is filled with the result of calling
    django.template.loader.render_to_string() with the passed arguments.
    """
    content = loader.render_to_string(template_name, context, request, using=using)
    return HttpResponse(content, content_type, status)
```



**模板中的数据使用**

​	在 Django 的 `tpl.html` 模板文件中，可以使用 Django 模板语言（DTL）来访问和渲染传递过来的数据。DTL 提供了一套丰富的标签和过滤器，使得在模板中处理数据变得简单高效。

**访问变量**

​	要在模板中访问变量，直接使用字典（`context`）参数中的键名：

```django
<!-- 渲染变量 n1 的值 -->
<div>{{ n1 }}</div>    <!-- 这会将 page_name 变量的值渲染到 HTML 页面中。 -->
```

​	这种方式适用于直接显示字符串、数字或其他基本类型的变量。

**渲染列表数据**

​	若要将列表整体渲染到页面，同样使用变量语法：

```django
<!-- 渲染整个列表 n2 -->
<div>{{ n2 }}</div>    <!-- 这会直接将列表 n2 渲染为一个字符串并显示。 -->
```

**列表元素索引**

​	要通过下标访问列表中的元素，使用点（`.`）后跟索引号的方式：

```django
<!-- 通过下标访问列表 n2 中的元素 -->
<div>{{ n2.0 }}</div>
<div>{{ n2.1 }}</div>
<div>{{ n2.2 }}</div>    
<!-- 上面的代码会依次输出列表 n2 中的第1、第2和第3个元素：管理员、CEO、保安 -->
```

**注意**：直接在模板中使用下标访问列表元素的方式是基于 Django 模板语言的约定，并不是所有模板引擎都支持这种语法。

**使用 for 循环遍历列表**

​	DTL 提供了 `for` 循环标签，用于遍历列表或其他可迭代对象：

```django
<!-- 使用 for 循环遍历列表 n2，并渲染每个元素 -->
<div>
    {% for item in n2 %}
        <span>{{ item }}</span>
    {% endfor %}
</div>
<!-- for 和 endfor 标签是固定写法，它们定义了循环的开始和结束。 -->
```

​	这种方法适用于需要逐个处理列表中每个元素的情况。

**字典的处理**

​	对于字典类型的数据，可以直接通过键名访问其值：

```django
<!-- 渲染字典 n3 中的某个值 -->
<div>{{ n3.name }}</div>    <!-- 假设 n3 是一个字典，这会渲染 n3 字典中键为 'name' 的值。 -->
```

​	此外，DTL 也支持遍历字典的键、值或键值对：

```django
<!-- 遍历字典 n3 的键 -->
<ul>
    {% for key in n3.keys %}
        <li>{{ key }}</li>
    {% endfor %}
</ul>

<!-- 遍历字典 n3 的值 -->
<ul>
    {% for value in n3.values %}
        <li>{{ value }}</li>
    {% endfor %}
</ul>

<!-- 遍历字典 n3 的键值对 -->
<ul>
    {% for key, value in n3.items %}
        <li>{{ key }}: {{ value }}</li>
    {% endfor %}
</ul>
```

​	通过这些方法，可以灵活地在模板中展示和处理后端传递的各种类型的数据。

**判断分支语句**

​	DTL还提供了if判断语句：

```django
<div>
    {% if n4.0.name == "王五" %}
        <span>我是王五</span>
    {% elif n4.0.name == "李四" %}
        <span>我是李四</span>
    {% else %}
        <span>我是张三</span>
    {% endif %}
</div>
```



## 六、请求和响应

​	在 Django 的 `views.py` 中，每个视图函数都至少接收一个参数 —— `request` 对象。这个对象是 `django.http.HttpRequest` 的一个实例，它封装了来自客户端的所有请求信息，包括请求方法、请求头、GET 或 POST 参数等。

```tex
request.method：一个字符串，表示 HTTP 请求的方法，如 'GET' 或 'POST'。
request.GET：一个类似字典的对象，包含所有的 GET 参数。
request.POST：一个类似字典的对象，包含所有的 POST 参数。
request.FILES：一个类似字典的对象，包含所有的上传文件。
request.COOKIES：一个字典，包含所有的 cookies。
request.path：请求的完整路径，不包括域名。
request.user：表示发起请求的用户，如果启用了 Django 的认证系统。
```

### 请求

#### 1、获取GET请求的值：

​	GET 请求的数据通常附加在 URL 中，每个键值对之间以 `&` 分隔，键与值之间以 `=` 连接。在 Django 视图中，可以通过 `request.GET` 属性来访问这些值。`request.GET` 是一个类似字典的对象，因此可以像处理字典那样处理它。

```python
def my_view(request):
    # 假设 URL 是 "/my-url/?key1=value1&key2=value2"
    key1_value = request.GET.get('key1', 'default_value')
    key2_value = request.GET['key2']  # 如果 key2 不存在，这将引发 KeyError
    # 如果不确定键是否存在，使用 .get() 方法可以避免错误，它允许你指定一个默认值。
    
    # 也可以使用 .getlist() 方法获取相同键的所有值（适用于键有多个值的情况）
    key3_values = request.GET.getlist('key3')  # 返回 key3 的所有值的列表
    
    # 处理逻辑...
    return HttpResponse('GET 值已接收。')

```

#### 2、获取POST值

​	POST 请求的数据通常用于提交表单。这些数据在 HTTP 请求的正文（body）中发送，而不是在 URL 中。在 Django 视图中，可以通过 `request.POST` 属性来访问这些值。就像 `request.GET` 一样，`request.POST` 也是一个类似字典的对象。

```python
def my_view(request):
    if request.method == 'POST':
        # 使用 request.POST['key'] 当键不存在时会抛出 KeyError
        key1_value = request.POST.get('key1', 'default_value')
        key2_value = request.POST['key2']  # 如果 key2 不存在，这将引发 KeyError
        
        # 对于复选框等可能有多个值的表单元素，使用 .getlist()
        key3_values = request.POST.getlist('key3')
        
        # 处理逻辑...
        return HttpResponse('POST 值已接收。')
    else:
        # 非 POST 请求的处理...
        return HttpResponse('这不是一个 POST 请求。')

```

#### 注意事项

- 在处理 POST 请求时，一定要检查请求的方法是否为 'POST'，以确保只在接收 POST 请求时处理数据。
- 使用 `get()` 方法获取字典键的值可以避免在键不存在时抛出异常，同时允许你指定一个默认值。
- 对于可能有多个值的键（如复选框），使用 `getlist()` 方法可以获取所有值的列表。

通过这种方式，可以灵活地处理来自前端的 GET 和 POST 请求，并根据提交的数据执行相应的逻辑。

### 响应

​	Django一般有三种响应方法：

#### 1、纯文本响应

​	使用`HttpResponse()`方法，响应一个纯文本：

```python
def something(request):
	return HttpResponse("响应请求")
```

#### 2、页面响应

​	使用`render()`方法，响应一个页面：

```python
def something(request):
    return render(request, 'something.html', context)
```

#### 3、重定向响应

​	使用`redirect()`方法重定向页面：

```python
def something(request):
    return redirct("http://www.baidu.com")
```

### 实例

下面用一个用户登录的实例演示Django对于请求和响应的处理：

​	首先，在`urls.py`中，编写URL和视图方法的对应关系：

```python
# urls.py 

from django.urls import path
from app01 import views

urlpatterns = [
    path("login/", views.login)
]
```

​	然后在`views.py`中编写视图方法：

```python
# views.py

from django.shortcuts import render

def login(request):
	# 根据请求方法不同，返回不同页面
    if request.method == 'GET':
        return render(request, 'login.html')
	# 通过get()方法获得POST值
    username = request.POST.get('username')
    password = request.POST.get('password')
	# 判断用户名和密码
    if username == 'admin' and password == 'admin123' :
        return HttpResponse("login--pass")
    return render(request, 'login.html', {
        "error_msg": "login--failed",
    })
```

​	然后编写前端页面：

```django
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<form action="/login/" method="post">
    {% csrf_token %} 
    <!-- 注意这里的 csrf_token。Django 为了防御 CSRF 攻击，要求在表单提交时包含一个 CSRF 令牌。这个令牌确保了提交表单的请求是经过用户验证的，防止恶意网站提交伪造的请求。如果表单缺少 {% csrf_token %} 标签，Django 会拒绝这个请求，返回一个 CSRF 验证失败的错误。因此，在 Django 中创建表单时，包含这一行是必须的，以确保应用的安全。 -->
    <input type="text" name="username" placeholder="username">
    <input type="password" name="password" placeholder="password">
    <input type="submit" value="提交">
    {{ error_msg }}
    <!-- 注意后端的判断逻辑，只有当用户登录失败的时候才会显示这个提示信息 -->
</form>
</body>
</html>
```

​	这是一个简单的登录实例代码，根据用户的登录情况输出不同的内容。值得注意的是，Django默认提供了csrf_token，用于防止CSRF攻击（具体请看[CSRF](../Web安全/CSRF跨站请求伪造)）。



