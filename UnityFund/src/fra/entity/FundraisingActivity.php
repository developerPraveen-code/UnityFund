<?php

// BCE Role: Entity
// Entity only communicates with database. No $_SESSION is used here.

require_once __DIR__ . '/../../shared/database/Database.php';

class FundraisingActivity
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createFRA(int $fundraiserId, string $title, string $description, float $goalAmount, string $category): string
    {
        $sql = "INSERT INTO fundraising_activities
                    (fundraiser_id, title, description, goal_amount, current_amount, category, status, start_date, end_date, view_count, shortlist_count)
                VALUES
                    (:fundraiserId, :title, :description, :goalAmount, 0, :category, 'Active', CURRENT_DATE, CURRENT_DATE + INTERVAL '30 days', 0, 0)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fundraiserId', $fundraiserId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':goalAmount', $goalAmount);
        $stmt->bindParam(':category', $category);

        return $stmt->execute()
            ? 'FRA created successfully.'
            : 'FRA creation failed.';
    }

    public function getFRAList(int $fundraiserId): array
    {
        $sql = "SELECT
                    fra_id AS \"fraId\",
                    fundraiser_id AS \"fundraiserId\",
                    title,
                    description,
                    goal_amount AS \"goalAmount\",
                    current_amount AS \"amountRaised\",
                    category,
                    status,
                    start_date AS \"startDate\",
                    end_date AS \"endDate\",
                    view_count AS views,
                    shortlist_count AS \"shortlistCount\"
                FROM fundraising_activities
                WHERE fundraiser_id = :fundraiserId
                ORDER BY fra_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fundraiserId', $fundraiserId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllFRA(): array
    {
        $sql = "SELECT
                    fra_id AS \"fraId\",
                    fundraiser_id AS \"fundraiserId\",
                    title,
                    description,
                    goal_amount AS \"goalAmount\",
                    current_amount AS \"amountRaised\",
                    category,
                    status,
                    start_date AS \"startDate\",
                    end_date AS \"endDate\",
                    view_count AS views,
                    shortlist_count AS \"shortlistCount\"
                FROM fundraising_activities
                WHERE status = 'Active'
                ORDER BY fra_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFRA(int $fraId): ?array
    {
        $this->increaseViewCount($fraId);

        $sql = "SELECT
                    fra_id AS \"fraId\",
                    fundraiser_id AS \"fundraiserId\",
                    title,
                    description,
                    goal_amount AS \"goalAmount\",
                    current_amount AS \"amountRaised\",
                    category,
                    status,
                    start_date AS \"startDate\",
                    end_date AS \"endDate\",
                    view_count AS views,
                    shortlist_count AS \"shortlistCount\"
                FROM fundraising_activities
                WHERE fra_id = :fraId
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fraId', $fraId, PDO::PARAM_INT);
        $stmt->execute();

        $fra = $stmt->fetch(PDO::FETCH_ASSOC);

        return $fra ?: null;
    }

    public function updateFRA(int $fraId, string $title, string $description, float $goalAmount, string $status): ?array
    {
        $sql = "UPDATE fundraising_activities
                SET title = :title,
                    description = :description,
                    goal_amount = :goalAmount,
                    status = :status
                WHERE fra_id = :fraId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':goalAmount', $goalAmount);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':fraId', $fraId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->getFRA($fraId);
        }

        return null;
    }

    public function disableFRA(int $fraId): ?array
    {
        $sql = "UPDATE fundraising_activities
                SET status = 'Disabled'
                WHERE fra_id = :fraId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fraId', $fraId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->getFRA($fraId);
        }

        return null;
    }

    public function searchMyFRA(int $fundraiserId, string $keyword): array
    {
        $sql = "SELECT
                    fra_id AS \"fraId\",
                    fundraiser_id AS \"fundraiserId\",
                    title,
                    description,
                    goal_amount AS \"goalAmount\",
                    current_amount AS \"amountRaised\",
                    category,
                    status,
                    start_date AS \"startDate\",
                    end_date AS \"endDate\",
                    view_count AS views,
                    shortlist_count AS \"shortlistCount\"
                FROM fundraising_activities
                WHERE fundraiser_id = :fundraiserId
                AND (
                    title LIKE :keyword
                    OR description LIKE :keyword
                    OR category LIKE :keyword
                )
                ORDER BY fra_id DESC";

        $searchKeyword = '%' . $keyword . '%';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fundraiserId', $fundraiserId, PDO::PARAM_INT);
        $stmt->bindParam(':keyword', $searchKeyword);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchAllFRA(string $keyword): array
    {
        $sql = "SELECT
                    fra_id AS \"fraId\",
                    fundraiser_id AS \"fundraiserId\",
                    title,
                    description,
                    goal_amount AS \"goalAmount\",
                    current_amount AS \"amountRaised\",
                    category,
                    status,
                    start_date AS \"startDate\",
                    end_date AS \"endDate\",
                    view_count AS views,
                    shortlist_count AS \"shortlistCount\"
                FROM fundraising_activities
                WHERE status = 'Active'
                AND (
                    title LIKE :keyword
                    OR description LIKE :keyword
                    OR category LIKE :keyword
                )
                ORDER BY fra_id DESC";

        $searchKeyword = '%' . $keyword . '%';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':keyword', $searchKeyword);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function viewShortlistCount(int $fraId): int
    {
        $sql = "SELECT shortlist_count
                FROM fundraising_activities
                WHERE fra_id = :fraId
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fraId', $fraId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int)$result['shortlist_count'] : 0;
    }

    public function increaseShortlistCount(int $fraId): void
    {
        $sql = "UPDATE fundraising_activities
                SET shortlist_count = shortlist_count + 1
                WHERE fra_id = :fraId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fraId', $fraId, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function increaseViewCount(int $fraId): void
    {
        $sql = "UPDATE fundraising_activities
                SET view_count = view_count + 1
                WHERE fra_id = :fraId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fraId', $fraId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCompletedFRA(int $fundraiserId): array
    {
        $sql = "SELECT
                    fra_id AS \"fraId\",
                    fundraiser_id AS \"fundraiserId\",
                    title,
                    description,
                    goal_amount AS \"goalAmount\",
                    current_amount AS \"amountRaised\",
                    category,
                    status,
                    start_date AS \"startDate\",
                    end_date AS \"endDate\",
                    view_count AS views,
                    shortlist_count AS \"shortlistCount\"
                FROM fundraising_activities
                WHERE fundraiser_id = :fundraiserId
                AND status = 'Completed'
                ORDER BY end_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fundraiserId', $fundraiserId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchCompletedFRA(int $fundraiserId, string $keyword): array
    {
        $sql = "SELECT
                    fra_id AS \"fraId\",
                    fundraiser_id AS \"fundraiserId\",
                    title,
                    description,
                    goal_amount AS \"goalAmount\",
                    current_amount AS \"amountRaised\",
                    category,
                    status,
                    start_date AS \"startDate\",
                    end_date AS \"endDate\",
                    view_count AS views,
                    shortlist_count AS \"shortlistCount\"
                FROM fundraising_activities
                WHERE fundraiser_id = :fundraiserId
                AND status = 'Completed'
                AND (
                    title LIKE :keyword
                    OR description LIKE :keyword
                    OR category LIKE :keyword
                )
                ORDER BY end_date DESC";

        $searchKeyword = '%' . $keyword . '%';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fundraiserId', $fundraiserId, PDO::PARAM_INT);
        $stmt->bindParam(':keyword', $searchKeyword);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
