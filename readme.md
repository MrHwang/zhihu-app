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


#### 用户注册

- 注册需要用到邮件激活，引用两个包
    > https://github.com/NauxLiu/Laravel-SendCloud
    `composer require guzzlehttp/guzzle`和`composer require naux/sendcloud`

- 配置sendcloud
