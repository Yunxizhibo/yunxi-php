### 云犀直播PHP-SDK, 官方API部分
@author  holger <whjsjq@gmail.com>
@link
@version 1.0

## 版本要求
PHP 版本 5.3 及以上

## 安装
### 手动引入
```php
require_once('/path/to/Yunxizhibo/Yunxizhibo.php');
```

### 接入方法
### 初始化
```php
$accessKey = 'accessKey';
$secretKey = 'secretKey';
 ```

### 填写AccessKey、SecretKey
 ```php
$yxObj = new Yunxizhibo($accessKey, $secretKey);
 ```

###获取活动列表
```php
$data = $yxObj->getActivityList();

if(empty($data)) {
   echo $yxObj->$errCode;
   echo $yxObj->$errMsg;
}
```
### 列表
 ```php
$list = $data["activitys"];
```
### 页数
 ```php
$pageCount = $data["pageCount"];
```

### 获取活动资料
 ```php
 $data = $yxObj->getActivityInfo();

if(empty($data)) {
   echo $yxObj->$errCode;
   echo $yxObj->$errMsg;
}
```

### 活动详情
```php
$activity = $data["activity"];
```


### 获取直播视频资料
```php
$data = $yxObj->getLivestreamInfo();

if(empty($data)) {
   echo $yxObj->$errCode;
   echo $yxObj->$errMsg;
}
```
### 直播视频详情
```php
$livestream = $data["livestream"];
```
### 微信嵌入地址
```php
$wechatPlayUrl = $data["wechatPlayUrl"];
```
### 网页嵌入地址
```php
$webPlayUrl = $data["webPlayUrl"];
```
### app嵌入地址
```php
$appPlayUrl = $data["appPlayUrl"];
```
### 播放器嵌入地址
```php
$embedPlayerUrl = $data["embedPlayerUrl"];
```
### 围观人数
```php
$totalNum = $data["totalNum"];
```


