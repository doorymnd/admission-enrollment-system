<?php

namespace App\Models\ImportProgress;

use Illuminate\Database\Eloquent\Model;

class FailedImportRow extends Model
{
    protected $fillable = [
        'import_batch_id',
        'email',
        'reason',
        'row_data'
    ];

    protected $casts = [
        'row_data' => 'array',
    ];


        public function batch()
    {
        return $this->belongsTo(ImportBatch::class, 'import_batch_id');
    }
}

