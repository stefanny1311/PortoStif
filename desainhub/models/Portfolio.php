<?php
class Portfolio extends Model
{
    protected string $table = 'portfolio';

    public function gallery(?int $categoryId = null, int $limit = 12): array
    {
        $sql = "SELECT p.*, c.nama AS kategori, d.id AS designer_id, u.nama AS designer_nama
                FROM portfolio p
                JOIN categories c ON c.id = p.category_id
                JOIN designers d ON d.id = p.designer_id
                JOIN users u ON u.id = d.user_id
                WHERE p.status = 'approved'";
        $params = [];
        if ($categoryId) {
            $sql .= " AND p.category_id = :cat";
            $params['cat'] = $categoryId;
        }
        $sql .= " ORDER BY p.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue(":$k", $v);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
