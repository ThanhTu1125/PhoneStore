<?php
function getAll() {
    // Với lệnh không có biến truyền vào, ta giữ nguyên
    return db_fetch_array("SELECT * FROM tbl_category");
}

function insert_category($data) {
    // Hàm db_insert mới đã tự động xử lý array $data an toàn
    return db_insert("tbl_category", $data);
}

function get_category_by_id($id) {
    // Sử dụng Placeholder (:id) và truyền mảng giá trị vào cuối
    return db_fetch_array("SELECT * FROM tbl_category WHERE id = :id", [':id' => $id]);
}

function delete_category_by_id($id) {
    // Tương tự, tách biến $id ra khỏi chuỗi SQL
    return db_delete("tbl_category", "id = :id", [':id' => $id]);
}

function update_category_by_id($id, $data) {
    // Tách riêng cục mảng WHERE clause ra
    return db_update("tbl_category", $data, "id = :id", [':id' => $id]);
}
?>