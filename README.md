## 生成镜像

脚本没有执行权限

```
git update-index --chmod=+x run.sh
```



```bash
docker build -t yaokun/php-nginx:7-alpine .
```

> 需要注意文件格式，应为unix格式，脚本文件需要有执行权限。
>


## 运行镜像
```bash
docker run -d -p 80:80 yaokun/php-nginx:7-alpine
```

