# 環境構築手順

## VirtualBoxをインストール

1. [https://www.virtualbox.org/wiki/Downloads](https://www.virtualbox.org/wiki/Downloads) からダウンロードしてインストール

2. インストールが完了したらターミナルでバージョンを確認

    ```bash
    vboxmanage --version

    -> 6.0.14r133895
    ```

## Vagrantをインストール

1. [https://releases.hashicorp.com/vagrant/](https://releases.hashicorp.com/vagrant/) からダウンロードしてインストール

2. Vagrant のプラグインをインストール

    ```bash
    vagrant plugin install vagrant-vbguest
    ```

3. インストールが完了したらターミナルでバージョンを確認

    ```bash
    vagrant --version

    > Vagrant 2.2.6

    vagrant plugin list

    > vagrant-vbguest (0.23.0)
    ```

    Note: If Vagrant is using plugin vagrant-vbguest but with different version, please remove it and install that plugin with correct version

    ```bash
    vagrant plugin uninstall vagrant-vbguest
    vagrant plugin install vagrant-vbguest --plugin-version 0.23.0
    ```

## ソースをダウンロード

1. Download all file in folder `GDrive > ... > 01_環境構築手順書 > vagrant_setup` to your working folder.

2. Clone source code to working folder with folder name is `sol_traveler_ec-site`.

3. Download database file from Gdrive. This step is used for migrating database `genkou_traveler_eccube_db_20200108001.dump` to folder `postgres`

4. Vagrant preparation

    * Download CentOS 8 box

        ```bash
        wget http://cloud.centos.org/centos/8/x86_64/images/CentOS-8-Vagrant-8.1.1911-20200113.3.x86_64.vagrant-virtualbox.box centos_8.box
        ```

    * Add above box to Vagrant

        ```bash
        vagrant box add centos_8 centos_8.box
        ```

5. Start Vagrant

    ```bash
    vagrant up
    ```

6. File configuration

    * DB configuration: copy file content `php/install.php` to `sol_traveler_ec/data/install.php` (Notice when commit git).
    * php.ini (Optional configuration)
      * Add below config to /etc/php.ini

        ```ini
        mbstring.language?=?Japanese
        default_charset = "euc-jp"
        ```

      * Run command `sudo service httpd restart`
    * Note: config some common file  
    * In file sol_traveler_ec-site/html/.htaccess: Put line 24 to 31 into IfModule tag

        ```php
        <IfModule mod_php7.c>
            #基本はphp_ini.incで設定するが、ini_setで反映されないものはここで設定する
            #php_value mbstring.language Japanese
            #php_value output_handler mb_output_handler
            php_value max_execution_time 180
            php_value memory_limit 512M
            #php_flag mbstring.encoding_translation 1
            php_flag magic_quotes_gpc 0
            #php_flag session.use_cookies 0
        </IfModule>
        ```

    * Smarty: if there is error, create folder with given directory in error message.

        ```log
        Fatal error : Smarty error: the $compile_dir 'var/www/sol_traveler_ec/data/Smarty/template_c/goods/aff'does not exist, or is not a directory in 'var/www/sol_traveler_ec/data/module/Smarty/libs/Smarty.class.php'
        ```

    In error above, create folder `aff` inside `goods` folder with directory `var/www/sol_traveler_ec/data/Smarty/template_c`

* Hosting configuration: Append hosts config `/etc/hosts`
  * `192.168.33.10 giftland.trunk`
  * `192.168.33.10 traveler-store.trunk`
  * `192.168.33.10 c.giftland.trunk`

1. Page access

    * Frontend page
        * https://giftland.trunk
        * https://traveler-store.trunk
        * https://c.giftland.trunk

    * Admin page
        * https://giftland.trunk/admin
        * Account: `admin`/`password`
2. Errors resolve

    * Smarty can't write content. Solution: On host PC run command: `sudo chmod -R 777 sol_traveler_ec-site/data/Smarty/templates_c/`

    * Forbidden acess when reload/restart vagrant. SSH to vagrant and run command: `sudo setenforce 0`

## ミドルウェアが想定のバージョンか

1. PHP:

    ```bash
    php --version
    -> PHP 7.3.13 (cli) (built: Dec 17 2019 10:29:15) ( NTS )
    ```

2. Apache:

    ```bash
    httpd -v
    -> Server version: Apache/2.4.37 (centos)
    Server built: Dec 23 2019 20:45:34
    ```

3. Postgresql:

    ```bash
    psql --version
    -> psql (PostgreSQL) 10.6
    ```

4. Postfix:

    ```bash
    postconf mail_version
    -> mail_version = 3.3.1
    ```

## 各ミドルウェアが起動できているか

1. Apache :

    ```bash
    service httpd status
    -> Active: active (running) since Tue 2020-01-21 07:25:44 UTC; 1h 18min ago

    ```

2. PHP is an apache module so we can check PHP status by command above
3. Postfix:

    ```bash
    service postfix status
    -> Active: active (running) since Tue 2020-01-21 07:25:47 UTC; 1h 21min ago
    ```

4. Postgresql:

    ```bash
    service postgresql status
    -> Active: active (running) since Tue 2020-01-21 07:25:43 UTC; 1h 22min ago
    ```

## DBのリストアが成功しているかどうか

1. `sudo -u postgres psql`
2. Command `\l` to show all databases and check whether database `traveler_eccube_db` is existed.
3. Command `\dt` to show all tables
4. Command `select * from dtb_member;` to view all member that is imported to database `traveler_eccube_db`
