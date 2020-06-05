<?php 

namespace Platform\Controllers\App;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CategoryController extends \App\Http\Controllers\Controller {

    /**
     * Return categories that are being used.
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsedCategories() {
      $location = auth()->user()->location ?? 'Grenada';
      $usedCategories = \DB::select(
          "SELECT
          c.name, c.id AS cid
          FROM business_categories c
          INNER JOIN businesses b 
          ON c.id=b.category_id
          INNER JOIN users u
          ON b.created_by=u.id
          WHERE u.active=1
          AND u.location = '$location'
          GROUP BY c.name"
      );
      return response()->json($usedCategories);
    }

    /**
     * Return All categories.
     * Will be used in Business Register Page & Business Profile (for updating.)
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllCategories() {
        $data = \DB::table('business_categories')
        ->orderBy('name')
        ->get(['name', 'id as cid']); //...
        return response()->json($data);
    }
    

}
