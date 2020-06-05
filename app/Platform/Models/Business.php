<?php

namespace Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Platform\Controllers\Core;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Support\Str;

class Business extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'businesses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'active', 
      'name', 
    ];

    /**
     * Appended columns.
     *
     * @var array
     */
    protected $appends = [
      'logo', 'cover'
    ];

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
      'social' => 'json',
      'settings' => 'json',
      'attributes' => 'json',
      'meta' => 'json'
    ];

    public function registerMediaCollections() {
      $this
        ->addMediaCollection('logo')
        ->singleFile();

      $this
        ->addMediaCollection('cover')
        ->singleFile();
    }

    public function registerMediaConversions(Media $media = null) {
      $this
        ->addMediaConversion('square')
        ->width(512)
        ->height(512)
        ->performOnCollections('logo');

      $this
        ->addMediaConversion('cover')
        ->width(1280)
        ->height(800)
        ->performOnCollections('cover');
    }

    /**
     * Date/time fields that can be used with Carbon.
     *
     * @return array
     */
    public function getDates() {
      return ['created_at', 'updated_at'];
    }

    public static function boot() {
      parent::boot();

      // On update
      static::updating(function ($model) {
        // Currency fraction digits
        $currencyRepository = new \CommerceGuys\Intl\Currency\CurrencyRepository;
        $currency = $currencyRepository->get($model->currency);
        $model->currency_fraction_digits = $currency->getFractionDigits();

        if (auth()->check()) {
          $model->updated_by = auth()->user()->id;
        }
      });

      // On create
      self::creating(function ($model) {
        // Generate and set UUID
        $uuid = Str::orderedUuid();
        $model->uuid = (string) $uuid;

        // Currency fraction digits
        $currencyRepository = new \CommerceGuys\Intl\Currency\CurrencyRepository;
        $currency = $currencyRepository->get($model->currency);
        $model->currency_fraction_digits = $currency->getFractionDigits();

        if (auth()->check()) {
          $model->created_by = auth()->user()->id;
        }
      });
    }

    /**
     * Form for creating a new record, per role
     *
     * @return array
     */
    public static function getCreateForm() {
      $admin = [
        'tab1' => [
          'text' => trans('app.general'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'text', 'column' => 'name', 'text' => trans('app.name'), 'validate' => 'required|max:64', 'required' => true],
                ['type' => 'image', 'image' => ['thumb_max_width' => '140px', 'thumb_max_height' => '100px'], 'column' => 'cover', 'text' => trans('app.cover'), 'validate' => 'nullable', 'icon' => 'mdi-paperclip'],
              ]
            ]
          ]
        ]
      ];
      $business = null;

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Name used in plan limitations (optional)
     *
     * @return string
     */
    public static function getLimitationName() {
      return 'businesses';
    }

    /**
     * Extra columns used in select queries, exposed in json response
     *
     * @return array
     */
    public static function getExtraSelectColumns() {
      $admin = ['uuid'];
      $business = $admin;

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Extra columns used in select queries, hidden from json response
     *
     * @return array
     */
    public static function getExtraQueryColumns() {
      $admin = ['id', 'created_by'];
      $business = $admin;

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Generic settings
     *
     * actions: add actions column (true / false)
     *
     * @return array
     */
    public static function getSettings() {
      $admin = ['select_all' => true, 'actions' => true, 'create' => true, 'actions_width' => '90px', 'dialog_height' => 375];
      $business = $admin;

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Language variables
     *
     * @return array
     */
    public static function getTranslations() {
      return [
        'items' => trans('app.businesses'),
        'edit_item' => trans('app.edit_business'),
        'create_item' => trans('app.create_business'),
      ];
    }

    /**
     * Define per role if and what they can see
     *
     * all: all records from all accounts
     * account: all records from the current account
     * personal: only records the current user has created
     * created_by: only records created by the user id defined like created_by:1
     * none: this role has no permission
     *
     * @return array
     */
    public static function getPermissions() {
      $admin = ['view' => 'all', 'delete' => 'all', 'update' => 'all', 'create' => true];
      $business = ['view' => 'personal', 'delete' => 'personal', 'update' => 'personal', 'create' => true];

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * The headers for the data table, per role
     *
     * @return array
     */
    public static function getHeaders() {
      $admin = [
        ['visible' => true, 'value' => 'name', 'text' => trans('app.business'), 'align' => 'left', 'sortable' => true]
      ];
      $business = $admin;

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * The columns used for searching the table
     *
     * @return array
     */
    public static function getSearchColumns() {
      $admin = ['name', 'email'];
      $business = $admin;

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Available actions for data table row, per role
     *
     * @return array
     */
    public static function getActions() {
      $admin = [
        ['text' => trans('app.edit'), 'action' => 'edit', 'icon' => 'mdi-pencil', 'color' => 'accent'],
        ['text' => trans('app.delete'), 'action' => 'delete', 'icon' => 'mdi-delete-outline', 'color' => 'accent'],
      ];

      $customer = null;
      $business = null;

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Images
     * -------------
     */

    public function getLogoAttribute() {
      if ($this->getFirstMediaUrl('logo') !== '') {
        $media = $this->getMedia('logo');
        return $media[0]->getFullUrl();
      } else {
        return null;
      }
    }

    /**
     * Get cover
     *
     * @return string for use in <img> src
     */
    public function getCoverAttribute() {
      if ($this->getFirstMediaUrl('cover') !== '') {
        $media = $this->getMedia('cover');
        return $media[0]->getFullUrl();
      } else {
        return null;
      }
    }

    /**
     * Get business details based on user account
     */
    public function getDetails() {
      // Get user who owns business
      $user = \App\User::where('id', $this->created_by)->first();
      return $user->getBusiness();
    }
 
    /**
     * Relationships
     * -------------
     */

    public function user() {
      return $this->hasOne(\App\User::class, 'id', 'created_by');
    }
}
