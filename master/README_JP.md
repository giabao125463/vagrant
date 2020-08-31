# 環境構築手順

## VirtualBoxをインストール

1. [https://www.virtualbox.org/wiki/Downloads](https://www.virtualbox.org/wiki/Downloads) からダウンロードしてインストール

2. インストールが完了したらターミナルでバージョンを確認

    ```bash
    vboxmanage --version
    # => 6.0.14r133895
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
    # => Vagrant 2.2.6

    vagrant plugin list
    # => vagrant-vbguest (0.23.0)
    ```

    **備考：** 異なるバージョンの`vagrant-vbguest`を使用していると`vagramt up`で失敗することがあるので、一度アンインストールしてから再度インストールする

    ```bash
    vagrant plugin uninstall vagrant-vbguest
    vagrant plugin install vagrant-vbguest --plugin-version 0.23.0
    ```

## ソースをダウンロード

1. Google Driveから `Google Drive > ... > 01_環境構築手順書 > vagrant_setup` フォルダをダウンロードする

2. Gitからソースコードを`sol_traveler_ec-site`という名前でクローンする

3. データベースのダンプファイルを`genkou_traveler_eccube_db_20200108001.dump`という名前で`postgres`フォルダに格納しておく

4. Vagrantの準備を行う

    * CentOS 8のboxファイルをダウンロード

        ```bash
        wget http://cloud.centos.org/centos/8/x86_64/images/CentOS-8-Vagrant-8.1.1911-20200113.3.x86_64.vagrant-virtualbox.box centos_8.box
        ```

    * Vagrantにboxファイルを追加する

        ```bash
        vagrant box add centos_8 centos_8.box
        ```

5. Vagrantを起動

    ```bash
    vagrant up
    ```

6. ファイル書き換え

    * DB設定: `php/install.php` を `sol_traveler_ec/data/install.php` にコピーする
    。 (Notice when commit git).
    * php.ini (任意)
      * `/etc/php.ini`に下記設定を追加する

        ```ini
        mbstring.language?=?Japanese
        default_charset = "euc-jp"
        ```

      * 設定追加後は `sudo service httpd restart` でApacheを再起動
    * Note: config some common file  
    * `sol_traveler_ec-site/html/.htaccess`24〜31行を`IfModule`タグで囲う

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

    * Smarty: 次のエラーが出る場合は、エラーメッセージに従ってディレクトリ・ファイルを作成する

        ```log
        Fatal error : Smarty error: the $compile_dir 'var/www/sol_traveler_ec/data/Smarty/template_c/goods/aff'does not exist, or is not a directory in 'var/www/sol_traveler_ec/data/module/Smarty/libs/Smarty.class.php'
        ```

    上記の場合は`var/www/sol_traveler_ec/data/Smarty/template_c`フォルダ内に`goods`フォルダを作成し、その中にさらに`aff`フォルダを作成する。

* ホスト環境の`/etc/hosts`にhosts設定を登録する
  * `192.168.33.10 giftland.trunk`
  * `192.168.33.10 traveler-store.trunk`
  * `192.168.33.10 c.giftland.trunk`

1. 表示確認

    * フロント画面
        * https://giftland.trunk
        * https://traveler-store.trunk
        * https://c.giftland.trunk

    * 管理画面
        * https://giftland.trunk/admin
        * アカウント: `admin`/`password`

2. エラー対応

    * Smartyの書き込み権限がないので、次のコマンドを実行する

        ```bash
        sudo chmod -R 777 sol_traveler_ec/data/Smarty/templates_c/
        ```

    * Forbiddenとなる場合は次のコマンドを実行する:

        ```bash
        sudo setenforce 0
        ```

## 確認

### ミドルウェアが想定のバージョンか

1. PHP:

    ```bash
    php --version
    # => PHP 7.3.13 (cli) (built: Dec 17 2019 10:29:15) ( NTS )
    ```

2. Apache:

    ```bash
    httpd -v
    # => Server version: Apache/2.4.37 (centos)
    # => Server built: Dec 23 2019 20:45:34
    ```

3. Postgresql:

    ```bash
    psql --version
    # => psql (PostgreSQL) 10.6
    ```

4. Postfix:

    ```bash
    postconf mail_version
    # => mail_version = 3.3.1
    ```

### 各ミドルウェアが起動できているか

1. Apache :

    ```bash
    service httpd status
    # => Active: active (running) since Tue 2020-01-21 07:25:44 UTC; 1h 18min ago
    ```

2. PHP is an apache module so we can check PHP status by command above
3. Postfix:

    ```bash
    service postfix status
    # => Active: active (running) since Tue 2020-01-21 07:25:47 UTC; 1h 21min ago
    ```

4. Postgresql:

    ```bash
    service postgresql status
    # => Active: active (running) since Tue 2020-01-21 07:25:43 UTC; 1h 22min ago
    ```

### DBのリストアが成功しているか

1. `sudo -u postgres psql`コマンドを実行してデータベースに接続する
2. `\l`コマンドでDB一覧が表示されるので、`traveler_eccube_db`が存在することを確認する
3. `\c traveler_eccube_db`コマンドでDBを切り替え、`\dt`でテーブルが作成されていることを確認する
4. `select * from dtb_member;`コマンドでdtb_memberテーブルにデータがインポートされていることを確認する

**備考：** `\q`コマンドで終了
