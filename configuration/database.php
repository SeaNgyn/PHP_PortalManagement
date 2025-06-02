<?php
// Định nghĩa các hằng số cấu hình kết nối
define('DATABASE_SERVER', 'localhost');          // Địa chỉ host của MySQL (localhost hoặc IP)
define('DATABASE_USER', 'root');                 // Tên người dùng MySQL
define('DATABASE_PASSWORD', 'Mysql18032003.');   // Mật khẩu MySQL
define('DATABASE_NAME', 'db_php_ver4');                // Tên cơ sở dữ liệu cần kết nối
define('DATABASE_PORT', '3306');                 // Cổng MySQL đang lắng nghe (mặc định là 3306)


$connection = null;
try {
    // Tạo đối tượng PDO với DSN (Data Source Name) gồm host, port, và tên database
    $connection = new PDO(
        "mysql:host=" . DATABASE_SERVER . ";port=" . DATABASE_PORT . ";dbname=" . DATABASE_NAME . ";charset=utf8mb4",
        DATABASE_USER,
        DATABASE_PASSWORD
    );

    // Thiết lập chế độ báo lỗi của PDO thành Exception (ném lỗi khi có vấn đề)
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION Đây là một hằng số static (const) của lớp PDO. Và vì nó là static, bạn dùng :: để truy cập
    //$connection->setAttribute() $connection là object → dùng '->' setAttribute(...) là phương thức của object này

    // echo "Connected successfully";
} catch (PDOException $e) {
    // Bắt lỗi nếu kết nối thất bại và hiển thị thông báo lỗi
    echo "Connection failed: " . $e->getMessage();
    $connection = null;
}

//  :: – Toán tử phạm vi (:: Scope Resolution Operator) Dùng để truy cập thành phần tĩnh (static) của một lớp, hoặc hằng số, hàm static, hoặc nạp lớp cha.
// class MyClass {
//     const VERSION = '1.0';
    
//     public static function sayHello() {
//         echo "Hello from static method!";
//     }
// }

// echo MyClass::VERSION;           // Truy cập hằng số
// MyClass::sayHello();             // Gọi phương thức tĩnh

//-------------------------------------------------------------------
// -> – Toán tử đối tượng (Object Operator) Dùng để truy cập thuộc tính hoặc phương thức của một đối tượng đã được khởi tạo (object).
// class Car {
//     public $color = 'red';

//     public function drive() {
//         echo "Driving the car!";
//     }
// }

// $myCar = new Car();
// echo $myCar->color;     // Truy cập thuộc tính
// $myCar->drive();        // Gọi phương thức

//------------------------------------------------------------------
// ✅ Kết luận ngắn:
// Dùng :: khi bạn không cần khởi tạo đối tượng, thường là với static hoặc const.

// Dùng -> khi bạn đang làm việc với một đối tượng cụ thể, đã được tạo bằng new.