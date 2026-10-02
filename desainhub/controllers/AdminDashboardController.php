<?php
class AdminDashboardController extends Controller
{
    public function __construct()
    {
        Auth::requireRole('admin');
    }

    public function index(): void
    {
        $this->view('dashboard/admin/index', [], 'dashboard');
    }

    public function users(): void
    {
        $this->view('dashboard/admin/users', [], 'dashboard');
    }

    public function designers(): void
    {
        $this->view('dashboard/admin/designers', [], 'dashboard');
    }

    public function verifyDesigner(string $id): void
    {
        setFlash('success', 'Status verifikasi designer berhasil diubah.');
        $this->redirect('/admin/designers');
    }

    public function verifyPortfolio(string $id): void
    {
        setFlash('success', 'Status verifikasi portfolio berhasil diubah.');
        $this->redirect('/admin/orders');
    }

    public function orders(): void
    {
        try {
            $pdo = Database::connect();
            $stmt = $pdo->query(
                "SELECT o.*, cu.nama AS customer_nama, du.nama AS designer_nama, p.nama_paket
                 FROM orders o
                 JOIN users cu ON cu.id = o.user_id
                 JOIN designers d ON d.id = o.designer_id
                 JOIN users du ON du.id = d.user_id
                 JOIN packages p ON p.id = o.package_id
                 ORDER BY o.created_at DESC"
            );
            $orders = $stmt->fetchAll();
        } catch (\Throwable $e) {
            $orders = [];
        }
        $this->view('dashboard/admin/orders', ['orders' => $orders], 'dashboard');
    }

    public function chat(): void
    {
        $this->view('dashboard/admin/chat', [], 'dashboard');
    }

    public function payments(): void
    {
        $this->view('dashboard/admin/payments', [], 'dashboard');
    }

    public function reviews(): void
    {
        $this->view('dashboard/admin/reviews', [], 'dashboard');
    }

    public function blog(): void
    {
        $this->view('dashboard/admin/blog', [], 'dashboard');
    }

    public function statistics(): void
    {
        $this->view('dashboard/admin/statistics', [], 'dashboard');
    }
}