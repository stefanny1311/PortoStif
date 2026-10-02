<?php
class DesignerDashboardController extends Controller
{
    public function __construct()
    {
        Auth::requireRole('designer');
    }

    public function index(): void
    {
        $user = Auth::user();
        try {
            $designer = (new Designer())->findBy('user_id', $user['id']);
            $pdo = Database::connect();
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE designer_id = :did");
            $stmt->execute(['did' => $designer['id'] ?? 0]);
            $orderCount = $stmt->fetch()['total'] ?? 0;
        } catch (\Throwable $e) {
            $orderCount = 0;
        }
        $this->view('dashboard/designer/index', ['orderCount' => $orderCount], 'dashboard');
    }

    public function newOrders(): void
    {
        $user = Auth::user();
        try {
            $designer = (new Designer())->findBy('user_id', $user['id']);
            $pdo = Database::connect();
            $stmt = $pdo->prepare(
                "SELECT o.*, u.nama AS customer_nama, p.nama_paket
                 FROM orders o
                 JOIN users u ON u.id = o.user_id
                 JOIN packages p ON p.id = o.package_id
                 WHERE o.designer_id = :did AND o.status IN ('menunggu_pembayaran','dibayar','brief_masuk')
                 ORDER BY o.created_at DESC"
            );
            $stmt->execute(['did' => $designer['id'] ?? 0]);
            $orders = $stmt->fetchAll();
        } catch (\Throwable $e) {
            $orders = [];
        }
        $this->view('dashboard/designer/new-orders', ['orders' => $orders], 'dashboard');
    }

    public function activeOrders(): void
    {
        $user = Auth::user();
        try {
            $designer = (new Designer())->findBy('user_id', $user['id']);
            $pdo = Database::connect();
            $stmt = $pdo->prepare(
                "SELECT o.*, u.nama AS customer_nama, p.nama_paket
                 FROM orders o
                 JOIN users u ON u.id = o.user_id
                 JOIN packages p ON p.id = o.package_id
                 WHERE o.designer_id = :did AND o.status IN ('proses_desain','revisi','menunggu_approval','selesai')
                 ORDER BY o.updated_at DESC"
            );
            $stmt->execute(['did' => $designer['id'] ?? 0]);
            $orders = $stmt->fetchAll();
        } catch (\Throwable $e) {
            $orders = [];
        }
        $this->view('dashboard/designer/active-orders', ['orders' => $orders], 'dashboard');
    }

    public function revisions(): void
    {
        $this->view('dashboard/designer/revisions', [], 'dashboard');
    }

    public function chat(): void
    {
        $this->view('dashboard/designer/chat', [], 'dashboard');
    }

    public function portfolio(): void
    {
        $user = Auth::user();
        $designerModel = new Designer();
        $designer = $designerModel->findBy('user_id', $user['id']);

        $portfolioModel = new Portfolio();
        $myPortfolio = $designer ? $portfolioModel->where('designer_id', $designer['id'], 'created_at DESC') : [];

        $categoryModel = new Category();
        $categories = $categoryModel->all('nama ASC');

        $this->view('dashboard/designer/portfolio', [
            'myPortfolio' => $myPortfolio,
            'categories'  => $categories,
        ], 'dashboard');
    }

    public function storePortfolio(): void
    {
        $user = Auth::user();
        $designerModel = new Designer();
        $designer = $designerModel->findBy('user_id', $user['id']);

        if (!$designer) {
            setFlash('error', 'Profil designer tidak ditemukan.');
            $this->redirect('/designer-dashboard/portfolio');
            return;
        }

        $judul      = $this->input('judul');
        $categoryId = (int) $this->input('category_id');
        $deskripsi  = $this->input('deskripsi');

        if (empty($judul) || empty($categoryId) || empty($_FILES['gambar']['name'])) {
            setFlash('error', 'Judul, kategori, dan gambar wajib diisi.');
            $this->redirect('/designer-dashboard/portfolio');
            return;
        }

        // Upload gambar
        $uploadDir = UPLOAD_PORTFOLIO;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext      = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $fileName = 'portfolio_' . time() . '_' . uniqid() . '.' . strtolower($ext);
        $filePath = $uploadDir . $fileName;

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array(strtolower($ext), $allowed)) {
            setFlash('error', 'Format gambar tidak didukung (jpg, jpeg, png, gif, webp).');
            $this->redirect('/designer-dashboard/portfolio');
            return;
        }

        if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $filePath)) {
            setFlash('error', 'Gagal mengupload gambar.');
            $this->redirect('/designer-dashboard/portfolio');
            return;
        }

        $portfolioModel = new Portfolio();
        $portfolioModel->create([
            'designer_id' => $designer['id'],
            'category_id' => $categoryId,
            'judul'       => $judul,
            'deskripsi'   => $deskripsi,
            'gambar'      => $fileName,
            'status'      => 'pending',
        ]);

        setFlash('success', 'Portfolio berhasil ditambahkan! Menunggu verifikasi admin.');
        $this->redirect('/designer-dashboard/portfolio');
    }

    public function earnings(): void
    {
        $this->view('dashboard/designer/earnings', [], 'dashboard');
    }

    public function withdraw(): void
    {
        setFlash('success', 'Permintaan penarikan dana sedang diproses.');
        $this->redirect('/designer-dashboard/earnings');
    }

    public function settings(): void
    {
        $this->view('dashboard/designer/settings', [], 'dashboard');
    }
}