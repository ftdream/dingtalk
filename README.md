# sensor日志监控sdk lua版

## 概述

该SDK是用于广告等接口提供日志监控服务的lua版sdk

## 注意

+ **该sdk会在服务器端缓存1k的日志，需要在服务器开启lua_shared_dict
+ **luajit版本目前兼容lua 5.1 API+ABI, 需要安装luasocket的2.0.2版本

## 安装需求

+ **luaJIT
+ **luasocket

## 版本

## 接入指南

### （一）参数说明

<table>
	<tr>
		<th>参数</th>
		<th>值类型</th>
		<th>默认值</th>
		<th>说明</th>
	</tr>
	<tr>
		<td>disabled</td>
		<td>boolean</td>
		<td>false</td>
		<td>是否关闭采样服务</td>
	</tr>
	<tr>
		<td>buffer_key</td>
		<td>string</td>
		<td>sensor_log</td>
		<td>日志缓存名称</td>
	</tr>
</table>

### （二）配置说明

<table>
	<tr>
		<th>参数</th>
		<th>值类型</th>
		<th>默认值</th>
		<th>说明</th>
	</tr>
	<tr>
		<td>buffer_size</td>
		<td>integer</td>
		<td>1024</td>
		<td>日志缓存大小</td>
	</tr>
	<tr>
		<td>sensor_server.host</td>
		<td>string</td>
		<td>10.79.80.109(测试ip)</td>
		<td>日志服务器ip</td>
	</tr>
	<tr>
		<td>sensor_server.port</td>
		<td>integer</td>
		<td>29981</td>
		<td>日志服务器监听端口号</td>
	</tr>
	<tr>
		<td>prefix.*.key</td>
		<td>string</td>
		<td>acc.sso.、gauge.sso.、spl.sso.</td>
		<td>日志记录前缀</td>
	</tr>
	<tr>
		<td>prefix.*.rule</td>
		<td>string</td>
		<td>ACC、NUM</td>
		<td>udp协议规则前缀</td>
	</tr>
</table>

### （三）接入代码示例

```javascript

http {
        ...

		lua_shared_dict buffer 1m 

		...

        server {
                ...
                location /sensor {
 
                	content_by_lua '

						local cfg = {

						}
					    client = require "src.client"
						hc = client.new(cfg)
						hc:acc("api.ceshi.cc.ok", 1) 
               		';
               } 
 
        ...
        }
}
```

