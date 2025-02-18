<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class CommentController extends Controller
{
    /**
     * Повертає список коментів
     */
    public function index(Request $request)
    {
        // Поля сортування
        $allowedSortFields = ['user_name', 'email', 'created_at', 'text'];

        $sortField = $request->input('sort_field', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Перевірка небажаних значень
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        // Завантажуємо тільки батьківські коментарі з дітьми 
        $comments = Comment::whereNull('parent_id')
            ->with([
                'userProfile',            
                'children.userProfile',   
                'children.children.userProfile', 
            ])
            ->orderBy($sortField, $sortOrder)
            ->paginate(25);

        // Аватар для всіх коментів
        $comments->transform(function ($comment) {
            $this->setAvatarUrlRecursive($comment);
            return $comment;
        });

        return response()->json($comments);
    }

  
    private function setAvatarUrlRecursive(Comment $comment)
    {
        // Аватар 
        $comment->avatar_url = $comment->userProfile
            ? $comment->userProfile->avatar_url
            : '/images/default-profile.png';


        if ($comment->relationLoaded('children') && $comment->children) {
            $comment->children->transform(function ($child) {
                $this->setAvatarUrlRecursive($child);
                return $child;
            });
        }
    }

    /**
     * Зберігає коментар і профіль користувача.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'text'       => 'required|string',
            'captcha'    => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,jpg,png,gif,txt'
        ]);

        if ($validated['captcha'] !== '1234') {
            return response()->json(['error' => 'Невірна капча.'], 422);
        }

        $profile = UserProfile::first();
        if (!$profile || empty($profile->user_name) || empty($profile->email)) {
            return response()->json(['error' => 'Будь ласка, збережіть профіль перед коментуванням.'], 422);
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            if ($file->isValid()) {
                $extension = strtolower($file->getClientOriginalExtension());
                $filename = time() . '_' . $file->getClientOriginalName();

                if (in_array($extension, ['jpeg', 'jpg', 'png', 'gif'])) {
                    $img = Image::make($file);
                    $img->resize(320, 240, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save(public_path('storage/attachments/' . $filename));
                    $attachmentPath = 'storage/attachments/' . $filename;
                    $attachmentType = 'image';
                } elseif ($extension === 'txt') {
                    if ($file->getSize() > 102400) {
                        return response()->json(['error' => 'Текстовий файл перевищує 100 кб'], 422);
                    }
                    $attachmentPath = $file->store('attachments', 'public');
                    $attachmentType = 'text';
                } else {
                    return response()->json(['error' => 'Непідтримуваний тип файлу'], 422);
                }
            }
        }

        $comment = Comment::create([
            'parent_id'       => $request->input('parent_id') ?: null,
            'user_profile_id' => $profile->id,
            'text'            => $validated['text'],
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'user_name'       => $profile->user_name,
            'email'           => $profile->email,
        ]);
        
        $comment->load('userProfile');

        return response()->json([
            'comment'    => $comment,
            'avatar_url' => $comment->userProfile->avatar_url ?? null,
        ], 201);
    }
}
