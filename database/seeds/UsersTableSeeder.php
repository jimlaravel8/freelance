<?php

use Illuminate\Database\Seeder;
use Platform\Controllers\Core;

use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Platform\Models\Business;
use Platform\Models\History;
use Platform\Models\Code;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      Eloquent::unguard();

      $user = new \App\User;

      $user->role = 1;
      $user->first_name = 'Admin';
      $user->last_name = 'User';
      $user->email = 'admin@example.com';
      $user->password = bcrypt('welcome123');
      $user->language = config('default.language');
      $user->locale = config('default.locale');
      $user->timezone = config('default.timezone');
      $user->currency = config('default.currency');

      $user->save();

      /**
       * -----------------------------------------------------------------
       * Demo seeds
       * -----------------------------------------------------------------
       */

      $amounts = [2.99, 4.95, 7.49, 8.99, 10, 14.99, 19.95, 20, 50, 100, 200, 300];
      $business_count = 5;
      $business_users = [];
      $customer_count = 5;

      if (env('APP_DEMO', false)) {
        $faker = Faker::create();

        // Business user
        $user = new \App\User;
        $user->role = 3;
        $user->first_name = 'Nikolas';
        $user->last_name = 'Beier';
        $user->email = 'business@example.com';
        $user->password = bcrypt('welcome123');
        $user->language = config('default.language');
        $user->locale = config('default.locale');
        $user->timezone = config('default.timezone');
        $user->currency = config('default.currency');
        $user->created_by = 1;
        
        $user->save();

        $business_users[] = $user->id;

        $avatar = base_path() . '/database/seeds/media/avatar2.jpg';

        $user
        ->addMedia($avatar)
        ->preservingOriginal()
        ->sanitizingFileName(function($fileName) {
          return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
        })
        ->toMediaCollection('avatar', 'media');

        // Add business
        $business = new \Platform\Models\Business;
        $business->currency = config('default.currency');
        $business->currency_fraction_digits = config('default.currency_fraction_digits');
        $business->name = $faker->company;
        $business->short_description = $faker->catchPhrase;
        $business->created_by = $user->id;

        $business->save();

        $cover = base_path() . '/database/seeds/media/business1.jpg';

        $business
        ->addMedia($cover)
        ->preservingOriginal()
        ->sanitizingFileName(function($fileName) {
          return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
        })
        ->toMediaCollection('cover', 'media');

        $men = 1;
        $women = 1;

        // Business users
        foreach (range(1, $business_count) as $index) {
          $gender = (mt_rand(0, 1) == 1) ? 'male' : 'female';
          if ($gender == 'male') {
            $firstName = $faker->firstNameMale;
            $avatar = base_path() . '/database/seeds/avatars/men/' . $men . '.jpg';
            $men++;
          } else {
            $firstName = $faker->firstNameFemale;
            $avatar = base_path() . '/database/seeds/avatars/women/' . $women . '.jpg';
            $women++;
          }
          $lastName = $faker->lastName;
          $email = Str::slug(substr($firstName, 0, 1)) . '.' . Str::slug($lastName, '_') . '@' . $faker->domainName;

          $created_at = $faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now');
          $updated_at = $faker->dateTimeBetween($startDate = $created_at, $endDate = 'now');

          $user = new \App\User;
          $user->role = 3;
          $user->first_name = $firstName;
          $user->last_name = $lastName;
          $user->email = $email;
          $user->password = bcrypt('welcome123');
          $user->language = config('default.language');
          $user->locale = config('default.locale');
          $user->timezone = config('default.timezone');
          $user->currency = config('default.currency');
          $user->logins = mt_rand(1, 12);
          $user->last_login = $updated_at;
          $user->last_ip_address = $faker->ipv4;
          $user->created_by = 1;
          $user->created_at = $created_at;
          $user->updated_by = 1;
          $user->updated_at = $updated_at;

          $user->save();

          $business_users[] = $user->id;

          $user
          ->addMedia($avatar)
          ->preservingOriginal()
          ->sanitizingFileName(function($fileName) {
            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
          })
          ->toMediaCollection('avatar', 'media');

          // Add business
          $business = new \Platform\Models\Business;
          $business->currency = config('default.currency');
          $business->currency_fraction_digits = config('default.currency_fraction_digits');
          $business->name = $faker->company;
          $business->short_description = $faker->catchPhrase;
          $business->created_by = $user->id;

          $business->save();

          $cover = base_path() . '/database/seeds/media/business' . ($index + 1) . '.jpg';

          $business
          ->addMedia($cover)
          ->preservingOriginal()
          ->sanitizingFileName(function($fileName) {
            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
          })
          ->toMediaCollection('cover', 'media');
        }

        // Customers
        $user = new \App\User;
        $user->role = 2;
        $user->first_name = 'Hannah';
        $user->last_name = 'McLaughlin';
        $user->email = 'customer@example.com';
        $user->password = bcrypt('welcome123');
        $user->language = config('default.language');
        $user->locale = config('default.locale');
        $user->timezone = config('default.timezone');
        $user->currency = config('default.currency');
        $user->created_by = 1;

        $user->save();

        $avatar = base_path() . '/database/seeds/media/avatar1.jpg';

        $user
        ->addMedia($avatar)
        ->preservingOriginal()
        ->sanitizingFileName(function($fileName) {
          return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
        })
        ->toMediaCollection('avatar', 'media');

        // Add points
        for ($i = 0; $i < mt_rand(4, 8); $i++) {
          $business_user_id = Arr::random($business_users, 1)[0];
          for ($j = 0; $j < mt_rand(3, 12); $j++) {
            $this->addPoints($business_user_id, $user->id, Arr::random($amounts, 1)[0]);
          }
        }

        foreach (range(1, $customer_count) as $index) {
          $gender = (mt_rand(0, 1) == 1) ? 'male' : 'female';
          if ($gender == 'male') {
            $firstName = $faker->firstNameMale;
            $avatar = base_path() . '/database/seeds/avatars/men/' . $men . '.jpg';
            $men++;
          } else {
            $firstName = $faker->firstNameFemale;
            $avatar = base_path() . '/database/seeds/avatars/women/' . $women . '.jpg';
            $women++;
          }
          $lastName = $faker->lastName;
          $email = Str::slug(substr($firstName, 0, 1)) . '.' . Str::slug($lastName, '_') . '@' . $faker->domainName;

          $created_at = $faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now');
          $updated_at = $faker->dateTimeBetween($startDate = $created_at, $endDate = 'now');

          $user = new \App\User;
          $user->role = 2;
          $user->first_name = $firstName;
          $user->last_name = $lastName;
          $user->email = $email;
          $user->password = bcrypt('welcome123');
          $user->language = config('default.language');
          $user->locale = config('default.locale');
          $user->timezone = config('default.timezone');
          $user->currency = config('default.currency');
          $user->logins = mt_rand(1, 12);
          $user->last_login = $updated_at;
          $user->last_ip_address = $faker->ipv4;
          $user->created_by = 1;
          $user->created_at = $created_at;
          $user->updated_by = 1;
          $user->updated_at = $updated_at;

          $user->save();

          $user
          ->addMedia($avatar)
          ->preservingOriginal()
          ->sanitizingFileName(function($fileName) {
            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
          })
          ->toMediaCollection('avatar', 'media');

          // Add points
          for ($i = 0; $i < mt_rand(4, 8); $i++) {
            $business_user_id = Arr::random($business_users, 1)[0];
            for ($j = 0; $j < mt_rand(3, 12); $j++) {
              $this->addPoints($business_user_id, $user->id, Arr::random($amounts, 1)[0]);
            }
          }
        }
      }

      Eloquent::reguard();
    }

    public function addPoints($business_user_id, $customer_id, $amount) {
      $faker = Faker::create();

      $customer = User::where('id', $customer_id)->first();
      $businessUser = User::where('id', $business_user_id)->first();
      $business = $businessUser->getBusiness(true);

      $points = $amount * $business['points_per_currency'];
      if (intval($points) < intval($business['min_points_per_purchase'])) $points = $business['min_points_per_purchase'];
      if (intval($points) > intval($business['max_points_per_purchase'])) $points = $business['max_points_per_purchase'];

      $created_at = $faker->dateTimeBetween($startDate = '-10 months', $endDate = 'now');

      $history = new History;

      $history->user_id = $business['user_id'];
      $history->business_id = $business['business_id'];
      $history->customer_id = $customer->id;
      $history->customer_number = $customer->customer_number;
      $history->staff_id = $businessUser->id;
      $history->staff_first_name = $businessUser->first_name;
      $history->staff_last_name = $businessUser->last_name;
      $history->staff_email = $businessUser->email;
      $history->currency = $business['currency'];
      $history->currency_fraction_digits = $business['currency_fraction_digits'];
      $history->points = $points;
      $history->points_used = 0;
      $history->points_per_currency = $business['points_per_currency'];
      $history->point_value = $business['point_value'];
      $history->purchase_amount = intval($amount * intval(str_pad('1', $business['currency_fraction_digits'] + 1, '0')));
      $history->event = 'purchase';
      $history->expires_at = Carbon::parse($created_at)->addMonths($business['points_expiration_months']);
      $history->created_at = $created_at;
      $history->created_by = $customer->id;

      $history->save();
    }
}
