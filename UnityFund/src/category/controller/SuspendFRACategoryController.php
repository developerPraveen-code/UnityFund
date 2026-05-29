<?php

// USER STORY #39: Suspend FRA Category
// BCE Role: Controller

require_once __DIR__ . '/../entity/FRACategory.php';

class SuspendFRACategoryController
{
    private FRACategory $fraCategory;

    public function __construct()
    {
        $this->fraCategory = new FRACategory();
    }

    public function suspendCategory(int $categoryId): string
    {
        return $this->fraCategory->suspendCategory($categoryId);
    }
}