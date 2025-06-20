# tutorial実施及びdockerで環境構築検討用  
## 環境  
<img alt="Static Badge" src="https://img.shields.io/badge/wsl2-w?style=plastic&logo=linux&logoColor=000000&labelColor=%23FCC624&color=%23FCC624"> <img alt="Static Badge" src="https://img.shields.io/badge/ubuntu-u?style=plastic&logo=ubuntu&logoColor=%23ffffff&labelColor=%23E95420&color=%23E95420"> <img alt="Static Badge" src="https://img.shields.io/badge/debian-l?style=plastic&logo=debian&logoColor=ffffff&labelColor=A81D33&color=A81D33">  
<img alt="Static Badge" src="https://img.shields.io/badge/Docker-d?style=plastic&logo=docker&logoColor=%23ffffff&labelColor=%232496ED&color=%232496ED">
<img alt="Static Badge" src="https://img.shields.io/badge/NGINX-n?style=plastic&logo=nginx&logoColor=%23ffffff">
<img alt="Static Badge" src="https://img.shields.io/badge/MySQL-m?style=plastic&logo=mysql&logoColor=%23ffffff&labelColor=%234479A1&color=%234479A1">
<img alt="Static Badge" src="https://img.shields.io/badge/php-p?style=plastic&logo=php&logoColor=%23ffffff&labelColor=%23777BB4&color=%23777BB4">
<img alt="Static Badge" src="https://img.shields.io/badge/-%20filament?style=plastic&logo=filament&logoColor=000000&label=filament&labelColor=%23FDAE4B&color=%23FDAE4B">  
<img alt="Static Badge" src="https://img.shields.io/badge/Laravel12-l?style=plastic&logo=laravel&logoColor=%23ffffff&labelColor=%23FF2D20&color=%23FF2D20">
<img alt="Static Badge" src="https://img.shields.io/badge/bun-b?style=plastic&logo=bun&logoColor=%23ffffff&labelColor=%23000000&color=%23000000">
<img alt="Static Badge" src="https://img.shields.io/badge/bootstrap-b?style=plastic&logo=bootstrap&logoColor=%23ffffff&labelColor=%237952B3&color=%237952B3">
<img alt="Static Badge" src="https://img.shields.io/badge/vite-v?style=plastic&logo=vite&logoColor=%23ffffff&labelColor=%23646CFF&color=%23646CFF">  


## 構築手順  
#### 1. wsl使用の為、仮想マシン プラットフォームを有効化  
####  (wslがインストールされていない場合:Linuxカーネル更新プログラムパッケージを  
####  インストールする)  
#### 2. wsl --set-default-version 2 コマンドでLinuxを標準でWSL2上で動くように設定  
#### 3. wsl --list --verbose　コマンドでLinuxがWSL1とWSL2のどちらで動いているかを確認  
#### 4. ubuntuをインストール  
#### 5. terminalにてアカウント作成  
#### 6. 任意のフォルダ作成  
#### 7. Docker Desktopインストール  
#### 8. dockerでwslを使用する設定に変更  
#### 9. terminalに戻りdockerで使用するイメージのフォルダ構成作成  
#### 10. Dockerfileにて、使用するイメージ作成の設定  
#### 11. composer.ymlにて作成するコンテナの初期状態を  
#### 「ports:」「volumes:」などYAML形式を用いて定義。  
#### 12. その他の使用するイメージの設定ファイル(my.cnf、default.conf)作成  
#### 13. docker compose up -d　コマンドを実行してコンテナの作成・起動  
#### 14. docker exec -it conteinerID bashでコンテナにはいる  
#### 15. 以降はLaravel12の環境構築  
#### ※bunインストールがalpineだとうまくいかなかった為、今回はphp-fpm(debian)を使用
#### 16. composer create-project laravel/laravel example　コマンドでLaravelプロジェクトの作成  
#### 17．cd example  
#### 18. php artisan serve --host 0.0.0.0　コマンドで開発サーバーを起動
#### ※初回のみ実施、以降はdocker compose up -dでDoker起動 
#### ※エラー：failed to open stream: Permission denied  
#### chmod -R 777 storage　コマンドで解消  
#### 19. mysql使用の為、".env"の下記内容を修正
#### DB_CONNECTION=mysql　sqlite→mysql  
#### DB_HOST=127.0.0.1　使用しているdb名に修正  
#### DB_HOST以降からDB_PASSWORDまでのコメントアウト解除及び、自身で設定した内容への修正を行う  
#### 20. php artisan migrate　コマンドでマイグレーション仕直す  
#### 21. composer require filament/filament:"^3.2" -W　コマンドでfilamentインストール  
#### 22. php artisan filament:install --panels　コマンド実行でpanelインストール  
#### 23. php artisan make:filament-user　コマンド実行でuser登録情報作成  
#### 24. bun install　コマンドでbunインストール  
#### 25. bun run build　コマンド実行
#### 26. composer require --dev "squizlabs/php_codesniffer=*"　コマンドでPHP_CodeSniffierのインストール
#### 27. config/app.phpの言語設定を変更し日本語化  
#### 28. `http://localhost:8000/admin`　でアクセス
#### ※ポート番号までの場合Laravel12の画面表示のみとなりログインはできない  
#### ※個人お試し用以外での用途は非推奨  
## git clone後  
#### 1. docker exec -it conteinerID bashでコンテナにはいる  
#### 2．cd example　コマンド実行  
#### 3. composer update　コマンド実行でautoload.php作成  
#### 4. cp .env.example .env　コマンドで.env作成  
#### 5. php artisan key:generate　コマンド実行  
#### 6. php artisan migrate　コマンドでdb再度作成
#### 7. php artisan make:filament-user　コマンド実行でuser登録情報再度作成  
#### 8. bun install　コマンドでbunインストール  
#### 9. bun run build　コマンド実行  
## 参考  
#### [tutorialはこちら](https://filamentphp.com/docs/3.x/panels/getting-started#prerequisites)  
## 所感  
#### 一般的な使い方はよくわからないけど、頑張らなくても素敵レイアウトができる。  
#### 頑張ってすべてのキャプションを日本語にしたかったが変更箇所が不明でできなかった。  
#### 通貨単位が€なので円のformatは何なのか調べて初めて知った。
