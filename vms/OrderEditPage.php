<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\OrderModel;
class OrderEditPage {
    public $rows;
    public $user;
    public $category;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Locaton: /");
        }
        $this->rows = UserAPI::getOrderById($params[0]);
        $this->user = UserAPI::getUserById($this->rows->message[0]['user_id']);
        $this->title  = "Sửa đơn hàng";
        $this->category = UserAPI::getAllCategory();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            $order = new OrderModel($_POST);
            UserAPI::updateOrderById($order);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<?php foreach($this->rows->message as $row): ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Đơn hàng
                <small>Sửa</small>
            </h1>
        </div>
        <div class="col-lg-7">
            <form action="/editorder" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>"/>
                <div class="form-group">
                    <label>Nguời mua hàng</label>
                    <input type="text" class="form-control" name="email" value="<?= $this->user->message[0]['email'] ?>" disabled>
                </div>
                <div class="form-group">
                <label>Danh mục</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <option>Xin mời chọn danh mục</option>
                        <?php foreach($this->category->message as $category): ?>
                            <?php if($category['id'] === $row['cate_id']): ?>
                                <option value="<?= $category['id'] ?>" selected><?= $category['name'] ?></option>
                            <?php else: ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Đồ ăn/Thức uống</label>
                    <select class="form-control" name="product_id" id="product_id" required></select>
                </div>
                <div class="form-group">
                    <label>Giá cả</label>
                    <input type="text" class="form-control" name="price" id="price" disabled>
                </div>
                <div class="form-group">
                    <label>Số lượng</label>
                    <input type="num" class="form-control" name="quantity" id="quantity" min=1 required>
                </div>
                <div class="form-group">
                    <label>Tổng tiền</label>
                    <input type="text" class="form-control" name="total" id="total" disabled>
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select class="form-control" name="status" id="status" required>
                        <?php if($row['status']==="complete"): ?>
                            <option value="complete" selected>Hoàn thành</option>
                            <option value="cancle">Hủy</option>
                        <?php elseif($row['status']==="cancle"): ?>
                            <option value="complete">Hoàn thành</option>
                            <option value="cancle" selected>Hủy</option>
                        <?php endif; ?>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Sửa</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<?php }}