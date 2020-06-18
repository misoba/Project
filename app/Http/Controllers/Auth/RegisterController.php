<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
          'user_name' => ['required', 'string', 'max:255', 'unique:users'],
          'full_name' => ['required', 'string', 'max:255'],
          'nic_number'=>['required','regex:|^[0-9]{9}[Vv]$|', 'unique:users'],
          'image_copy' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
          'job' => ['required', 'string', 'max:255'],
          'dob' => ['required','date','before:today'],
          'phone' => ['required', 'string', 'max:255'],
          'mobile' => ['required', 'string', 'max:255'],
          'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
          'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $request = request();

        $avatar = $request->file('avatar');
        $avatarSaveAsName = time() . "-avatar." . $avatar->getClientOriginalExtension();

        $avatar_upload_path = 'avatar/';
        $avatar_url = $avatar_upload_path . $avatarSaveAsName;
        $success = $avatar->move($avatar_upload_path, $avatarSaveAsName);

        $image_copy = $request->file('image_copy');
        $image_copySaveAsName = time() . "-image_copy." . $image_copy->getClientOriginalExtension();

        $image_upload_path = 'image/';
        $image_url = $image_upload_path . $image_copySaveAsName;
        $success = $image_copy->move($image_upload_path, $image_copySaveAsName);

        return User::create([
          'user_name' => $data['user_name'],
          'full_name' => $data['full_name'],
          'nic_number' => $data['nic_number'],
          'job' => $data['job'],
          'phone' => $data['phone'],
          'mobile' => $data['mobile'],
          'email' => $data['email'],
          'password' => bcrypt($data['password']),
          'dob' => $data['dob'],
          'avatar' => $avatar_url,
          'image_copy' => $image_url,
          "status" => 'false',
        ]);
    }
}
