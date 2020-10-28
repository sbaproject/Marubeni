1. composer install
2. đổi tên file .env.example -> .env
3. chạy lệnh : php artisan key:generate
4. trong db tạo trước database tên [marubeni]
5. mở file .env config lại thông tin DB theo máy mình làm
6. php artisan migrate:fresh --seed
-> OK