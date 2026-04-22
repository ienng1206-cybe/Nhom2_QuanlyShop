<?php

class ReviewController extends BaseController
{
    public function store()
    {
        require_login();
        $productId = (int) ($_POST['product_id'] ?? 0);
        $rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
        $comment = trim($_POST['comment'] ?? '');
        $image = null;

        if ($productId > 0 && $comment !== '') {
            $userId = current_user()['id'];
            if ((new OrderModel())->userCanReviewProduct((int) $userId, $productId)) {
                // Xử lý upload ảnh nếu có
                if (!empty($_FILES['review_image']['name'])) {
                    $file = $_FILES['review_image'];
                    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, $allowedExt) && $file['size'] <= $maxSize) {
                        $uploadDir = __DIR__ . '/../assets/uploads/reviews/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        $filename = time() . '_' . $userId . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                        $uploadPath = $uploadDir . $filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                            $image = 'assets/uploads/reviews/' . $filename;
                        }
                    }
                }
                
                (new ReviewModel())->create($userId, $productId, $rating, $comment, $image);
                $_SESSION['success'] = 'Cảm ơn bạn đã đánh giá.';
            } else {
                $_SESSION['error'] = 'Bạn chỉ có thể đánh giá sau khi đơn hàng đã được giao.';
            }
        }

        redirect('product/detail&id=' . $productId);
    }

    public function index()
    {
        $reviewModel = new ReviewModel();
        $reviews = [];
        
        try {
            $reviews = $reviewModel->getAllWithDetails();
        } catch (Throwable $e) {
            // Bảng reviews có thể chưa được tạo
        }

        $this->render('review/index', [
            'title' => 'Đánh giá sản phẩm',
            'view' => 'review/index',
            'reviews' => $reviews,
        ]);
    }
}
