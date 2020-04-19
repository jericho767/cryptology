<?php

namespace App;

use App\Models\BaseModel;

class Word extends BaseModel
{
    protected $table = 'words';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['word'];

    /**
     * @var int Maximum acceptable length of the word attribute
     */
    const WORD_MAX_LENGTH = 25;

    public function gameMaps()
    {
        // TODO Add relation
    }
}
