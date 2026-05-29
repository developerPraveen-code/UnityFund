<?php

// USER STORY #40: Search FRA Category
// BCE Role: Controller

require_once __DIR__ . '/../entity/FRACategory.php';

class SearchCategoryController
{
    private FRACategory $fraCategory;

    public function __construct()
    {
        $this->fraCategory = new FRACategory();
    }

    public function searchCategory(string $searchTerm): array
    {
        return $this->fraCategory->searchCategory($searchTerm);
    }
}