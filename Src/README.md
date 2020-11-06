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

-------------------------------------------------------------------------------------------------
--------------------------Quy tắc đặt tên route - controller - action----------------------------
-------------------------------------------------------------------------------------------------

* -- Đặt tên action của Controller -- *
	+ index - GET: trang list
	+ create - GET: trang đăng ký
	+ store - POST: lưu đăng ký
	+ show - GET: trang edit
	+ update - POST: lưu edit
	+ delete - POST: xóa

* -- Đặt tên URL -- *
	+ trang list -> chỉ cần "/"
		=> ví dụ: list user -> domain/user/
	+ trang add -> "add/"
		=> ví dụ: add new user -> domain/user/add
	+ trang edit -> "edit/{id}"
		=> ví dụ: edit user 1 => domain/user/edit/1
	+ trang xóa -> "delete/{id}"
		=> ví dụ: delete user 1 -> domain/user/delete/1
	**Nếu tên có nhiều từ thì cách nhau dấu "-"
		=> ví dụ: flowsetting -> flow-setting

* -- Đặt tên cho Route -- *
	+ trang list(GET) -> "user.index"
	+ trang add(GET) -> "user.create"
	+ trang add(POST) -> "user.store"
	+ trang edit(GET) -> "user.show"
	+ trang edit(POST) -> "user.update"
	+ trang delete(POST) -> "user.delete"
	
**Ở trên là quy định chung cơ bản, nếu route,controller,action phức tạp hơn thì sẽ tùy biến.

-------------------------------------------------------------------------------------------------
--------------------------Đọc file đã upload lên storage----------------------------
-------------------------------------------------------------------------------------------------
1. trong file .env thay [APP_URL] = tên domain của mình (kèm port nếu có)
2. link folder upload từ /storage vào /public để có thể xem được file upload
	Trong đó:
		+ /storage/[your_folder_upload] : Là nơi chứa thực tế file được upload
		+ /public/storage/[your_folder_upload] : Là một link folder (không mất thêm không gian ổ cứng) giúp user có thể truy cập file, giống như các file static khác muốn truy cập từ bên ngoài phải đặt trong /public vậy.
	-> chạy lệnh: php artisan storage:link

-------------------------------------------------------------------------------------------------
--------------------------Set TimeZone----------------------------
-------------------------------------------------------------------------------------------------
Để set TimZone vào /config/app.php tìm đến 'timezone' và set tại đây.
Tham khảo list timezone: https://www.php.net/manual/en/timezones.php