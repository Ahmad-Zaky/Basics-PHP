<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use Core\Validator;
use Exception;

class AuthController extends Controller
{
    public function signinForm()
    {
        view("auth.signin", ["heading" => 'Sign In']);        
    }

    public function signin()
    {
        if (validate(User::rules()["signin"])) {
            $data = Validator::validated();

            $user = User::findByEmail($data["email"]);

            if ($user && verifyHash($data["password"], $user->password)) {
                signin($user);

                redirect(route("home"));
            }

            back([
                "errors" => ["email" => ["Email or Password doesn't exists"]]
            ]);
        }

        redirect(route("home"));            
    }

    public function signupForm()
    {
        view("auth.signup", ["heading" => 'Sign Up']);
    }

    public function signup()
    {
        if (validate(User::rules()["signup"])) {
            $data = Validator::validated();

            $user = User::create([
                "name" => $data["name"],
                "email" => $data["email"],
                "password" => bcrypt($data["password"]),
            ]);

            if (! $user) throw new Exception("Failed to signup !");

            signin($user);
        }

        redirect(route("home"));
    }

    public function signout()
    {
        signout();

        redirect(route("home"));
    }
}