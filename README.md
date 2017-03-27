# YjcPHP
A simple framework for study.

## Examples

you can visit by :
```
http://localhost/index.php/Index/verify
```

If you are only used for API development and want to return JSON data, you can edit config file `App/Config/config.php`:
```
'return_type' => 'json', //html,json
```
then you visit the index:
```
http://localhost/index.php/
```
sample response data :
```
{"code":0,"msg":"\u6210\u529f","data":[{"id":"1","name":"allen","gender":"1","age":"20","flag":"1"},{"id":"5","name":"eve","gender":"2","age":"20","flag":"1"}]}
```
