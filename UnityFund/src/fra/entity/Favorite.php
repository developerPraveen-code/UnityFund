<?php

// USER STORY: Save FRA to Favourite List
// USER STORY: View Saved FRA
// USER STORY: Search Saved FRA
// BCE Role: Entity
// Entity only communicates with database. No $_SESSION is used here.

require_once __DIR__ . '/../../shared/database/Database.php';
require_once __DIR__ . '/FundraisingActivity.php';

class Favorite
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function saveFavorite(int $userId, int $fraId): bool
    {
        $checkSql = "SELECT favorite_id 
                     FROM favorites
                     WHERE user_id = :userId
                     AND fra_id = :fraId
                     LIMIT 1";

        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $checkStmt->bindParam(':fraId', $fraId, PDO::PARAM_INT);
        $checkStmt->execute();

        if ($checkStmt->fetch(PDO::FETCH_ASSOC)) {
            return false;
        }

        $insertSql = "INSERT INTO favorites 
                        (user_id, fra_id, saved_date)
                      VALUES 
                        (:userId, :fraId, CURDATE())";

        $insertStmt = $this->conn->prepare($insertSql);
        $insertStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $insertStmt->bindParam(':fraId', $fraId, PDO::PARAM_INT);

        $saved = $insertStmt->execute();

        if ($saved) {
            $fundraisingActivity = new FundraisingActivity();
            $fundraisingActivity->increaseShortlistCount($fraId);
        }

        return $saved;
    }

    public function getSavedFRA(int $userId): array
    {
        $sql = "SELECT 
                    fa.fra_id AS fraId,
                    fa.fundraiser_id AS fundraiserId,
                    fa.title,
                    fa.description,
                    fa.category,
                    fa.goal_amount AS goalAmount,
                    fa.current_amount AS currentAmount,
                    fa.status,
                    fa.view_count AS viewCount,
                    fa.shortlist_count AS shortlistCount,
                    f.saved_date AS savedDate
                FROM favorites f
                INNER JOIN fundraising_activities fa
                    ON f.fra_id = fa.fra_id
                WHERE f.user_id = :userId
                ORDER BY f.saved_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchSavedFRA(int $userId, string $keyword): array
    {
        $sql = "SELECT 
                    fa.fra_id AS fraId,
                    fa.fundraiser_id AS fundraiserId,
                    fa.title,
                    fa.description,
                    fa.category,
                    fa.goal_amount AS goalAmount,
                    fa.current_amount AS currentAmount,
                    fa.status,
                    fa.view_count AS viewCount,
                    fa.shortlist_count AS shortlistCount,
                    f.saved_date AS savedDate
                FROM favorites f
                INNER JOIN fundraising_activities fa
                    ON f.fra_id = fa.fra_id
                WHERE f.user_id = :userId
                AND (
                    fa.title LIKE :keyword
                    OR fa.description LIKE :keyword
                    OR fa.category LIKE :keyword
                )
                ORDER BY f.saved_date DESC";

        $searchKeyword = '%' . $keyword . '%';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':keyword', $searchKeyword);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}