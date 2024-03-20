<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid'; // Assuming your primary key column is named 'uuid'
    public $incrementing = false;

    protected $fillable = ['read_at'];

    public function markAsRead()
    {
        // Marquer la notification comme lue ici
        $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
    }

}
