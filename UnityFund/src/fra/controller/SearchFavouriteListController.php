<?php

// USER STORY #23: Search Favourite FRA
// BCE Role: Controller
// Handles searching within the donee's saved/favourite fundraising activities.

require_once __DIR__ . '/../entity/Favorite.php';

class SearchFavouriteListController
{
    private Favorite $favorite;

    public function __construct()
    {
        $this->favorite = new Favorite();
    }

    public function getSearchShortlistFRA(int $userId, string $keyword): array
    {
        return $this->favorite->searchSavedFRA($userId, $keyword);
    }
}