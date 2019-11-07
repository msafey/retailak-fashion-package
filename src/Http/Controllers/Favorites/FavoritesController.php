<?php

namespace App\Http\Controllers\Favorites;

use App\Models\Favorite;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ProductsTransformer;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class FavoritesController extends Controller
{

    public function addFavorite(Request $request)
    {
        try {
            $token = getTokenFromReq($request);
            $lang = getLang();
            if ($token != null) {
                $user = User::where('token', $token)->first();
                if (empty($user)) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to add to favorites, Please login.'], 401);
                    } else {
                        return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاَ '], 401);

                    }
                } else if ($request->input('product_id') != null && $request->input('name') != null) {
                    $product_name = $request->input('name');
                    $product_id = $request->input('product_id');

                    $duplicate = DB::table('favorite')
                        ->join('users', 'favorite.user_id', '=', 'users.id')
                        ->join('products', 'favorite.product_id', '=', 'products.id')
                        ->where('user_id', $user->id)->where('product_id', $product_id)->first();
                    if ($duplicate) {
                        if ($lang == 'en') {
                            return Response::json(['Status' => 'Erorr', 'message' => 'Duplicate Entry in the Database.'], 412);
                        } else {
                            return Response::json(['Status' => 'Erorr', 'message' => 'المنتج مسجل بالفعل '], 412);
                        }
                    } else {
                        $favorite = new Favorite;

                        $favorite->product_name = $product_name;
                        $favorite->product_id = $product_id;
                        $favorite->user_id = $user->id;
                        $done = $favorite->save();
                    }
                    if ($done) {
                        if ($lang == 'en') {
                            return Response::json(['Status' => 'Success', 'message' => 'Success.'], 200);
                        } else {
                            return Response::json(['Status' => 'Success', 'message' => 'تم الاضافه بنجاح.'], 200);
                        }
                    } else {
                        return Response::json(['Status' => 'Erorr', 'message' => 'Something went wrong. Please try again later.'], 500);
                    }

                } else {
                    return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
                }

            } else {
                return Response::json(['Status' => 'Erorr', 'message' => 'Missing Headers'], 400);
            }
        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine());
            //event(new ErrorEmail($error ));
            return $error;
        }
    }

    public function deleteFavorite(Request $request)
    {
        try {
            $token = getTokenFromReq($request);
            $lang = getLang();
            if (isset($_GET['product_id'])) {
                $product_id = $_GET['product_id'];
                if ($token != null) {
                    // $user = Auth::guard()->user();
                    $user = \App\User::where('token', '=', $token)->first();
                    if (empty($user)) {
                        if ($lang == 'en') {
                            return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get to Delivered Order, Please login.'], 401);
                        } else {
                            return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ.'], 401);

                        }
                    } else {

                        $found = DB::table('favorite')
                            ->join('users', 'favorite.user_id', '=', 'users.id')
                            ->join('products', 'favorite.product_id', '=', 'products.id')
                            ->where('user_id', $user->id)->where('product_id', $product_id)->first();
                        if ($found) {
                            $done = DB::table('favorite')
                                ->join('users', 'favorite.user_id', '=', 'users.id')
                                ->join('products', 'favorite.product_id', '=', 'products.id')
                                ->where('user_id', $user->id)->where('product_id', $product_id)->delete();

                            if ($done) {
                                return Response::json(['Status' => 'Success', 'message' => 'Success.'], 200);
                            } else {
                                return Response::json(['Status' => 'Erorr', 'message' => 'Something went wrong. Please try again later.'], 500);

                            }

                        } else {
                            if ($lang == 'en') {
                                return Response::json(['Status' => 'Erorr', 'message' => ' Entry Not Found In the Database.'], 412);

                            } else {
                                return Response::json(['Status' => 'Erorr', 'message' => ' المنتج غير موجود بقائمه البيانات'], 412);

                            }
                        }
                    }
                } else {
                    return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
                }
            } else {
                return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);

            }
        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine());
            //event(new ErrorEmail($error ));
            return $error;
        }

    }

    public function listFavourite(Request $request)
    {


            $token = getTokenFromReq($request);
            $lang = getLang();
            if ($token != null) {
                $user = User::where('token', '=', $token)->first();
                if (empty($user)) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to list favorites, Please login.'], 401);
                    } else {
                        return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاً.'], 401);
                    }
                }

                $product_ids = $user->favourites->pluck('product_id') ;
                $products = Products::whereIn('id',$product_ids)
                    ->select('products.id', 'parent_variant_id',
                        getLang() == 'en' ? 'name_en as name' : 'name',
                        getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                        getLang() == 'en' ? 'description_en as description' : 'description',
                        'season_id')
                    ->has('variantsProducts')
                    ->with('variantsProducts', 'favourite','images')
                    ->get();

                $product_transformer = new ProductsTransformer;
                return $product_transformer->transformCollection($products->toArray());
        }


    }

}


