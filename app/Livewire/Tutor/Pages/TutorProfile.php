<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Tutor;
use App\Models\TutorReview;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class TutorProfile extends Component
{    
    public $tutor;
    public $background_image;
    public $reviews;

    public function mount($tutor_id)
    {
        $this->tutor = Tutor::find($tutor_id);
        $background_images = [
            '/images/profile_back_1.jpg',
            '/images/profile_back_2.jpg',
            '/images/profile_back_3.jpg',
            '/images/profile_back_4.jpg',
        ];
        $this->background_image = $background_images[array_rand($background_images)];
        $this->reviews = TutorReview::where('tutor_id', $tutor_id)->where('approved', 1)->get();
    }

    public function render()
    {
        return view('livewire.tutor.pages.tutor-profile');
    }
}
