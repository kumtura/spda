<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCounterAssignment extends Model
{
    protected $table = 'tb_ticket_counter_assignment';
    protected $primaryKey = 'id_assignment';

    protected $fillable = [
        'id_user',
        'id_objek_wisata',
        'aktif',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek_wisata', 'id_objek_wisata');
    }
}
