<?php 

namespace Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class History extends Model
{
    protected $table = 'history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'event'
    ];

    /**
     * Append programmatically added columns.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Field mutators.
     *
     * @var array
     */
    protected $casts = [
      'expires_at' => 'datetime',
    ];

    public static function boot() {
      parent::boot();

      // On update
      static::updating(function ($model) {
        if (auth()->check()) {
          $model->updated_by = auth()->user()->id;
        }
      });

      // On create
      self::creating(function ($model) {
        // Generate and set UUID
        $uuid = Str::orderedUuid();
        $model->uuid = (string) $uuid;

        if (auth()->check()) {
          $model->created_by = auth()->user()->id;
        }
      });
    }

    /**
     * Relationships
     * -------------
     */

    public function customer() {
      return $this->hasOne(\App\USer::class, 'id', 'customer_id');
    }

    public function staff() {
      return $this->hasOne(\App\USer::class, 'id', 'staff_id');
    }
}