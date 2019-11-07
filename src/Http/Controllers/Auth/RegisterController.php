<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Socialite;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Redirect;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/erorr';
    private $token;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');

        $this->token = new UsersController();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    //    public function osama($x)
    //    {
    //        $user = $this->handleProviderCallback();
    //        return $user;
    //    }

    public function handleProviderCallback()
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
            $create['name'] = $socialUser->getName();
            $create['email'] = $socialUser->getEmail();
            $create['facebook_id'] = $socialUser->getId();


            $userModel = new User;
            $createdUser = $userModel->addNew($create);
            Auth::loginUsingId($createdUser->id);



            // $socialUser->token;
            //                dd($socialUser->token);

            // $fbUser = $this->token->loginFcebookToken($socialUser->token);

            //            $fbUuserData = json_decode($fbUser);


            // return $fbUser;
            //            $this->osama($fbUser);
            //            return Redirect::route('user');
            //            return redirect('/fblogin');


        } catch (Exception $e) {
            return redirect('../retailak/login');
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
