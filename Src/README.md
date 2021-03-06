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

-------------------------------------------------------------------------------------------------
--------------------------Load Font cho PDF----------------------------
-------------------------------------------------------------------------------------------------

1. Copy tất cả fonts từ /fonts past vào /storage/fonts/

<!-- php load_font.php 'notosans-bold' storage/fonts/NotoSans-Bold.ttf
php load_font.php 'notosans-bolditalic' storage/fonts/NotoSans-BoldItalic.ttf
php load_font.php 'notosans-italic' storage/fonts/NotoSans-Italic.ttf
php load_font.php 'notosans-regular' storage/fonts/NotoSans-Regular.ttf -->
-------------------------------------------------------------------------------------------------
--------------------------Chú ý khi code----------------------------
-------------------------------------------------------------------------------------------------

1. So sánh
	+ "some_text" == 0
		=> TRUE
	+ null == 0
		=> TRUE

-------------------------------------------------------------------------------------------------
--------------------------Chú ý khi migrate db----------------------------
-------------------------------------------------------------------------------------------------
Nếu migrate db gặp lỗi này:
' 1071 Specified key was too long; max key length is 1000 bytes '
thì vào config/database.php -> tìm đến 'engine' => null  và chuyển thành 'engine' => 'InnoDB'
Link tham khảo: https://stackoverflow.com/questions/42244541/laravel-migration-error-syntax-error-or-access-violation-1071-specified-key-wa



-------------------------------------------------------------------------------------------------
--------------------------Chức năng Check IP khi Đăng nhập mạng ngoài----------------------------
-------------------------------------------------------------------------------------------------
1/Xem IP mạng nội bộ của mình truy cập: https://www.whatismyip.com/
2/Cách cấu hình IP mạng nội bộ:
Lấy IP ở Bước 1 gán vào file .evn như bên dưới! 
## IP CHECK NETWORK EXTERNAL
IP_INTERNAL = "171.252.155.152"

CHỨC NĂNG CHI TIẾT:
1/Khi truy cập từ mạng ngoài (IP khác với IP đã cấu hình ở trên) thì sẽ gửi mã xác nhận vào mail đăng nhập của User!
2/User mở mail của mình và lấy mã xác nhận!
3/Nhập mã xác nhận vào Form xác nhận sẽ có thể truy cập !
Lưu ý: Mã xác nhận có hiệu lực 10 phút, phiên làm việc có hiệu lực 3h, sau đó muốn truy cập tiếp phải xác nhận lại !