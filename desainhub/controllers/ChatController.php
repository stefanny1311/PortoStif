<?php
/**
 * ChatController — API untuk fitur chat antara pelanggan ↔ desainer ↔ admin
 */

class ChatController extends Controller
{
    public function __construct()
    {
        if (!Auth::check()) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized. Silakan login terlebih dahulu.']);
            exit;
        }
    }

    /**
     * GET /api/chat/conversations
     * Mengembalikan daftar percakapan user saat ini
     */
    public function getConversations(): void
    {
        $user = Auth::user();
        $role = $user['role'];
        $uid  = (int) $user['id'];
        $pdo  = Database::connect();

        try {
            if ($role === 'admin') {
                $stmt = $pdo->query(
                    "SELECT 
                        o.id AS order_id,
                        o.kode_order,
                        o.status AS order_status,
                        cu.nama AS customer_nama,
                        cu.id AS customer_id,
                        du.nama AS designer_nama,
                        du.id AS designer_id,
                        (SELECT pesan FROM messages m WHERE (m.order_id = o.id OR (m.receiver_id = {$uid} AND m.sender_id IN (o.user_id, du.id)))
                         ORDER BY m.created_at DESC LIMIT 1) AS last_message,
                        (SELECT created_at FROM messages m WHERE (m.order_id = o.id OR (m.receiver_id = {$uid} AND m.sender_id IN (o.user_id, du.id)))
                         ORDER BY m.created_at DESC LIMIT 1) AS last_time,
                        (SELECT COUNT(*) FROM messages m WHERE (m.order_id = o.id) AND m.receiver_id = {$uid} AND m.is_read = 0) AS unread
                     FROM orders o
                     JOIN users cu ON cu.id = o.user_id
                     JOIN designers d ON d.id = o.designer_id
                     JOIN users du ON du.id = d.user_id
                     WHERE EXISTS (SELECT 1 FROM messages m WHERE m.order_id = o.id OR (m.sender_id = {$uid} AND m.receiver_id IN (o.user_id, du.id)))
                     ORDER BY last_time DESC"
                );
            } elseif ($role === 'designer') {
                $designerStmt = $pdo->prepare("SELECT id FROM designers WHERE user_id = :uid");
                $designerStmt->execute(['uid' => $uid]);
                $designer = $designerStmt->fetch();

                if (!$designer) {
                    $this->json([]);
                    return;
                }

                $did = $designer['id'];
                $stmt = $pdo->prepare(
                    "SELECT 
                        o.id AS order_id,
                        o.kode_order,
                        o.status AS order_status,
                        cu.nama AS customer_nama,
                        cu.id AS customer_id,
                        (SELECT pesan FROM messages m WHERE (m.order_id = o.id OR (m.receiver_id = :uid AND m.sender_id = cu.id))
                         ORDER BY m.created_at DESC LIMIT 1) AS last_message,
                        (SELECT created_at FROM messages m WHERE (m.order_id = o.id OR (m.receiver_id = :uid2 AND m.sender_id = cu.id))
                         ORDER BY m.created_at DESC LIMIT 1) AS last_time,
                        (SELECT COUNT(*) FROM messages m WHERE (m.order_id = o.id) AND m.receiver_id = :uid3 AND m.is_read = 0) AS unread
                     FROM orders o
                     JOIN users cu ON cu.id = o.user_id
                     WHERE o.designer_id = :did
                       AND EXISTS (SELECT 1 FROM messages m WHERE m.order_id = o.id OR (m.sender_id IN (:uid4, cu.id) AND m.receiver_id IN (:uid5, cu.id)))
                     ORDER BY last_time DESC"
                );
                $stmt->execute([
                    'uid'  => $uid, 'uid2' => $uid, 'uid3' => $uid,
                    'uid4' => $uid, 'uid5' => $uid, 'did'  => $did,
                ]);
            } else {
                $stmt = $pdo->prepare(
                    "SELECT 
                        o.id AS order_id,
                        o.kode_order,
                        o.status AS order_status,
                        du.nama AS designer_nama,
                        du.id AS designer_id,
                        d.id AS did,
                        (SELECT pesan FROM messages m WHERE (m.order_id = o.id OR (m.receiver_id = :uid AND m.sender_id = du.id))
                         ORDER BY m.created_at DESC LIMIT 1) AS last_message,
                        (SELECT created_at FROM messages m WHERE (m.order_id = o.id OR (m.receiver_id = :uid2 AND m.sender_id = du.id))
                         ORDER BY m.created_at DESC LIMIT 1) AS last_time,
                        (SELECT COUNT(*) FROM messages m WHERE (m.order_id = o.id) AND m.receiver_id = :uid3 AND m.is_read = 0) AS unread
                     FROM orders o
                     JOIN designers d ON d.id = o.designer_id
                     JOIN users du ON du.id = d.user_id
                     WHERE o.user_id = :uid4
                       AND EXISTS (SELECT 1 FROM messages m WHERE m.order_id = o.id OR (m.sender_id IN (:uid5, du.id) AND m.receiver_id IN (:uid6, du.id)))
                     ORDER BY last_time DESC"
                );
                $stmt->execute([
                    'uid'  => $uid, 'uid2' => $uid, 'uid3' => $uid,
                    'uid4' => $uid, 'uid5' => $uid, 'uid6' => $uid,
                ]);
            }

            $conversations = $stmt->fetchAll();
        } catch (\Throwable $e) {
            $conversations = [];
            error_log('Chat getConversations error: ' . $e->getMessage());
        }

        $this->json($conversations);
    }

    /**
     * GET /api/chat/messages/{orderId}
     */
    public function getMessages(string $orderId): void
    {
        $user = Auth::user();
        $uid  = (int) $user['id'];
        $pdo  = Database::connect();

        try {
            $stmt = $pdo->prepare(
                "SELECT m.*, 
                        s.nama AS sender_nama, 
                        s.avatar AS sender_avatar,
                        s.role AS sender_role
                 FROM messages m
                 JOIN users s ON s.id = m.sender_id
                 WHERE m.order_id = :oid
                 ORDER BY m.created_at ASC"
            );
            $stmt->execute(['oid' => (int)$orderId]);
            $messages = $stmt->fetchAll();

            $updateStmt = $pdo->prepare(
                "UPDATE messages SET is_read = 1 WHERE order_id = :oid AND receiver_id = :uid AND is_read = 0"
            );
            $updateStmt->execute(['oid' => (int)$orderId, 'uid' => $uid]);
        } catch (\Throwable $e) {
            $messages = [];
            error_log('Chat getMessages error: ' . $e->getMessage());
        }

        $this->json($messages);
    }

    /**
     * POST /api/chat/send
     */
    public function sendMessage(): void
    {
        $user = Auth::user();

        if (!$this->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $orderId    = (int) $this->input('order_id');
        $receiverId = (int) $this->input('receiver_id');
        $pesan      = trim($this->input('pesan') ?? '');

        if (empty($pesan) || empty($receiverId) || empty($orderId)) {
            $this->json(['error' => 'Pesan, order_id, dan receiver_id wajib diisi.'], 400);
            return;
        }

        $pdo = Database::connect();

        try {
            $lampiran = null;
            if (!empty($_FILES['lampiran']['name'])) {
                $uploadDir = APP_ROOT . '/public/assets/uploads/chat/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext      = pathinfo($_FILES['lampiran']['name'], PATHINFO_EXTENSION);
                $fileName = 'chat_' . time() . '_' . uniqid() . '.' . strtolower($ext);
                $filePath = $uploadDir . $fileName;

                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'zip', 'rar', 'doc', 'docx'];
                if (!in_array(strtolower($ext), $allowed)) {
                    $this->json(['error' => 'Format file tidak didukung.'], 400);
                    return;
                }

                if (move_uploaded_file($_FILES['lampiran']['tmp_name'], $filePath)) {
                    $lampiran = $fileName;
                }
            }

            $stmt = $pdo->prepare(
                "INSERT INTO messages (order_id, sender_id, receiver_id, pesan, lampiran) 
                 VALUES (:order_id, :sender_id, :receiver_id, :pesan, :lampiran)"
            );
            $stmt->execute([
                'order_id'    => $orderId,
                'sender_id'   => $user['id'],
                'receiver_id' => $receiverId,
                'pesan'       => $pesan,
                'lampiran'    => $lampiran,
            ]);

            $messageId = $pdo->lastInsertId();

            $notifStmt = $pdo->prepare(
                "INSERT INTO notifications (user_id, judul, pesan, tipe, link) 
                 VALUES (:uid, :judul, :pesan, 'chat', :link)"
            );
            $notifStmt->execute([
                'uid'   => $receiverId,
                'judul' => 'Pesan Baru',
                'pesan' => mb_strlen($pesan) > 80 ? mb_substr($pesan, 0, 77) . '...' : $pesan,
                'link'  => '/dashboard/chat?order=' . $orderId,
            ]);

            $userStmt = $pdo->prepare("SELECT id, nama, avatar, role FROM users WHERE id = :id");
            $userStmt->execute(['id' => $user['id']]);
            $sender = $userStmt->fetch();

            $this->json([
                'success'    => true,
                'message'    => [
                    'id'             => $messageId,
                    'order_id'       => $orderId,
                    'sender_id'      => (int)$user['id'],
                    'receiver_id'    => $receiverId,
                    'pesan'          => $pesan,
                    'lampiran'       => $lampiran,
                    'is_read'        => 0,
                    'created_at'     => date('Y-m-d H:i:s'),
                    'sender_nama'    => $sender['nama'],
                    'sender_avatar'  => $sender['avatar'],
                    'sender_role'    => $sender['role'],
                ],
            ]);
        } catch (\Throwable $e) {
            error_log('Chat sendMessage error: ' . $e->getMessage());
            $this->json(['error' => 'Gagal mengirim pesan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/chat/orders-for-chat
     */
    public function getAvailableOrders(): void
    {
        $user = Auth::user();
        $role = $user['role'];
        $uid  = (int) $user['id'];
        $pdo  = Database::connect();

        try {
            if ($role === 'admin') {
                $stmt = $pdo->query(
                    "SELECT o.id, o.kode_order, o.status,
                            cu.nama AS customer_nama, cu.id AS customer_id,
                            du.nama AS designer_nama, du.id AS designer_id
                     FROM orders o
                     JOIN users cu ON cu.id = o.user_id
                     JOIN designers d ON d.id = o.designer_id
                     JOIN users du ON du.id = d.user_id
                     ORDER BY o.updated_at DESC"
                );
            } elseif ($role === 'designer') {
                $stmt2 = $pdo->prepare("SELECT id FROM designers WHERE user_id = :uid");
                $stmt2->execute(['uid' => $uid]);
                $designer = $stmt2->fetch();
                if (!$designer) {
                    $this->json([]);
                    return;
                }
                $stmt = $pdo->prepare(
                    "SELECT o.id, o.kode_order, o.status,
                            cu.nama AS customer_nama, cu.id AS customer_id
                     FROM orders o
                     JOIN users cu ON cu.id = o.user_id
                     WHERE o.designer_id = :did
                     ORDER BY o.updated_at DESC"
                );
                $stmt->execute(['did' => $designer['id']]);
            } else {
                $stmt = $pdo->prepare(
                    "SELECT o.id, o.kode_order, o.status,
                            du.nama AS designer_nama, du.id AS designer_id
                     FROM orders o
                     JOIN designers d ON d.id = o.designer_id
                     JOIN users du ON du.id = d.user_id
                     WHERE o.user_id = :uid
                     ORDER BY o.updated_at DESC"
                );
                $stmt->execute(['uid' => $uid]);
            }
            $orders = $stmt->fetchAll();
        } catch (\Throwable $e) {
            error_log('Chat getAvailableOrders error: ' . $e->getMessage());
            $orders = [];
        }

        $this->json($orders);
    }

    /**
     * GET /api/chat/unread-count
     */
    public function getUnreadCount(): void
    {
        $user = Auth::user();
        $uid  = (int) $user['id'];
        $pdo  = Database::connect();

        try {
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) as total FROM messages WHERE receiver_id = :uid AND is_read = 0"
            );
            $stmt->execute(['uid' => $uid]);
            $count = $stmt->fetch()['total'] ?? 0;
        } catch (\Throwable $e) {
            error_log('Chat getUnreadCount error: ' . $e->getMessage());
            $count = 0;
        }

        $this->json(['unread' => (int)$count]);
    }
}