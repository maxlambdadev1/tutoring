<?php

namespace App\Livewire\Admin\Components;

use App\Models\TutorApplication;
use App\Models\TutorApplicationReference;
use Livewire\Component;

class ApplicationDescription extends Component
{
    public $app;

    public function mount($app_id)
    {
        $app = TutorApplication::find($app_id);

        $tutor_reference1 = TutorApplicationReference::where('application_id', $app->id)->where('reference_email', $app->tutor_email1)->first();
        $app->tutor_reference1 = $tutor_reference1;
        $tutor_reference2 = TutorApplicationReference::where('application_id', $app->id)->where('reference_email', $app->tutor_email2)->first();
        $app->tutor_reference2 = $tutor_reference2;

        $this->app = $app;
    }

    public function render()
    {

        $shared_secret = 'happykahala!0987654321@1234567890#';
        $secret = sha1($this->app->id . $shared_secret);
        $register_url = 'https://alchemy.team/register?url=' . base64_encode('secret=' . $secret . '&application_id=' . $this->app->id);

        return view('livewire.admin.components.application-description', compact('register_url'));
    }
}
