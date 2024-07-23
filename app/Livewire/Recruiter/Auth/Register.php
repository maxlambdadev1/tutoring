<?php

namespace App\Livewire\Recruiter\Auth;

use App\Models\User;
use App\Models\Recruiter;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use setasign\Fpdi\Tfpdf\Fpdi;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('recruiter.layouts.main')]
class Register extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
    public $suburb;
    public $postcode;
    public $state;
    public $password;
    public $password_confirmation;
    public $ABN;
    public $bank_account_name;
    public $bsb;
    public $bank_account_number;

    public function checkUser()
    {
        if (!empty($this->email)) {
            $user = User::where('email', $this->email)->first();
            if (!empty($user)) return true;
            else return false;
        } else return false;
    }

    public function registerRecruiter($signature_img)
    {
        try {
            // if (!$this->checkUser()) throw new \Exception('The user already existed');
            if (empty($signature_img)) throw new \Exception('You did not sign');

            $path = storage_path('app/public/uploads/sign/');
            if (!file_exists($path . urlencode($this->email))) {
                mkdir($path . urlencode($this->email), 0777, true);
            }
            $exp = explode(',', $signature_img);
            $data = base64_decode($exp[1]);
            $sign_photo = urlencode($this->email) . '/' . urlencode($this->email) . '_signature.png';
            $file = $path . $sign_photo;
            file_put_contents($file, $data);

            $current_date = date('d/m/Y');
            $fullname = $this->first_name . ' ' . $this->last_name;
            
            $pdf = new Fpdi();
            $pdf->AddPage();
            $current_x = 20;
            $font_family = 'Helvetica';
            $pdf->SetMargins(20,20,20);
            $pdf->SetFont($font_family, 'B', 14);
            $pdf->Cell(0, 20 , 'Alchemy Tuition Recruiter Agreement', 0, 1, 'C');
            $pdf->SetFont('Times','',12);
            $pdf->Write(7 , 'The individual named below agrees to all terms and conditions of the Alchemy Tuition recruiter agreement.');
            $pdf->Cell(0, 10 , '', 0, 1);
            $pdf->Cell(0, 20 , 'Subcontractor name:' . '   ' . $fullname, 0, 1);
            $pdf->Cell(0, 20 , 'On date:' . '   ' . $current_date, 0, 1);
            $pdf->Cell(0, 20 , 'Signature:   ', 0, 1);
            $pdf->Image($file, 45, 100);
            $signature = 'uploads/sign/'.urlencode($this->email).'/'.urlencode($this->email).'_signature_pdf.pdf';
            $pdf_path = storage_path('app/public/' . $signature);
            $pdf->Output('F', $pdf_path);
            unlink($file);


            $validated = $this->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);

            $validated['role'] = 3;    
            $validated['password'] = Hash::make($validated['password']);    
            event(new Registered($user = User::create($validated)));

			$referral_key = $this->getToken(5);
			while ($this->check_referral_key($referral_key)) {
				$referral_key = $this->getToken(5);
			}

            Recruiter::create([
                'user_id' => $user->id,
                'ABN' => $this->ABN,
                'bank_account_name' => $this->bank_account_name,
                'bsb' => $this->bank_account_number,
                'bank_account_number' => $this->bank_account_number,
                'signature' => $signature,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'status' => 1,
                'address' => $this->address,
                'suburb' => $this->suburb,
                'postcode' => $this->postcode,
                'referral_key' => $referral_key,
                'last_updated' => date('d/m/Y') 
            ]);

    
            Auth::login($user);    
            $this->redirect(RouteServiceProvider::HOME, navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.recruiter.auth.register');
    }
    
	private function check_referral_key($referral_key) {
		$referral = Recruiter::where('referral_key', $referral_key)->first();
		if (!empty($referral)) {
			return true;
		} else {
			return false;
		}
	}

	private function getToken($length)
	{
		$token = "";
		$codeAlpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codebet= "0123456789";

		for ($i=0; $i < $length; $i++) {
			$token .= $codebet[mt_rand(0, 9)];
		}

		return $codeAlpha[mt_rand(0,25)].$token;
	}
}
