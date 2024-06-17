<?php

namespace App\Livewire\Admin\Wwcc;

use Illuminate\Support\Facades\DB;
use App\Models\Tutor;
use App\Models\TutorWwcc;
use App\Models\TutorWwccValidate;
use App\Trait\Functions;
use App\Trait\WithTutors;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class VerifyWwcc extends Component
{
    use Functions, WithTutors;
    public $tutor_id;
    public $tutor;
    public $unverified_tutors;

    public function mount() {
        $this->unverified_tutors = Tutor::query()
            ->leftJoin('alchemy_tutor_wwcc', function ($wwcc) {
                $wwcc->on('alchemy_tutor_wwcc.tutor_id', '=', 'tutors.id');
            })
            ->where('tutor_status', 1)
            ->whereNotNull('wwcc_number')
            ->where('wwcc_number', '!=', '')
            ->whereNull('verified_by')
            ->select('tutors.*', 'alchemy_tutor_wwcc.verified_on', 'alchemy_tutor_wwcc.verified_by')
            ->get();
    }

    public function selectTutor()
    {
        if (!empty($this->tutor_id)) $this->tutor = Tutor::find($this->tutor_id);
        else $this->tutor = null;
    }

    public function render()
    {
        return view('livewire.admin.wwcc.verify-wwcc');
    }

    public function blockOrUnblockFromJobs1($tutor_id)
    {
        try {
            $this->blockOrUnblockFromJobs($tutor_id);

            $this->tutor = $this->tutor->fresh();

            return redirect()->back()->with('info', __('Job accept status was updated successfully!'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    
    public function verifyWWCC1($tutor_id)
    {
        try {
            $this->verifyWWCC($tutor_id);
            
            $this->mount();

            return redirect()->back()->with('info', __('WWCC verified!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }
}
