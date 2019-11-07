<?php

namespace App\Http\Controllers\GomlaUsers;

use App\Models\Addresses;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Auth;
use Response;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class UserssController extends Controller
{


    protected $userscontroller;
     function __construct()
    {
        $this->userscontroller = new UsersController();
    }

      public function createuser(){
        return view('admin.users.add');
      }
    public function storeuser(Request $request)
    {


        $request['header']='en';

         $result = $this->userscontroller->doapiregister($request);

         // return $result;
         if($result->status() == 412){
          return redirect()->back()->withErrors('Please enter a valid phone number');
         }

         if($result->status() == 401){
          return redirect()->back()->withErrors('This Email Exists already');
         }

         // dd(5);


      return redirect('admin\users')->with('success','User Created Successfully');
    }
    public function editUserData($id){
      $user = User::findOrFail($id);
      $userId = $user->email;
      $addressId = $user->address[0]['id'];
      $address = Addresses::where('id', $addressId)->where('user_id', $userId)->first();

      return view('admin.users.edit',compact('user','address'));
    }

     public function updateUserData(Request $request,$id){
        // dd($request->all());
          $user = User::findOrFail($id);

          $request['header']='en';

         $result = $this->userscontroller->updateCmsUser($request,$user);

          if($result->status() == 412){
           return redirect()->back()->withErrors('Please enter a valid phone number');
          }
          return redirect('admin\users')->with('success','User Updated Successfully');
      }

    public function reports()
    {

      $ordersCount =  DB::table('orders')->join('address','orders.address_id','address.id')
       ->select('orders.id', DB::raw('count(*) as total'),'regoin')
       ->groupBy('regoin')
     ->get();


     $ordersCountDistrict =  DB::table('orders')
      ->join('address','orders.address_id','address.id')
      ->join('districts','address.regoin','districts.id')
       ->select('orders.id', DB::raw('count(*) as total'),'district_ar')
       ->groupBy('address.regoin')
     ->get();

     return "Count Without Join Restricts"."__" .$ordersCount ."<br>"."<br>"."<br>". "Count With Join Restricts".$ordersCountDistrict ;
      // dd($user_info);
    }


    public function index()
    {
      // dd(1);
//       if(isset($_GET['search']))
//             {
//               $value = $_GET['search'];
//               $users = User::where('name','LIKE','%'.$value.'%')->orWhere('email','LIKE','%'.$value.'%')->paginate(15);
//             }
//             else
//             {
//               $users = User::orderBy('created_at', 'desc')->paginate(15);
//             }
// dd($users);
//         return view('admin.users.list', compact('users'));
    }

    //  public function userslist()
    // {
    //   if(isset($_GET['search']))
    //         {
    //           $value = $_GET['search'];
    //           $users = User::where('name','LIKE','%'.$value.'%')->orWhere('email','LIKE','%'.$value.'%')->paginate(15);
    //         }
    //         else
    //         {
    //           $users = User::orderBy('created_at', 'desc')->paginate(15);
    //         }
    //     // $counter = 0;
    //     // $usersArray = [];
    //     // $users = User::orderBy('id', 'desc')->select('id','name','email')->get();
    //     // $collection = collect($users);
    //     return Datatables::of($users)->make(true);

    // }
    public function getuseremail(){
      $userId = $_GET['userid'];
      $user =User::find($userId);
      return $user->email;
    }

    public function userDetails($id)
    {
        $user = User::findOrFail($id);
//        dd(json_decode($user->orders[4]->productlist));
        $products = array();
        $price = array();

        $x = 0;
        $ordersSum = 0;
        foreach ($user->orders as $order) {
            $final_total_price = $order->shipping_rate;

            foreach (json_decode($order->productlist) as $product) {

                $productsArray = DB::table('products')->where('item_code', $product->item_code)->first();
                if(!$productsArray) $productsArray = DB::table('oldproducts')->where('item_code', $product->item_code)->first();

                $product_order['total_price'] = $product->qty * $productsArray->standard_rate;
                $ordersSum+= $product_order['total_price'];
                $products [$x][] = array_merge((array)$product_order, (array)$productsArray , (array)$product);
                $final_total_price  += $product_order['total_price'];

            }
            $x++;
            $price[]  = $final_total_price;
        }

//        return ($products);

        return view('admin.users.user-details', compact('user','products','price','ordersSum'));
    }

    public function usersCount(){

        $users_with_email = User::where('email','!=',"")->orWhereNull('email')->get()->count();
        $users_with_Phone = User::where('phone','!=',"")->orWhereNull('phone')->get()->count();
        $orders = Orders::count();

        dd($orders);
    }


}
