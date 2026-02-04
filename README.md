# HỆ THỐNG QUẢN LÝ BÁN VÉ XEM PHIM - NHÓM 2

Chào mừng đến với dự án Website Quản Lý Bán Vé Xem Phim. Đây là một hệ thống toàn diện cho phép người dùng tìm kiếm, đặt vé và thanh toán trực tuyến, đồng thời cung cấp giao diện quản trị mạnh mẽ cho rạp chiếu phim.

## 👥 Thành Viên Nhóm 2

*   **Từ Hữu Minh Vũ (23010076)** 
*   **Phạm Thị Minh Ngọc (23010167)**
*   **Trần Minh Tuấn (23010573)**

---

## 🚀 Giới Thiệu Dự Án

Hệ thống được xây dựng trên nền tảng **Laravel**, tập trung vào trải nghiệm người dùng hiện đại, giao diện bắt mắt và quy trình đặt vé mượt mà.

### 🌟 Tính Năng Chính

#### Cho Người Dùng (Khách Hàng)
*   **Trang Chủ Bắt Mắt**: Slider phim thịnh hành, danh sách phim đang chiếu với hiệu ứng hover đẹp mắt.
*   **Tìm Kiếm Thông Minh**: Tìm phim theo tên nhanh chóng.
*   **Đặt Vé & Chọn Ghế**:
    *   Giao diện chọn ghế trực quan mô phỏng rạp phim thực tế.
    *   Phân loại ghế: Trống, Đang Chọn, Đã Đặt.
    *   Xử lý đụng độ ghế (real-time locking simulation).
*   **Thanh Toán Đa Dạng**: Hỗ trợ chọn phương thức thanh toán (Tiền mặt, VNPay, MoMo) với giao diện radio list rõ ràng.
*   **Lịch Sử Đặt Vé**: Xem lại vé đã mua, mã QR và trạng thái vé.

#### Cho Quản Trị Viên (Admin)
*   **Quản Lý Phim**: Thêm, sửa, xóa phim, tải lên poster.
*   **Quản Lý Lịch Chiếu**: Xếp lịch chiếu cho các phòng rạp.
*   **Quản Lý Phòng & Ghế**: Thiết lập sơ đồ ghế ngồi.
*   **Thống Kê**: Xem doanh thu và số lượng vé bán ra.

---

## 🛠️ Công Nghệ Sử Dụng

*   **Backend**: Laravel Framework (PHP).
*   **Database**: MySQL.
*   **Frontend**:
    *   Blade Templating Engine.
    *   HTML5 / CSS3 (Sử dụng class đặt tên tiếng Việt không dấu: `.khung-trangchu`, `.nut-datve`...).
    *   JavaScript (ES6+) xử lý logic chọn ghế và thanh toán.
*   **Styling**: Custom CSS kết hợp Tailwind CSS (cho layout chính).

---

## ⚙️ Hướng Dẫn Cài Đặt

Để chạy dự án này trên máy cục bộ, vui lòng làm theo các bước sau:

1.  **Clone dự án về máy:**
    ```bash
    git clone https://github.com/username/quanlybanve.git
    cd quanlybanve
    ```

2.  **Cài đặt các gói phụ thuộc PHP:**
    ```bash
    composer install
    ```

3.  **Cài đặt các gói phụ thuộc JavaScript:**
    ```bash
    npm install
    npm run build
    ```

4.  **Cấu hình môi trường:**
    *   Sao chép file `.env.example` thành `.env`
    *   Cấu hình thông tin database trong file `.env`
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Chạy Migrations và Seeders (Dữ liệu mẫu):**
    ```bash
    php artisan migrate:fresh --seed
    ```
    *(Lệnh này sẽ tạo bảng và thêm dữ liệu mẫu như Phim, Ghế, Người dùng admin)*

6.  **Khởi chạy Server:**
    ```bash
    php artisan serve
    ```
    Truy cập vào: `http://localhost:8000`

---

## 📸 Hình Ảnh Giao Diện

*   **Trang Chủ**: Banner slider lớn, danh sách phim dạng lưới.
*   **Chọn Ghế**: Sơ đồ ghế ngồi trực quan với chú thích màu sắc (Trắng: Trống, Đỏ: Đang chọn, Xám: Đã đặt).
*   **Thanh Toán**: Giao diện 2 cột hiện đại, chọn phương thức thanh toán dễ dàng.
*   **Lịch Chiếu**: Hiển thị lịch theo ngày/giờ, lọc nhanh theo rạp và thể loại phim.
---
LỜI CẢM ƠN: Xin chân thành cảm ơn thầy/cô và các bạn đã dành thời gian theo dõi và đóng góp ý kiến cho sản phẩm.

© 2026 Nhóm 2 - Quản Lý Bán Vé Xem Phim. All rights reserved.
