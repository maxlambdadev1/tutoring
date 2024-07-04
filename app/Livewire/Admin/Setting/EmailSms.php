<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Option;
use App\Trait\Functions;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class EmailSms extends Component
{
    use Functions;

    public $email_name_lists = []; //email name array
    public $email_str = '';
    public $email_name = '';
    public $email_subject;
    public $email_content;

    public $sms_name_lists = []; //sms name array
    public $sms_str = '';
    public $sms_name = '';
    public $sms_content;

    public function mount() {
        $this->searchEmailByNameAndContent();
        $this->searchSMSByNameAndContent();
    }

    public function searchEmailByNameAndContent() {
        $str = '-subject';
        $options = Option::where('option_name', 'like', '%'.$str)->orderBy('option_name')->get();
        $email_name_lists = [];
        if (!empty($this->email_str)) {
            foreach ($options as $option) {
                $email_name = str_replace($str, '', $option->option_name);
                $email_subject = $this->getOption($option->option_value);
                $email_content = $this->getOption($email_name);
                if (strpos($email_subject, $this->email_str) !== false || strpos($email_content, $this->email_str)) {
                    $email_name_lists[] = $email_name;
                }
            }
        } else {
            foreach ($options as $option) {
                $email_name = str_replace($str, '', $option->option_name);
                $email_name_lists[] = $email_name;
            }
        }
        $this->email_name_lists = $email_name_lists;
    }
    
    public function saveEmail() {
        try {
            $str = '-subject';
            if (!empty($this->email_name)) {
                Option::updateOrCreate([
                    'option_name' => $this->email_name. $str
                ], [
                    'option_value' => $this->email_subject
                ]);
                Option::updateOrCreate([
                    'option_name' => $this->email_name 
                ], [
                    'option_value' => $this->email_content
                ]);
                
                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Saved successfully!'
                ]);
            } else throw new \Exception('Please select email!');
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function selectEmail($email_name) {
        $this->email_str = $email_name;
        $str = '-subject';
        $this->email_subject = $this->getOption($email_name . $str);
        $this->email_content = $this->getOption($email_name);
        $this->email_name = $email_name;
    }
    
    public function searchSMSByNameAndContent() {
        $str = '-sms';
        $options = Option::where('option_name', 'like', '%'.$str)->orderBy('option_name')->get();
        $sms_name_lists = [];
        if (!empty($this->sms_str)) {
            foreach ($options as $option) {
                $sms_name = $option->option_name;
                $sms_content = $option->option_value;
                if (strpos($sms_name, $this->sms_str) !== false || strpos($sms_content, $this->sms_str)) {
                    $sms_name_lists[] = $sms_name;
                }
            }
        } else {
            foreach ($options as $option) {
                $sms_name_lists[] = $option->option_name;
            }
        } 
        $this->sms_name_lists = $sms_name_lists;
    }

    public function saveSMS() {
        try {
            if (!empty($this->sms_name)) {
                Option::updateOrCreate([
                    'option_name' => $this->sms_name 
                ], [
                    'option_value' => $this->email_content
                ]);
                
                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Saved successfully!'
                ]);
            } else throw new \Exception('Please select sms!');
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function selectSMS($sms_name) {
        $this->sms_str = $sms_name;
        $this->sms_content = $this->getOption($sms_name);
        $this->sms_name = $sms_name;
    }

    public function render()
    {
        return view('livewire.admin.setting.email-sms');
    }
}
