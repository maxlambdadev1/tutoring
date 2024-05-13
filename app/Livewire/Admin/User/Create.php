<?php

namespace App\Livewire\Admin\User;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Validate; 
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\AdminRole;
use App\Models\Admin;
use App\Models\User;


#[Layout('admin.layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    #[Validate('required|unique:users|max:255|email')]
    public $email;

    #[Validate('required|max:255')]
    public $first_name;
    
    #[Validate('required|max:255')]
    public $last_name;
    
    #[Validate('nullable|unique:admins|max:15')]
    public $phone;

    #[Validate('required|max:255|min:6|same:password_confirmation')]
    public $password;

    public $password_confirmation;
    
    #[Validate('required|digits_between:1,3')]
    public $admin_role_id;
    
    #[Validate('nullable|image|max:1024')]
    public $photo;

    public function create() {
        $this->validate();

        try {
            $body = [
                'email' => $this->email,
                'first_name'=> $this->first_name,
                'last_name'=> $this->last_name,
                'phone'=> $this->phone,
                'password'=> $this->password,
                'role' => 1, //admin
                'admin_role_id'=> $this->admin_role_id,
                'photo'=> '',
                'user_id' => ''
            ];

            $user = new User();
            $user->storeUser($body);

            $directory = "uploads/" . $user->email;
            $photo_url = "";
            if ($this->photo) {
                $extension = $this->photo->getClientOriginalExtension();
                $image_name = $user->email . "-profile" . "." . $extension;
                $this->photo->storeAs(path: "public/" . $directory, name: $image_name); 
                $photo_url = $directory . "/" . $image_name;
            }
            $admin = new Admin();
            $body['photo'] = $photo_url;
            $body['user_id'] = $user->id;
            $admin->storeAdmin($body);

            $this->resetValues();
            return redirect()->back()->with('info', __('New member has been registered successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

    }

    public function render()
    {
        $admin_roles = AdminRole::get();
        return view('livewire.admin.user.create', compact('admin_roles'));
    }

    private function resetValues() {
        $this->email = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->phone = "";
        $this->admin_role_id = "";
        $this->password = "";
        $this->password_confirmation = "";
        $this->photo = null;
    }
}
