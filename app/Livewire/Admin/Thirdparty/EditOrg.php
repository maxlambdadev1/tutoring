<?php

namespace App\Livewire\Admin\Thirdparty;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Models\ThirdpartyOrganisation;
use App\Models\User;

#[Layout('admin.layouts.app')]
class EditOrg extends Component
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

    public $org;

    public function mount(ThirdpartyOrganisation $org) {
        $this->organisation_name = $org->organisation_name;
        $this->primary_contact_first_name = $org->primary_contact_first_name;
        $this->primary_contact_last_name = $org->primary_contact_last_name;
        $this->primary_contact_role = $org->primary_contact_role;
        $this->primary_contact_email = $org->primary_contact_email;
        $this->primary_contact_phone = $org->primary_contact_phone;
        $this->email_for_billing = $org->email_for_billing;
        $this->email_for_confirmations = $org->email_for_confirmations;
        $this->comment = $org->comment;
        $this->org = $org;
    }

    public function goBack() {
        return $this->redirect('/thirdparty/organisations', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.thirdparty.edit-org');
    }

    public function update()
    {
        $this->validate();

        try {
            $org = $this->org;
            $user = User::where('email', '=', $this->primary_contact_email)->first();
            if (!empty($user)) throw new \Exception("The Email already existed. Please use other one.");

            $compare_org = ThirdpartyOrganisation::where('id', '!=', $org->id)->where(function ($query) {
                $query->where('organisation_name', '=', $this->organisation_name)->orWhere('primary_contact_email', '=', $this->primary_contact_email)->orWhere('primary_contact_phone', '=', $this->primary_contact_phone); })->first();
            if (!empty($compare_org)) throw new \Exception("The Organisation info already existed.");

            $org->update([
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
            
            return redirect()->route('admin.thirdparty.organisations')->with('info', __("The organisation has been updated successfully!"));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

}
