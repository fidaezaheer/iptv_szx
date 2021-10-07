# iptv_szx

SZX IPTV Management System

PHP/Mysql


## Stop MySQL Server on Linux

To stop MySQL Server on Linux, you use the following command:

`/etc/init.d/mysqld stop` 

Some Linux distributions provide server command:

`service mysqld stop` 

Or

`service mysql stop` 

In this tutorial, you have learned how to stop MySQL Server on Windows and Linux.


## Start MySQL Server on Linux

1.  sudo service mysql start.
2.  sudo /etc/init.d/mysql start.
3.  sudo systemctl start mysqld.
4.  mysqld.

## Start, Stop and Restart Nginx using `systemctl` [#](https://linuxize.com/post/start-stop-restart-nginx/#start-stop-and-restart-nginx-using-systemctl)

SystemD is a system and service manager for the latest Ubuntu [18.04](https://linuxize.com/post/how-to-install-nginx-on-ubuntu-18-04/) /[16.04](https://linuxize.com/post/how-to-install-nginx-on-ubuntu-16-04/) , CentOS [7](https://linuxize.com/post/how-to-install-nginx-on-centos-7/) /[8](https://linuxize.com/post/how-to-install-nginx-on-centos-8/) , and Debian [10](https://linuxize.com/post/how-to-install-nginx-on-debian-10/) /[9](https://linuxize.com/post/how-to-install-nginx-on-debian-9/) releases.

Whenever you make changes to the Nginx configuration, you need to restart or reload the webserver processes. Execute the following command to restart the Nginx service:

```
sudo systemctl restart nginx
```

When adding or editing server blocks, prefer reloading over restarting. Restart the service only when making significant modifications like changing ports or interfaces. On reload, Nginx loads the new configuration, starts new worker processes with the new configuration, and gracefully shuts down old worker processes.

Run the command below to reload the Nginx service:

```
sudo systemctl restart nginx
```

Nginx can also be directly controlled with [signals](https://linuxize.com/post/kill-command-in-linux/) . For example, to reload the service, you can use the following command:

```
sudo /usr/sbin/nginx -s reload
```

To start the Nginx service, execute:

```
sudo systemctl start nginx
```








[![](https://github.com/david-syncoria/iptv_szx/raw/main/images/screencapture/left1.png)](https://github.com/david-syncoria/iptv_szx/blob/main/images/screencapture/left1.png)


[![](https://github.com/david-syncoria/iptv_szx/raw/main/images/screencapture/left2.png)](https://github.com/david-syncoria/iptv_szx/blob/main/images/screencapture/left2.png)


[![](https://github.com/david-syncoria/iptv_szx/raw/main/images/screencapture/left3.png)](https://github.com/david-syncoria/iptv_szx/blob/main/images/screencapture/left3.png)


[![](https://github.com/david-syncoria/iptv_szx/raw/main/images/screencapture/left4.png)](https://github.com/david-syncoria/iptv_szx/blob/main/images/screencapture/left4.png)


[![](https://github.com/david-syncoria/iptv_szx/raw/main/images/screencapture/left5.png)](https://github.com/david-syncoria/iptv_szx/blob/main/images/screencapture/left5.png)


[![](https://github.com/david-syncoria/iptv_szx/raw/main/images/screencapture/left6.png)](https://github.com/david-syncoria/iptv_szx/blob/main/images/screencapture/left6.png)


[![](https://github.com/david-syncoria/iptv_szx/raw/main/images/screencapture/left7.png)](https://github.com/david-syncoria/iptv_szx/blob/main/images/screencapture/left7.png)

Execute the following command to stop the Nginx service:

```
sudo systemctl stop nginx
```
