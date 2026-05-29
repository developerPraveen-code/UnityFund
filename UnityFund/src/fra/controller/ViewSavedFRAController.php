<?php

require_once __DIR__ . '/../entity/Favorite.php';

class ViewSavedFRAController
{
    private Favorite $favorite;

    public function __construct()
    {
        $this->favorite = new Favorite();
    }

    public function getSavedFRA(int $userId): array
    {
        return $this->favorite->getSavedFRA($userId);
    }
}