<?php

namespace Toetet\FileInput\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    // protected $table = 'files';

    protected $fillable = [
        'file', 'input_name', 'related_id', 'related_type'
    ];

    public function getTable()
    {
        return config('fileinput.table_name', parent::getTable());
    }

    public function file()
    {
        return $this->morphTo();
    }
}
