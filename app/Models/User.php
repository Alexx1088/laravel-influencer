<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

//use Laravel\Sanctum\HasApiTokens;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property int|null $role_id
 * @property-read \App\Models\Role|null $role
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @property int $is_influencer
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsInfluencer($value)
 * @property-read mixed $revenue
 * @property-read mixed $full_name
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /*protected $fillable = [
        'first_name',
        'last_name',
        'password',
        'email',
        'role_id',
        'is_influencer',
    ];*/

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role() {
        return $this->hasOneThrough(Role::class, UserRole::class,
            'user_id', 'id', 'id', 'role_id');
    }

    public function permissions(){
        return $this->role->permissions()->pluck('name');
    }
    public function hasAccess($access) {
        return $this->permissions()->contains($access);
    }
    public function isAdmin():bool {
        return $this->is_influencer === 0;
    }
    public function isInfluencer():bool {
        return $this->is_influencer === 1;
    }
    public function getRevenueAttribute() {
        $orders = Order::where('user_id', $this->id)->where('complete', 1)->get();

        return $orders->sum(function (Order $order) {
            return $order->influencer_total;
        });
    }
    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
