<?php

namespace App\Livewire\Admin\Community;

use App\Models\AlchemyParent;
use App\Models\Session;
use App\Trait\Mailable;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class Feedback extends Component
{
    use Mailable;

    public $active_status = 'pending';


    public function changeStatus($status)
    {
        if ($this->active_status != $status) $this->active_status = $status;
    }

    public function sendTotmEmail()
    {
        try {
            $parents = AlchemyParent::get();
            foreach ($parents as $parent) {
                $price_tutors = $parent->price_tutors;
                if (!empty($price_tutors)) {
                    foreach ($price_tutors as $price_tutor) {
                        $child = $price_tutor->child;
                        if (!empty($child) && $child->child_status == 1) {
                            $count = Session::where('tutor_id', $price_tutor->tutor_id)
                                ->where('parent_id', $price_tutor->parent_id)
                                ->where('child_id', $price_tutor->child_id)
                                ->where('session_status', 2)
                                ->whereRaw("TIMESTAMPDIFF(DAY, STR_TO_DATE(session_date, '%d/%m/%Y'), STR_TO_DATE('" . date('d/m/Y') . "', '%d/%m/%Y')) <= 90")
                                ->count(); 

                            if ($count >= 3) {
                                $params = [
                                    'email' => $parent->parent_email,
                                    'parentfirstname' => $parent->parent_first_name,
                                    'feedbackurl' => 'https://alchemy.team/tutorvote?url=' . base64_encode('parent_id=' . $parent->id)
                                ];
                                $this->sendEmail($parent->parent_email, 'send-totm-email', $params);
                                break;
                            }
                        }
                    }
                }
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'It is processing successfully.'
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
        return view('livewire.admin.community.feedback');
    }
}
