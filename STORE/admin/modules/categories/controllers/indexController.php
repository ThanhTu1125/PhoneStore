<?php

function construct()
{

	load_model('index');
}

function indexAction() {}

function addAction()
{
	$name = '';
	$code = '';
	$image = '';
	$description = '';
	$user = '';
	$err = array();

	if (!empty($_POST['btn_submit'])) {
		// Kiểm tra dữ liệu đầu vào
		if (!empty($_POST['name'])) {
			$name = $_POST['name'];
		} else {
			$err['name'] = "Tên danh mục không được để trống";
		}

		if (!empty($_POST['code'])) {
			$code = $_POST['code'];
		} else {
			$err['code'] = "Mã danh mục không được để trống";
		}

		if (!empty($_POST['description'])) {
			$description = $_POST['description'];
		} else {
			$err['description'] = "Mô tả không được để trống";
		}

		if (!empty($_POST['user'])) {
			$user = $_POST['user'];
		} else {
			$err['user'] = "Người tạo không được để trống";
		}

		// Xử lý upload file ảnh
		$image_result = upload_image($_FILES['image']);
		if (isset($image_result['errors'])) {
			$err['image'] = implode(', ', $image_result['errors']);
		} else {
			$image = $image_result['path'];
		}

		// Nếu không có lỗi, thực hiện thêm mới danh mục
		if (empty($err)) {
			$create_date = date("Y-m-d H:i:s", time()); // Lưu ngày tháng hiện tại
			$data = [
				'name' => $name,
				'code' => $code,
				'image' => $image,
				'description' => $description,
				'create_date' => $create_date,
				'user' => $user
			];

			// Gọi hàm insert_category để thêm dữ liệu
			if (insert_category($data)) {
				// Hiển thị thông báo và chuyển về danh sách danh mục
				echo "<script type='text/javascript'>alert('Thêm mới danh mục thành công');</script>";
				header("Location: ?modules=categorys&controllers=index&action=list");
				exit();
			} else {
				echo "<script type='text/javascript'>alert('Thêm mới danh mục thất bại');</script>";
			}
		} else {
			// Gom tất cả các lỗi thành một chuỗi và hiển thị thông báo
			$error_message = implode("\\n", $err);
			echo "<script type='text/javascript'>alert('Có lỗi xảy ra:\\n$error_message');</script>";
		}
	}

	// Tải view để hiển thị form thêm danh mục
	load_view('add');
}

function upload_image($file, $target_dir = "public/uploads/", $max_size = 2000000, $allowed_types = ['jpg', 'png', 'jpeg', 'gif'])
{
	$target_file = $target_dir . basename($file["name"]);
	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
	$errors = [];

	if (!getimagesize($file["tmp_name"])) {
		$errors[] = "File không phải là ảnh hợp lệ.";
	}
	if ($file["size"] > $max_size) {
		$errors[] = "File quá lớn.";
	}
	if (!in_array($imageFileType, $allowed_types)) {
		$errors[] = "Định dạng file không được hỗ trợ.";
	}
	if (empty($errors)) {
		if (move_uploaded_file($file["tmp_name"], $target_file)) {
			return ['path' => $target_file];
		} else {
			$errors[] = "Không thể tải lên file.";
		}
	}
	return ['errors' => $errors];
}

function listAction()
{

	$data_tmp = getAll();

	// for ($i=0; $i <count($data_tmp) ; $i++) {

	// 	$data_tmp[$i]['category'] = get_category_by_id($data_tmp[$i]['id']);
	// 	//$data_tmp[$i]['brand']  = get_brand_by_id($data_tmp[$i]['id_brand']) ;
	// };

	//phân trang//////////////////////////////////////////////////
	//$id_cat = $_GET['id_cat'];
	// $name = getNameCatById($id_cat);
	// $data_tmp = getAllByIDCat($id_cat);
	// $id =$id_cat;
	$page;
	if (!empty($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}

	$numProduct = count($data_tmp);
	$productOnPage = 5;
	$num = ceil($numProduct / $productOnPage);
	if (!empty($_GET['page']) && $_GET['page'] > $num) {
		$page = $num;
	}
	$start = ($page - 1) * $productOnPage;
	$res = [];
	for ($i = $start; $i < $start + $productOnPage; $i++) {
		if (isset($data_tmp[$i]))
			$res[] = $data_tmp[$i];
	};

	$data = [$res, $num, $page];
	load_view('list', $data);
}

function deleteAction()
{

	$id = $_GET['id'];
	delete_category_by_id($id);
	header('location:?modules=categorys&controllers=index&action=list');
}

function editAction()
{

	$id = $_GET['id'];
	$data = get_category_by_id($id);
	load_view('show', $data);
}

function updateAction()
{
	$id = $_GET['id'];
	$image;
	$data = get_category_by_id($id);
	$data1 = array();
	if (!empty($_POST['btn_submit'])) {

		if (empty($_POST['name'])) {
			$data1['name'] = $data[0]['name'];
		} else {
			$data1['name'] = $_POST['name'];
		}

		if (empty($_POST['code'])) {
			$data1['code'] = $data[0]['code'];
		} else {
			$data1['code'] = $_POST['code'];
		}

		if (empty($_POST['user'])) {
			$data1['user'] = $data[0]['user'];
		} else {
			$data1['user'] = $_POST['user'];
		}

		if (empty($_POST['description'])) {
			$data1['description'] = $data[0]['description'];
		} else {
			$data1['description'] = $_POST['description'];
		}

		$target_dir = "public/uploads/";
		$target_file = $target_dir . basename($_FILES["image"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		if (isset($_POST["submit"])) {
			$check = getimagesize($_FILES["image"]["tmp_name"]);
			if ($check !== false) {
				$uploadOk = 1;
			} else {
				$uploadOk = 0;
			}
		}

		if (file_exists($target_file)) {
			$uploadOk = 0;
		}

		if ($_FILES["image"]["size"] > 200000000) {
			$uploadOk = 0;
		}

		if (
			$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif"
		) {
			$uploadOk = 0;
		}

		if ($uploadOk == 0) {
		} else {
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
				$image = $target_dir . basename($_FILES["image"]["name"]);
			} else {
			}
		}

		if (!empty($image)) {
			$data1['image'] = $image;
		} else {
			$data1['image'] = $data[0]['image'];
		}
	}

	if (update_category_by_id($id, $data1)) {
		$res = get_category_by_id($id);
		load_view('show', $res);
		echo " <script type='text/javascript'> alert('Cập Nhật Thành Công');</script>";
	} else {
		load_view('show', $data);
		echo " <script type='text/javascript'> alert('Cập Nhật Thất Bại');</script>";
	}
}
