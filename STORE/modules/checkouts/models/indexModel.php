<?php

// Hàm lấy thông tin người dùng dựa trên ID
function getUserById($id)
{
	// Truy vấn cơ sở dữ liệu và trả về một dòng dữ liệu người dùng từ bảng `tbl_customer` có `id` bằng với tham số đầu vào
	return db_fetch_row("SELECT * FROM `tbl_customer` WHERE `id` = '$id'");
}

// Hàm lấy danh sách đơn hàng của một khách hàng dựa trên `id_customer`
function getListOrder($id_customer)
{
	// Truy vấn bảng `tbl_order` và trả về danh sách tất cả các đơn hàng có `custom_id` bằng với `id_customer`
	return db_fetch_array("SELECT * FROM `tbl_order` WHERE `custom_id` = '$id_customer'");
}

// Hàm lấy chi tiết của đơn hàng dựa trên `id_order`
function getListOrderByIDOrder($id)
{
	// Truy vấn bảng `tbl_detail_order` và trả về chi tiết các sản phẩm trong đơn hàng có `id_order` bằng với tham số đầu vào
	return db_fetch_array("SELECT * FROM `tbl_detail_order` WHERE `id_order` = '$id'");
}

// Hàm lấy thông tin sản phẩm dựa trên ID sản phẩm
function getProductByID($id)
{
	// Truy vấn bảng `tbl_product` và trả về thông tin sản phẩm có `id` bằng với tham số đầu vào
	return db_fetch_row("SELECT * FROM `tbl_product` WHERE `id` = '$id'");
}

// Hàm thêm một đơn hàng mới vào bảng `tbl_order`
function insertOrder($custom_id, $total_price, $total_num_product, $create_date, $note, $payment_method, $status, $id_cart, $time, $code)
{
	// Mảng dữ liệu chứa các thông tin cần thêm vào bảng `tbl_order`
	$data = [
		'custom_id' => $custom_id,  // ID của khách hàng
		'total_price' => $total_price,  // Tổng giá trị đơn hàng
		'total_num_product' => $total_num_product,  // Tổng số sản phẩm
		'create_date' => $create_date,  // Ngày tạo đơn hàng
		'note' => $note,  // Ghi chú cho đơn hàng
		'payment_method' => $payment_method,  // Phương thức thanh toán
		'status' => $status,  // Trạng thái đơn hàng
		'id_cart' => $id_cart,  // ID giỏ hàng
		'time' => $time,  // Thời gian xử lý đơn hàng
		'code' => $code  // Mã đơn hàng
	];
	// Chèn dữ liệu vào bảng `tbl_order`
	db_insert("tbl_order", $data);
}

// Hàm xóa giỏ hàng của khách hàng
function deletecart()
{
	// Lấy ID khách hàng từ session
	$id_customer = $_SESSION['id_customer'];
	// Truy vấn bảng `tbl_cart` để lấy thông tin giỏ hàng của khách hàng
	$data_cart = db_fetch_row("SELECT * FROM `tbl_cart` WHERE `id_customer` = '$id_customer'");
	// Lấy ID giỏ hàng
	$id_cart = $data_cart['id'];
	// Xóa các chi tiết giỏ hàng khỏi bảng `tbl_detail_cart` dựa trên `id_cart`
	db_delete('tbl_detail_cart', "`id_cart`='$id_cart'");
	// Cập nhật giỏ hàng trong bảng `tbl_cart`, đặt lại tổng số lượng và tổng giá trị bằng 0
	$data = ['total_num' => 0, 'total_price' => 0];
	db_update('tbl_cart', $data, "`id_customer` = '$id_customer'");
	// Xóa giỏ hàng khỏi session
	unset($_SESSION['cart']);
}
function inserOderDetail($id_order, $id_product, $qty, $sub_total_price)
{
	// Lấy thông tin sản phẩm từ bảng `tbl_product`
	$product_data = db_fetch_row("SELECT `quantity`, `name` FROM `tbl_product` WHERE `id` = '$id_product'");

	// Kiểm tra số lượng tồn kho
	if ($product_data['quantity'] < $qty) {
		// Nếu không đủ số lượng, lưu thông báo lỗi vào session
		$_SESSION['error'] = "Sản phẩm {$product_data['name']} không đủ số lượng trong kho!";
		header('location: ?modules=checkouts&controllers=index&action=index');
		exit;
	}

	// Nếu đủ số lượng, cập nhật số lượng sản phẩm trong kho
	$new_quantity = $product_data['quantity'] - $qty;
	db_query("UPDATE `tbl_product` SET `quantity` = '$new_quantity' WHERE `id` = '$id_product'");

	// Chèn dữ liệu chi tiết đơn hàng vào bảng `tbl_detail_order`
	$data = [
		'id_order' => $id_order,
		'id_product' => $id_product,
		'qty' => $qty,
		'sub_total_price' => $sub_total_price
	];
	db_insert("tbl_detail_order", $data);
}
// Hàm gửi email thông báo đơn hàng (chưa hoàn thiện)
function sendMail($id_order)
{
	// Lấy thông tin đơn hàng từ bảng `tbl_order` dựa trên `id_order`
	$data = db_fetch_row("SELECT * FROM `tbl_order` WHERE `id` = '$id_order'");
	// Lấy danh sách sản phẩm trong đơn hàng từ bảng `tbl_detail_order` dựa trên `id_order`
	$data_product =  db_fetch_array("SELECT * FROM `tbl_detail_order` WHERE `id_order` = '$id_order'");

	// Biến `$send_mail` chứa nội dung email sẽ gửi đi (nội dung chưa được hoàn thiện trong đoạn mã)
	$send_mail = "
        
    ";
}
