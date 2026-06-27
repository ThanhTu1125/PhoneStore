<?php

// Hàm lấy tất cả danh mục từ bảng 'tbl_category'
function getAll()
{
	// Thực thi câu lệnh SELECT để lấy tất cả bản ghi từ bảng 'tbl_category'
	return db_fetch_array("SELECT * FROM `tbl_category`");
}

// Hàm thêm một danh mục mới vào bảng 'tbl_category'
// Tham số $data là mảng chứa dữ liệu cần thêm vào bảng
function insert_category($data)
{
	// Sử dụng hàm db_insert để thực hiện câu lệnh INSERT INTO vào bảng 'tbl_category'
	return db_insert("tbl_category", $data);
}

// Hàm xóa danh mục theo ID từ bảng 'tbl_category'
// Tham số $id là ID của danh mục cần xóa
function delete_category_by_id($id)
{
	// Sử dụng hàm db_delete để thực thi câu lệnh DELETE từ bảng 'tbl_category' với điều kiện ID tương ứng
	return db_delete("tbl_category", "`id` = '$id'");
}

// Hàm lấy danh mục theo ID từ bảng 'tbl_category'
// Tham số $id là ID của danh mục cần tìm
function get_category_by_id($id)
{
	// Thực thi câu lệnh SELECT để lấy một bản ghi từ bảng 'tbl_category' có ID tương ứng
	return db_fetch_array("SELECT * FROM `tbl_category` WHERE `id` = '$id'");
}

// Hàm cập nhật danh mục theo ID trong bảng 'tbl_category'
// Tham số $id là ID của danh mục cần cập nhật, $data là mảng chứa các giá trị cần cập nhật
function update_category_by_id($id, $data)
{
	// Sử dụng hàm db_update để thực thi câu lệnh UPDATE vào bảng 'tbl_category' với điều kiện ID tương ứng
	return db_update("tbl_category", $data, "`id`='$id'");
}
