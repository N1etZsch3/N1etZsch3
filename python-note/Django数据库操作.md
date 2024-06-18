# 数据库操作



## 原生Python操作数据库

​	在原生Python代码中，数据库的操作一般使用`PyMySQL`库，例如：

```python
import pymysql

# Connect to the database
connection = pymysql.connect(
    host='localhost',
    user='your_username',
    password='your_password',
    database='your_database'
)

# Create a cursor object
cursor = connection.cursor()

# Execute a query
query = "SELECT * FROM your_table"
cursor.execute(query)

# Fetch all rows
rows = cursor.fetchall()

# Process the rows
for row in rows:
    # Access the data using row[column_index]
    column1 = row[0]
    column2 = row[1]
    # Do something with the data

# Close the cursor and connection
cursor.close()
connection.close()
```

​	而在Django中，不会直接利用`PyMySQL`操作数据库，而是集成了ORM框架。



## ORM框架

​	ORM（Object-Relational Mapping）框架是一种在不同编程语言环境中使用的技术，用于在关系型数据库和对象语言之间进行映射。核心原理就是在关系型数据库和对象模型（通常是面向对象编程语言中的类和实例）之间建立一个映射关系。简而言之，ORM框架允许开发者以面向对象的方式操作数据库，而不必直接编写SQL语句。

### ORM的核心概念

- **模型（Model）**：在ORM框架中，数据库的表（table）通常被映射为编程语言中的类（class），表中的记录（record）被映射为类的实例（instance）。
- **数据库会话（Session）**：ORM框架通过会话管理与数据库的连接，以及在数据库中执行的操作。
- **查询（Query）**：ORM提供了一种方法或接口，允许开发者使用对象语言的方式来构建和执行数据库查询，而无需编写原生的SQL语句。
- **事务（Transaction）**：ORM框架通常提供了对数据库事务的支持，允许开发者以编程方式控制事务的开始、提交和回滚。

### ORM的优点

- **提高开发效率**：通过消除需要手动编写SQL语句的需求，ORM框架加快了开发过程。
- **减少错误**：由于ORM框架自动化了许多数据库操作的细节，因此减少了因手动处理这些操作而可能导致的错误。
- **数据库无关性**：ORM框架提供了数据库无关性，这意味着在不同的数据库系统之间迁移应用程序变得更容易，因为ORM层抽象了底层数据库的具体实现细节。
- **提高代码的可读性和可维护性**：使用面向对象的方法操作数据库使得代码更易于理解和维护。

### ORM的缺点

- **性能问题**：由于ORM在对象模型和数据库之间进行自动映射，可能会产生不如手写SQL优化的查询，特别是在复杂查询和大数据操作场景下。
- **学习曲线**：虽然ORM框架旨在简化数据库操作，但要充分利用其功能，开发者需要花时间学习特定ORM框架的使用方法和最佳实践。
- **灵活性减少**：对于高度复杂和定制化的查询操作，ORM框架可能不如直接使用SQL灵活。

### ORM防御SQL注入

​	对于几乎所有的数据库操作而言，SQL注入无疑是最大的敌人。关于SQL注入可以看[SQL注入]()。而使用ORM框架，能有效防御SQL注入攻击。主要原因在于其设计原理以及如何处理SQL语句的执行。

+ **参数化查询**：ORM框架使用参数化查询（预编译语句）来执行数据库操作。
+ **抽象化SQL语句**：ORM框架允许开发者以面向对象的方式操作数据库，开发者操作对象以及其方法对数据库进行增删查改操作，而不需要直接编写SQL语句。
+ **自动转义特殊字符**：在将参数值插入到SQL查询中时，ORM框架通常会自动转义特殊字符。
+ **白名单式查询构建**：ORM框架通常只允许通过其API定义的方法来构建查询，这些方法内部确保了SQL的安全性。



## Django操作数据库

### 创建数据库

​	尽管ORM框架支持直接创建数据库，但是由于在真正的项目中，数据库架构的设计相对复杂、极其重要且一般需要较高的权限。因此，一般创建数据库的操作都是直接使用原生的SQL语句在SQL客户端上创建。

### 配置Django连接数据库

​	首先进入项目根目录中，找到`settings.py`文件，对其中的`DATABASES`配置项进行修改：

```python
DATABASES = {
    "default": {
        "ENGINE": "django.db.backends.sqlite3",
        "NAME": BASE_DIR / "db.sqlite3",
    }
}
```

​	原本的配置文件默认使用`sqlite3`数据库引擎，假如我们需要使用MySQL，则需要修改其内容。

```python
DATABASES = {
    "default": {
        "ENGINE": "django.db.backends.mysql",	# 数据库引擎
        "NAME": "django",			# 数据库名称
        "USER": "root",			# 用户名
        "PASSWORD": "admin123",	# 密码
        "HOST": "47.109.137.34",			# 数据库服务器地址
        "PORT": "3308",		# 端口
    }
}
```

### Django操作数据表

+ #### 创建表：

​	创建表需要先在应用目录中的`models.py`中写一个类，这一步为定义模型。在Django中，数据表由Python类表示，这些类继承自**`Django.db.models.Model`**，因此创建类的时候需要带上**`models.Model`**，表示类的继承关系：

```python
class UserInfo(models.Model):
    name = models.CharField(max_length=32)  
    # CharField()方法创建类型为字符串类型的字段，max_length表示字段长度。
    password = models.CharField(max_length=64)
    age = models.IntegerField(max_length=3)
    # IntegerField()方法创建类型为整形类型的字段
    mail = models.EmailField()
```

​	然后Django的ORM框架会将其翻译为类似下面的SQL语句，创建对应的表：

```mysql
create table app01_userinfo(
    id int primary key auto_increment,
    name varchar(32),
    password varchar(64),
    age int(3),
    mail varchar(32)
)
```

​	创建或修改完模型之后，需要通过迁移（migrations）来应用这些变更到数据库。Django的迁移系统记录模型变更，且能自动或手动将这些变更应用到数据库中。

​	**创建迁移文件：**

```powershell
python manage.py makemigrations
```

​	**应用迁移到数据库：**

```powershell
python manage.py migrate
```

> 注意！迁移之前，app必须已经在`settings.py`中被**注册**，否则迁移无效。

​	执行上面两条命令之后，Django就会在数据库中生成一个`应用名_类名`的数据表。例如我的应用名为`app01`，类名为`UserInfo`，那么就会生成一个`app01_userinfo`（全小写）的一个数据表。

​	在第一次执行上面两条命令时会生成除用户主动创建的表以外的其他表，这是Django提供的一些功能所必须要使用到的表：

```sql
                        +----------------------------+
                        | Tables_in_django           |
                        +----------------------------+
                        | auth_group                 |
                        | auth_group_permissions     |
                        | auth_permission            |
                        | auth_user                  |
                        | auth_user_groups           |
                        | auth_user_user_permissions |
                        | django_admin_log           |
                        | django_content_type        |
                        | django_migrations          |
                        | django_session             |
                        +----------------------------+
```



### Django操作数据