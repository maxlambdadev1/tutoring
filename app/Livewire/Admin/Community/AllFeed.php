<?php

namespace App\Livewire\Admin\Community;

use App\Models\NewsPost;
use App\Models\PostCommentLike;
use App\Models\Tutor;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class AllFeed extends Component
{
    public $total;
    public $page_size = 10;
    public $current_page = 1;
    public $posts;


    public function mount() {
        $this->total = NewsPost::count();
        $this->getPosts();
    }

    public function getPosts() {
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
                $post->avatar = $tutor->getPhoto;
                $post->profile = 'tutor' . $tutor->id;
                $post->is_admin = false;
            } else if ($user_role->name == 'admin') {
                $admin = $user->admin;
                $post->name = $admin->admin_name;
                $post->avatar = $admin->photo;
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

            $post_comment_likes = PostCommentLike::where('comment_id', 0)
                ->where('post_id', $post->id)->get();

            $user_post_like = PostCommentLike::where('comment_id', 0)
                ->where('post_id', $post->id)->where('user_id', auth()->user()->id)
                ->count() > 0 ? true : false;

            $post->post_comment_likes = $post_comment_likes;
            $post->post_like_total = count($post_comment_likes);
            $post->user_post_like = $user_post_like;
        }        

        $this->posts = $posts;
    }

    public function render()
    {
        return view('livewire.admin.community.all-feed');
    }
}
