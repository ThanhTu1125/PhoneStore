<?php get_header(); ?>

<div id="main-content-wp" class="list-product-page">
    <div class="wrap clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="title-page">
                <div class="clearfix">
                    <h3 id="index" class="fl-left">Danh sách đơn hàng thành công</h3>
                </div>
            </div>
            <div class="section" id="detail-page">
                <div class="section-detail">
                    <div class="table-responsive">
                        <table class="table list-table-wp">
                            <thead>
                                <tr>
                                    <td><span class="thead-text">STT</span></td>
                                    <td><span class="thead-text">Mã đơn hàng</span></td>
                                    <td><span class="thead-text">Thời gian đặt</span></td>
                                    <td><span class="thead-text">Thời gian hoàn thành</span></td>
                                    <td><span class="thead-text">Khách hàng</span></td>
                                    <td><span class="thead-text">Tổng số mặt hàng</span></td>
                                    <td><span class="thead-text">Tổng tiền</span></td>
                                    <td><span class="thead-text">Phương thức thanh toán</span></td>
                                    <td><span class="thead-text">Hoàn tác</span></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data['0'])) foreach ($data['0'] as $key => $value) {?>
                                <tr>
                                    <td><span class="tbody-text"><?php echo ($key +1); ?></span></td>
                                    <td><span class="tbody-text"><?php  echo $value['code']; ?></span></td>
                                    <td><span class="tbody-text"><?php  echo $value['create_date']; ?></span></td>
                                    <td><span class="tbody-text"><?php  echo $value['date_confirm']; ?></span></td>
                                    <td><span class="tbody-text"><?php  echo $value['fullname']; ?></span></td>
                                    <td><span class="tbody-text"><?php  echo $value['total_num_product']; ?></span></td>
                                    <td><span class="tbody-text"><?php  echo $value['total_price']; ?></h3></span>
                                    <td><span class="tbody-text"><?php  echo $value['payment_method']; ?></span></td>
                                    <td><span class="tbody-text" style="color: green;">Thành công</span></td>
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
                        <?php for ($i=1; $i <= $data['1'] ; $i++) { ?>
                        <li>
                            <a <?php if($i == $data['2']) echo 'style="background-color: green;color:white; border-radius:300px;"';  ?>  href="?modules=orders&controllers=index&action=list&page=<?php echo $i; ?>" title=""><?php echo $i; ?></a>
                        </li>
                        <?php }; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>