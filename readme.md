# App-Slice <应用分片管理组件> 
为`Laravel`项目量身开发项目分治组件，可以让您以不同应用来更加灵活的组织项目结构
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
## 3、提取配置文件（可选）：
```composer
    在命令行执行：php artisan vendor:publish
```
## 4、修改默认配置（可选）
   * 打开项目根目录下的config目录下的slice.php。
   * 修改core配置项下的name与path,其中name是项目组目录名，你未来所创建的所有目录都将放在这里，默认是Core;path是项目组所在路径默认放在根目录的app下。

## 5、创建应用
```php
1) 命令行运行：php artisan make:app
2) 按提示输入应用名称
3）进度条打满，应用就被创建完成
```
## 6、辅助命令
#### （1） php artisan make:app 创建应用
#### （2） php artisan make:c  `控制器名`  --app=`应用名称` --r 在指定应用内创建控制器，
+ `name`为控制器名称，不穿值将提示询问。
+ --app=的值为指定应用名称，如果不传，则进入选择界面，这样更精确。 
+ --r 当输入此选项时将创建一个资源控制器
#### （3） php artisan make:m `控制器名` --app=`应用名称` 在指定应用内创建模型
+ `name`属性与`--app`选项用法同上

####  （4）php artisan slice 查看当前版本


## 7、使用说明
假如我们采用默认配置，创建一个叫home的应用，slice就会在项目根目录的`core`目录下创建一个为Home的应用，slice已经为你默认创建的Controllers和Views，他们是存放控制器和视图文件的。
并且slice默认创建了一个demo的控制器和视图。在Home/下还已经建立好了route.php用来书写路由，他们被分配在home分组下。
你可以使用app_view()方法来像view()一样渲染视图,只不过他会动态查找本应用下的对应的视图文件，而不是resources目录下。使用过程中如遇到问题或者意见建议，请联系作者微信：`qq927994432`。如果此扩展对您的项目有帮助，
请帮作者点亮小星星（^_^）Y