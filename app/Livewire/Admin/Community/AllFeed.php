<?php

namespace App\Livewire\Admin\Community;

use App\Models\NewsComment;
use App\Models\NewsPost;
use App\Models\PostCommentLike;
use App\Models\Tutor;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class AllFeed extends Component
{
    public $total;
    public $page_size = 20;
    public $current_page = 1;
    public $posts;
    public $current_user;
    public $current_user_avatar;


    public function mount() {
        $this->getPosts();
    }

    public function getPosts() {
        $this->total = NewsPost::count();

        $current_user = auth()->user();
        $user_role = $current_user->user_role;
        if ($user_role->name == 'tutor') {
            $current_user_avatar = $current_user->tutor->getPhoto();
        }else if ($user_role->name == 'admin') {
            $current_user_avatar = $current_user->admin->getPhoto();        
        }
        $this->current_user = $current_user;
        $this->current_user_avatar = $current_user_avatar;

        $start_at = $this->page_size * ($this->current_page - 1);

        $posts = NewsPost::query()->orderBy('pin', 'desc')
            ->orderBy('created_at', 'desc')
            ->offset($start_at)
            ->limit($this->page_size)
            ->get();

        foreach ($posts as $post) {
            $user = $post->user;
            $user_role = $user->user_role;
            if ($user_role->name == 'tutor') {
                $tutor = $user->tutor;
                $post->name = $tutor->tutor_name;
                $post->avatar = $tutor->getPhoto();
                $post->profile = 'tutor' . $tutor->id;
                $post->is_admin = false;
            } else if ($user_role->name == 'admin') {
                $admin = $user->admin;
                $post->name = $admin->admin_name;
                $post->avatar = $admin->getPhoto();
                $post->profile = '#';
                $post->is_admin = true;
            }

            $tagged_tutors = [];
            if (!empty($post->tagged_tutor)) {
                $tutor_ids = explode(',', $post->tagged_tutor);
                foreach ($tutor_ids as $tutor_id) {
                    $tutor = Tutor::find($tutor_id);
                    $tagged_tutors[] = $tutor;
                }
            }
            $post->tagged_tutors = $tagged_tutors;

            $post_likes = PostCommentLike::where('comment_id', 0)
                ->where('post_id', $post->id)->get();

            $user_post_like = PostCommentLike::where('comment_id', 0)
                ->where('post_id', $post->id)->where('user_id', auth()->user()->id)
                ->count() > 0 ? true : false;

            $post->post_likes = $post_likes;
            $post->post_like_total = count($post_likes);
            $post->user_post_like = $user_post_like;

            $comments = NewsComment::where('post_id', $post->id)->orderBy('created_at', 'desc')->get();
            if (!empty($comments)) {
                foreach ($comments as $comment) {
                    $user = $comment->user;
                    $user_role = $user->user_role;
                    if ($user_role->name == 'tutor') {
                        $tutor = $user->tutor;
                        $comment->name = $tutor->tutor_name;
                        $comment->avatar = $tutor->getPhoto();
                        $comment->profile = 'tutor' . $tutor->id;
                        $comment->is_admin = false;
                    } else if ($user_role->name == 'admin') {
                        $admin = $user->admin;
                        $comment->name = $admin->admin_name;
                        $comment->avatar = $admin->photo;
                        $comment->profile = '#';
                        $comment->is_admin = true;
                    }

                    $comment_likes = PostCommentLike::where('comment_id', $comment->id)
                    ->where('post_id', $post->id)->get();
    
                    $user_comment_like = PostCommentLike::where('comment_id', $comment->id)
                        ->where('post_id', $post->id)->where('user_id', auth()->user()->id)
                        ->count() > 0 ? true : false;
        
                    $comment->comment_likes = $comment_likes;
                    $comment->comment_like_total = count($comment_likes);
                    $comment->user_comment_like = $user_comment_like;
                }
            } else $comments = [];
            $post->comments = $comments;
        }        

        $this->posts = $posts;
    }

    public function render()
    {
        return view('livewire.admin.community.all-feed');
    }
}
