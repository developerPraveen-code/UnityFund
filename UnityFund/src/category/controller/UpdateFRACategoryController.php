<?php

// USER STORY #38: Update FRA Category
// BCE Role: Controller

require_once __DIR__ . '/../entity/FRACategory.php';

class UpdateFRACategoryController
{
    private FRACategory $fraCategory;

    public function __construct()
    {
        $this->fraCategory = new FRACategory();
    }

    public function getCategory(int $categoryId): ?array
    {
        return $this->fraCategory->getCategory($categoryId);
    }

    public function updateCategory(int $categoryId, string $categoryName, string $description): string
    {
        return $this->fraCategory->updateCategory($categoryId, $categoryName, $description);
    }
}