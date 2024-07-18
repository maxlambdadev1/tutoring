<?php

namespace App\Livewire\Tutor\YourDetail;

use RahulHaque\Filepond\Facades\Filepond;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class Profile extends Component
{

    public $tutor;
    public $preferred_first_name;
    public $current_status;
    public $degree;
    public $currentstudy;
    public $career;
    public $favourite_item;
    public $favourite_content;
    public $book_author;
    public $book_title;
    public $achivement;
    public $hobbies_1;
    public $hobbies_2;
    public $hobbies_3;
    public $great_tutor;
    public $vaccinated;
    public $profile_server_id;


    public function mount() {
        $tutor = auth()->user()->tutor;
        $this->tutor = $tutor;
        $this->preferred_first_name = $tutor->preferred_first_name ?? '';
        $this->current_status = $tutor->current_status ?? 0;
        $this->degree = $tutor->degree ?? '';
        $this->currentstudy = $tutor->currentstudy ?? '';
        $this->career = $tutor->career ?? '';
        $this->favourite_item = explode(';', $tutor->favourite)[0] ?? '';
        $this->favourite_content = explode(';', $tutor->favourite)[1] ?? '';
        $this->book_author = $tutor->book_author ?? '';
        $this->book_title = $tutor->book_title ?? '';
        $this->achivement = $tutor->achivement ?? '';
        $this->hobbies_1 = explode(';', $tutor->hobbies)[0] ?? '';
        $this->hobbies_2 = explode(';', $tutor->hobbies)[1] ?? '';
        $this->hobbies_3 = explode(';', $tutor->hobbies)[2] ?? '';
        $this->great_tutor = $tutor->question2 ?? '';
        $this->vaccinated = !!$tutor->vaccinated ? true : false;
    }
    
    public function updateProfile($profile_server_id) {
        try {
            $photo_url = '';
            if (!empty($profile_server_id)) { //dd($profile_server_id);
                $photo_url = 'uploads/' . $this->tutor->tutor_email . '/profile-'. $this->tutor->id; 
                // Retrieve the file from Filepond, move to a temporary location
                $tmp_file_path = Filepond::field($profile_server_id)->moveTo($photo_url);
                // Create a path for the file in the temporary storage
                $tmp_storage_path = "app/public/" . $tmp_file_path['location'];
                $extension = pathinfo($tmp_storage_path,    PATHINFO_EXTENSION);
                $photo_url = $photo_url . '.' . $extension;
            }

            $this->tutor->update([
                'preferred_first_name' => $this->preferred_first_name,
                'current_status' => $this->current_status,
                'degree' => $this->current_status != 3 ? $this->degree : '',
                'currentstudy' => $this->currentstudy != 3 ? $this->degree : '',
                'career' => $this->career,
                'favourite' => $this->favourite_item . ';' . $this->favourite_content,
                'book_title' => $this->book_title,
                'book_author' => $this->book_author,
                'hobbies' => $this->hobbies_1 . ';' . $this->hobbies_2 . ';'. $this->hobbies_3,
                'question2' => $this->great_tutor,
                'achivement' => $this->achivement,
                'vaccinated' => $this->vaccinated,
                'photo' => $photo_url,
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'You have updated your profile!',
                'flag' => true
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tutor.your-detail.profile');
    }
}
