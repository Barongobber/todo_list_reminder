<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function retrieve() {
        $user = User::where('id', Auth::user()->id)->first();
        return $user;
    }

    public function register() {
        //Validating request form
        request()->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'username' => ['required', 'string']
        ]);

        //Checking procedure of the unique attributes
        $check = User::where('email', request('email'))->first();
        if ($check)
            abort(400, 'The email address has already been used by another account.');

        $check = User::where('username', request('username'))->first();
        if ($check)
            abort(400, 'The username has already been used by another account.');

        
        $userData = [
            'name' => request('name'),
            'username' => request('username'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'api_token' => Str::random(60),
            'picture' => "default.jpg"
        ];
        
        //Inserting new user
        $user = User::create($userData);

        //Processing photo
        $newPath = public_path() . '/storage/img/user/' . $user->id;
        mkdir($newPath);
        $ext = '.jpg';
        $newName = $newPath."/default".$ext;
        $defaultPhoto = public_path() . '/storage/img/user/default.jpg';
        copy($defaultPhoto, $newName); 

        return [
            "data" => $user
        ];
    }

    public function update() {
        $user = Auth::user();

        //Validating request form
        request()->validate([
            'name' => ['required', 'string'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'nullable', 
                'max:255', 
                Rule::unique('users')->ignore($user->id)
            ],
            'username' => ['required', 'string']
        ]);

        $data = request()->only([
            'name',
            'email',
            'username'
        ]);

        //Updating
        $data['email'] = strtolower($data['email']);
        $user->update($data);

        //Response
        $response = [
            // "user" => $data
            "user" => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'api_token' => $user->api_token
            ]
        ];
        return $response;
    }

    public function updateImage()
    {
        request()->validate([
            'picture' => ['required', 'image']
        ]);

        // get partner data by id
        $user = User::find(Auth::user()->id);
        $oldPicturePath = public_path() . '/storage/img/user/' . $user->id;
        $oldFile = $oldPicturePath . '/' . $user->picture;

        // replace old picture name
        array_map('unlink', glob("$oldPicturePath/*.*"));
        $pictureName = request('picture')->getClientOriginalName();
        $user->picture = $pictureName;
        $user->save();

        // add new picture to storage
        request('picture')->move($oldPicturePath, $pictureName);

        return $user;
    }

    public function changePassword() {
        $user = Auth::user();

        // Validating request form
        request()->validate([
            'password' => ['required', 'min:8']
        ]);

        // $pass = bcrypt(request('password'));
        $data = request()->only([
            'password'
        ]);
        
        $data['password'] = bcrypt($data['password']);

        // Updating
        $user->update($data);
        $isUpdated = $user->wasChanged();

        // Response
        $response = [
            "user" => [
                'password_updated' => $isUpdated
            ]
        ];

        return $response;
    }
}
