<?php

// USER STORY #48: Create FRA Category
// BCE Role: Controller
// Handles creation of a new FRA category.

require_once __DIR__ . '/../entity/FRACategory.php';

class CreateFRACategoryController
{
    private FRACategory $fraCategory;

    public function __construct()
    {
        $this->fraCategory = new FRACategory();
    }

    public function createCategory(string $categoryName, string $description): string
    {
        return $this->fraCategory->createCategory($categoryName, $description);
    }
}