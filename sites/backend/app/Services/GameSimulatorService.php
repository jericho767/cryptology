<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GameMap;
use App\Models\GameTurn;
use App\Models\TurnGuess;
use App\Models\TurnOrder;
use Exception;
use Faker\Generator as Faker;

/**
 * Class SimulateGameService
 * @package App\Services
 */
class GameSimulatorService extends BaseService
{
    private $gameId;
    private $game;
    private $isGameDone;

    private $turnOrders;

    private $turnOrderService;
    private $gameMapService;
    private $gameTurnService;
    private $gameService;

    private $faker;

    /**
     * SimulateGameService constructor.
     * @param TurnOrderService $toService
     * @param GameMapService $gmService
     * @param GameTurnService $gtService
     * @param GameService $gService
     * @param Faker $faker
     */
    public function __construct(
        TurnOrderService $toService,
        GameMapService $gmService,
        GameTurnService $gtService,
        GameService $gService,
        Faker $faker)
    {
        parent::__construct();
        $this->turnOrderService = $toService;
        $this->gameMapService = $gmService;
        $this->gameTurnService = $gtService;
        $this->gameService = $gService;
        $this->faker = $faker;
    }

    /**
     * Sets the class attributes necessary to play the game.
     *
     * @param int $gameId
     * @return void
     */
    private function setAttributes(int $gameId): void
    {
        // Set gameId class attribute
        $this->gameId = $gameId;
        // Fetch the game
        $this->game = Game::with([
            'participants.players.turnOrder.gameTeamPlayer.gameTeam',
            'mapBlocks'
            ])
            ->find($this->gameId);
        // Set status of the game to ongoing
        $this->isGameDone = false;
        // Sequence of turns
        $this->turnOrders = $this->game
            ->getRelation('participants') // Fetch all game teams
            ->pluck('players') // Pluck all players of all game teams
            ->flatten(1) // Merge them into a single dimensional array
            ->pluck('turnOrder') // Pluck their turn orders
            ->sortBy('id') // Sort them by ID to know who goes first and so on
            ->values(); // Reset indeces
    }

    /**
     * Plays the game.
     *
     * @param int $gameId
     * @throws Exception
     */
    public function simulate(int $gameId)
    {
        // Set attributes
        $this->setAttributes($gameId);

        // Initiate the game
        while (!$this->isGameDone) {
            // Fetch turn to make the guess
            $currentTurn = $this->turnOrderService->getCurrentTurn($this->game->getAttribute('id'));

            // There's must be a turn in order for the game to continue
            if ($currentTurn !== null) {
                $this->playTurn($currentTurn);
            } else {
                // No more turns, end the game
                $this->isGameDone = true;
            }

            sleep(1);
        }
    }

    /**
     * Gets the guess count for the turn
     *
     * @param int $gameTeamId
     * @return int
     */
    private function getGuessCount(int $gameTeamId): int
    {
        // Gets the team's unguessed blocks
        $unguessedBlocks = $this->gameMapService->getBlocksOfTeam($gameTeamId, false);
        // 20% of the total unguessed blocks will be set as maximum guessable
        $maxGuessCount = ceil($unguessedBlocks->count() * .25);

        return rand(1, $maxGuessCount);
    }

    /**
     * Plays the turn
     *
     * @param TurnOrder $turnOrder
     */
    private function playTurn(TurnOrder $turnOrder): void
    {
        // Fetch `game_team_id` which will be the basis on how many will be guessed
        $gameTeamId = $turnOrder
            ->getRelation('gameTeamPlayer')
            ->getAttribute('game_team_id');
        // Fetch for the count of guesses
        $guessCount = $this->getGuessCount($gameTeamId);

        // Make the turn object
        $gameTurn = new GameTurn([
            'turn_order_id' => $turnOrder->getAttribute('id'),
            'guess_count' => $guessCount,
            'clue' => $this->faker->word,
        ]);
        $gameTurn->save();

        // Make guesses
        $this->makeGuesses($guessCount, $gameTeamId, $gameTurn->getAttribute('id'));
    }

    /**
     * Makes guesses and promptly end turn if the guess is wrong
     *
     * @param int $guessCount
     * @param int $gameTeamId
     * @param int $gameTurnId
     * @return void
     */
    private function makeGuesses(int $guessCount, int $gameTeamId, int $gameTurnId): void
    {
        // Gets all unguessed blocks
        $unguessedBlocks = $this->gameMapService->getUnguessedBlocks($this->game->getAttribute('id'));
        $hasGuessedAnAssassin = false;

        $unguessedBlocks
            ->shuffle() // Shuffle it so it'll be in a random order
            ->random($guessCount) // Pick a number of guesses
            ->shuffle() // Shuffle the order of guessing
            ->each(function (GameMap $gameMap) use ($gameTeamId, $gameTurnId, &$hasGuessedAnAssassin): ?bool {
                // Create the guess
                $turnGuess = new TurnGuess([
                    'game_turn_id' => $gameTurnId,
                    'game_map_id' => $gameMap->getAttribute('id'),
                ]);
                $turnGuess->save();
                $continueGuessing = true;

                switch ($gameMap->getAttribute('block_owner')) {
                    case GameMap::ASSASSIN_BLOCK_NUM:
                        $hasGuessedAnAssassin = true;
                        // Eliminate team from the game
                        $this->turnOrderService->eliminateTeam($gameTeamId);
                        $continueGuessing = false;
                        break;
                    case GameMap::CIVILIAN_BLOCK_NUM:
                        // End turn
                        $continueGuessing = false;
                        break;
                    case GameMap::TEAM_BLOCK_NUM:
                        // Block guessed is not belonging on the player's team
                        $continueGuessing = $gameTeamId === $gameMap->getAttribute('game_team_id');
                        break;
                }

                $winner = $this->gameService->updateGameWinner($this->game->getAttribute('id'));

                // If there's a winner, then there's no need to proceed
                if ($winner !== null) {
                    $continueGuessing = false;
                    $this->isGameDone = true;
                }

                // If it's true, it'll just reiterate
                return $continueGuessing;
            });

        // No need to end turn when guessed an assassin
        if (!$hasGuessedAnAssassin) {
            // After making the making the guesses, end turn
            $this->gameTurnService->endTurn($gameTurnId);
        }
    }
}
