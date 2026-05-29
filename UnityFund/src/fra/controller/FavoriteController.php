<?php

require_once __DIR__ . '/../entity/Favorite.php';

class FavoriteController
{
    private Favorite $favorite;

    public function __construct()
    {
        $this->favorite = new Favorite();
    }

    public function saveFavorite(int $userId, int $fraId): bool
    {
        return $this->favorite->saveFavorite($userId, $fraId);
    }
}