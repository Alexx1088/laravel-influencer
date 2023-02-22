<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class UserService
{
    private $endpoint = 'nginx:80/api';

    public function headers()
    {
        //  return ['Autorization' => request()->headers->get('Autorization')];

        $headers = [];

        if ($jwt = request()->cookie('jwt')) {
            $headers['Authorization'] = "Bearer {$jwt}";
        }
        if (request()->headers->get('Authorization')) {
            $headers['Authorization'] = request()->headers->get('Authorization');
        }
        //  dd($headers);
        return $headers;
    }

    public function getUser(): User
    {
        // dd($this->headers());
        $json = Http::withHeaders($this->headers())->get("{$this->endpoint}/user")->json();

        $user = new User();
        $user->id = $json['id'];
        $user->first_name = $json['first_name'];
        $user->last_name = $json['last_name'];
        $user->email = $json['email'];
        $user->is_influencer = $json['is_influencer'];

        return $user;
    }

    public function isAdmin()
    {
        return Http::withHeaders($this->headers())->get("{$this->endpoint}/admin")->successful();
    }
    public function isInfluencer()
    {
        return Http::withHeaders($this->headers())->get("{$this->endpoint}/influencer")->successful();
    }
}
