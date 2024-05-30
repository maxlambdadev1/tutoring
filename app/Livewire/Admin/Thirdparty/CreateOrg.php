<?php

namespace App\Livewire\Admin\Thirdparty;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Models\ThirdpartyOrganisation;
use App\Models\User;

#[Layout('admin.layouts.app')]
class CreateOrg extends Component
{

    #[Validate('required|max:255')]
    public $organisation_name;

    #[Validate('required|max:255')]
    public $primary_contact_first_name;

    #[Validate('required|max:255')]
    public $primary_contact_last_name;
    public $primary_contact_role;

    #[Validate('required|max:15')]
    public $primary_contact_phone;

    #[Validate('required|max:255|email')]
    public $primary_contact_email;
    public $email_for_billing;
    public $email_for_confirmations;
    public $comment;

    public function render()
    {
        return view('livewire.admin.thirdparty.create-org');
    }

    public function create()
    {
        $this->validate();

        try {
            $user = User::where('email', '=', $this->primary_contact_email)->first();
            if (!empty($user)) throw new \Exception("The Email already existed. Please use other one.");

            $org = ThirdpartyOrganisation::where('organisation_name', '=', $this->organisation_name)->orWhere('primary_contact_email', '=', $this->primary_contact_email)->orWhere('primary_contact_phone', '=', $this->primary_contact_phone)->first();
            if (!empty($org)) throw new \Exception("The Organisation info already existed.");

            ThirdpartyOrganisation::create([
                'organisation_name' => $this->organisation_name,
                'primary_contact_first_name' => $this->primary_contact_first_name,
                'primary_contact_last_name' => $this->primary_contact_last_name,
                'primary_contact_role' => $this->primary_contact_role,
                'primary_contact_phone' => $this->primary_contact_phone,
                'primary_contact_email' => $this->primary_contact_email,
                'email_for_billing' => $this->email_for_billing,
                'email_for_confirmations' => $this->email_for_confirmations,
                'comment' => $this->comment,
            ]);

            $this->resetValues();
            return redirect()->back()->with('info', __('New organisation has been registered successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function viewOrganisation() {
        return $this->redirect('/thirdparty/organisations', navigate: true);
    }

    private function resetValues()
    {
        $this->organisation_name = "";
        $this->primary_contact_first_name = "";
        $this->primary_contact_last_name = "";
        $this->primary_contact_role = "";
        $this->primary_contact_email = "";
        $this->primary_contact_phone = "";
        $this->email_for_billing = "";
        $this->email_for_confirmations = "";
        $this->comment = "";
    }
}
