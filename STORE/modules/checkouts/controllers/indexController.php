<?php

// Hàm khởi tạo cho controller, có thể được gọi khi trang được yêu cầu
function construct()
{
	// Tải model 'index', dùng để xử lý dữ liệu liên quan đến trang chủ
	load_model('index');
}

// Hàm xử lý yêu cầu trang chủ (index)
function indexAction()
{
	// Mảng dữ liệu để lưu thông tin cần gửi đến view
	$data = array();

	// Kiểm tra xem người dùng đã đăng nhập hay chưa (có ID khách hàng trong session)
	if (!empty($_SESSION['id_customer'])) {
		// Nếu đã đăng nhập, lấy thông tin người dùng từ database và lưu vào mảng dữ liệu
		$data[] = getUserById($_SESSION['id_customer']);
	}

	// Tải view 'index' và truyền dữ liệu người dùng vào
	load_view('index', $data);
}

// Hàm xử lý yêu cầu trang lịch sử đơn hàng của khách hàng
function storyAction()
{
	// Mảng dữ liệu lưu lịch sử đơn hàng
	$data = array();

	// Kiểm tra xem người dùng đã đăng nhập hay chưa
	if (!empty($_SESSION['id_customer'])) {
		// Nếu đã đăng nhập, lấy danh sách các đơn hàng của khách hàng
		$data = getListOrder($_SESSION['id_customer']);
	}

	// Tải view 'story' và truyền lịch sử đơn hàng vào
	load_view('story', $data);
}

// Hàm xử lý yêu cầu chi tiết đơn hàng
function detailStoryAction()
{
	// Lấy ID đơn hàng và mã đơn hàng từ URL
	$id = $_GET['idOrder'];
	$code = $_GET['code'];

	// Lấy danh sách chi tiết đơn hàng từ bảng `tbl_detail_order` theo ID đơn hàng
	$data = getListOrderByIDOrder($id);

	// Duyệt qua từng chi tiết đơn hàng và lấy thông tin sản phẩm
	for ($i = 0; $i < count($data); $i++) {
		// Lấy thông tin sản phẩm theo ID sản phẩm
		$product = getProductByID($data[$i]['id_product']);
		// Thêm các thông tin sản phẩm vào mảng dữ liệu
		$data[$i]['image'] = $product['image'];
		$data[$i]['name'] = $product['name'];
		$data[$i]['price'] = $product['promotional_price'];
		$data[$i]['code'] = $product['code'];
	}

	// Thêm mã đơn hàng vào cuối mảng dữ liệu
	$data[count($data)] = $code;

	// Tải view 'detail_story' và truyền dữ liệu chi tiết đơn hàng vào
	load_view('detail_story', $data);
}

function checkoutAction()
{
	// Kiểm tra xem người dùng đã nhấn nút "submit" hay chưa
	if (!empty($_POST['btn_submit'])) {

		// Kiểm tra người dùng đã đăng nhập chưa
		if (isset($_SESSION['id_customer'])) {

			// Kiểm tra nếu phương thức thanh toán đã được chọn và giỏ hàng không trống
			if (!empty($_POST['payment_method']) && !empty($_SESSION['cart']['buy'])) {

				// Lấy thông tin giỏ hàng từ session
				foreach ($_SESSION['cart']['buy'] as $value) {
					$id_product = $value['id'];    // ID sản phẩm
					$qty = $value['qty'];         // Số lượng đặt mua

					// Lấy thông tin sản phẩm từ cơ sở dữ liệu
					$product_data = db_fetch_row("SELECT `quantity`, `name` FROM `tbl_product` WHERE `id` = '$id_product'");

					// Kiểm tra số lượng tồn kho
					if ($product_data['quantity'] < $qty) {
						// Nếu không đủ số lượng, hiển thị thông báo và dừng xử lý
						echo "<script>alert('Sản phẩm {$product_data['name']} không đủ số lượng trong kho! Vui lòng kiểm tra lại.');</script>";
						echo "<script>window.location.href = '?modules=checkouts&controllers=index&action=index';</script>";
						exit;
					}
				}

				// Nếu đủ tồn kho, tiếp tục xử lý đơn hàng
				$custom_id = $_SESSION['id_customer'];
				$total_price = $_SESSION['cart']['info']['total'];
				$total_num_product = $_SESSION['cart']['info']['num_oder'];
				$create_date = date("d/m/Y", time());
				$time = time();
				$code = $_SESSION['username'] . "($time)";
				$note = $_POST['note'];
				$payment_method = $_POST['payment_method'];
				$status = "chờ xác nhận";

				// Lấy thông tin giỏ hàng của khách hàng
				$id_customer = $_SESSION['id_customer'];
				$data_cart = db_fetch_row("SELECT * FROM `tbl_cart` WHERE `id_customer` = $id_customer");
				$id_cart = $data_cart['id'];

				// Thêm đơn hàng vào bảng `tbl_order`
				insertOrder($custom_id, $total_price, $total_num_product, $create_date, $note, $payment_method, $status, $id_cart, $time, $code);

				// Lấy thông tin đơn hàng vừa thêm vào
				$data_order = db_fetch_row("SELECT * FROM `tbl_order` WHERE `time` = '$time'");
				$id_order = $data_order['id'];

				// Thêm chi tiết đơn hàng vào bảng `tbl_detail_order`
				foreach ($_SESSION['cart']['buy'] as $value) {
					inserOderDetail($id_order, $value['id'], $value['qty'], $value['sub_total']);
				}

				// Gửi email xác nhận đơn hàng
				sendMail($id_order);

				// Xóa giỏ hàng sau khi hoàn tất đơn hàng
				deletecart();

				// Xóa dữ liệu giỏ hàng trong session
				unset($_SESSION['cart']['buy']);
				$_SESSION['success'] = true;

				// Chuyển hướng tới trang lịch sử đơn hàng
				header('location: ?modules=checkouts&controllers=index&action=story');
			}
			// Nếu không có sản phẩm trong giỏ hàng
			else if (!empty($_POST['payment_method']) && empty($_SESSION['cart']['buy'])) {
				$_SESSION['messBuy'] = true;
				header('location: ?modules=checkouts&controllers=index&action=index');
			}
			// Nếu thiếu thông tin thanh toán
			else {
				$_SESSION['messa'] = true;
				header('location: ?modules=checkouts&controllers=index&action=index');
			}
		}
		// Nếu người dùng chưa đăng nhập
		else {
			$_SESSION['mess'] = true;
			header('location: ?modules=users&controllers=index&action=index');
		}
	}
}
