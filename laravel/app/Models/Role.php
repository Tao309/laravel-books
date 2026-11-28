<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    public const string TABLE_NAME = 'roles';

    public const string PARAM_ID = 'id';

    public const string PARAM_CODE = 'code';

    public const string PARAM_TITLE = 'title';

    public const string PARAM_DATE_CREATED = 'date_created';

    public $timestamps = false;

    protected $fillable = [
        self::PARAM_CODE,
        self::PARAM_TITLE,
        self::PARAM_DATE_CREATED,
    ];

    protected function casts(): array
    {
        return [
            self::PARAM_DATE_CREATED => 'datetime',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
