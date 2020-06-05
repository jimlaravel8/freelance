<?php

  /*
   |--------------------------------------------------------------------------
   | Defaults
   |--------------------------------------------------------------------------
   |
   | The values below are defaults for the app.
   | Can be overridden in the .env file.
   |
   */

return [
  /*
   |--------------------------------------------------------------------------
   | App
   |--------------------------------------------------------------------------
   */

  'app_name' => env('APP_NAME', 'My Business'),
  'app_logo' => env('APP_LOGO', ''),
  'app_url' => env('APP_URL', 'https://localhost'),
  'app_contact_email' => env('APP_CONTACT_EMAIL', 'hi@example.com'),

  /*
   |--------------------------------------------------------------------------
   | E-mail
   |--------------------------------------------------------------------------
   */

  'mail_from_name' => env('MAIL_FROM_NAME', 'My Business'),
  'mail_from_address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),

  /*
   |--------------------------------------------------------------------------
   | Localization
   |--------------------------------------------------------------------------
   */

  'language' => env('DEFAULT_LANGUAGE', 'en'),
  'locale' => env('DEFAULT_LOCALE', 'en_US'),
  'timezone' => env('DEFAULT_TIMEZONE', 'UTC'),
  'currency' => env('DEFAULT_CURRENCY', 'USD'),
  'currency_fraction_digits' => env('DEFAULT_CURRENCY_FRACTION_DIGITS', 2), // Currency decimals

  /*
   |--------------------------------------------------------------------------
   | Premium account settings
   |--------------------------------------------------------------------------
   */

  'premium_price_monthly' => (env('PREMIUM_PRICE_MONTHLY') == null || env('PREMIUM_PRICE_MONTHLY') == '') ? 39 : env('PREMIUM_PRICE_MONTHLY', 39),
  'premium_price_yearly' => (env('PREMIUM_PRICE_YEARLY') == null || env('PREMIUM_PRICE_YEARLY') == '') ? 399 : env('PREMIUM_PRICE_YEARLY', 399),
  'premium_price_currency' => (env('PREMIUM_PRICE_CURRENCY') == null || env('PREMIUM_PRICE_CURRENCY') == '') ? 'USD' : env('PREMIUM_PRICE_CURRENCY', 'USD'),
  'premium_grace_period_days' => (env('PREMIUM_GRACE_PERIOD_DAYS') == null || env('PREMIUM_GRACE_PERIOD_DAYS') == '') ? 2 : env('PREMIUM_GRACE_PERIOD_DAYS', 2),

  /*
   |--------------------------------------------------------------------------
   | Payment gateway
   |--------------------------------------------------------------------------
   */

  'payment_gateway' => env('PAYMENT_GATEWAY', 'manual'),

  /*
   |--------------------------------------------------------------------------
   | Payment gateway - Stripe
   |--------------------------------------------------------------------------
   */

  'stripe_public_key' => env('STRIPE_PUBLIC_KEY', ''), # Developers > API keys > Publishable key (https://dashboard.stripe.com/test/apikeys)
  'stripe_secret_key' => env('STRIPE_SECRET_KEY', ''), # Developers > API keys > Secret key (https://dashboard.stripe.com/test/apikeys)
  'stripe_plan_id_monthly' => env('STRIPE_PLAN_ID_MONTHLY', ''), # Starts with "plan_"
  'stripe_plan_id_yearly' => env('STRIPE_PLAN_ID_YEARLY', ''), # Starts with "plan_"

  /*
   |--------------------------------------------------------------------------
   | Customer codes
   |--------------------------------------------------------------------------
   */

  'discount_code_expiration_time_hours' => (env('DISCOUNT_CODE_EXPIRATION_TIME_HOURS') == null || env('DISCOUNT_CODE_EXPIRATION_TIME_HOURS') == '') ? 48 : env('DISCOUNT_CODE_EXPIRATION_TIME_HOURS', 48),

  /*
   |--------------------------------------------------------------------------
   | Rules
   |--------------------------------------------------------------------------
   */

  'points_expiration_months' => (env('DEFAULT_POINTS_EXPIRATION_MONTHS') == null || env('DEFAULT_POINTS_EXPIRATION_MONTHS') == '') ? 6 : env('DEFAULT_POINTS_EXPIRATION_MONTHS', 6),
  'points_per_currency' => (env('DEFAULT_POINTS_PER_CURRENCY') == null || env('DEFAULT_POINTS_PER_CURRENCY') == '') ? 200 : env('DEFAULT_POINTS_PER_CURRENCY', 200),
  'point_value' => (env('DEFAULT_POINT_VALUE') == null || env('DEFAULT_POINT_VALUE') == '') ? 0.0001 : env('DEFAULT_POINT_VALUE', 0.0001),
  'min_points_per_purchase' => (env('DEFAULT_MIN_POINTS_PER_PURCHASE') == null || env('DEFAULT_MIN_POINTS_PER_PURCHASE') == '') ? 100 : env('DEFAULT_MIN_POINTS_PER_PURCHASE', 100),
  'max_points_per_purchase' => (env('DEFAULT_MAX_POINTS_PER_PURCHASE') == null || env('DEFAULT_MAX_POINTS_PER_PURCHASE') == '') ? 1000000 : env('DEFAULT_MAX_POINTS_PER_PURCHASE', 1000000),
  'min_points_per_redemption' => (env('DEFAULT_MIN_POINTS_PER_REDEMPTION') == null || env('DEFAULT_MIN_POINTS_PER_REDEMPTION') == '') ? 15000 : env('DEFAULT_MIN_POINTS_PER_REDEMPTION', 15000),
  'max_points_per_redemption' => (env('DEFAULT_MAX_POINTS_PER_REDEMPTION') == null || env('DEFAULT_MAX_POINTS_PER_REDEMPTION') == '') ? 500000 : env('DEFAULT_MAX_POINTS_PER_REDEMPTION', 500000),

  /*
   |--------------------------------------------------------------------------
   | Social
   |--------------------------------------------------------------------------
   */

   'social' => [
    ['name' => 'Facebook', 'icon' => 'mdi-facebook', 'url' => env('SOCIAL_FACEBOOK', '')],
    ['name' => 'Instagram', 'icon' => 'mdi-instagram', 'url' => env('SOCIAL_INSTAGRAM', '')],
    ['name' => 'Twitter', 'icon' => 'mdi-twitter', 'url' => env('SOCIAL_TWITTER', '')],
    ['name' => 'Youtube', 'icon' => 'mdi-youtube', 'url' => env('SOCIAL_YOUTUBE', '')],
    ['name' => 'Medium', 'icon' => 'mdi-medium', 'url' => env('SOCIAL_MEDIUM', '')],
    ['name' => 'Snapchat', 'icon' => 'mdi-snapchat', 'url' => env('SOCIAL_SNAPCHAT', '')],
    ['name' => 'LinkedIn', 'icon' => 'mdi-linkedin', 'url' => env('SOCIAL_LINKEDIN', '')],
    ['name' => 'Github', 'icon' => 'mdi-github-circle', 'url' => env('SOCIAL_GITHUB', '')]
   ],
  
   /*
    |--------------------------------------------------------------------------
    | Limitations (currently not used)
    |--------------------------------------------------------------------------
    */

    'max_items' => env('MAX_ITEMS', 0),
    'max_items_premium' => env('MAX_ITEMS_PREMIUM', 50),
   
   /*
    |--------------------------------------------------------------------------
    | Third party analytics
    |--------------------------------------------------------------------------
    */
 
   'google_analytics' => env('CONFIG_GOOGLE_ANALYTICS', ''),
];
