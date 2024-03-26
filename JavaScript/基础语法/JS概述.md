# JavaScript

### 概述

​	JavaScript是一种运行在客户端（浏览器）的编程语言。JS由两部分组成：

+ **ECMAScript：**即JS的基础语法
+ **Web APIs：**即DOM、BOM操作等

### 书写位置

​	JS的书写位置和CSS相似，分为：内联（行内）、内部（嵌入式）和外部。

+ 内联（行内）：

```html
<div onclick="alert(1)"></div>
```

+ 内部（嵌入式）：

```html
<script>
  alert(1)
</script>
```

​	嵌入式的JS代码最好写在</body>的前面一行，这是因为浏览器加载代码是从上到下按顺序加载，对于JS而言如果待处理的标签在JS代码的下面，则无法操作对应的标签，例如：

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
  <script>
    let test = document.getElementById("test").value
    console.log(test)
  </script>
</head>
<body>
<input type="text" id="test">
</body>
</html>
```

​	这样是无法看见输出结果的，因为JS代码在下面`<input>`标签前加载，无法获取到`<input>`里的值。

+ 外部JavaScript：

​	在单独的.js文件中编写，并通过`<script src="File Path">`引入。

```html
<script scr="my.js"></script>
```

> 外联式的`<script>`标签中间不能再写其他的JS代码，否则不会被执行。

### 基础语法

#### 输入输出

​	**输入**

​	JS获取用户输入用：`prompt()`方法，这个方法用于弹出一个对话框，让用户输入一些文本并返回该文本。

```js
let result = prompt(message, default)
// 'message（必需）'：这是一个字符串（提示文本），用于提示用户的输入
// 'default（可选）'：这是一个默认值，当用户无输入时为空，如果不定义该参数则默认为空。
```

> `prompt()`函数会阻塞浏览器中的其他操作，直到用户关闭对话框。`prompt()`的返回值为字符串。

​	**输出**

​	JS有三种输出方式，分别是`console.log()`、`alert()`和`document.write()`。这三个方法分别将内容输出到控制台、页面弹窗和页面`document`元素中：

​	控制台输出：

```js
console.log(1)
```

​	弹窗：

```js
alert(1)
```

​	页面文档：

```js
document.write("<div>1</div>")
```



#### 变量常量

#### 数组

#### 数据类型

+ **数字类型：**
+ **字符串类型：**
+ **布尔型：**
+ **null值：**
+ **undefined值：**
+ **`typeof`运算符检测数据类型：**

#### 运算符

+ **一元运算符**
+ 

#### 类型转换

+ **隐式转换：**
+ **显示转化：**

#### 常见错误
