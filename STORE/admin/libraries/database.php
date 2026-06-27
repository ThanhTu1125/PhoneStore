<?php

// 1. Hàm kết nối dữ liệu bằng PDO
function db_connect() {
    global $conn;
    $db = func_get_arg(0);
    
    try {
        // Chuỗi kết nối DSN cho PostgreSQL
        $dsn = "pgsql:host={$db['hostname']};port={$db['port']};dbname={$db['database']}";
        
        $conn = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Báo lỗi rõ ràng
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Luôn trả về mảng kết hợp
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Bảo mật tốt hơn
        ]);
    } catch (PDOException $e) {
        db_sql_error('Lỗi kết nối CSDL', '', $e->getMessage());
    }
}

// 2. Thực thi chuỗi truy vấn (Dùng cho SELECT)
function db_query($query_string) {
    global $conn;
    try {
        $stmt = $conn->query($query_string);
        return $stmt;
    } catch (PDOException $e) {
        db_sql_error('Lỗi câu lệnh SQL', $query_string, $e->getMessage());
    }
}

// 3. Lấy một dòng trong CSDL (Dành cho chi tiết 1 sản phẩm, 1 user)
function db_fetch_row($query_string) {
    $stmt = db_query($query_string);
    return $stmt ? $stmt->fetch() : [];
}

// 4. Lấy nhiều dòng trong CSDL (Dành cho danh sách)
function db_fetch_array($query_string) {
    $stmt = db_query($query_string);
    return $stmt ? $stmt->fetchAll() : [];
}

// 5. Lấy số lượng bản ghi
function db_num_rows($query_string) {
    $stmt = db_query($query_string);
    return $stmt ? $stmt->rowCount() : 0;
}

// 6. Thêm dữ liệu (Đã tích hợp chống SQL Injection)
function db_insert($table, $data) {
    global $conn;
    $keys = array_keys($data);
    $fields = implode(", ", $keys);
    $placeholders = ":" . implode(", :", $keys);

    $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
        return $conn->lastInsertId(); 
    } catch (PDOException $e) {
        db_sql_error('Lỗi Insert', $sql, $e->getMessage());
    }
}

// 7. Cập nhật dữ liệu (Đã tích hợp chống SQL Injection)
function db_update($table, $data, $where) {
    global $conn;
    $set_arr = [];
    foreach ($data as $key => $value) {
        $set_arr[] = "$key = :$key";
    }
    $set_string = implode(", ", $set_arr);

    $sql = "UPDATE $table SET $set_string WHERE $where";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        db_sql_error('Lỗi Update', $sql, $e->getMessage());
    }
}

// 8. Xóa một dòng trong bảng
function db_delete($table, $where) {
    global $conn;
    $sql = "DELETE FROM $table WHERE $where";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        db_sql_error('Lỗi Delete', $sql, $e->getMessage());
    }
}

// 9. Thoát chuỗi an toàn (Dự phòng cho code cũ)
function escape_string($str) {
    global $conn;
    // Bỏ cặp dấu nháy đơn ở 2 đầu do PDO::quote tự sinh ra
    return substr($conn->quote($str), 1, -1);
}

// 10. Hiển thị lỗi SQL (Debug giao diện)
function db_sql_error($message, $query_string = "", $pdo_error = "") {
    $sqlerror = "<div style='font-family: Arial, sans-serif; background:#fee; border:1px solid #f00; padding:15px; margin:20px; border-radius:5px;'>";
    $sqlerror .= "<h3 style='color:red; margin-top:0;'>{$message}</h3>";
    $sqlerror .= "<ul style='line-height:1.6;'>";
    if (!empty($query_string)) {
        $sqlerror .= "<li><strong>Query SQL:</strong> <code style='background:#fff; padding:2px 5px;'>{$query_string}</code></li>";
    }
    if (!empty($pdo_error)) {
        $sqlerror .= "<li><strong>Chi tiết lỗi:</strong> {$pdo_error}</li>";
    }
    $sqlerror .= "<li><strong>File chạy:</strong> {$_SERVER['REQUEST_URI']}</li>";
    $sqlerror .= "</ul></div>";
    
    echo $sqlerror;
    exit;
}