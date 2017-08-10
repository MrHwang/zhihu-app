## Laravel vue.js开发知乎实例

### 版本说明

Laravel使用5.3版本，Vue.js使用2.0版本

### day01

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