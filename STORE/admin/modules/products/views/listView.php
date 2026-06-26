<?php get_header(); ?>
<div id="main-content-wp" class="list-product-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách sản phẩm</h3>
                    <a href="?modules=products&controllers=index&action=add" title="" id="add-new" class="fl-left">Thêm mới</a>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="table-responsive">
                    <table class="table list-table-wp">
                        <thead>
                            <tr>
                                <td><span class="thead-text">STT</span></td>
                                <td><span class="thead-text">Mã sản phẩm</span></td>
                                <td><span class="thead-text">Hình ảnh</span></td>
                                <td><span class="thead-text">Tên sản phẩm</span></td>
                                <td><span class="thead-text">Giá</span></td>
                                <td><span class="thead-text">Khuyến mại</span></td>
                                <td><span class="thead-text">Số lượng</span></td>
                                <td><span class="thead-text">Trạng thái</span></td>
                                <td><span class="thead-text">Độ hot</span></td>
                                <td><span class="thead-text">Danh mục</span></td>
                                <td><span class="thead-text">Thương hiệu</span></td>
                                <td><span class="thead-text">Người tạo</span></td>
                                <td><span class="thead-text">Thời gian</span></td>
                                <td><span class="thead-text">Hoàn tác</span></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            if (!empty($data['0'])) foreach ($data['0'] as $value) {
                                $i++; ?>

                                <tr>
                                    <td><span class="tbody-text"><?php echo $i; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['code']; ?></span></td>
                                    <td>
                                        <div class="tbody-thumb">
                                            <img src="<?php echo $value['image']; ?>" alt="">
                                        </div>
                                    </td>
                                    <td><span class="tbody-text"><?php echo $value['name']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['price']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['promotional_price']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['quantity']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['status']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['level']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['category']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['brand']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['user']; ?></span></td>
                                    <td><span class="tbody-text"><?php echo $value['create_date']; ?></span></td>
                                    <td>
                                        <ul class="list-operation ">
                                            <li><a href="?modules=products&controllers=index&action=update&id=<?php echo $value['id']; ?>" title="Sửa" class="edit"><i class="fa fa-pencil" aria-hidden="true"></i></a></li>
                                            <li><a href="?modules=products&controllers=index&action=delete&id=<?php echo $value['id']; ?>" title="Xóa" class="delete"><i class="fa fa-trash" aria-hidden="true"></i></a></li>
                                        </ul>
                                    </td>
                                </tr>
                            <?php }; ?>
                        </tbody>

                    </table>
                    <hr>
                </div>
            </div>
        </div>
        <div class="section" id="paging-wp">
            <div class="section-detail clearfix">
                <p id="desc" class="fl-left">Chọn vào checkbox để lựa chọn tất cả</p>
                <ul id="list-paging" class="fl-right">
                    <?php for ($i = 1; $i <= $data['1']; $i++) { ?>
                        <li>
                            <a <?php if ($i == $data['2']) echo 'style="background-color: green; color:white; border-radius:300px;"';  ?> href="?modules=products&controllers=index&action=list&page=<?php echo $i; ?>" title=""><?php echo $i; ?></a>
                        </li>
                    <?php }; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
<?php get_footer(); ?>