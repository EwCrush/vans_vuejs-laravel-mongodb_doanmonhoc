<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use DB;
use Illuminate\Pagination\Paginator;

class ProductsController extends Controller
{
    public function show(){
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 5;
        $total = Product::count();

        $data = Product::raw((function($collection) use ($page, $perPage) {
            return $collection->aggregate([
              [
                '$lookup' => [
                  'from' => 'categories',
                  'localField' => 'category',
                  'foreignField'=> '_id',
                  'as' => 'category'
                ]
                ],
                [
                    '$unwind' => '$category'
                ],
                [
                    '$lookup' => [
                      'from' => 'categories',
                      'localField' => 'subcategory',
                      'foreignField'=> '_id',
                      'as' => 'subcategory'
                    ]
                ],
                [
                    '$unwind' => '$subcategory'
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        'productname' => 1,
                        'originalprice' => 1,
                        'sellingprice' => 1,
                        'thumbnail' => 1,
                        'images' => 1,
                        'sizes' => 1,
                        'category' => '$category.categoryname',
                        'subcategory' => '$subcategory.categoryname',
                    ]
                ],
                ['$skip' => ($page - 1) * $perPage],
                ['$limit' => $perPage],
            ]);
       }));

        return new \Illuminate\Pagination\LengthAwarePaginator($data, $total, $perPage, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);
    }

    public function showAll(){
        $data = Product::all();

        return response()->json($data);
    }

    public function searchByKeyword($keyword){
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 5;
        $total = Product::where('productname', 'regexp', '/'.$keyword.'/')->count();

        $data = Product::raw((function($collection) use ($page, $perPage, $keyword) {
            return $collection->aggregate([
              [
                '$lookup' => [
                  'from' => 'categories',
                  'localField' => 'category',
                  'foreignField'=> '_id',
                  'as' => 'category'
                ]
                ],
                [
                    '$unwind' => '$category'
                ],
                [
                    '$lookup' => [
                      'from' => 'categories',
                      'localField' => 'subcategory',
                      'foreignField'=> '_id',
                      'as' => 'subcategory'
                    ]
                ],
                [
                    '$unwind' => '$subcategory'
                ],

                ['$match' => ['productname' => ['$regex' => '^.*'.$keyword.'.*$']]],

                [
                    '$project' => [
                        '_id' => 1,
                        'productname' => 1,
                        'originalprice' => 1,
                        'sellingprice' => 1,
                        'thumbnail' => 1,
                        'images' => 1,
                        'sizes' => 1,
                        'category' => '$category.categoryname',
                        'subcategory' => '$subcategory.categoryname',
                    ]
                ],
                ['$skip' => ($page - 1) * $perPage],
                ['$limit' => $perPage],
            ]);
       }));

        return new \Illuminate\Pagination\LengthAwarePaginator($data, $total, $perPage, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);
    }

    public function searchByID($id){
        $data = Product::find($id);

        return response()->json($data);
    }

    public function showSizes($id){
        $data = Product::raw((function($collection) use($id) {
            return $collection->aggregate([
                [
                    '$match' => [
                            '_id' => [ '$eq' => (int)$id]
                       ],
                ],
                [
                    '$unwind' => '$sizes'
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'size' => '$sizes.size',
                        'quantity' => '$sizes.quantity',
                    ]
                ],
            ]);
       }));

        return response()->json($data);
    }

    public function showImages($id){
        $data = Product::raw((function($collection) use($id) {
            return $collection->aggregate([
                [
                    '$match' => [
                            '_id' => [ '$eq' => (int)$id]
                       ],
                ],
                [
                    '$unwind' => '$images'
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'filename' => '$images.filename',
                    ]
                ],
            ]);
       }));

        return response()->json($data);
    }
}
