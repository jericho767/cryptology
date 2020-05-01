<?php

namespace App\Services;

class GameService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the expected number of assassins for given participant count
     *
     * @param int $participantCount
     * @return int
     */
    public function getNumOfAssassins(int $participantCount): int
    {
        return $participantCount - 1;
    }
}
