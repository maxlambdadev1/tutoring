<?php

namespace App\Livewire\Admin\Components;

use App\Models\User;
use App\Models\AlchemyParent;
use App\Models\ParentCc;
use App\Models\PriceParentDiscount;
use App\Models\ThirdpartyOrganisation;
use App\Trait\Functions;
use Livewire\Component;

class EditParentModal extends Component
{
    use Functions;

    public $parent;
    public $parent_name;
    public $parent_email;
    public $parent_cc;
    public $manual_payer = false;
    public $parent_phone;
    public $parent_address;
    public $parent_suburb;
    public $parent_postcode;
    public $parent_price;
    public $parent_apply_discount = false;
    public $discount_type = 'percentage';
    public $discount_amount;
    public $thirdparty_org_id;




    public function mount($parent_id)
    {
        $parent = AlchemyParent::find($parent_id);
        if (!empty($parent)) {
            $this->parent = $parent;
            $this->parent_name = $parent->parent_first_name . ' ' . $parent->parent_last_name;
            $this->parent_email = $parent->parent_email;
            $this->parent_cc = !empty($parent->parent_cc) ? $parent->parent_cc->cc : '';
            if (stripos($parent->stripe_customer_id, 'Manual') !== false) $this->manual_payer = true;
            else $this->manual_payer = false;
            $this->parent_phone = $parent->parent_phone;
            $this->parent_address = $parent->parent_address;
            $this->parent_suburb = $parent->parent_suburb;
            $this->parent_postcode = $parent->parent_postcode;
            $this->parent_price = $parent->parent_price;
            $this->thirdparty_org_id = $parent->thirdparty_org_id;

            if (!empty($parent->price_parent)) $this->parent_price = $parent->price_parent->f2f;
            $price_parent_discount = $parent->price_parent_discount;
            if (!empty($price_parent_discount)) {
                $this->parent_apply_discount = true;
                $this->discount_type = $price_parent_discount->discount_type;
                $this->discount_amount = $price_parent_discount->discount_amount;
            }
        }
    }

    public function updateParentDetail()
    {
        try {
            $parent = $this->parent;
            $parent_name_arr = explode(' ', $this->parent_name);
            $parent_first_name = $parent_name_arr[0] ?? '';
            $parent_last_name = $parent_name_arr[1] ?? '';

            $parent_stripe = $this->manual_payer ? 'Manual payer' : '';
            $coords = $this->getCoord(str_replace(' ', '+', $this->parent_address . '+' . $this->parent_suburb . '+NSW+Australia'));

            $user = $parent->user;
            if (empty($user)) {
                User::create([
                    'email' => $this->parent_email,
                    'password' => bcrypt('password'),
                    'role' => 2
                ]);
            } else {
                $user->update([
                    'email' => $this->parent_email
                ]);
            }

            $parent->update([
                'parent_first_name' => $parent_first_name,
                'parent_last_name' => $parent_last_name,
                'parent_email' => $this->parent_email,
                'parent_phone' => $this->parent_phone,
                'parent_address' => $this->parent_address,
                'parent_suburb' => $this->parent_suburb,
                'parent_postcode' => $this->parent_postcode,
                'parent_lat' => $coords['lat'],
                'parent_lon' => $coords['lon'],
                'thirdparty_org_id' => $this->thirdparty_org_id
            ]);

            if (!empty($parent_stripe)) $parent->update(['stripe_customer_id' => $parent_stripe]);

            ParentCc::updateOrCreate([
                'parent_id' => $parent->id
            ], [
                'cc' => $this->parent_cc
            ]);

            if ($this->parent_apply_discount && !empty($this->discount_type) && !empty($this->discount_amount)) {
                PriceParentDiscount::updateOrCreate([
                    'parent_id' => $parent->id
                ], [
                    'discount_type' => $this->discount_type,
                    'discount_amount' => $this->discount_amount
                ]);
            } else {
                PriceParentDiscount::where('parent_id', $parent->id)->delete();
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The parent was successfuly edited'
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
        $organisations = ThirdpartyOrganisation::get();

        return view('livewire.admin.components.edit-parent-modal', compact(['organisations']));
    }
}
