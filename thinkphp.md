# ThinkPHP

### 一、TP是什么？

> ThinkPHP是一个快速、兼容而且简单的轻量级国产PHP开发框架

### 二、利用框架开发的优势

* 有利于团队开发
* 快速简洁
* 安全性较高
* 更好地优化

### 三、下载、安装TPS

### 四、目录结构

* application  （网站应用)：index（前台模块）；config.php（网站的配置文件）；database.php（网站的数                      

  ​                                              据库配置文件）

* extend(扩展类库目录)

* public(web部署目录):index.php(入口文件)；static(静态资源)

* runtime(网站运行时的临时目录)

* thinkphp(TP框架的核心文件)

* vendor(第三方类库目录)

### 五、URL地址

>`http://localhost/thinkphp/public/index.php/index/index/index`

* http：网络传输协议
* `localhost/thinkphp/public/index.php`  换成域名
* index(第一个) 前台模块
* index(第二个) 控制器
* index(第三个) 前台方法

### 六、使用TP

* 开启错误提示 config.php  app_debug
* 写方法 

### 七、MVC模式

>Model View Controller，是模型(model)－视图(view)－控制器(controller)的缩写，一种软件设计典范，用一种业务逻辑、数据、界面显示分离的一种开发模式

* C-controller-控制器：主要负责业务逻辑
* V-view-视图：网站界面
* M-model-数据模式：主要负责数据库的相关处理


### 八、基本概念

#### 1、入口文件

> `public/index.php`  主要调配整个网页的资源加载

#### 2、应用

>`applicetion` 一个应用代表一个网站

#### 3、模块

>应用中的文件夹，一个文件夹代表一个模块，TP中默认有前台index模块.

#### 4、控制器：控制整个业务流程

* 一个控制器到代表一个模块功能
* 控制器文件名的首字母大写
* 声明控制器时要与文件名以一一对应，首字母大写
* 访问：入口文件\模块\控制器\方法

#### 5、方法

* 每一个方法，相当于传统开发中的每一个页面
* 创建方法：`public function add() {echo "1111"}`
* 访问：地址栏

#### 6、模型

> 主要对数据库进行相关操作与注册    

#### 7、视图

> 网站的静态页面

#### 8、命名空间

* 产生原因：解决命名冲突的问题

* 声明命名空间

  ````php
  namespace uek;
  function var+dum(){
   echo "我是uek下的var_dump"   
  }
  ````

* 跨命名空间调用法

  ````php
  // 在uek2空间下调用uek中的var_dump方法
  \uek\var_dump
  //调用系统方法，调动系统中的var_dump方法
  \var_dump(1111111)
  ````

* 跨命名空间调用类

  ````php
  //用use导入空间类 类名相同需要起别名
  use \uek\Pereson as Person1;
  ````

* 注意事项：

  * 命名空间的书写必须顶头书写，前面不能有输出
  * 一个页面可以有多个命名空间，但建议只声明一个

### 九、控制器的学习

#### 1、如何创建控制器

#### 2、控制器如何加载页面

    ````php+HTML
return view();
​    ````

#### 3、页面跳转

```php
//用ues导入系统控制器
//参数：提示信息，跳转地址，给页面传参，跳转时间
    $this->success('新增成功', 'User/list');
```

#### 4、空操作

>防止用户的恶意操作

```php
public function _empty($name)
{
//重定向到指定的URL地址 并且使用302
$this->redirect('http://thinkphp.cn/blog/2',302)；
}
```

* 每个控制器都有空操作方法

#### 5、空控制器

> 当系统找不到指定的控制器名称的时候，系统会尝试定位空控制器(Error)，利用这个机制
> 我们可以用来定制错误页面和进行URL的优化

```php
Error
```

### 十、数据库操作

1、`找到databasephp` 连接数据库

2、引入数据库类 ues Db；

3、执行

```php
Db::query($sql);	//返回查询的数据
Db::execute($sql);	//返回影响的行数
```

### 十一、CURD操作

> 数据库技术的单词缩写

#### 查询构造器：

* table():   设置数据表
* select():    查询所有数据(二维数组)
* find():    查询一条数据(一维数组)
* where():    查询指定条件
  * 单条件：`where('id','2')`
  * 多条件：`where("id='2' and password='1234567'")`
  * 区间范围：`where("id>=10 and id<=25")`
* field()：查询指定字段
* order()：排序
  * 升序：order("sort")
  * 降序：order("sort desc")
* limit()：截取
* count()：数据总数

#### 添加构造器：

* insert($data)：插入一条数据，返回值为影响行数
* insertGetId($data)：插入一条数据，返回值为插入数据的id

#### 删除构造器：

* 删除主键：`Db::table("admin")->delete("1")`;
* 按照其他条件删除：`Db::table("admin")->where('username','lisi')->delete("")`

#### 修改构造器：

* ```php
  $data=[
      "username"="lisi",
      "password"="1235678"
  ];
  $res=Db::table("admin")->where('id','20')->update("$data"); 
  ```

### 十二、联想后台

#### 查看数据：

* 数据渲染
  * 分配数据：`$this->assign(name,value)`；视图：$name
  * 循环渲染：`{volist name="admin"  id="value"}{/volist}`
  * 条件判断：`{if condition="$value['status']"}`
* 总数据：
  * count()---分配数据---显示数据
* 分页效果：
  * paginate(10)查数据
  * {$admin->render}
* 搜索：前台  中台  后台
  * 跳转到本页 `$_GET`
  * 条件：`where("username like '%$seach%'")`

#### 添加数据：

* 跳转添加页面：url();
* 处理添加方法：insert;
* 判断：用户名/密码是否输入，密码长度，密码是否一致，用户名是否已存在

#### 删除数据：

* 跳转删除方法：url();
* 接收id：input("id");
* 删除

#### 修改数据：

* 携带id跳转edit方法，渲染数据
* 跳转update，接收数据，判断

### 十三、模板

#### 1、模板赋值

* `$this->assign("name","value")`;
* `return view("","name"=>"value")`；

#### 2、变量输出

* 常规输出：`<?php echo $str?>`；
* TP：`{$str}`；

#### 3、修改定界符

config.php---tpl_begin/tpl_end

#### 4、使用函数

* 常规输出：`<?php echo date("Y-m-d H:i:s",$str)?>`；
* 使用：`{：date("Y-m-d H:i:s",$str)`；

#### 5、默认值

* 三元运算符：`{$nickname?$nickname:"该用户未设置昵称"}`；
* |：`{$nickname|defaule="该用户未设置昵称"}`；

#### 6、循环标签   volist

```php
{volist name="" id=""}
{/volist}
name：数据名
id：值，相当于foreach中的$v
```

#### 7、for标签

```php
{for start="10" end="20" step="2" name="a"}
<div>{$a}</div>
{/for}
//start：开始值
//end：结束值
//step：步进值	写降序值为负值
//name：指定循环变量名	默认变量为i
//comparison：指定升序lt降序gt
```

#### 8、if标签

```php
{if condition="$i%2"}
<div style="color=red">{$i}</div>
{else /}
<div style="color=blue">{$i}</div>
{/if}
```

#### 9、switch标签

```php
{switch name="变量" }
{case value="值1" break="0或1"}输出内容1{/case}
{case value="值2"}输出内容2{/case}
{default /}默认情况
{/switch}

```

