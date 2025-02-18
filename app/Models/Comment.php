<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'parent_id', 'user_profile_id', 'user_name', 'email', 'text', 'attachment_path', 'attachment_type'
    ];

    public function userProfile()
{
    return $this->belongsTo(UserProfile::class, 'user_profile_id');
}

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function getAvatarUrlAttribute()
{
    return $this->userProfile ? $this->userProfile->avatar_url : null;
}


}
