<?php

namespace App\Models\V1;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_active',
        'must_change_password',
        'username',
        'image',
    ];

    protected $attributes = [
        'is_active' => true,
        'must_change_password' => true,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean'
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function dentist() {
        return $this->hasOne(Dentist::class);
    }

    public function patient() {
        return $this->hasOne(Patient::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function toggleActivity()
    {
        static::withoutTimestamps(function () {
            $this->is_active = !$this->is_active;
            $this->save();
        });
    }

    public function resetPassword(string $newPassword)  {
        static::withoutTimestamps(function () use ($newPassword) {
            $this->password = Hash::make($newPassword);
            $this->must_change_password = true;
            $this->save();
        });
    }

    /**
     * if the user has an image it return absolute path to the user image, else it returns path for default image
     */
    public function getAvatarAttribute()
    {
        // return    $this->image
        //     ? Storage::disk('public')->url($this->image)
        //     : Storage::disk('public')->url(Config::get('app.default_avatar_path'));

        // Check if image path exists first
        if ($this->image) {
            // Use the asset() helper for publicly accessible files
            return asset('storage/' . $this->image);
        }

        // Return the default avatar path
        return asset('storage/' . Config::get('app.default_avatar_path'));
    }
}
