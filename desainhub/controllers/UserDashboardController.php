<?php
class UserDashboardController extends Controller
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    public function index(): void
    {
        $user = Auth::user();
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = :uid");
        $stmt->execute(['uid' => $user['id']]);
        $orderCount = $stmt->fetch()['total'] ?? 0;

        $this->view('dashboard/user/index', [
            'orderCount' => $orderCount,
        ], 'dashboard');
    }

    public function orders(): void
    {
        $user = Auth::user();
        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare(
                "SELECT o.*, u.nama AS designer_nama, p.nama_paket
                 FROM orders o
                 JOIN designers d ON d.id = o.designer_id
                 JOIN users u ON u.id = d.user_id
                 JOIN packages p ON p.id = o.package_id
                 WHERE o.user_id = :uid
                 ORDER BY o.created_at DESC"
            );
            $stmt->execute(['uid' => $user['id']]);
            $orders = $stmt->fetchAll();
        } catch (\Throwable $e) {
            $orders = [];
        }

        $this->view('dashboard/user/orders', ['orders' => $orders], 'dashboard');
    }

    public function orderDetail(string $id): void
    {
        $user = Auth::user();
        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare(
                "SELECT o.*, u.nama AS designer_nama, u.email AS designer_email, d.spesialisasi,
                        p.nama_paket, p.harga, p.jumlah_revisi, p.estimasi_hari
                 FROM orders o
                 JOIN designers d ON d.id = o.designer_id
                 JOIN users u ON u.id = d.user_id
                 JOIN packages p ON p.id = o.package_id
                 WHERE o.id = :id AND o.user_id = :uid"
            );
            $stmt->execute(['id' => (int)$id, 'uid' => $user['id']]);
            $order = $stmt->fetch();

            // Ambil detail (brief, dll)
            $stmt2 = $pdo->prepare("SELECT * FROM order_detail WHERE order_id = :oid ORDER BY created_at ASC");
            $stmt2->execute(['oid' => (int)$id]);
            $details = $stmt2->fetchAll();
        } catch (\Throwable $e) {
            $order = null;
            $details = [];
        }

        if (!$order) {
            http_response_code(404);
            require APP_ROOT . '/views/errors/404.php';
            return;
        }

        $this->view('dashboard/user/order-detail', [
            'order'   => $order,
            'details' => $details,
        ], 'dashboard');
    }

    public function chat(): void
    {
        $this->view('dashboard/user/chat', [], 'dashboard');
    }

    public function invoice(string $id): void
    {
        $this->view('dashboard/user/invoice', ['id' => $id], 'dashboard');
    }

    public function profile(): void
    {
        $this->view('dashboard/user/profile', [], 'dashboard');
    }

    public function updateProfile(): void
    {
        setFlash('success', 'Profil berhasil diperbarui.');
        $this->redirect('/dashboard/profile');
    }

    public function settings(): void
    {
        $this->view('dashboard/user/settings', [], 'dashboard');
    }
}