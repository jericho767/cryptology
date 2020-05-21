<?php

use App\Models\Player;
use App\Models\Word;
use Illuminate\Database\Seeder;

/**
 * Class WordSeeder
 */
class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Fetch all players
        $players = Player::all();

        // We can't create words if no one is there to create it duh
        if ($players->count() > 0) {
            factory(Word::class, 100)->make()->each(function (Word $word) use ($players): void {
                // Check if the word already exists
                $hasExists = Word::query()
                    ->where('word', $word->getAttribute('word'))
                    ->exists();

                /** @var Player $wordCreator */
                $wordCreator = $players->random(1);
                $word->setAttribute('created_by', $wordCreator->getAttribute('id'));

                if (!$hasExists) {
                    $word->save();
                }
            });
        }
    }
}
