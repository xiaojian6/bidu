# 游戏转入交易所 
#### 请求地址: *`/api/exchange/shift_to`*  
#### 请求类型: `POST`
|参数|类型|必填|参与签名|说明|
|-|:-:|:-:|:-:|-|
|appid|string|是|是|appid|
|address|string|是|是|转入地址|
|currency_name|string|是|是|转入币种名称|
|number|float|是|是|转入数量|
|voucher_no|string|是|是|游戏转出凭证号|
|timestamp|integer|是|是|时间戳|
|nonce|string|是|是|随机口令|
|signature|string|是|--|签名|
#### 返回值
```
成功:
{
    "type": "ok",
    "message": "提交成功",
}
失败:
{
    "type": "error",
    "message": "签名无效",
}
```
### 【签名计算】
将除signature之外的参数按照key升序排序，拼接成query_string，然后分用appid和appsecret与其首尾拼接，对拼接结果做md5运算。  
### 【PHP示例代码】
```
$param = [
    'appid' => '100001',
    'address' => '0xfngoirqonveohrqorjewqofnsnfsopafjne',
    'number' => 100,
    'currency_name' => 'BEAU',
    'voucher_no' => '201',
    'timestamp' => 1548167684,
    'nonce' => 'fq58Ne',
];
ksort($param, SORT_STRING);
$signature = md5($appid . http_build_query($param) . $appsecret);
```