<?php

namespace App\Models\ImportProgress;

use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    protected $fillable = [
        'total', 
        'processed', 
        'failed', 
        'status'];

    public function failedRows()
    {
        return $this->hasMany(FailedImportRow::class);
    }
}

