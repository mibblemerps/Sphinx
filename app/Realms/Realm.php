<?php

namespace App\Realms;

use Illuminate\Database\Eloquent\Model;

/**
 * Realm model.
 *
 * @package App\Realms
 */
class Realm extends Model
{
    // Realm states.
    const STATE_OPEN = 'OPEN';
    const STATE_CLOSED = 'CLOSED';
    const STATE_ADMINLOCK = 'ADMIN_LOCK';
    const STATE_UNINITIALIZED = 'UNINITIALIZED';

    protected $guarded = [];
}