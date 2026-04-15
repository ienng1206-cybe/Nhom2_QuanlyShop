<?php

class PaymentModel extends BaseModel
{
    protected $table = 'payments';

    /**
     * Tạo bản ghi thanh toán mới cho đơn hàng
     * 
     * @param int $orderId
     * @param string $method (COD, Banking, etc.)
     * @return int|null Payment ID hoặc null nếu thất bại
     */
    public function create(int $orderId, string $method = 'COD'): ?int
    {
        if (!$this->tableExists()) {
            return null;
        }

        $method = trim($method) ?: 'COD';
        
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO payments (order_id, method, status) VALUES (:order_id, :method, :status)'
            );
            $stmt->execute([
                'order_id' => $orderId,
                'method' => $method,
                'status' => 'pending',
            ]);

            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Lấy thông tin thanh toán của đơn hàng
     */
    public function getByOrder(int $orderId)
    {
        if (!$this->tableExists()) {
            return null;
        }

        $stmt = $this->pdo->prepare('SELECT * FROM payments WHERE order_id = :oid ORDER BY id DESC LIMIT 1');
        $stmt->execute(['oid' => $orderId]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Cập nhật trạng thái thanh toán
     * Statuses: pending, completed, failed, cancelled
     */
    public function updateStatus(int $paymentId, string $status): bool
    {
        if (!$this->tableExists()) {
            return false;
        }

        $validStatuses = ['pending', 'completed', 'failed', 'cancelled'];
        if (!in_array($status, $validStatuses, true)) {
            return false;
        }

        $stmt = $this->pdo->prepare('UPDATE payments SET status = :status WHERE id = :id');
        $result = $stmt->execute(['status' => $status, 'id' => $paymentId]);

        return (bool) $result;
    }

    /**
     * Đánh dấu thanh toán là hoàn thành cùng ngày giờ
     */
    public function markCompleted(int $paymentId): bool
    {
        if (!$this->tableExists()) {
            return false;
        }

        $stmt = $this->pdo->prepare('UPDATE payments SET status = :status, paid_at = NOW() WHERE id = :id');
        $result = $stmt->execute(['status' => 'completed', 'id' => $paymentId]);

        return (bool) $result;
    }

    /**
     * Xóa bản ghi thanh toán (khi xóa đơn hàng)
     */
    public function deleteByOrder(int $orderId): bool
    {
        if (!$this->tableExists()) {
            return true;
        }

        $stmt = $this->pdo->prepare('DELETE FROM payments WHERE order_id = :oid');
        $result = $stmt->execute(['oid' => $orderId]);

        return (bool) $result;
    }

    /**
     * Kiểm tra bảng payments tồn tại
     */
    private function tableExists(): bool
    {
        try {
            $stmt = $this->pdo->query("SHOW TABLES LIKE 'payments'");
            return (bool) $stmt->fetch(PDO::FETCH_NUM);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Lấy danh sách phương thức thanh toán khả dụng
     */
    public static function getPaymentMethods(): array
    {
        return [
            'COD' => 'Thanh toán khi nhận hàng',
            'Banking' => 'Chuyển khoản ngân hàng',
            'Card' => 'Thẻ tín dụng/Ghi nợ',
        ];
    }
}
