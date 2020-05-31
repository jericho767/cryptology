<?php

namespace App\Services;

use App\Models\Player;
use Illuminate\Support\Facades\Auth;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseService
{
    /**
     * Logged in player
     *
     * @var Player
     */
    protected $player;

    public function __construct()
    {
        $this->player = Auth::user();
    }
}
