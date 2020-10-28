** Để chạy lệnh console cần cd vào thư mục root của source ở đây là /src trước khi chạy lệnh
1. Cài đặt packages(vendor)
	-> composer install
2. Tạo file môi trường [.env] 
	-> Đổi tên file [.env.example] -> [.env]
3. Generate app key
	-> php artisan key:generate
4. Tạo cấu trúc db và dữ liệu mẫu đơn giản
	4.1 Trong db tạo trước database tên [marubeni]
	4.2 Mở file [.env] config lại thông tin DB theo máy mình làm.
	4.3 php artisan migrate:fresh --seed
5. DONE