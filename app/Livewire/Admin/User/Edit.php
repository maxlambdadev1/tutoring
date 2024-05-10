<?php

namespace App\Livewire\Admin\User;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Validate; 
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\AdminRole;
use App\Models\Admin;
use App\Models\User;

#[Layout('admin.layouts.app')]
class Edit extends Component
{
    public $admin;
    
    use WithFileUploads;

    // #[Validate('required|unique:users|max:255|email')]
    public $email;

    #[Validate('required|max:255')]
    public $first_name;
    
    #[Validate('required|max:255')]
    public $last_name;
    
    // #[Validate('nullable|unique:admins|max:15')]
    public $phone;
    
    #[Validate('required|digits_between:1,3')]
    public $admin_role_id;
    
    #[Validate('nullable|image|max:1024')]
    public $photo;
    
    protected function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->admin->user->id), // Exclude the current user's ID
            ],
            'phone' => [
                'nullable',
                'max:15',
                Rule::unique('admins')->ignore($this->admin->id)
            ]
        ];
    }

    public function mount(Admin $admin) {
        $this->admin = $admin;
        $this->email = $admin->user->email;
        $name = explode(" ", $admin->admin_name);
        $this->first_name = $name[0];
        $this->last_name = $name[1];
        $this->phone = $admin->phone;
        $this->admin_role_id = $admin->admin_role_id;
    }
    
    public function save() {
        $this->validate();

        try {
            $body = [
                'email' => $this->email,
                'first_name'=> $this->first_name,
                'last_name'=> $this->last_name,
                'phone'=> $this->phone,
                'admin_role_id'=> $this->admin_role_id,
                'photo'=> $this->admin->photo
            ];

            $user = $this->admin->user;
            $user->email = $this->email;
            $user->save();

            $directory = "uploads/" . $user->email;
            if ($this->photo) {
                if ($this->admin->photo) Storage::delete($this->admin->photo);

                $extension = $this->photo->getClientOriginalExtension();
                $image_name = $user->email . "-profile" . "." . $extension;
                $this->photo->storeAs(path: "public/" . $directory, name: $image_name); 
                $body['photo'] = $directory . "/" . $image_name;
            }
            $body['user_id'] = $user->id;

            $this->admin->storeAdmin($body);

            return redirect()->route("admin.users")->with('info', __('The member has been updated successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

    }

    public function render()
    {
        $admin = $this->admin;
        $admin_roles = AdminRole::get();
        return view('livewire.admin.user.edit', compact('admin', 'admin_roles'));
    }
}
