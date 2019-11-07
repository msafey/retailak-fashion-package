<?php

namespace App\Http\Controllers\GomlaUsers;

use App\Models\Addresses;
use App\Models\District;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Models\Orders;
use App\Models\ShippingRule;
use App\Models\ShopType;
use App\Models\StoreDetails;
use App\Models\User;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Image;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Response;
use Session;
use Yajra\Datatables\Datatables;

class UsersController extends Controller
{


    public function findUser()
    {
        if (!Auth::guard('admin_user')->user()->can('users')) {
            return view('admin.un-authorized');
        }

        $user = User::where('name', 'like', '%' . \request('name') . '%')
            ->orWhere('phone', 'like', '%' . \request('name') . '%')
            ->take(100)->get();

        return $user;

    }

    public function reports()
    {
        if (!Auth::guard('admin_user')->user()->can('users')) {
            return view('admin.un-authorized');
        }

        $ordersCount = DB::table('orders')->join('address', 'orders.address_id', 'address.id')
            ->select('orders.id', DB::raw('count(*) as total'), 'district_id')
            ->groupBy('district_id')
            ->get();
        $ordersCountDistrict = DB::table('orders')
            ->join('address', 'orders.address_id', 'address.id')
            ->join('districts', 'address.district_id', 'districts.id')
            ->select('orders.id', DB::raw('count(*) as total'), 'district_ar')
            ->groupBy('address.district_id')
            ->get();
        return "Count Without Join Restricts" . "__" . $ordersCount . "<br>" . "<br>" . "<br>" . "Count With Join Restricts" . $ordersCountDistrict;
        // dd($user_info);
    }

    public function createaddressview()
    {
        if (!Auth::guard('admin_user')->user()->can('users')) {
            return view('admin.un-authorized');
        }

        $user_id = Input::get('id');
        $create = 0;
        $create = Input::get('create');
        $user = User::where('id', $user_id)->first();
        if ($user) {
            $token = $user->token;
        } else {
            $token = '';
        }
        $districts = District::all();
        return view('admin.addresses.add', compact('create', 'districts', 'token', 'user'));
    }

    public function editaddressview($id)
    {
        if (!Auth::guard('admin_user')->user()->can('users')) {
            return view('admin.un-authorized');
        }

        $address = Addresses::find($id);
        $districts = District::all();
        return view('admin.addresses.edit', compact('address', 'districts'));
    }

    public function updateaddress($id, Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('users')) {
            return view('admin.un-authorized');
        }

        $address = Addresses::find($id);
        $user = User::where('id', $address->user_id)->first();

        if ($address) {
            $address->street = $request->input('street');
            $address->district_id = $request->input('region');
            $address->lat = $request->input('lat');
            $address->lng = $request->input('lng');
            $address->save();
        }
        if ($user) {
            $user_id = $user->id;
            return redirect('admin/user/details/' . $user_id)->with('success', 'Address Updated');
        } else {
            return redirect('admin/users');
        }
    }

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        if (isset($_GET['search'])) {
            $value = $_GET['search'];
            $users = User::where('name', 'LIKE', '%' . $value . '%')->orWhere('phone', 'LIKE', '%' . $value . '%')->where('active', 1)->paginate(15);
        } else {
            $users = User::orderBy('created_at', 'desc')->paginate(15);
        }
        foreach ($users as $user) {
            if (isset($user->id) && !empty($user->id)) {
                $address = DB::table('address')->where('user_id', $user->id)->where('active', true)->first();
                if ($address) {
                    if (isset($address->address_phone) && !empty($address->address_phone)) {
                        $user->phone = $address->address_phone;
                    }
                }
            }
        }
        return view('admin.users.list', compact('users'));
    }

    public function export()
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        $users = User::select('id', 'name', 'phone')->get();
        return Excel::create('users', function ($excel) use ($users) {
            $excel->sheet('Sheet 1', function ($sheet) use ($users) {
                $sheet->fromArray($users);
            });
        })->export('xls');
    } //end

    public function importExcel(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        if ($request->hasFile('import_file')) {
            $results = Excel::load($request->file('import_file')->getRealPath());
            $duplicates = [];
            $total = 0;
            $imported = 0;
            Session()->forget('insufficient');
            $insufficient = [];
            $failed = 0;
            if (count($results->toArray()) > 0) {
                foreach ($results->toArray() as $key => $row) {
                    $total += 1;
                    $active = 1;
                    $phone_exist = User::where('phone', $row['phone'])->first();
                    if ($phone_exist) {
                        if (!array_key_exists($row['phone'], $duplicates)) {
                            $duplicates[$row['phone']] = 1;
                        } else {
                            $duplicates[$row['phone']] += 1;
                        }
                        $insufficient[$total] = ['error' => 'phone already exist', 'phone' => $row['phone']];
                        continue;
                    }
                    if (!array_key_exists('phone', $row)) {
                        $insufficient[$total] = ['error' => 'phone is missing', 'phone' => 0];
                        continue;
                    }
                    $data['name'] = $row['customer_name'];
                    $data['phone'] = $row['phone'];
                    if (isset($row['email'])) {
                        $data['email'] = $row['email'];
                    } else {
                        $data['email'] = '';
                    }

                    if (!preg_match("/^([\+]2)?((01[0125]\d{8}))$/", $data['phone'])) {
                        $insufficient[$total] = ['error' => 'phone is not valid', 'phone' => $row['phone']];
                        continue;
                    }
                    if (!empty($data)) {
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => bcrypt(123456),
                            'phone' => $data['phone'],
                            'active' => $active,
                            'token' => md5(str_random(60)),
                        ]);
                        if (array_key_exists('address', $row)) {
                            $data['address'] = $row['address'];
                            Addresses::create([
                                'street' => $data['address'],
                                'user_id' => $data['phone'],
                                // 'address_phone' => $data['phone'],
                                'lat' => 0,
                                'lng' => 0,
                                'district_id' => 1,
                            ]);
                        }
                        if (array_key_exists('market_name', $row)) {
                            $data['market_name'] = $row['market_name'];
                            StoreDetails::create([
                                'store_name' => $data['market_name'],
                                'user_id' => $user->id,
                            ]);
                        }
                        $imported += 1;
                    }
                }
                $failed = $total - $imported;
                Session::push('insufficient', $insufficient);
                return redirect()->action('GomlaUsers\UsersController@importExcelDetails', ['imported' => $imported, 'failed' => $failed, 'total' => $total]);
            } else {
                return redirect()->back()->withErrors('Empty Excel Sheet');
            }
        }
    }

    public function importExcelDetails()
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        $insufficient = Session::get('insufficient');
        $imported = $_GET['imported'];
        $failed = $_GET['failed'];
        $total = $_GET['total'];

        return view('admin.users.imported', compact('imported', 'total', 'failed', 'insufficient'));
        // dd();
    }

    public function userslist()
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        $users = User::orderBy('id', 'desc')->select('id', 'name', 'phone')->with('address')->where('active', 1)->get();
        $collection = collect($users);
        return Datatables::of($collection)->make(true);
    }

    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        $districts = District::where('active', 1)->get();
        $sales_order_request = Input::get('sales_order_request');
        return view('admin.users.add', compact('districts', 'sales_order_request'));
    }

    public function store(AddUserRequest $request)
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }
        return DB::transaction(function () use ($request) {
            $data = $request->all();
            $setting = DB::table('settings')->first();
            if ($setting) {
                if ($setting->user_activation == 0) {
                    $active = 1;
                } else {
                    $active = 0;
                }
            }
            $user = User::create([
                'name' => $data['name'],
                // 'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'phone' => $data['phone'],
                'active' => $active,
                'token' => md5(str_random(60)),
            ]);
            $logged_user = Auth::guard('admin_user')->user();
            $user->adjustments()->attach($logged_user->id, ['key' => "Users", 'action' => "Added", 'content_name' => $user->name]);
            if ($request->has('sales_order_request')) {
                return redirect('admin/sales-orders/create?sales_order_request=' . $user->id);
            } else {
                return redirect('admin/users')->with('success', 'User Created Successfully');
            }
        });
    }

    public function getuseremail()
    {
        $userId = $_GET['userid'];
        $user = User::find($userId);
        return $user->id;
    }

    public function userDetails($id)
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }
        if (!User::find($id)) {
            return redirect()->back();
        }
        $setting = DB::table('settings')->first();
        $user_activation = 0;
        if ($setting) {
            // dd($setting);
            if ($setting->user_activation == 1) {
                $user_activation = 0;
            }
        }
        $user = User::findOrFail($id);
        $products = array();
        $price = array();
        $x = 0;
        $ordersSum = 0;
        $address_phone = '';
        if (isset($user->phone)) {
            $user_phone = $user->phone;
        }
        $dbDistricts = DB::table('districts')->where('active', true)->select('id', 'district_ar')->get();
        $districts = [];
        foreach ($dbDistricts as $district) {
            $districts[$district->id] = $district->district_ar;
        }
        $store_details = StoreDetails::where('user_id', $user->id)->first();
        if ($store_details) {
            $store_type = ShopType::where('id', $store_details->shop_type_id)->first();
            if ($store_type) {
                $store_type_name = $store_type->type;
            } else {
                $store_type_name = '';
            }
        }
        foreach ($user->orders as $order) {
            $address_phone = '';
            $shipping_rate = 0;
            $address_id = $order->address_id;
            if ($address_id > 0) {
                $address = DB::table('address')->where('active', true)->where('id', $address_id)->first();
                $district = null;
                // $address_phone = $address->address_phone;
                {
                    $address_phone = 0;
                    $district = District::where('id', $address->district_id)->first();
                }

                if ($district) {
                    $shipping_rule = ShippingRule::where('id', $district->shipping_role)->first();
                    if ($shipping_rule) {
                        $shipping_rate = $shipping_rule->rate;
                    }
                }
            }
            $final_total_price = $shipping_rate;
            $order_items = $order->OrderItems;
            if (count($order_items) > 0) {
                foreach ($order_items as $product) {
                    $productsArray = DB::table('products')->where('id', $product->item_id)->first();
                    $item_price = $product->rate;
                    $productsArray->standard_rate = $item_price;
                    $productsArray->qty = $product->qty;
                    $product_order['total_price'] = $product->qty * $item_price;
                    $ordersSum += $product_order['total_price'];
                    $products[$x][] = array_merge((array)$product_order, (array)$productsArray, (array)$product);
                    $final_total_price += $product_order['total_price'];
                }
                $price[] = $final_total_price;
            }
            $x++;
        }
        return view('admin.users.user-details', compact('user_phone', 'store_type_name', 'store_details', 'user', 'user_activation', 'address_phone', 'products', 'price', 'ordersSum', 'districts'));
    }

    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        $user = User::where('id', $id)->first();
        if ($user) {
            $store_details = StoreDetails::where('user_id', $user->id)->first();
        }
        $shop_types = ShopType::all();
        return view('admin.users.edit', compact('user', 'store_details', 'shop_types'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request, $id) {
            $user = User::find($id);
            if ($user) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();
            }
            if ($request->has('store_details')) {
                $store_details = StoreDetails::where('id', $request->store_details)->first();
                if ($store_details) {
                    $store_details->store_name = $request->store_name;
                    $store_details->shop_type_id = $request->shop_type_id;
                    $TaxCard = $store_details->tax_card;
                    if ($TaxCard) {
                        File::delete(public_path() . '/imgs/store_details/' . $TaxCard);
                    }
                    $CommercialRegister = $store_details->commercial_register;
                    if ($CommercialRegister) {
                        File::delete(public_path() . '/imgs/store_details/' . $CommercialRegister);
                    }
                    if (Input::hasFile('commercial_register')) {
                        $image = Input::file('commercial_register');
                        $filename = time() . '.' . $image->getClientOriginalExtension();
                        $path = public_path('imgs/store_details/' . $filename);
                        $img = Image::make($image->getRealPath());
                        $img->save($path);
                        $img->resize(300, 300);
                        $destinationPath = public_path('imgs/store_details/');
                        if (!file_exists($destinationPath . 'thumb/')) {
                            mkdir($destinationPath . 'thumb/', 0777, true);
                        }
                        $img->save($destinationPath . 'thumb/' . $filename);
                        $store_details->commercial_register = $filename;
                    }
                    if (Input::hasFile('tax_card')) {
                        $image = Input::file('tax_card');
                        $filename = time() . '1.' . $image->getClientOriginalExtension();
                        $path = public_path('imgs/store_details/' . $filename);
                        $img = Image::make($image->getRealPath());
                        $img->save($path);
                        $img->resize(300, 300);
                        $destinationPath = public_path('imgs/store_details/');
                        if (!file_exists($destinationPath . 'thumb/')) {
                            mkdir($destinationPath . 'thumb/', 0777, true);
                        }
                        $img->save($destinationPath . 'thumb/' . $filename);
                        $store_details->tax_card = $filename;
                    }
                    $store_details->save();
                }
            }
            return redirect('admin/user/details/' . $user->id)->with('success', 'User Created Successfully');
        });
    }

    public function status($id)
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        $user = User::where('id', $id)->first();
        if ($user) {
            $user->active = 1;
            $user->save();
            return Response::json('true');
        } else {
            return Response::json('false');
        }
    }

    public function createUser(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('users'))
        {
            return view('admin.un-authorized');
        }

        if (empty($request->name) || empty($request->phone)) {
            return 'false';
        } else {
            if (!preg_match("/^([\+]2)?((01[0125]\d{8}))$/", $request->phone)) {
                return 'invalid_phone_format';
            }
            $user_exist = User::where('phone', $request->phone)->where('email', $request->email)->first();
            if ($user_exist) {
                return 'user_exists';
            }
            $user = User::create(['name' => $request->name, 'phone' => $request->phone, 'email' => $request->email]);
            $token = md5(uniqid($user->id . time(), true));
            $user->token = $token;
            $user->save();
            if ($request->has('addressEnabled')) {
                Addresses::create(['street' => $request->street, 'district_id' => $request->district, 'user_id' => $user->id, 'lat' => $request->lat, 'lng' => $request->lng]);
            }
            return $user;
        }
    }
}
