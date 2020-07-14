<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Platform\Controllers\Core;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;
use ShortUUID\ShortUUID;
use CommerceGuys\Intl\Currency\CurrencyRepository;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Platform\Models\Promo;

class User extends Authenticatable implements JWTSubject, HasLocalePreference, HasMedia
{
    use Notifiable;
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', "location"
    ];

    /**
     * Append programmatically added columns.
     *
     * @var array
     */
    protected $appends = [
      'avatar', 'cover', 'name', 'business_name', 'premium', 'plan_limitations'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'premium_expires_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    public function getJWTIdentifier() {
      return $this->getKey();
    }

    public function getJWTCustomClaims() {
      return [];
    }

    public function registerMediaCollections() {
      $this
        ->addMediaCollection('avatar')
        ->singleFile();

      $this
        ->addMediaCollection('cover')
        ->singleFile();
    }

    public function registerMediaConversions(Media $media = null) {
      $this
        ->addMediaConversion('avatar')
        ->width(512)
        ->height(512)
        ->performOnCollections('avatar');

      $this
        ->addMediaConversion('cover')
        ->width(1280)
        ->height(800)
        ->performOnCollections('cover');
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale() {
        return $this->language;
    }

    public static function boot() {
      parent::boot();

      // On select
      static::retrieved(function ($model) {
      });

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

        // Customer number
        if ($model->role == 2) {
          $model->customer_number = $model->getUniqueCustomerNumber($model);
        }

        // API token
        $model->token = Str::random(32);

        if (auth()->check()) {
          $model->created_by = auth()->user()->id;
        }
      });
    }

    /**
     * Make sure customer number is unique
     *
     * @return boolean
     */
    public function getUniqueCustomerNumber($model) {
      $customer_number = Core\Secure::getRandom(12, '1234567890');
      $customer_number = implode('-', str_split($customer_number, 3));

      $business = User::where('id', '<>', $model->id)->where('customer_number', $customer_number)->first();
      if ($business === null) {
        return $customer_number;
      } else {
        return $model->getUniqueCustomerNumber();
      }
    }

    /**
     * Get avatar
     *
     * @return string for use in <img> src
     */
    public function getAvatarAttribute() {
      if ($this->getFirstMediaUrl('avatar') !== '') {
        $media = $this->getMedia('avatar');
        return $media[0]->getFullUrl();
      } else {
        return (string) \Avatar::create(strtoupper($this->name))->toBase64();
      }
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getNameAttribute() {
      return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get business name
     *
     * @return string
     */
    public function getBusinessNameAttribute() {
      $business = \Platform\Models\Business::where('created_by', $this->id)->first();
      return ($business) ? $business->name : $this->customer_number;
    }

    /**
     * Get plan limiations.
     *
     * @return string
     */
    public function getPlanLimitationsAttribute() {
      if ($this->role != 1) {
        if ($this->premium == 1) {
          return config('default.max_items_premium');
        } else {
          return config('default.max_items');
        }
      } else {
        return true;
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
        //return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
      }
    }

    /**
     * Is premium
     *
     * @return boolean
     */
    public function getPremiumAttribute() {
      if ($this->parent_id !== null) {
        return ($this->parent->premium_expires_at == null || $this->parent->premium_expires_at->addDays(config('default.premium_grace_period_days'))->isPast()) ? false : true;
      } else {
        return ($this->premium_expires_at == null || $this->premium_expires_at->addDays(config('default.premium_grace_period_days'))->isPast()) ? false : true;
      }
    }

    /**
     * Money formatting
     */
    public function formatMoney($amount, $currency = 'USD', $formatHtml = false) {
      if ($currency == null) $currency = 'USD';
      $value = Money::{$currency}($amount);
      $currencies = new \Money\Currencies\ISOCurrencies();

      $numberFormatter = new \NumberFormatter($this->getLanguage(), \NumberFormatter::CURRENCY);
      $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

      $amount = $moneyFormatter->format($value);
      if ($formatHtml) {
        $amount = explode($numberFormatter->getSymbol(0), $amount);
        $amount = $amount[0] . '<span class="cents">' . $numberFormatter->getSymbol(0) . $amount[1] . '</span>';
      }

      return $amount;
    }

    /**
     * Date / time formatting
     */
    public function formatDate($date, $format = 'date_medium') {
      if ($date !== null) {
        switch ($format) {
          case 'date_medium': $date = $date->timezone($this->getTimezone())->format('d-m-y'); break;
          case 'datetime_medium': $date = $date->timezone($this->getTimezone())->format('d-m-y H:i'); break;
          case 'friendly': $date = $date->timezone($this->getTimezone())->diffForHumans(); break;
        }
        return $date;
      } else {
        return null;
      }
    }

    /**
     * User language
     */
    public function getLanguage() {
      if ($this->language === NULL) {
        return config('default.language');
      } else {
        return $this->language;
      }
    }

    /**
     * User locale
     */
    public function getLocale() {
      if ($this->locale === NULL) {
        return config('default.locale');
      } else {
        return $this->locale;
      }
    }

    /**
     * User timezone
     */
    public function getTimezone() {
      if ($this->timezone === NULL) {
        return config('default.timezone');
      } else {
        return $this->timezone;
      }
    }

    /**
     * User currency
     */
    public function getCurrency() {
      if ($this->currency === NULL) {
        return config('default.currency');
      } else {
        return $this->currency;
      }
    }

    /**
     * Currency decimal points
     */
    public function getCurrencyFormat($get = null) {
      $currencyRepository = new CurrencyRepository;
      $currency = $currencyRepository->get($this->getCurrency());

      $format = [
          'numeric_code' => $currency->getNumericCode(),
          'fraction_digits' => $currency->getFractionDigits(),
          'name' => $currency->getName(),
          'symbol' => $currency->getSymbol(),
          'locale' => $currency->getLocale()
      ];

      return ($get === null) ? $format : $format[$get];
    }

    /**
     * The headers for the data table, per role
     *
     * @return array
     */
    public static function getHeaders() {
      $admin = [
        ['visible' => false, 'value' => 'avatar', 'exclude_from_select' => true, 'width' => '60px', 'text' => trans('app.avatar'), 'align' => 'left', 'sortable' => false],
        ['visible' => true, 'value' => 'role', 'type' => 'enum', 'items' => ['1' => trans('app.admin'), '2' => trans('app.customer'), '3' => trans('app.business'), '4' => trans('app.staff')], 'text' => 'Role', 'align' => 'left', 'sortable' => false],
        ['visible' => true, 'value' => 'name', 'exclude_from_select' => true, 'text' => trans('app.name'), 'align' => 'left', 'sortable' => false],
        ['visible' => true, 'value' => 'email', 'text' => trans('app.email'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'business_name', 'exclude_from_select' => true, 'relation' => ['type' => 'hasOne', 'with' => 'business', 'table' => 'businesses', 'val' => 'name'], 'text' => trans('app.business_or_customer_number'), 'align' => 'left', 'sortable' => false],
        //['visible' => true, 'value' => 'customer_number', 'text' => trans('app.customer_number'), 'align' => 'left', 'sortable' => false],
        ['visible' => true, 'value' => 'logins', 'type' => 'number', 'text' => trans('app.logins'), 'align' => 'right', 'sortable' => true],
        ['visible' => true, 'value' => 'last_login', 'type' => 'date_time', 'format' => 'ago', 'text' => trans('app.last_login'), 'align' => 'left', 'sortable' => true],
        //['visible' => true, 'value' => 'active', 'text' => trans('app.active'), 'align' => 'center', 'sortable' => true, 'type' => 'boolean'],
        // ['visible' => true, 'value' => 'premium', 'exclude_from_select' => true, 'text' => trans('app.premium'), 'align' => 'center', 'sortable' => true, 'type' => 'boolean']
      ];

      $business = [
        ['visible' => true, 'value' => 'avatar', 'exclude_from_select' => true, 'width' => '60px', 'text' => trans('app.avatar'), 'align' => 'left', 'sortable' => false],
        ['visible' => true, 'value' => 'name', 'exclude_from_select' => true, 'text' => trans('app.name'), 'align' => 'left', 'sortable' => false],
        ['visible' => true, 'value' => 'email', 'text' => trans('app.email'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'logins', 'type' => 'number', 'text' => trans('app.logins'), 'align' => 'right', 'sortable' => true],
        ['visible' => true, 'value' => 'last_login', 'type' => 'date_time', 'format' => 'ago', 'text' => trans('app.last_login'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'active', 'text' => trans('app.active'), 'align' => 'center', 'sortable' => true, 'type' => 'boolean']
      ];

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Form for creating a new record, per role
     *
     * @return array
     */
    public static function getCreateForm() {
      $admin = [
        'tab1' => [
          'text' => trans('app.account'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'date', 'format' => 'LLL', 'column' => 'premium_expires_at', 'text' => trans('app.premium_expiry_date'), 'validate' => 'nullable', 'required' => false],
                ['type' => 'text', 'column' => 'first_name', 'text' => trans('app.first_name'), 'validate' => 'required|max:32', 'required' => true],
                ['type' => 'text', 'column' => 'last_name', 'text' => trans('app.last_name'), 'validate' => 'required|max:32', 'required' => true],
                ['type' => 'email', 'column' => 'email', 'text' => trans('app.email'), 'validate' => 'required|email|max:64|unique:users', 'required' => true],
                ['type' => 'image', 'class' => 'round', 'image' => ['thumb_max_width' => '140px', 'thumb_max_height' => '140px'], 'column' => 'avatar', 'text' => trans('app.avatar'), 'validate' => 'nullable', 'icon' => 'mdi-paperclip'],
                ['type' => 'boolean', 'default' => true, 'column' => 'active', 'text' => trans('app.active'), 'validate' => 'nullable']
              ]
            ]
          ]
        ]
      ];

      $business = [
        'tab1' => [
          'text' => trans('app.account'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'text', 'column' => 'first_name', 'text' => trans('app.first_name'), 'validate' => 'required|max:32', 'required' => true],
                ['type' => 'text', 'column' => 'last_name', 'text' => trans('app.last_name'), 'validate' => 'required|max:32', 'required' => true],
                ['type' => 'email', 'column' => 'email', 'text' => trans('app.email'), 'validate' => 'required|email|max:64|unique:users', 'required' => true],
                ['type' => 'password', 'column' => 'password', 'text' => trans('app.password'), 'validate' => 'required|min:8|max:24', 'required' => true],
                ['type' => 'boolean', 'default' => true, 'column' => 'active', 'text' => trans('app.active'), 'validate' => 'nullable']
              ]
            ]
          ]
        ]
      ];

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Form for updating a new record, per role
     *
     * @return array
     */
    public static function getUpdateForm() {
      $admin = [
        'tab1' => [
          'text' => trans('app.account'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'date', 'format' => 'LLL', 'column' => 'premium_expires_at', 'text' => trans('app.premium_expiry_date'), 'validate' => 'nullable', 'required' => false],
                ['type' => 'text', 'column' => 'first_name', 'text' => trans('app.first_name'), 'validate' => 'required|max:32', 'required' => true],
                ['type' => 'text', 'column' => 'last_name', 'text' => trans('app.last_name'), 'validate' => 'required|max:32', 'required' => true],
                ['type' => 'email', 'column' => 'email', 'text' => trans('app.email'), 'validate' => 'required|email|max:64|unique:users,email', 'required' => true],
                ['type' => 'image', 'class' => 'round', 'image' => ['thumb_max_width' => '140px', 'thumb_max_height' => '140px'], 'column' => 'avatar', 'text' => trans('app.avatar'), 'validate' => 'nullable', 'icon' => 'mdi-paperclip'],
                ['type' => 'text', 'column' => 'transaction_fee', 'text' => 'Transaction Fee', 'validate' => 'required', 'required' => true],
                ['type' => 'boolean', 'default' => true, 'column' => 'active', 'text' => trans('app.active'), 'validate' => 'nullable'],
              ]
            ]
          ]
        ]
      ];

      $business = [
        'tab1' => [
          'text' => trans('app.account'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'text', 'column' => 'first_name', 'text' => trans('app.first_name'), 'validate' => 'required|max:32', 'required' => true],
                ['type' => 'text', 'column' => 'last_name', 'text' => trans('app.last_name'), 'validate' => 'required|max:32', 'required' => true],
                ['type' => 'email', 'column' => 'email', 'text' => trans('app.email'), 'validate' => 'required|email|max:64|unique:users,email', 'required' => true],
                ['type' => 'password', 'column' => 'password', 'text' => trans('app.password'), 'validate' => 'min:8|max:24', 'required' => false],
                ['type' => 'boolean', 'default' => true, 'column' => 'active', 'text' => trans('app.active'), 'validate' => 'nullable']
              ]
            ]
          ]
        ]
      ];

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Defaults (optional)
     * Define default values for columns.
     *
     * @return string
     */
    public static function getDefaultsClass() {
      $admin = [];
      $business = ['parent_id' => auth()->user()->id, 'role' => 4];

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Export class name if table can be exported (optional)
     *
     * @return string
     */
    public static function getExportClass() {
      $admin = 'Platform\Exports\UsersExport';
      $business = null;

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Extra columns used in select queries, exposed in json response
     *
     * @return array
     */
    public static function getExtraSelectColumns() {
      $admin = ['uuid', 'first_name', 'last_name', 'premium_expires_at', 'customer_number', 'whatsapp'];
      $business = ['uuid', 'first_name', 'last_name'];

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
     * widths were: 105px
     *
     * @return array
     */
    public static function getSettings() {
      $admin = ['select_all' => true, 'actions' => true, 'create' => false, 'actions_width' => '135px'];
      $business =['select_all' => true, 'actions' => true, 'create' => true, 'actions_width' => '135px'];

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Columns used for filters
     *
     * @return array
     */
    public static function getTableFilters() {
      $admin = [
        ['column' => 'role', 'text' => trans('app.all_roles'), 'icon' => 'mdi-filter-variant', 'type' => 'enum', 'items' => ['2' => trans('app.customer'), '3' => trans('app.business'), '4' => trans('app.staff')], 'default' => null]
      ];
      $business = [];

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
        'items' => trans('app.users'),
        'edit_item' => trans('app.edit_user'),
        'create_item' => trans('app.create_user'),
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
      $admin = ['view' => 'personal', 'delete' => 'personal', 'update' => 'personal', 'create' => true];
      $business = ['view' => 'personal', 'delete' => 'personal', 'update' => 'personal', 'create' => true];

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
      $admin = ['first_name', 'last_name', 'email', 'customer_number'];
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
        ['text' => 'Payments', 'action' => 'open_payments', 'icon' => 'mdi-cash', 'color' => 'green'],
        ['text' => trans('app.edit'), 'action' => 'edit', 'icon' => 'mdi-pencil', 'color' => 'accent'],
        ['text' => trans('app.delete'), 'action' => 'delete', 'icon' => 'mdi-delete-outline', 'color' => 'accent'],
        ['divider'],
        ['text' => trans('app.log_in_to_account'), 'action' => 'log_in_as', 'icon' => 'mdi-login', 'color' => 'accent'],
      ];

      $business = $admin;

      return [
        1 => $admin,
        3 => $business
      ];
    }

    /**
     * Admin stats
     */
    public function getAdminStats($statsPeriod = '7days') {
      // Totals
      $totals = [
        'users' => $this->users->count()
      ];

      // Period
      if ($statsPeriod == '7days') {
        $from = now()->addDays(-7);
        $to = now();
        $fromPrevious = now()->addDays(-15);
        $toPrevious = now()->addDays(-8);
      }

      // User signups for current period
      $period = new \DatePeriod( new \DateTime($from), new \DateInterval('P1D'), new \DateTime($to));

      $range = [];
      foreach($period as $date){
        $range[$date->format("Y-m-d")] = 0;
      }

      $data = $this->users()
        ->select([
          DB::raw('DATE(`created_at`) as `date`'),
          DB::raw('COUNT(id) as `count`')
        ])
        ->whereBetween('created_at', [$from, $to])
        ->groupBy('date')
        ->get()
        /*
        ->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('d');
        })*/
        ->pluck('count', 'date');

      $dbData = [];
      $total = 0;
      if ($data !== null) {
        foreach($data as $date => $count) {
          $dbData[$date] = (int) $count;
          $total += $count;
        }
      }

      $businessSignups = array_replace($range, $dbData);
      $businessSignupsTotal = $total;

      // Customer signups for previous period
      $period = new \DatePeriod( new \DateTime($fromPrevious), new \DateInterval('P1D'), new \DateTime($toPrevious));
      $data = $this->users()
        ->select([
          DB::raw('COUNT(id) as `count`')
        ])
        ->whereBetween('created_at', [$fromPrevious, $toPrevious])
        ->get()
        ->pluck('count');

      $businessSignupsTotalPrevious = (int) $data[0];

      $stats = [
        'version' => config('version.current'),
        'total' => $totals,
        'users' => [
          'signupsCurrentPeriod' => $businessSignups,
          'signupsCurrentPeriodTotal' => $businessSignupsTotal,
          'signupsPreviousPeriodTotal' => $businessSignupsTotalPrevious,
          'signupsChange' => $businessSignupsTotal - $businessSignupsTotalPrevious
        ]
      ];

      return $stats;
    }

    /**
     * Get business details
     */
    public function getBusiness($returnIds = false) {
      if ($this->parent_id !== null) {
        $user = User::where('id', $this->parent_id)->first();
      } else {
        $user = $this;
      }

      if (!$user->business) return null;

      $currency_fraction_digits = $user->business->currency_fraction_digits ?? config('default.currency_fraction_digits');
      $currency_step = floatval(number_format(0, $currency_fraction_digits - 1) . 1);
      $currency_point_value_step = floatval(number_format(0, $currency_fraction_digits + 1) . 1);
      $currency_point_value_max = $currency_point_value_step * 1000000;

      if (!$user->premium) {
        $points_expiration_months = config('default.points_expiration_months');
        $points_per_currency = config('default.points_per_currency');
        $point_value = config('default.point_value');
        $min_points_per_purchase = config('default.min_points_per_purchase');
        $max_points_per_purchase = config('default.max_points_per_purchase');
        $min_points_per_redemption = config('default.min_points_per_redemption');
        $max_points_per_redemption = config('default.max_points_per_redemption');
      } else {
        $points_expiration_months = $user->business->points_expiration_months ?? config('default.points_expiration_months');
        $points_per_currency = $user->business->points_per_currency ?? config('default.points_per_currency');
        $point_value = $user->business->point_value ?? config('default.point_value');
        $min_points_per_purchase = $user->business->min_points_per_purchase ?? config('default.min_points_per_purchase');
        $max_points_per_purchase = $user->business->max_points_per_purchase ?? config('default.max_points_per_purchase');
        $min_points_per_redemption = $user->business->min_points_per_redemption ?? config('default.min_points_per_redemption');
        $max_points_per_redemption = $user->business->max_points_per_redemption ?? config('default.max_points_per_redemption');
      }

      $business = [
        'id' => $user->business->id,
        'uuid' => $user->business->id,
        'cover' => $user->business->cover,
        'name' => $user->business->name,
        'transaction_fee' => $user->business->transaction_fee,
        'cid' => $user->business->category_id,
        'short_description' => $user->business->short_description,
        'currency' => $user->business->currency ?? config('default.currency'),
        'currency_fraction_digits' => $currency_fraction_digits,
        'currency_step' => $currency_step,
        'currency_point_value_step' => $currency_point_value_step,
        'currency_point_value_max' => $currency_point_value_max,
        'points_expiration_months' => $points_expiration_months,
        'points_per_currency' => $points_per_currency,
        'point_value' => floatval($point_value),
        'min_points_per_purchase' => $min_points_per_purchase,
        'max_points_per_purchase' => $max_points_per_purchase,
        'min_points_per_redemption' => $min_points_per_redemption,
        'max_points_per_redemption' => $max_points_per_redemption
      ];

      if ($returnIds) $business['business_id'] = $user->business->id;
      if ($returnIds) $business['user_id'] = $user->business->created_by;

      return $business;
    }

    /**
     * Get cards in user wallet
     */
    public function getWallet() {
      // Get businesses where customer has earned points, ordered by last earned points to keep list active
      $businesses = \DB::table('history')
        ->select('business_id', 'created_at')
        ->where('customer_id', $this->id)
        ->orderBy('created_at', 'asc')
        ->get()
				->keyBy('business_id');

      $businesses = $businesses->map(function ($item) {
        $business = \Platform\Models\Business::where('id', $item->business_id)->first();
        if ($business) {
          return [
            'uuid' => $business->uuid,
            'cover' => $business->cover,
            'name' => $business->name,
            'short_description' => $business->short_description,
            'balance' => $this->getBalanceForBusinessById($business->id, $business->created_by),
            'last_event' => $item->created_at
          ];
        }
      });

      return $businesses->sortByDesc('last_event')->values()->all();
    }

    /**
     * Get points from business
     */
    public function getBalanceForBusinessById($business_id, $business_user_id) {
      $history = \Platform\Models\History::where('customer_id', $this->id)->where('business_id', $business_id)->where('expires_at', '>', Carbon::now())->get();
      $business = \App\User::where('id', $business_user_id)->first()->getBusiness();
      $points = 0;
      $point_value = 0;
      if (isset($history[0])) {
        foreach ($history as $event) {
          if ($event->event == 'purchase') {
            $point_value = $event->point_value;
            $available_points = $event->points - $event->points_used;
            $points += $available_points;
          }
        }
      }

      $value = $points * $point_value;
      $redeemable = (intval($points) >= intval($business['min_points_per_redemption'])) ? true : false;
      $pointsRequired = (intval($business['min_points_per_redemption']) - intval($points)) ;

      $max_points_redeemable = (intval($points) >= $business['max_points_per_redemption']) ? $business['max_points_per_redemption'] : $points;
      $max_amount_redeemable = $max_points_redeemable * $business['point_value'];

      $rules = [
        'points_expiration_months' => $business['points_expiration_months'],
        'points_per_currency' => $business['points_per_currency'],
        'currency_step' => $business['currency_step'],
        'min_amount_redeemable' => $business['min_points_per_redemption'] * $business['point_value'],
        'max_amount_redeemable' => floor($max_amount_redeemable * 100) / 100,
        'max_points_redeemable' => $max_points_redeemable,
        'point_value' => $business['point_value'],
        'min_points_per_purchase' => $business['min_points_per_purchase'],
        'max_points_per_purchase' => $business['max_points_per_purchase'],
        'min_points_per_redemption' => $business['min_points_per_redemption'],
        'max_points_per_redemption' => $business['max_points_per_redemption']
      ];

      return [
        'points' => $points,
        'value' => $value,
        'currency' => $business['currency'],
        'redeemable' => $redeemable,
        'pointsRequired' => $pointsRequired,
        'rules' => $rules
      ];
    }

    /**
     * Get card details by business uuid
     */
    public function getCard($uuid, $history = true, $returnIds = false) {
      $business = \Platform\Models\Business::where('uuid', $uuid)->first();
      if (!$business) return null;

      if ($history) $history = $this->getHistoryForBusinessById($business->id, $business->created_by);

      $card = [
        'uuid' => $business->uuid,
        'cover' => $business->cover,
        'name' => $business->name,
        'short_description' => $business->short_description,
        'discount_code_expiration_time_hours' => config('default.discount_code_expiration_time_hours'),
        'balance' => $this->getBalanceForBusinessById($business->id, $business->created_by),
        'history' => $history
      ];

      if ($returnIds) $card['business_id'] = $business->id;
      if ($returnIds) $card['user_id'] = $business->created_by;

      return $card;
    }

    /**
     * Get history from business
     */
    public function getHistoryForBusinessById($business_id, $business_user_id) {
      $history = \Platform\Models\History::where('customer_id', $this->id)->where('business_id', $business_id)->orderBy('created_at', 'desc')->get();
      $business = \App\User::where('id', $business_user_id)->first()->getBusiness();

      $events = [];
      foreach ($history as $event) {
        $events[] = [
          'event' => $event->event,
          'points' => $event->points,
          'points_used' => $event->points_used,
          'points_available' => $event->points - $event->points_used,
          'points_value' => $event->points * $event->point_value,
          'purchase_amount' => $event->purchase_amount / intval(str_pad('1', $business['currency_fraction_digits'] + 1, '0')),
          'created_at' => $event->created_at->timezone($this->getTimezone())->diffForHumans(),
        //   'created_at' => $event->created_at->timezone($this->getTimezone())->format('Y-m-d H:i:s'),
          'expires_at' => ($event->expires_at !== null) ? $event->expires_at->timezone($this->getTimezone())->format('Y-m-d H:i:s') : null,
          'expired' => ($event->expires_at !== null) ? $event->expires_at->timezone($this->getTimezone())->isPast() : false
        ];
      }
      return $events;
    }

    /**
     * Redeem points with business id
     */
    public function redeemPoints($points, $business_id) {
      // Get non-expired points
      $history = \Platform\Models\History::where('customer_id', $this->id)->where('business_id', $business_id)->where('expires_at', '>', Carbon::now())->where('event', 'purchase')->orderBy('created_at', 'asc')->get();
      if (!$history) {
        return [
          'success' => false,
          'msg' => 'No points found'
        ];
      };

      $business = \App\User::where('id', $history[0]->user_id)->first()->getBusiness();

      $redeemed = 0;
      foreach ($history as $event) {
        if ($event->event == 'purchase') {
          $available_points = $event->points - $event->points_used;
          if ($available_points > 0 && $redeemed < $points) {
            if (($available_points + $redeemed <= $points) && ($redeemed < $points)) {
              // Redeem all available points
              $redeem = $available_points;
            } elseif (($available_points + $redeemed > $points) && ($redeemed < $points)) {
              // Redeem partially
              $redeem = $points - $redeemed;
            }
            $event->points_used = $redeem;
            $event->save();
            $redeemed += $redeem;
            $point_value = $event->point_value;
            $point_value = $event->point_value;
          }
        }

        if ($redeemed == $points) {
          $value = $points * $point_value;
          return [
            'success' => true,
            'points' => $points,
            'value' => $value,
            'currency' => $business['currency'],
            'customer_number' => $history[0]->customer_number
          ];
        }
      }
    }

    /**
     * Relationships
     * -------------
     */

    public function users() {
      return $this->hasMany(\App\User::class, 'created_by', 'id');
    }

    public function parent() {
      return $this->hasOne(\App\User::class, 'id', 'parent_id');
    }

    public function business() {
      return $this->hasOne(\Platform\Models\Business::class, 'created_by', 'id');
    }

    public function getCustomerNumberAttribute($value)
    {
        $user = User::where('customer_number', $value)->first(['whatsapp', 'role']);
        if ($user->role) {
            $number = $user->whatsapp;
            $length = Str::length($number);
            if ($length == 10) {
                $formatted_number = "$number[0]-$number[1]$number[2]$number[3]-$number[4]$number[5]$number[6]-$number[7]$number[8]$number[9]";
            } elseif ($length == 11) {
                $formatted_number = "$number[0]-$number[1]$number[2]$number[3]-$number[4]$number[5]$number[6]-$number[7]$number[8]$number[9]$number[10]";
            } else {
                $formatted_number = null;
            }
            return $formatted_number;
        } else {
            return $value;
        }
    }


    public function promos()
    {
        return $this->hasMany(Promo::class);
    }
}
