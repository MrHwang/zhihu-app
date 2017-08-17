
## Laravel vue.js开发知乎实例

### 版本说明

Laravel使用5.3版本，Vue.js使用2.0版本

#### 开发准备

- win10 64位系统，vagrant1.9.7（截止到目前2017-08-07最新版），VirtualBox5.1.26（截止到目前2017-08-07最新版）
- 开发环境使用官方推荐的Homestead，实际使用Homestead3.0
- 编辑器选用phpstorm2016.3.3

#### 项目配置和用户表设计

- `> cd ~/Homestead `
- 编辑Homestead目录下的Homestead.yaml文件设置目录、站点和数据库（注意内容的缩进！）
- 编辑hosts文件设置虚拟ip
- `> vagrant up`或者`vagrant reload --provision`
- `$ cd ~/Code`
- `$ composer create-project --prefer-dist laravel/laravel zhihu-app 5.2.*`
- phpstorm中设置app目录为Resource Root
    > 在 app 目录下创建新的文件夹或者 php 文件的时候，只要符合 psr-4 的标准，phpstorm 就会自动加上命名空间
- 用户表结构设计
   `$ php artisan migration`

#### 用户注册

- 注册需要用到邮件激活，引用两个包
    > https://github.com/NauxLiu/Laravel-SendCloud
    `composer require guzzlehttp/guzzle`和`composer require naux/sendcloud`

- 配置sendcloud
    - `.env`文件中配置MAIL_DRIVER/SEND_CLOUD_USER/SEND_CLOUD_KEY,`.env`文件中需要注意大小写，注释，如`MAIL_DRIVER=sendCloud`会提示`Driver [sendCloud] not found`，准确设置应该是`MAIL_DRIVER=sendcloud`
    -  配置`app.php`文件
- 使用Laravel自带的登录注册功能
    `$ php artisan route:list`
    `$ php artisan make:auth`
    `$ php artisan rote:list`

- 引入Laravel开发三件套 
    > https://segmentfault.com/a/1190000005085328
    > http://www.bcty365.com/content-153-5897-1.html
    1. `composer require barryvdh/laravel-debugbar --dev`（页面调试）
    2. `composer require barryvdh/laravel-ide-helper --dev`、`php artisan ide-helper:generate`和`php artisan ide-helper:models`（代码补全）
    3. `composer require mpociot/laravel-test-factory-helper --dev`（数据生成）
    
#### 用户登录

- 引入laracasts/flash
    > https://github.com/laracasts/flash
    `composer require laracasts/flash`

#### 本地化和自定义消息

- 引入overtrue/laravel-lang
    > https://github.com/overtrue/laravel-lang
    `composer require "overtrue/laravel-lang:~3.0"`
    
#### 实现找回密码

#### 设计问题表

- `php artisan make:model Question -m

#### 发布问题

- 引用UEditor
    `composer require "overtrue/laravel-ueditor:~1.0"`
    > https://github.com/overtrue/laravel-ueditor

- `php artisan make:controller QuestionsController --resource`
- Laravel 5.3 UEditor图片上传失败问题
    > php artisan storkage:link
    > https://stackoverflow.com/questions/39496598/laravel-5-3-storagelink-symlink-protocol-error
    
#### 验证问题表单字段

- validator验证
- request验证(对Question中的store方法进行Request注入/验证)
    `php artisan make:request StoreQuestionRequest`
    
#### 美化编辑器

- 使用`simple-ueditor`替换`public/vender/ueditor`
> https://github.com/JellyBool/simple-ueditor

```
$ cd ~/Code
$ git clone https://github.com/JellyBool/simple-ueditor.git
```

#### 定义话题与问题的关系

- `php artisan make:model Topic -m`
- 话题与问题关联关系表
`php artisan make:migration create_questions_topics_table --create=question_topic`
- `php artisan migrate`
- Topic与Question模型建立关联关系

#### 使用select2优化话题选择

> https://select2.github.io/examples.html
> https://select2.github.io/

- 下载select2的css文件
    ```
    $ cd ~/Code/zhihu-app/resources/assets`
    $ mkdir css
    $ cd ~/Code/zhihu-app/resources/assets/css
    $ wget https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css
    ```
    
-  下载slelect2的js文件
    ```
    $ ~/Code/zhihu-app/resources/assets/js
    $ wget https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js
    ```

-  bootstrap.js中配置加载select2.min.js
-  app.scss配置加载select2.min.css
-  gulp编译
    > https://npm.taobao.org/
    > https://github.com/cnpm/cnpm
    - `sudo npm install -g cnpm --registry=https://registry.npm.taobao.org`
    - `cpm install`
    - `gulp`
    
**或者**
- `$ yarn config set registry https://registry.npm.taobao.org`
- `$ yarn install`

- 解决/预防css缓存问题
    1. gulpfile.js中使用` mix.version(['js/app.js','css/app.css'])`
    2. app.blade.php中使用 `{{ elixr('css/app.css') }}`代替`'/css/app.css'`,`{{elixir('js/app.js')}}`代替`'/css/app.css'`

- 生成测试数据
    - 编辑ModelFactory
    - `php artisan tinker`或者`tinker`
    - 测试能否准确生成`>>> factory(App\Topic::class,11)->make()`
    - 生成数据`>>> factory(App\Topic::class,11)->create()`
- 测试路由
`http://zhihu.dev/api/topics?q=at`

#### 实现选择话题整个流程

#### 使用 Repository 模式

> 当Controller中处理业务请求需要调用多个Model获取数据时，就需要在Controller中引用多个Model来获取数据，在大型项目中，这便会让Controller看起来非常臃肿。但，如果把这些获取多个Model的数据的处理放在某一个Model中，会让这个Model变得没有预期的那么“纯粹”（一个Model预期只处理一个表的数据获取/转换等）。换一种思路就是，在某个Controller和多个Model中分出一个Repository，这个Repository中来引入Model来处理业务，这种方式一方面简化了Controller代码，也能保留对Model层的预期。
> 比如，场景：查询某个订单的详细信息，需要查询订单表、订单商品表、仓库表(订单中的发货仓库id查询仓库表得到仓库名称)等。

#### 实现编辑问题

#### 问题Feed和删除问题

- 显示问题列表
- 删除问题

#### 创建问题的答案

- `php artisan make:model Answer -m`
- `php artisan migrate`
- 定义关系/关联表

#### 实现提交答案

- `php artisan make:controller AnswerController`
- `php artisam make:request StoreAnswerRequest`

#### 用户关注问题

- 登录用户才能提交答案
- 用户登录成功返回之前浏览页面
- 一个用户可以关注多个问题，一个问题可以被多个用户关注(创建关联关系)
`php artisan make:migration create_user_question_table --ccreate=user_question`
`php artisan migrate`
`php artisan make:model Follow`
`php artisan make:controller QuestionFollowController`

#### 使用Vuejs组件化

- phpstorm安装Vuejs插件（语法支持）/ Setting -> Languages & Frameworks -> Javascript -> Version -> ECMAScript 6
- QuestionFollowButton.vue
- 定义路由
- app.js中引入vue组件
- gulp
- gulp后vue组件修改需要重新gulp?
- show.blade中使用<question-follow-button></question-follow-button>使用vue组件
- vuejs的property传递参数

**遇到的问题**：
QuestionFollowButton.vue中声明的路由`'api/question/follower'`对应的路由是`http://zhihu.dev/questions/api/question/follower`，准确写法为
`'/api/question/follower'`


