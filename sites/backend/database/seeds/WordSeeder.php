<?php

use App\Models\Word;
use Illuminate\Database\Seeder;

class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        factory(Word::class, 100)->make()->each(function (Word $word): void {
            // Check if the word already exists
            $hasExists = Word::query()
                ->where('word', $word->getAttribute('word'))
                ->exists();

            if (!$hasExists) {
                $word->save();
            }
        });
    }
}
