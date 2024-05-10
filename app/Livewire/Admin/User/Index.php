<?php

namespace App\Livewire\Admin\User;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\AdminRole;
use App\Models\Admin;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

#[Layout('admin.layouts.app')]
class Index extends Component
{

    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';


    public function render()
    {        
        $admin_roles = AdminRole::get();
        $admins = Admin::OrderBy('admin_role_id')->paginate(10);

        return view('livewire.admin.user.index', compact('admins', 'admin_roles'));
    }

    public function toggleActive(User $user) {
        $user->active = $user->active == 1 ? 0 : 1;
        $user->save();
    }

    public function deleteUser(Admin $admin) {
        try {
            $user = $admin->user;
            $user->delete();

            if ($admin->photo) Storage::delete($admin->photo);
            $admin->delete();

            return back()->with('info', __('The member has been deleted successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }
}
