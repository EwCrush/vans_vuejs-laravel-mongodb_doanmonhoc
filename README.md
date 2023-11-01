# vans_vuejs-laravel-mongodb_doanmonhoc
## Yêu cầu ##
-	PHP 8.1.17
-	XAMPP Version: 8.1.17
-	NodeJS
-	MongoDB

## Thiết lập môi trường để kết nối mongodb với laravel ##
- Thêm file `php_mongodb.dll` vào thư mục `xampp\php\ext`
  - Cài đặt mongo php driver có chứa file php_mongodb.dll [tại đây](https://github.com/mongodb/mongo-php-driver/releases).
    - **Thread-Safe (TS) x64**: `Đây là phiên bản PHP 64-bit hỗ trợ đa luồng, nghĩa là có khả năng hoạt động trong môi trường sử dụng nhiều luồng cùng lúc. Thường được sử dụng trên các máy chủ web chạy trên hệ điều hành Windows và là lựa chọn phù hợp cho môi trường máy chủ nhiều luồng như Apache, IIS, và một số môi trường PHP-FPM.`
    - **Non-Thread Safe (NTS) x64**: `Đây là phiên bản PHP 64-bit không hỗ trợ đa luồng. Nó thường được sử dụng trong môi trường mà PHP sẽ chạy dưới dạng các quy trình riêng lẻ thay vì các luồng. NTS x64 thường được sử dụng trong môi trường như Nginx hoặc Lighttpd, nơi việc đa luồng không cần thiết hoặc không được hỗ trợ.`
    - **Thread-Safe (TS) x86**: `Phiên bản này hỗ trợ đa luồng và thường được sử dụng trên máy chủ web chạy trên hệ điều hành Windows, trong các môi trường như Apache, IIS, và PHP-FPM trên nền tảng 32-bit.`
    - **Non-Thread Safe (NTS) x86**: `Phiên bản này không hỗ trợ đa luồng và thường được sử dụng trong môi trường mà PHP chạy dưới dạng các quy trình riêng lẻ thay vì các luồng. NTS x86 thường được sử dụng trong môi trường như Nginx hoặc Lighttpd trên nền tảng 32-bit.`
  - Kiểm tra phiên bản php mà mongo php driver hỗ trợ [tại đây](https://pecl.php.net/package/mongodb).
- Thêm `extension=php_mongodb.dll` vào file php.ini
- Chuyển folder `mongo-vans-laravel` tới đường dẫn `xampp\htdocs`

## Khởi động ứng dụng ##
- Laravel:
  > php artisan serve
- NodeJS:
  - Sử dụng npm:
    >`npm run dev`
  - Sử dụng yarn:
    >`yarn dev`

## Chức năng ##
- Đăng ký tài khoản
- Đăng nhập và phân quyền sử dụng ``laravel sanctum``
- Lấy lại mật khẩu với ``PHP Mailer``
- CRUD sản phẩm, loại sản phẩm, người dùng
- Tìm kiếm dữ liệu và lọc dữ liệu
- Lưu trữ hình ảnh với ``firebase storage``
- Thêm, xóa, sửa bình luận về sản phẩm, thích bình luận, trả lời bình luận, tương tác giữa người dùng
- Nhận thông báo khi có người dùng tương tác với bình luận
- Thống kê với ``vue google charts`` với nhiều dạng biểu đồ, lọc dữ liệu theo các mốc thời gian khác nhau
- Sử dụng ``Province Open API`` để lấy dữ liệu tỉnh thành Việt Nam
- Cập nhật thông tin người dùng
- Thay đổi mật khẩu
- Thêm vào giỏ hàng
- Đặt hàng
- Hủy đặt hàng

## Hạn chế ##
- Chưa thể đăng nhập với ``Google``
- Chưa tích hợp ``VNPay`` để thanh toán trực tuyến
- Chưa **responsive** để trang web có khả năng thích nghi và hiển thị tốt trên các thiết bị
   
## Kết quả ##
>Xem hình ảnh kết quả ở file word mục kết quả



 	
  
