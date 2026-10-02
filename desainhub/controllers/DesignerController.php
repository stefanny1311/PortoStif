<?php
class DesignerController extends Controller
{
    public function index(): void
    {
        try {
            $designerModel = new Designer();
            $designers = $designerModel->allWithUser(20);
        } catch (\Throwable $e) {
            $designers = [];
        }
        $this->view('landing/designer', ['designers' => $designers], 'main');
    }

    public function show(string $id): void
    {
        try {
            $designerModel = new Designer();
            $designer = $designerModel->findWithUser((int)$id);
        } catch (\Throwable $e) {
            $designer = null;
        }

        if (!$designer) {
            http_response_code(404);
            require APP_ROOT . '/views/errors/404.php';
            return;
        }

        try {
            $portfolioModel = new Portfolio();
            $portfolio = $portfolioModel->where('designer_id', (int)$id, 'created_at DESC');
        } catch (\Throwable $e) {
            $portfolio = [];
        }

        $this->view('landing/designer-detail', [
            'designer'  => $designer,
            'portfolio' => $portfolio,
        ], 'main');
    }

    /**
     * Proses pemesanan dari customer ke designer
     */
    public function order(string $id): void
    {
        // Cek login
        if (!Auth::check()) {
            setFlash('error', 'Silakan login terlebih dahulu untuk memesan.');
            $this->redirect('/login');
            return;
        }

        $user = Auth::user();
        if ($user['role'] !== 'customer') {
            setFlash('error', 'Hanya customer yang dapat memesan desain.');
            $this->redirect('/designer/' . $id);
            return;
        }

        try {
            $designerModel = new Designer();
            $designer = $designerModel->findWithUser((int)$id);
        } catch (\Throwable $e) {
            $designer = null;
        }

        if (!$designer) {
            setFlash('error', 'Designer tidak ditemukan.');
            $this->redirect('/designer');
            return;
        }

        $categoryId = (int) $this->input('category_id');
        $packageId  = (int) $this->input('package_id');
        $deskripsi  = $this->input('deskripsi');

        if (empty($categoryId) || empty($packageId) || empty($deskripsi)) {
            setFlash('error', 'Kategori, paket, dan deskripsi wajib diisi.');
            $this->redirect('/designer/' . $id);
            return;
        }

        // Ambil harga dari package
        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("SELECT harga FROM packages WHERE id = :pid AND designer_id = :did LIMIT 1");
            $stmt->execute(['pid' => $packageId, 'did' => $designer['id']]);
            $package = $stmt->fetch();

            if (!$package) {
                setFlash('error', 'Paket tidak valid untuk designer ini.');
                $this->redirect('/designer/' . $id);
                return;
            }

            $totalHarga = $package['harga'];

            // Buat order
            $kodeOrder = generateOrderCode();

            $stmt2 = $pdo->prepare(
                "INSERT INTO orders (kode_order, user_id, designer_id, package_id, total_harga, status, progress_percent)
                 VALUES (:kode, :uid, :did, :pid, :total, 'menunggu_pembayaran', 0)"
            );
            $stmt2->execute([
                'kode'  => $kodeOrder,
                'uid'   => $user['id'],
                'did'   => $designer['id'],
                'pid'   => $packageId,
                'total' => $totalHarga,
            ]);
            $orderId = (int) $pdo->lastInsertId();

            // Simpan brief
            $stmt3 = $pdo->prepare(
                "INSERT INTO order_detail (order_id, tipe, deskripsi, created_by)
                 VALUES (:oid, 'brief', :desc, :uid)"
            );
            $stmt3->execute([
                'oid'  => $orderId,
                'desc' => $deskripsi,
                'uid'  => $user['id'],
            ]);

            // Handle upload referensi jika ada
            if (!empty($_FILES['referensi']['name'])) {
                $uploadDir = UPLOAD_BRIEF;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $ext = pathinfo($_FILES['referensi']['name'], PATHINFO_EXTENSION);
                $fileName = 'brief_' . $orderId . '_' . time() . '.' . strtolower($ext);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['referensi']['tmp_name'], $filePath)) {
                    $stmt4 = $pdo->prepare(
                        "INSERT INTO order_detail (order_id, tipe, file_path, created_by)
                         VALUES (:oid, 'referensi', :fp, :uid)"
                    );
                    $stmt4->execute(['oid' => $orderId, 'fp' => $fileName, 'uid' => $user['id']]);
                }
            }

            setFlash('success', 'Pesanan berhasil dibuat! Kode: ' . $kodeOrder . '. Silakan lakukan pembayaran.');
            $this->redirect('/dashboard/orders/' . $orderId);

        } catch (\Throwable $e) {
            setFlash('error', 'Gagal membuat pesanan. Silakan coba lagi.');
            $this->redirect('/designer/' . $id);
        }
    }
}
