<?php

namespace App\Realms;

use Illuminate\Database\Eloquent\Model;

/**
 * World model. Each Realm has 3 worlds (slots).
 *
 * @property int $id World ID
 * @property int $realm_id ID of the Realm this world belongs to.
 * @property string $name World name
 * @property bool $pvp Is player vs player combat enabled?
 * @property int $gamemode Default gamemode. Represented in the form of Minecraft gamemode IDs.
 * @property bool $spawn_animals Should animals naturally spawn?
 * @property int $difficulty What difficulty level should the world be on? Represented in the form of Minecraft difficulty IDs.
 * @property bool $spawn_monsters Should monsters naturally spawn?
 * @property int $spawn_protection Radius around spawn point that should be protected against non-ops.
 * @property bool $spawn_npcs Should NPC's naturally spawn?
 * @property bool $force_gamemode Should the default gamemode be enforced?
 * @property bool $command_blocks Are command blocks allowed? (Still restricted to operators only).
 *
 * @package App\Realms
 */
class World extends Model
{
    const GAMEMODE_SURVIVAL = 0;
    const GAMEMODE_CREATIVE = 1;
    const GAMEMODE_ADVENTURE = 2;
    const GAMEMODE_SPECTATOR = 3;

    const DIFFICULTY_PEACEFUL = 0;
    const DIFFICULTY_EASY = 1;
    const DIFFICULTY_NORMAL = 2;
    const DIFFICULTY_HARD = 3;

    protected $guarded = [];

    /**
     * Realm relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function realm()
    {
        return $this->belongsTo('App\Realms\Realm');
    }
}
