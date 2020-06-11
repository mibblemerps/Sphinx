<?php

namespace App\Realms;

use Illuminate\Database\Eloquent\Model;

/**
 * World model. Each Realm has 3 worlds (slots).
 *
 * @property int $id World ID
 * @property int $realm_id ID of the Realm this world belongs to.
 * @property int $slot_id Which slot (between 1-3) this world occupies on the Realm.
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
 * @property string $seed World generation seed.
 * @property string $level_type Level type.
 * @property int $template_id ID of template used for world. @TODO implement this.
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

    const LEVEL_TYPE_DEFAULT = 'DEFAULT';
    const LEVEL_TYPE_FLAT = 'FLAT';
    const LEVEL_TYPE_LARGE_BIOMES = 'LARGEBIOMES';
    const LEVEL_TYPE_AMPLIFIED = 'AMPLIFIED';
    const LEVEL_TYPE_CUSTOMIZED = 'CUSTOMIZED';

    const DEFAULT_NAME = 'World 1';
    const DEFAULT_PVP = true;
    const DEFAULT_GAMEMODE = self::GAMEMODE_SURVIVAL;
    const DEFAULT_SPAWN_ANIMALS = true;
    const DEFAULT_DIFFICULTY = self::DIFFICULTY_NORMAL;
    const DEFAULT_SPAWN_MONSTERS = true;
    const DEFAULT_SPAWN_PROTECTION = 0;
    const DEFAULT_SPAWN_NPCS = true;
    const DEFAULT_FORCE_GAMEMODE = false;
    const DEFAULT_COMMAND_BLOCKS = false;

    protected $guarded = [];

    // Default values.
    protected $attributes = [
        'slot_id' => 1,
        'name' => self::DEFAULT_NAME,
        'pvp' => self::DEFAULT_PVP,
        'gamemode' => self::DEFAULT_GAMEMODE,
        'spawn_animals' => self::DEFAULT_SPAWN_ANIMALS,
        'difficulty' => self::DEFAULT_DIFFICULTY,
        'spawn_monsters' => self::DEFAULT_SPAWN_MONSTERS,
        'spawn_protection' => self::DEFAULT_SPAWN_PROTECTION,
        'spawn_npcs' => self::DEFAULT_SPAWN_NPCS,
        'force_gamemode' => self::DEFAULT_FORCE_GAMEMODE,
        'command_blocks' => self::DEFAULT_COMMAND_BLOCKS,

        'seed' => null,
        'level_type' => self::LEVEL_TYPE_DEFAULT,
        'template_id' => null
    ];

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
