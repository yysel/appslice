# App-Slice <应用分片管理组件> 
为`Laravel`项目量身开发项目分片组件，可以让您以不同应用来更加灵活的组织项目结构
## 1、安装： 
```composer
    在命令行执行：composer require kitty/appslice
```
## 2、添加服务提供者至``/config/app.php``文件下的'providers'数组内如下：
```php
'providers' => [
         Kitty\AppSlice\AppSliceProvider::class,
 ]
```
## 3、增加视图目录选项：修改/config/view.php的'paths'数组，添加一行，如下；
```php
      'paths' => [
            resource_path('views'),
            base_path()
        ],
```
## 4、提取配置文件：
```composer
    在命令行执行：php artisan vendor:publish
```
## 5、修改默认配置（可选）
   * 打开项目根目录下的config目录下的slice.php。
   * 修改core配置项下的name与path,其中name是项目组目录名，你未来所创建的所有目录都将放在这里，默认是Core;path是项目组所在路径默认放在根目录的app下。
   * 注意如果修改了默认的路径，并且不再是/app的时候，你需要修改composer.json,如下
   ```json
    "autoload": {
        "classmap": [
            "database"
        ],
        "psr-4": {
            "App\\": "app/",
            "Core\\": "Core/"
        }
    }
```
## 6、创建应用
    1) 命令行运行：php artisan make:app
    2) 按提示输入应用名称
    3）等待几秒钟应用就被创建完成
## 7、使用说明
假如我们采用默认配置，创建一个叫home的应用，slice就会在/app/Core/下创建一个为Home的应用，slice已经为你默认创建的Controllers和Views，他们是存放控制器和视图文件的，并且slice默认创建了一个demo的控制器和视图。在Home/下还已经建立好了route.php用来书写路由，他们被分配在home分组下。
你可以使用app_view()方法来像view()一样渲染视图,只不过他会动态查找本应用下的视图文件，而不是resources目录下