<?php

namespace App\Services;

use App\Models\TurnOrder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GameTurnService
 * @package App\Services
 */
class GameTurnService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ends the turn
     *
     * @param int $gameTurnId
     */
    public function endTurn(int $gameTurnId): void
    {
        // Fetch the turn order where the game turn belongs to
        $turnOrder = TurnOrder::query()
            ->whereHas('turns', function (Builder $builder) use ($gameTurnId): void {
                $builder->where('id', $gameTurnId);
            })
            ->firstOrFail();

        // Set the `has_played` status to played
        $turnOrder->setAttribute('has_played', TurnOrder::HAS_PLAYED);
        $turnOrder->save();
    }
}
