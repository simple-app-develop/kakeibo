git checkout main       # 対象ブランチに切り替え
git pull

# 依存ライブラリのインストール
composer install
npm install

# Laravel設定
cp .env.dev .env           
php artisan key:generate.  
php artisan migrate
php artisan cache:clear    
php artisan config:clear   
php artisan route:clear    
php artisan view:clear     

# htaccess反映
cp public/.htaccess.dev public/.htaccess
