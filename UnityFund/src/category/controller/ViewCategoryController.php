<?php

// USER STORY #37: View FRA Category
// BCE Role: Controller
// Retrieves FRA categories from FRACategory entity.

require_once __DIR__ . '/../entity/FRACategory.php';

class ViewCategoryController
{
    private FRACategory $fraCategory;

    public function __construct()
    {
        $this->fraCategory = new FRACategory();
    }

    public function getAllCategories(): array
    {
        return $this->fraCategory->getAllCategories();
    }
}