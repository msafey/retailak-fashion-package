<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Models\District;
use App\Http\Controllers\utilitiesController;
use App\Models\Payment_Method;
use App\Models\User;
use App\Models\Warehouses;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect;
use input;
use Request;
use Response;
use Session;
use Socialite;

//use Illuminate\Http\Request;

class UsersController extends utilitiesController
{
    use AuthenticatesUsers;

    // CMS LOGIN PAGE
    public function login()
    {
        return view('login');
    }

    // CMS LOGIN PROCESSING
    public function dologin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $result2 = $this->customerLoginErpnext($password, $username, 4);
        $result2 = json_decode($result2, true);
        if ($result2 == null) {
            return Redirect::back()->withErrors(['Couldn\'nt Login , Wrong Username Or Password', 'MSG']);
        } else {
            //dd($result2);
            session()->put('message', $result2['message']);
            session()->put('full_name', $result2['full_name']);
            return redirect('/');
        }
    }

    public function checkphone(\Illuminate\Http\Request $request)
    {
        $lang = getLang();
        if (!$request->has('phone') || !$request->has('phone')) {
            return Response::json(['Status' => 'Error', 'message' => 'Phone Required'], 400);
        }
        $userdata = [];
        $phone = $request->input('phone');
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return Response::json(['Status' => 'Error', 'message' => 'No User With This Phone Number'], 400);
        } else {
            $userdata['id'] = $user->id;
            $userdata['name'] = $user->name;
            $userdata['phone'] = $user->phone;
            $userdata['email'] = $user->email;
            return Response::json($userdata, 200);
        }
    }

    // CMS REGISTRATION PAGE
    public function register()
    {
        return view('register');
    }

    public function username()
    {
        $user_configurations = config('configurations.user');
        if (isset($user_configurations['login']) && $user_configurations['login'] == 'email') {
            return 'email';
        } else {
            return 'phone';
        }
    }

    protected function credentials(\Illuminate\Http\Request $request)
    {
        $input = $request->input($this->username());
        $field = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $request->merge([$field => $input]);
        return $request->only($field, 'password');
    }

    // CMS LOGIN PROCESSING
    public function getProfile()
    {
        $lang = app('request')->header('lang');
        $token = app('request')->header('Authorization');
        if ($token == null) {
            $token = app('request')->header('token');
        }
        if ($token != null) {
            $user = \App\User::where('token', '=', $token)->first();

            if (empty($user)) {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get User Profile, Please login.'], 401);
            } else {
                $userdata = $this->getUserData($user);
                $userdata = $this->getUserAddresses($user, $userdata);
                return Response::json($userdata, 200);

            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
    }

    public function getUserData($user)
    {

        $userdata = array();
        $userdata['id'] = $user->id;
        $userdata['phone'] = $user->phone;
        $userdata['name'] = $user->name;
        $userdata['auth_token'] = $user->token;
        $userdata['active'] = $user->active == 1 ? true : false;

        return $userdata;
    }

    // //  API Login Process

    public function apilogin(\Illuminate\Http\Request $request)
    {

        $lang = app('request')->header('lang');
        $device_os = $request->header('deviceOs');
        $app_version = $request->header('applicationVersion');
        $phone = $request->input('phone]');
        $user_configurations = config('configurations.user');
        if (isset($user_configurations['login']) && $user_configurations['login'] == 'email') {
            $username = trim($request->input('email'));
            if (empty($request->input('email')) || strlen($username) == 0) {
                return Response::json(['Status' => 'Error', 'message' => 'Bad Request'], 400);
            }
        } else {
            if (empty($request->input('phone')) || strlen($phone) == 0) {
                return Response::json(['Status' => 'Error', 'message' => 'Bad Request'], 400);
            }
            $phone = trim($request->input('phone'));
            $phone = $this->phoneTranslation($phone);
            $username = $phone;
        }
        $login_type = 'email';
        if (!$this->guard()->attempt($this->credentials($request))) {
            if ($lang == 'en') {
                if ($login_type == 'email') {
                    return Response::json(['message' => 'Email or password incorrect'], 401);
                } else {
                    return Response::json(['message' => 'Phone or password incorrect'], 401);
                }
            } else {
                return Response::json(['message' => ' أسم المستخدم أو كلمه المرور غير صحيحة '], 401);
            }
        } else {
            if ($login_type == 'email') {
                $user = \App\User::where('email', '=', $username)->first();
            } else {
                $user = \App\User::where('phone', '=', $username)->first();
            }
            $user->device_os = $device_os;
            $user->app_version = $app_version;
            $user->save();
            $userdata = array();
            $userdata['id'] = $user->id;
            $userdata['phone'] = $user->phone;
            $userdata['name'] = $user->name;
            $userdata['email'] = $user->email;
            $userdata['auth_token'] = $user->token;
            $userdata['active'] = $user->active == 1 ? true : false;
            $userdata = $this->getUserAddresses($user, $userdata);
            // $userdata = $this->getUserData($user);
            return Response::json($userdata, 200);
        }
    }

    // API REGISTRATION PROCESSING
    public function doapiregister(\Illuminate\Http\Request $request)
    {
        $lang = getLang();

        $app_version = $request->header('applicationVersion');
        if (!$request->has('phone') || !$request->has('phone')) {
            return Response::json(['Status' => 'Error', 'message' => 'Phone Required'], 400);
        }
        if (!$request->has('email') || !$request->has('email')) {
            return Response::json(['Status' => 'Error', 'message' => 'Email Required'], 400);
        }
        if (!$request->has('password') || !$request->has('password')) {
            return Response::json(['Status' => 'Error', 'message' => 'Password Required'], 400);
        }
        if (!$request->has('district_id') || !$request->has('district_id')) {
            return Response::json(['Status' => 'Error', 'message' => 'District Id Required'], 400);
        }
        if (!$request->has('street') || !$request->has('street')) {
            return Response::json(['Status' => 'Error', 'message' => 'Street  Required'], 400);
        }
        if (!$request->has('name') || !$request->has('name')) {
            return Response::json(['Status' => 'Error', 'message' => 'Name Required'], 400);
        }

        if (!$request->has('nearest_landmark') || !$request->has('nearest_landmark')) {
            return Response::json(['Status' => 'Error', 'message' => 'Nearest Land Mark Required'], 400);
        }

        $phone = $request->input('phone');
        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("-", "", $phone);
        $phone = $this->phoneTranslation($phone);
        if (!preg_match("/^([\+]2)?((01[0125]\d{8})|((02)?[23]\d{7}))$/", $phone)) {
            // $phone is valid

            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Please Enter Valid Phone Number'], 400);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'من فضلك ادخل رقم تليفون صحيح'], 400);
            }
        }
        $email = $request->input('email');
        $user_configurations = config('configurations.user');
        if (isset($user_configurations['login']) && $user_configurations['login'] == 'email') {
            $request_username = 'email';
            $userTest = \App\User::where('email', $email)->get();
        } else {
            $request_username = 'phone';
            $userTest = \App\User::where('phone', $phone)->get();
        }
        if (count($userTest)) {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'This ' . $request_username . ' Exists already'], 400);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'اسم المستخدم مسجل بالفعل'], 400);
            }
        }

        if (!$request->has($request_username) || !$request->has($request_username)) {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Please Enter ' . $request_username], 400);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'من فضلك ادخل اسم مستخدم صحيح'], 400);
            }
        }

        $requestData = $request->all();
        $requestData['name'] = $request->name;
        $requestData['phone'] = $request->phone;
        $requestData['password'] = bcrypt($request->password);
        $requestData['token'] = md5(str_random(60));
        $requestData['active'] = 1;
        $requestData['device_id'] = $request->header('deviceId');
        $requestData['device_os'] = $request->header('deviceOs');
        $requestData['app_version'] = $request->header('applicationVersion');

        // check if anonymous user or not

        //  check token exist

        // if token exist update guest user

        // if token not exist  create new user


        $token = $this->getTokenFromReq($request);

        $userExist = User::where('token', $token)->first();

        $user = $userExist ? $this->updateGuestUser($userExist, $requestData) : User::create($requestData);

        $userdata = $this->userObject($user);

        $address = Addresses::create(['title' => $request->input('address_title'), 'user_id' => $user->id, 'city' => $request->input('city'), 'street' => $request->input('street'), 'district_id' => $request->input('district_id'), 'nearest_landmark' => $request->input('nearest_landmark'), 'lat' => $request->input('lat'), 'lng' => $request->input('lng'), 'floor_no' => $request->input('floor_number'), 'building_no' => $request->input('building_number')]);

        $userdata = $this->getUserAddresses($user, $userdata);

        return Response::json($userdata, 200);
    }

    public function updateGuestUser($userExist, $data)
    {
        $data['type'] = "CUSTOMER";
        $userExist->update($data);

        return $userExist;
    }

    public function guestRegister()
    {
        $token = $this->getTokenFromReq(request());


        $user = User::where('token', $token)->first();

        if (!$user || !$token) {
            return $this->createGuestUser($token);
        }
        return $this->userObject($user);
    }

    public function createGuestUser($token)
    {
        $device_id = request()->header('deviceId');
        $device_os = request()->header('deviceOs');
        $app_version = request()->header('applicationVersion');

        $guest_num = User::count() + 1;

        $user = User::create([
            'name' => "guest" . $guest_num,
            'email' => "guest" . $guest_num . "@gmail.com",
            'phone' => "010" . $guest_num,
            'password' => $guest_num . bcrypt("guest" . $guest_num),
            'token' => md5(str_random(60)),
            'app_version' => $app_version,
            'device_os' => $device_os,
            'device_id' => $device_id,
            'active' => 1,
            'type' => 'GUEST',
        ]);

        return $this->userObject($user);

    }

    public function getAddressPhone($request)
    {
        $array = array();
        $lang = getLang();
        $address_phone = $request->input('address_phone');
        $address_phone = str_replace(" ", "", $address_phone);
        $address_phone = str_replace("-", "", $address_phone);
        $address_phone = $this->phoneTranslation($address_phone);
        if (!preg_match("/^([\+]2)?((01[0125]\d{8})|((02)?[23]\d{7}))$/", $address_phone)) {
            if ($lang == 'en') {
                $array['error'] = Response::json(['Status' => 'Erorr', 'message' => 'Please Enter A Valid Address Phone'], 412);
            }
            $array['error'] = Response::json(['Status' => 'Erorr', 'message' => 'قم بأدخال رقم الهاتف بطريقة صحيحة'], 412);
        } else {
            $array['error'] = 'valid';
        }
        $array['address_phone'] = $address_phone;
        return $array;
    }

    //Login With FaceBook
    public function loginFacebook(\Illuminate\Http\Request $request)
    {
        if (($request->input('access_token') != null)) {
            $token = $request->input('access_token');
            return $this->loginFcebookToken($token);
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
    }

    //Login With FaceBook
    public function loginFcebookToken($token)
    {

        $device_os = request()->header('deviceOs');
        $device_id = request()->header('deviceId');
        $app_version = request()->header('applicationVersion');
        $user_facebook = Socialite::driver('facebook')->userFromToken($token);
        $user_exits = User::where('facebook_id', $user_facebook->id)
            ->orWhere('email', $user_facebook->email)->first();
        if (!$user_exits) {
            $user_fb = new User;
            $user_fb->facebook_id = $user_facebook->id;
            $user_fb->token = md5(($user_facebook->id . time()));
            $user_fb->token_last_renew = \Carbon\Carbon::now();
            $user_fb->name = $user_facebook->name;
            $password = $user_facebook->id . 'g0mla';
            if (!empty($user_facebook->email)) {
                $user_fb->email = $user_facebook->email;
            } else {
                $user_fb->email = $user_facebook->id . '@goomla.com';
            }
            $user_fb->device_id = $device_id;
            $user_fb->device_os = $device_os;
            $user_fb->app_version = $app_version;
            $user_fb->save();
            $user_id = $user_fb->id;
            $user = \App\User::find($user_id);
            $erpnextName = $user->name . $user_id;
            $user->erpnext_name = $erpnextName;
            $name = $user_fb->name;
            $email = $user_fb->email;
            $token = $user_fb->token;
            $userdata['auth_token'] = $token;
            $userdata['name'] = $name;
            $userdata['email'] = $email;
            $userdata['phone'] = $user->phone;
            $user->save();

            //$result1  = $this->customerLoginErp( $erpnextName,$password,$email,4);
            // $result16 = $this->createCustomerErp($erpnextName, $email, 4);

            //$result2  = $this->updateCustomerPasswordErp( $email,$password, 4);
            return Response::json($userdata, 200);
        } else {
            $user_exits->device_id = $device_id;
            $user_exits->device_os = $device_os;
            $user_exits->app_version = $app_version;
            $user_exits->save();
            //$myresult = $this->getCustomerDataErp($user_exits->email, 4);
            //$myresult = json_decode($myresult, true);
            $user_payment = Payment_Method::where('user_id', '=', $user_exits->id)->first();
            if ($user_payment) {
                $userdata['mode_of_payment'] = $user_payment->mode_of_payment;
                $userdata['name_payment'] = $user_payment->name;
                $userdata['type_payment'] = $user_payment->type;
            }

            $userdata = array();

            $userdata['auth_token'] = $user_exits->token;
            $userdata['name'] = $user_exits->name;
            $userdata['email'] = $user_exits->email;
            // if (isset($myresult['data']['location'])) {
            //     $locationarr = explode(config('goomla.dilimiter'), $myresult['data']['location']);
            // }

            if (!empty($user_exits->phone)) {
                $userdata['phone'] = $user_exits->phone;
            }
            //$userdata['email'] = $myresult['data']['email'];

            $userdata['address'] = getUserAddresses($user_exits, $userdata);
            return Response::json($userdata, 200);
        }
    }

    public function getUserAddresses($user, $userdata)
    {
        $addresses = \App\Addresses::where('user_id', $user->id)->where('active', 1)->get();
        if (count($addresses) > 0) {
            foreach ($addresses as $key => $address) {
                $warehouse = $this->getWarehouse($address->district_id);
                $userdata['addresses'][$key]['address_id'] = $address->id;
                $userdata['addresses'][$key]['address_title'] = $address->title;
                $userdata['addresses'][$key]['lat'] = $address->lat;
                $userdata['addresses'][$key]['lng'] = $address->lng;
                // $userdata['addresses'][$key]['address_phone'] = $address->address_phone;
                $userdata['addresses'][$key]['street'] = $address->street;
                $userdata['addresses'][$key]['nearest_landmark'] = $address->nearest_landmark;
                $userdata['addresses'][$key]['district_id'] = $address->district_id;
                $userdata['addresses'][$key]['district_name'] = '';
                if (isset($userdata['addresses'][$key]['district_id'])) {
                    $district = District::where('id', $userdata['addresses'][$key]['district_id'])->first();
                    if ($district) {
                        $userdata['addresses'][$key]['district_name'] = $district->district_en;
                    }

                }
                $userdata['addresses'][$key]['warehouse'] = $warehouse;
                $userdata['addresses'][$key]['city'] = $address->city;
            }
        }
        return $userdata;
    }

    public function addnewaddress(\Illuminate\Http\Request $request)
    {
        $lang = getLang();
        if ($request->ajax() && $request->user_id) {
            $user = \App\User::where('token', '=', $request->user_id)->first();
        } else {
            $token = app('request')->header('token');
            if ($token == null) {
                $token = app('request')->header('Authorization');
            }
            if ($token != null) {
                $user = \App\User::where('token', '=', $token)->first();
            }
        }

        if (empty($user)) {
            return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to add addresse, Please login.'], 401);
        } else {

            if ($request->has('address_phone')) {
                $array = $this->getAddressPhone($request);
                if ($array['error'] != 'valid') {
                    return $array['error'];
                } else {
                    $address_phone = $array['address_phone'];
                }
            } else {
                $request->address_phone = $user->phone;
            }
            // return $request->all();
            $address = \App\Addresses::create(['title' => $request->input('address_title'), 'user_id' => $user->id, 'city' => $request->city, 'street' => $request->input('street'), 'district_id' => $request->district_id, 'nearest_landmark' => $request->input('nearest_landmark'), 'address_phone' => $request->address_phone, 'lat' => $request->lat, 'lng' => $request->lng]);
            $userdata = array();
            $userdata = $this->getUserAddresses($user, $userdata);
            if (isset($userdata) && !$request->ajax()) {
                return Response::json($userdata['addresses'], 200);
            } else {
                return 'added';
            }
        }
    }

    public function getTokenFromReq(\Illuminate\Http\Request $request)
    {
        $token = app('request')->header('token');
        if ($token == null) {
            $token = app('request')->header('Authorization');
        }

        return $token;
    }

    public function getWarehouse($districtId)
    {
        $branches = Warehouses::get();
        $warehouse = "Mokattam - GOL";
        foreach ($branches as $branch) {
            if (in_array($districtId, json_decode($branch->district_id))) {
                return $branch->name_en;
            }
        }
    }

    public function getaddress(\Illuminate\Http\Request $request)
    {
        $token = $this->getTokenFromReq($request);
        $lang = app('request')->header('lang');
        if ($token != null) {
            $user = \App\User::where('token', '=', $token)->first();
        }
        if (empty($user)) {
            return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to add to get addresses, Please login.'], 401);
        } else {
            $addresses = array();
            $addresses = $this->getUserAddresses($user, $addresses);
            if (isset($addresses['addresses'])) {
                $addresses = $addresses['addresses'];
            } else {
                $addresses = array();
            }

            if (count($addresses) > 0) {
                return Response::json($addresses, 200);
            } else {
                return Response::json([], 200);
            }

        }
    }

    public function getdistricts(\Illuminate\Http\Request $request)
    {
        $lang = getLang();

        if (getFromCache('District')) {
            return getFromCache('District');
            // dd('Cached', getFromCache('District'));

        }

        if (trim($lang) == 'en') {
            $districts = District::select('id', 'district_en as name')->where('district_en', '!=', 'Others')->where('active', 1)->get();
        } else {
            $districts = District::select('id', 'district_ar as name')->where('district_en', '!=', 'Others')->where('active', 1)->get();
        }

        if (count($districts) > 0) {
            foreach ($districts as $district) {
                $districtId = $district->id;
                $warehouses = Warehouses::get();
                foreach ($warehouses as $warehouse) {
                    if (in_array($districtId, json_decode($warehouse->district_id))) {
                        $warehouse_id = $warehouse->id;
                    }
                }

                $district->warehouse_id = isset($warehouse_id) ? $warehouse_id : 0;
                //$district->warehouse_name = $lang == "en" ? $warehouse->name_en : $warehouse->name;
            }
            // return putInCache('District', $districts);
            // dd('Cache', putInCache('District', $districts));
            return Response::json(putInCache('District', $districts), 200);
            // return Response::json($districts, 200);
        } else {
            return Response::json('There is no districts for this user yet', 412);
        }
    }

    public function phoneTranslation($phone = null)
    {
        if (!is_null($phone)) {
            $phone = str_replace('٠', '0', $phone);
            $phone = str_replace('١', '1', $phone);
            $phone = str_replace('٢', '2', $phone);
            $phone = str_replace('٣', '3', $phone);
            $phone = str_replace('٤', '4', $phone);
            $phone = str_replace('٥', '5', $phone);
            $phone = str_replace('٦', '6', $phone);
            $phone = str_replace('٧', '7', $phone);
            $phone = str_replace('٨', '8', $phone);
            $phone = str_replace('٩', '9', $phone);
        }
        return $phone;
    }

    public function getUser(\Illuminate\Http\Request $request)
    {

        $token = $this->getTokenFromReq($request);
        if ($token != null) {
            $userData = array();
            $user = DB::table('users')->where('token', '=', $token)->first();
            $address = DB::table('address')->where('address.user_id', '=', $user->id)->get();
            foreach ($address as $key => $addres) {
                $districtId = $addres->id;
                $branches = Warehouses::get();
                $warehouse = "Mokattam - GOL";
                foreach ($branches as $branch) {
                    if (in_array($districtId, json_decode($branch->district_id))) {
                        $warehouse = $branch->warehouse_name;
                    }
                }
                $addres->warehouse = $warehouse;
            }

            $userdata = array();
            $userdata['id'] = $user->id;
            $userdata['phone'] = $user->phone;
            $userdata['name'] = $user->name;
            $userdata['email'] = $user->email;
            $userdata['auth_token'] = $user->token;
            $userdata['active'] = $user->active == 1 ? true : false;
            $userdata = $this->getUserAddresses($user, $userdata);

            if ($user) {
                return Response::json($userdata, 200);
            } elseif (empty($user)) {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to  get user data, Please login.'], 401);
            }

        } else {
            return Response::json(['Status' => 'Error', 'message' => 'Bad Request.'], 400);

        }
    }

    public function updateUserData(\Illuminate\Http\Request $request)
    {
        $lang = app('request')->header('lang');
        $token = $this->getTokenFromReq($request);
        $user = \App\User::where('token', '=', $token)->first();
        if (empty($user)) {
            return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to Update This User, Please login.'], 401);
        }

        if ($token != null) {
            if (!$request->has('name') || !$request->has('phone')) {
                return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
            }
            if ($request->has('name')) {
                $name = $request->input('name');
                $UserNameUpdated = User::where('token', $token)->update(['name' => $name]);
            }
            if ($request->has('phone')) {
                $phone = $request->input('phone');
                $UserUpdated = User::where('token', $token)->update(['phone' => $phone]);
            }
            if ($UserUpdated || $UserNameUpdated) {
                $user = User::where('token', $token)->first();
                $userdata = $this->getUserData($user);
                $userdata = $this->getUserAddresses($user, $userdata);
                return Response::json($userdata, 200);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'Internal Server Erorr'], 500);
            }
        } else {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to From Update Profile, Please login.'], 401);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ لتعديل البيانات .'], 401);
            }
        }
    }

    public function updateUserPassword(\Illuminate\Http\Request $request)
    {
        $lang = app('request')->header('lang');
        $token = $this->getTokenFromReq($request);
        $user = \App\User::where('token', '=', $token)->first();
        if (empty($user)) {
            return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to Update This User, Please login.'], 401);
        }

        if ($token != null) {
            if (!$request->has('old_password') || !$request->has('new_password')) {
                return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
            }

            if ($request->has('old_password')) {
                $old_password = $request->old_password;
                if (!Hash::check($request->old_password, $user->password)) {
                    return Response::json(['Status' => 'Erorr', 'message' => 'Old password is incorrect'], 400);
                } else {
                    if (Hash::check($request->new_password, $user->password)) {
                        return Response::json(['Status' => 'Erorr', 'message' => 'new password can\'t be same as old password'], 400);
                    }

                    $new_password = bcrypt($request->new_password);
                }
                $UserUpdated = User::where('token', $token)->update(['password' => $new_password]);
            }

            if ($UserUpdated) {
                $user = User::where('token', $token)->first();
                $userdata = $this->getUserData($user);
                $userdata = $this->getUserAddresses($user, $userdata);

                return Response::json($userdata, 200);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'Internal Server Erorr'], 500);
            }
        } else {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to From Update Profile, Please login.'], 401);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ لتعديل البيانات .'], 401);
            }
        }
    }

    public function validateUserPassword(\Illuminate\Http\Request $request)
    {
        $lang = app('request')->header('lang');
        $token = $this->getTokenFromReq($request);
        $user = \App\User::where('token', '=', $token)->first();
        if (empty($user)) {
            return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to Update This User, Please login.'], 401);
        }

        if ($token != null) {
            if (!$request->has('old_password')) {
                return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
            }

            if ($request->has('old_password')) {
                $old_password = $request->old_password;
                if (!Hash::check($request->old_password, $user->password)) {
                    return Response::json(['Status' => 'Erorr', 'message' => false], 400);
                } else {
                    return Response::json(['Status' => 'Success', 'message' => true], 200);
                }
            }

        } else {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to From Update Profile, Please login.'], 401);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ لتعديل البيانات .'], 401);
            }
        }
    }

    public function updateAddress(\Illuminate\Http\Request $request, $addresseId)
    {
        $lang = getLang();
        $token = getTokenFromReq($request);
        $user = User::where('token', $token)->first();
        if (empty($user)) {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to From Edit Address, Please login.'], 401);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ.'], 401);

            }
        } else {
            $userId = $user->id;
            $address = Addresses::where('id', $addresseId)->where('user_id', $userId)->where('active', 1)->first();
            if ($address) {
                $email = $address->user_id;
                $address = \App\Addresses::where('user_id', $email)->where('id', $addresseId)->update(['city' => $request->input('city'), 'street' => $request->input('street'), 'district_id' => $request->input('district_id'), 'nearest_landmark' => $request->input('nearest_landmark'), 'lat' => $request->input('lat'), 'lng' => $request->input('lng'), 'apartment_no' => $request->input('apartment_no'), 'building_no' => $request->input('building_no'), 'floor_no' => $request->input('floor_no'), 'title' => $request->input('address_title')]);
                $userdata = array();
                $userdata = $this->getUserAddresses($user, $userdata);
                $userdata = $userdata['addresses'];

                return Response::json($userdata, 200);
            } else {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Erorr', 'message' => 'Address Id is not found '], 400);
                } else {
                    return Response::json(['Status' => 'Erorr', 'message' => 'لا يوجد'], 400);
                }
            }
        }
    }

    public function deleteAddress(\Illuminate\Http\Request $request, $addresseId)
    {
        $lang = app('request')->header('lang');

        $token = getTokenFromReq($request);

        $user = User::where('token', $token)->first();

        if (empty($user)) {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to From Delete Address, Please login.'], 401);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ لحذف العنوان.'], 401);

            }
        } else {

            $userId = $user->id;

            $deleted = Addresses::where('id', $addresseId)->where('user_id', $userId)->update(['active' => 0]);

            if ($deleted) {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Success', 'message' => 'Address Deleted Successfully '], 200);
                } else {
                    return Response::json(['Status' => 'Success', 'message' => 'تم حذف العنوان بنجاح'], 200);
                }

            } else {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Erorr', 'message' => 'Address Id is not found '], 400);

                } else {
                    return Response::json(['Status' => 'Erorr', 'message' => 'لا يوجد هذا العنوان'], 400);

                }
            }

        }

    }

    public function checkAppVersion(\Illuminate\Http\Request $request)
    {
        if (!is_null($request->header('buildNumber'))) {
            $buildNumber = $request->header('buildNumber');
            switch ($buildNumber) {
                case 3:
                    return Response::json(["app_status" => "Latest"], 200);
                case '3':
                    return Response::json(["app_status" => "Latest"], 200);
                case 2:
                    return Response::json(["app_status" => "Required"], 200);
                case '2':
                    return Response::json(["app_status" => "Required"], 200);
                default:
                    return Response::json(["app_status" => "ExtremelyRequired"], 200);

            }
        }

        // }

        return Response::json(["app_status" => "Your Device is not supported in this api"], 200);
    }

    public function removeuserNotifyProductBack(\Illuminate\Http\Request $request, $productId)
    {

        $lang = app('request')->header('lang');

        $headers = getallheaders();

        $token = getTokenFromReq($request);

        $districtId = getDistrictId();

        if (!is_null($token)) {

            $branch = getDistrictWarehouse($districtId);

            $user = \App\User::where('token', '=', $token)->first();

        }

        if (!isset($user) || empty($user)) {

            Log::useDailyFiles(storage_path() . '/logs/errors/debugUrl.log');

            Log::info(["Error" => "Unauthorized, Please login.", "Time" => \Carbon\Carbon::now(), "Token" => $token]);

            if ($lang == 'en') {

                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized, Please login.'], 401);

            } else {

                return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاًً'], 401);

            }

        }

        $product = Products::find($productId);

        if (!$product) {

            if ($lang == 'en') {

                return Response::json(['Status' => 'Error', 'Message' => 'This Product Does not exist'], 412);

            } else {

                return Response::json(['Status' => 'Error', 'Message' => 'هذا المنتج غير موجود'], 412);

            }

        }

        if ($product && $warehouse) {

            $checkRecord = UserItemNotification::where('product_id', $product->id)->where('warehouse_id', $warehouse->id)->where('user_id', $user->id)->first();
            if ($checkRecord) {
                $checkRecord->delete();
            } else {

                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'Message' => 'You did not require notification for this item\'s availability '], 412);
                } else {
                    return Response::json(['Status' => 'Error', 'Message' => 'لم تقم بطلب أشعار برجوع هذا المنتج'], 412);
                }
            }
        }

        if ($lang == 'en') {
            return Response::json(['Status' => 'Success', 'Message' => 'Thanks ,Now We will not send you a notification when this item gets back to stock'], 200);
        } else {
            return Response::json(['Status' => 'Success', 'Message' => 'شكرا لك , الان  لن نقوم بأشعارك عند توافر المنتج'], 200);
        }

    }

    public function userNotifyProductBack(\Illuminate\Http\Request $request, $productId)
    {

        $lang = app('request')->header('lang');

        $headers = getallheaders();

        $token = getTokenFromReq($request);

        $districtId = getDistrictId();
        if (!is_null($token)) {
            $warehouse = getDistrictWarehouse($districtId);
            $user = \App\User::where('token', '=', $token)->first();
        }

        if (!isset($user) || empty($user)) {


            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized, Please login.'], 401);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاًً'], 401);
            }

        }

        $product = Products::find($productId);

        if ($product && $warehouse) {

            $checkRecord = UserItemNotification::where('product_id', $product->id)->where('warehouse_id', $warehouse->id)->where('user_id', $user->id)->where('notification_sent', 0)->first();

            if (!$checkRecord) {

                $userItemNotification = new UserItemNotification;

                $userItemNotification->product_id = $product->id;

                $userItemNotification->warehouse_id = $warehouse->id;

                $userItemNotification->user_id = $user->id;

                $userItemNotification->notification_sent = 0;

                $userItemNotification->save();

            }

            if ($lang == 'en') {

                return Response::json(['Status' => 'Success', 'Message' => 'Thanks , We will send you a notification when this item gets back to stock'], 200);

            } else {

                return Response::json(['Status' => 'Success', 'Message' => 'شكرا لك , سوف نقوم بأشعارك حينما يكون لدينا المزيد من هذا المنتج'], 200);

            }

        } else {

            if ($lang == 'en') {

                return Response::json(['Status' => 'Error', 'Message' => 'This Product Does not exist'], 412);

            } else {

                return Response::json(['Status' => 'Error', 'Message' => 'هذا المنتج غير موجود'], 412);

            }

        }

    }

    /**
     * @param $user
     * @return array
     */
    public function userObject($user)
    {
        $userdata = [];
        $userdata['auth_token'] = $user->token;
        $userdata['name'] = $user->name;
        $userdata['phone'] = $user->phone;
        $userdata['email'] = $user->email;
        $userdata['device_id'] = $user->device_id;
        $userdata['active'] = $user->active == 1 ? true : false;
        $userdata['type'] = $user->type;
        return $userdata;
    }

}
