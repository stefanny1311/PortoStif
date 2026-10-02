<?php
class Designer extends Model
{
    protected string $table = 'designers';

    /** Ambil designer beserta data user (nama, avatar) */
    public function allWithUser(int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            "SELECT d.*, u.nama, u.avatar
             FROM designers d
             JOIN users u ON u.id = d.user_id
             WHERE d.status = 'approved'
             ORDER BY d.rating_rata DESC, d.jumlah_project DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findWithUser(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT d.*, u.nama, u.avatar, u.email
             FROM designers d JOIN users u ON u.id = d.user_id
             WHERE d.id = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
