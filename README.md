# ペット診療・管理アプリケーション  

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

## プロジェクト概要
このアプリは、動物病院などで使う想定の、ペット（Patient）とその飼い主（Owner）、および治療（Treatment）を管理するためのサンプルです。
Laravel の Eloquent ORM を使ってデータの関連（飼い主→複数のペット、ペット→複数の治療）を実装し、Filament ライブラリで管理画面（CRUD）を提供します。

## 学習・検証目的
設計検証・技術選定の検証を目的としたサンプルアプリケーション

## 主な機能

- 飼い主（Owner）の作成・閲覧・編集・削除
- ペット（Patient）の作成・一覧・編集・削除
  - ペット一覧では飼い主名や生年月日、種別が表示される
- 治療（Treatment）の記録（ペットに紐づく RelationManager を使用）
  - 診察料（price）は内部的に整数（例：円の×100）で保存し、表示時に小数（例：1000 -> 10.00）として扱う
- Filament 管理画面内で、ペット作成時に飼い主をその場で追加できる「createOptionForm」機能

## 使用技術
| カテゴリ | 使用技術 |
| :--- | :--- |
| **Backend** | Laravel 12, filament, flowframe/laravel-trend, PHP_CodeSniffer |
| **Frontend** | Vite, Tailwind CSS |
| **Infrastructure** | Docker Compose (App / Node / MySQL / Nginx) |
| **OS Environment** | WSL2 (Ubuntu / Alpine Linux) |
| **Database** | MySQL 8.x |

## セットアップ手順

### 1. インフラのビルドと起動
```bash
docker compose build
docker compose up -d
```

### 2. バックエンドの初期化
```
docker compose exec app ash
composer install
php artisan migrate
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```
### 3. フロントエンドの起動
```
docker compose exec node sh
npm install
npm run dev
```
## ディレクトリ構成（抜粋）
- app/
  - Models/: Eloquent モデル（Owner, Patient, Treatment, User）
  - Filament/: 管理画面に関するリソースと RelationManager（PatientResource など）
  - Casts/MoneyCast.php: 金額をセントで保持するためのカスタムキャスト
- database/
  - migrations/: テーブル定義（owners, patients, treatments）
  - seeders/DatabaseSeeder.php: テストユーザーを作成
  - database.sqlite: 開発用 SQLite ファイル
- resources/: フロントエンドの CSS/JS と Blade ビュー（`welcome.blade.php`）
- routes/web.php: Web ルート（トップページは welcome ビュー）
- public/: ビルド後のアセット
- composer.json / package.json: 依存関係と実行スクリプト


## 設計・実装の特徴

- Eloquent リレーション
  - Owner -> hasMany Patients（飼い主は複数のペットを持てる）
  - Patient -> belongsTo Owner / hasMany Treatments（ペットは飼い主所属、複数の治療履歴を持つ）
  - Treatment -> belongsTo Patient（治療はペットに紐づく）

- データ保存と MoneyCast
  - `treatments.price` はマイグレーションで `unsignedInteger` として定義されています。これは金額を小数で保存する際の浮動小数点誤差を避けるため、内部的に「円 × 100（セント相当）」で保持する設計です。
  - `App\Casts\MoneyCast` はモデルに対して get/set を実装しており、保存時は値に 100 を掛けて整数化、取得時は 100 で割って小数に戻します。Filament のテーブルでは `->money('JPY')` によって適切に表示されます。

- Filament の活用ポイント
  - `PatientResource` でフォームとテーブルを宣言的に定義しているため、管理画面の追加・変更が容易です。
  - `Select::relationship(...)->createOptionForm(...)` により、ペット作成時に飼い主をその場で追加できます（UX の向上）。
  - RelationManager（TreatmentsRelationManager）により、親リソースの編集画面から直接関連治療を追加・編集できます。

- マイグレーション設計
  - `patients` テーブルは `owner_id` に外部キー制約を設けており、親が削除されたら子も削除（cascade）されるようになっています。これはデータ整合性を保つための一般的な設計です。

## 今後の改善予定
- 認証の詳細設定（パスワードリセット、権限レベル）
