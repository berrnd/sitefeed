sitefeed
========

Monitor websites for changes and get them as a unified diff via RSS

## Requirements
Any webserver with PHP and the xdiff extension

## Installation
Just unpack on your webserver and browse to the folder to get a bookmarklet or manually subscribe like so:
`http://<your-install-path>/rss.php?url=http://somesite`

#### Installation of the PHP xdiff extension
Normally you can install xdiff, just like many other extensions, through PECL.

However, I had problems on my Ubuntu 12.04 x86 machine. So if you also encounter problems, for me the previous version worked which I got to work like so:
```
cd /tmp
wget http://www.xmailserver.org/libxdiff-0.23.tar.gz
tar -xzf libxdiff-0.23.tar.gz
cd libxdiff-0.23
./configure
make
sudo make install
wget http://pecl.php.net/get/xdiff-1.5.1.tgz
sudo pecl install xdiff-1.5.1.tgz
echo extension=xdiff.so | sudo tee /etc/php5/fpm/conf.d/xdiff.ini
```

#### License
The MIT License (MIT)
