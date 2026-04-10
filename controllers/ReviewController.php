<?php

class ReviewController extends BaseController
{
    public function store()
    {
        require_login();
        $productId = (int) ($_POST['product_id'] ?? 0);
        $rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
        $comment = trim($_POST['comment'] ?? '');

        if ($productId > 0 && $comment !== '') {
            (new ReviewModel())->create(current_user()['id'], $productId, $rating, $comment);
            $_SESSION['success'] = 'Cảm ơn bạn đã đánh giá.';
        }

        redirect('product/detail&id=' . $productId);
    }
}
