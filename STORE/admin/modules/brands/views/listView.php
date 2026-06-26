<?php get_header(); ?>
<div id="main-content-wp" class="list-product-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh Sách Thương Hiệu Sản Phẩm</h3>
                    <a href="?modules=brands&controllers=index&action=add" title="" id="add-new" class="fl-left">Thêm mới</a>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">

                    <div class="table-responsive">
                        <table class="table list-table-wp">
                            <thead>
                                <tr>
                                    <td><span class="thead-text">STT</span></td>
                                    <td><span class="thead-text">Mã thương hiêu</span></td>
                                    <td><span class="thead-text">Hình ảnh</span></td>
                                    <td><span class="thead-text">Tên Thương Hiệu</span></td>
                                    <td><span class="thead-text">Người tạo</span></td>
                                    <td><span class="thead-text">Thời gian</span></td>
                                    <td><span class="thead-text">Hoàn tác</span></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                if (!empty($data['0'])) foreach ($data['0'] as $key => $value) {
                                    $i++; ?>

                                    <tr>
                                        <td><span class="tbody-text"><?php echo $i; ?></h3></span>
                                        <td><span class="tbody-text"><?php echo $value['code']; ?></h3></span>
                                        <td>
                                            <div class="tbody-thumb">
                                                <img src="<?php echo $value['image']; ?>" alt="">
                                            </div>
                                        </td>
                                        <td class="clearfix">
                                            <div class="tb-title fl-left">
                                                <h3><?php echo $value['name']; ?></h3>
                                            </div>

                                        </td>
                                        <td><span class="tbody-text"><?php echo $value['user']; ?></span></td>
                                        <td><span class="tbody-text"><?php echo $value['create_date']; ?></span></td>
                                        <td>
                                            <ul class="list-operation ">
                                                <li><a href="?modules=brands&contollers=index&action=edit&id=<?php echo $value['id']; ?>" title="Sửa" class="edit"><i class="fa fa-pencil" aria-hidden="true"></i></a></li>
                                                <li><a href="?modules=brands&contollers=index&action=delete&id=<?php echo $value['id']; ?>" title="Xóa" class="delete"><i class="fa fa-trash" aria-hidden="true"></i></a></li>
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
                                <a <?php if ($i == $data['2']) echo 'style="background-color: green;color:white; border-radius:300px;"';  ?> href="?modules=brands&controllers=index&action=list&page=<?php echo $i; ?>" title=""><?php echo $i; ?></a>
                            </li>
                        <?php }; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>