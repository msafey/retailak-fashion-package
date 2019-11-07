<?php

namespace App\Http\Controllers\wishlist;

use App\Models\Favorite;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class WishlistController extends Controller
{
    private $token;

    public function __construct()
    {
        $this->token = new SalesOrderController();
    }


    public function addWishlist(Request $request)
    {
        try{
        $token = $this->token->getTokenFromReq($request);

        $lang = app('request')->header('lang');

        if ($token != null) {
            $user = \App\User::where('token', '=', $token)->first();
//            dd($token .'='.$user);
            if (empty($user)) {

                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to add to favorites, Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاَ '], 401);

                }
            } else if ($request->input('id') != null && $request->input('name') != null) {

                $product_name = $request->input('name');
                $product_id = $request->input('id');


                $duplicate = DB::table('favorite')
                    ->join('users', 'favorite.user_id', '=', 'users.id')
                    ->where('user_id', $user->id)->where('favorite.product_id', $product_id)->first();

                if ($duplicate) {

                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Erorr', 'message' => 'Duplicate Entry in the Database.'], 412);
                    } else {
                        return Response::json(['Status' => 'Erorr', 'message' => 'المنتج مسجل بالفعل '], 412);
                    }
                } else {
                    $favorite = new  Favorite;

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
         }catch (\Exception $e) {
          $error = array($e->getMessage(),$e->getFile(),$e->getLine());
          event(new ErrorEmail($error ));
          return $error;
      }
    }


    public function deleteWishlist(Request $request)
    {
        try{
        $token = $this->token->getTokenFromReq($request);

        $lang = app('request')->header('lang');

        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            if ($token != null) {
                $user = \App\User::where('token', '=', $token)->first();
                if (empty($user)) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to From Delete Favourite, Please login.'], 401);
                    } else {
                        return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ.'], 401);

                    }
                } else {


                    $found = DB::table('favorite')
                        ->join('users', 'favorite.user_id', '=', 'users.id')
                        ->where('user_id', $user->id)->where('favorite.product_id', $product_id)->first();
                    if ($found) {
                        $done = DB::table('favorite')
                            ->join('users', 'favorite.user_id', '=', 'users.id')
                            ->where('user_id', $user->id)->where('favorite.product_id', $product_id)->delete();


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
         }catch (\Exception $e) {
          $error = array($e->getMessage(),$e->getFile(),$e->getLine());
          event(new ErrorEmail($error ));
          return $error;
      }

    }

    public function listWishlist(Request $request)
    {
        try{

        $token = $this->token->getTokenFromReq($request);

        $lang = app('request')->header('lang');
        if ($token != null) {

            $user = \App\User::where('token', '=', $token)->first();

            if (empty($user)) {

                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to list favorites, Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاً.'], 401);

                }
            } else {

                if ($lang == 'en') {
                    $response = DB::table('favorite')
                        ->join('users', 'favorite.user_id', '=', 'users.id')
                        ->join('products', 'favorite.product_id', '=', 'products.id')->where('user_id', $user->id)->select('products.id' ,'products.name_en as name', 'products.description_en', 'image', 'item_group', 'min_order_qty', 'item_code', 'max_discount', 'standard_rate', 'uom', 'weight')->get();
                } else {
                    $response = DB::table('favorite')
                        ->join('users', 'favorite.user_id', '=', 'users.id')
                        ->join('products', 'favorite.product_id', '=', 'products.id')
                        ->where('user_id', $user->id)->select('products.id' , 'products.name as name', 'products.description', 'image', 'item_group', 'min_order_qty', 'item_code', 'max_discount', 'standard_rate', 'uom', 'weight')->get();
                }
                return Response::json($response, 200);

            }


        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }

         }catch (\Exception $e) {
          $error = array($e->getMessage(),$e->getFile(),$e->getLine());
          event(new ErrorEmail($error ));
          return $error;
      }
    }
}
