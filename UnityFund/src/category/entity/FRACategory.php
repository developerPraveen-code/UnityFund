<?php

// USER STORY #37: View FRA Category
// USER STORY #38: Update FRA Category
// USER STORY #39: Suspend FRA Category
// USER STORY #40: Search FRA Category
// USER STORY #48: Create FRA Category
// BCE Role: Entity

require_once __DIR__ . '/../../shared/database/Database.php';

class FRACategory
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // USER STORY #37: View FRA Category
    public function getAllCategories(): array
    {
        $sql = "SELECT 
                    category_id AS categoryId,
                    category_name AS categoryName,
                    description,
                    status
                FROM fra_categories
                ORDER BY category_id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // USER STORY #37 / #38: Get one FRA Category
    public function getCategory(int $categoryId): ?array
    {
        $sql = "SELECT 
                    category_id AS categoryId,
                    category_name AS categoryName,
                    description,
                    status
                FROM fra_categories
                WHERE category_id = :categoryId
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        return $category ?: null;
    }

    // USER STORY #48: Create FRA Category
    public function createCategory(string $categoryName, string $description): string
    {
        $sql = "INSERT INTO fra_categories 
                    (category_name, description, status)
                VALUES 
                    (:categoryName, :description, 'Active')";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':categoryName', $categoryName);
        $stmt->bindParam(':description', $description);

        return $stmt->execute()
            ? 'FRA category created successfully.'
            : 'FRA category creation failed.';
    }

    // USER STORY #38: Update FRA Category
    public function updateCategory(int $categoryId, string $categoryName, string $description): string
    {
        $sql = "UPDATE fra_categories
                SET category_name = :categoryName,
                    description = :description
                WHERE category_id = :categoryId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':categoryName', $categoryName);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

        return $stmt->execute()
            ? 'FRA category updated successfully.'
            : 'FRA category update failed.';
    }

    // USER STORY #39: Suspend FRA Category
    public function suspendCategory(int $categoryId): string
    {
        $sql = "UPDATE fra_categories
                SET status = 'Suspended'
                WHERE category_id = :categoryId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

        return $stmt->execute()
            ? 'FRA category suspended successfully.'
            : 'FRA category suspension failed.';
    }

    // USER STORY #40: Search FRA Category
    public function searchCategory(string $searchTerm): array
    {
        $sql = "SELECT 
                    category_id AS categoryId,
                    category_name AS categoryName,
                    description,
                    status
                FROM fra_categories
                WHERE category_name LIKE :searchTerm
                   OR description LIKE :searchTerm
                   OR status LIKE :searchTerm
                ORDER BY category_id ASC";

        $keyword = '%' . $searchTerm . '%';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':searchTerm', $keyword);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}