<?php

namespace App\Livewire\Tutor\Auth;

use App\Models\User;
use App\Models\State;
use App\Models\Availability;
use App\Models\Subject;
use App\Models\TutorApplication;
use App\Models\TutorApplicationStatus;
use App\Models\Tutor;
use App\Models\TutorSpecialReferralEmail;
use App\Providers\RouteServiceProvider;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\WithTutors;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use setasign\Fpdi\Tfpdf\Fpdi;
use RahulHaque\Filepond\Facades\Filepond;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class Register extends Component
{
    use Functions, Mailable, WithTutors;

    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $state;
    public $first_name;
    public $preferred_first_name;
    public $last_name;
    public $date_of_birth;
    public $gender;
    public $address;
    public $suburb;
    public $postcode;
    public $subjects = [];
    public $ABN;
    public $bank_account_name;
    public $bsb;
    public $bank_account_number;
    public $wwcc_fullname;
    public $wwcc_number;
    public $under_wwcc_check = false;
    public $current_status;
    public $degree;
    public $currentstudy;
    public $career;
    public $favourite_item;
    public $favourite_content;
    public $book_title;
    public $book_author;
    public $achivement;
    public $hobbies_1;
    public $hobbies_2;
    public $hobbies_3;
    public $great_tutor;
    public $vaccinated;

    public $states;
    public $all_subjects;
    public $total_availabilities = [];
    public $application;

    public function mount()
    {
        $url = request()->query('url') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = base64_encode($url);
            if (!empty($details)) {
                $exp = explode('&', $details);
                if (!empty($exp) && count($exp) >= 2) {
                    $secret = explode('=', $exp[0])[1] ?? '';
                    $application_id = explode('=', $exp[1])[1] ?? '';
                    if (!empty($application_id)) {
                        $secret_origin = sha1($application_id . env('SHARED_SECRET'));
                        if ($secret == $secret_origin) {
                            $this->application = TutorApplication::find($application_id);
                            if (!empty($this->application)) $flag = true;
                        }
                    }
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));
        // $application_id = 19;
        // if (!empty($application_id)) $this->application = TutorApplication::find($application_id);
        // if (empty($this->application))  $this->redirect(env('MAIN_SITE'));

        $this->states = State::get();
        $this->all_subjects = Subject::get() ?? [];
        $this->total_availabilities = Availability::get();
    }

    public function registerTutor($date_of_birth, $wwcc_expiry, $availabilities, $signature_img, $photo_image_id, $photo_back_image_id, $profile_image_id)
    {
        try {
            // if (!$this->checkUser()) throw new \Exception('The user already existed');
            if (empty($signature_img)) throw new \Exception('You did not sign');
            $user = $user = User::where('email', $this->email)->first();
            if (empty($this->email)) throw new \Exception("Please input email correctly");
            if (empty($photo_image_id)) throw new \Exception("Please add a photo of your ID");
            if (empty($profile_image_id)) throw new \Exception("Please add a photo of your profile");
            if (!empty($user)) throw new \Exception("The user already exist.");

            DB::beginTransaction();

            //add new user
            $validated = $this->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);
            $validated['role'] = 1;
            $validated['password'] = Hash::make($validated['password']);
            event(new Registered($user = User::create($validated)));

            $current_date = date('d/m/Y');
            $non_metro = 0;
            if (!empty($this->postcode) && !$this->checkMetro($this->postcode)) $non_metro = 1;
            $hobbies = $this->hobbies_1 . ';' . $this->hobbies_2 . ';' . $this->hobbies_3;
            $address = str_replace(" ", "+", $this->address . '+' . $this->suburb . '+' . $this->state . env('COUNTRY'));
            $coords = $this->getCoord($address);
            $lat = $coords['lat'] ?? '0';
            $lon = $coords['lon'] ?? '0';
            $availabilities_str = $this->generateBookingAvailability($availabilities);
            $gender = $this->gender ?? 'Male';
            $favourite = $this->favourite_item . ';' . $this->favourite_content;

            //create new tutor
            $tutor = Tutor::create([
                'user_id' => $user->id,
                'application_id' => $this->application->id ?? '',
                'ABN' => $this->ABN ?? '',
                'bank_account_name' => $this->bank_account_name ?? '',
                'bsb' => $this->bsb ?? '',
                'bank_account_number' => $this->bank_account_number ?? '',
                'tutor_name' => $this->first_name . ' ' . $this->last_name,
                'tutor_email' => $this->email ?? '',
                'tutor_phone' => $this->phone ?? '',
                'tutor_creat' => $current_date ?? '',
                'tutor_is_manager' => 0,
                'tutor_stripe_user_id' => '',
                'tutor_five_status' => 0,
                'stripe_publishable_key' => 0,
                'tutor_status' => 1,
                'wwcc_application_number' => '',
                'wwcc_fullname' => $this->wwcc_fullname ?? '',
                'wwcc_number' => $this->wwcc_number ?? '',
                'wwcc_expiry' => $wwcc_expiry ?? '',
                'address' => $this->address ?? '',
                'suburb' => $this->suburb ?? '',
                'state' => $this->state ?? '',
                'postcode' => $this->postcode ?? '',
                'tutor_state' => $this->state ?? '',
                'lat' => $lat ?? '',
                'lon' => $lon ?? '',
                'birthday' => $date_of_birth ?? '',
                'gender' => $gender ?? '',
                'preferred_first_name' => $this->preferred_first_name ?? '',
                'current_status' => $this->current_status ?? '',
                'degree' => $this->degree ?? '',
                'currentstudy' => $this->currentstudy ?? '',
                'career' => $this->career ?? '',
                'favourite' => $favourite ?? '',
                'book_title' => $this->book_title ?? '',
                'book_author' => $this->book_author ?? '',
                'hobbies' => $hobbies ?? '',
                'question2' => $this->great_tutor ?? '',
                'achivement' => $this->achivement ?? '',
                'expert_sub' => implode(',', $this->subjects) ?? '',
                'availabilities' => $availabilities_str ?? '',
                'online_acceptable_status' => 1,
                'vaccinated' => $this->vaccinated ? 1 : 0,
                'seeking_students' => 1,
                'seeking_students_timestamp' => time(),
                'non_metro' => $non_metro ?? '',
                'last_updated' => $current_date ?? '',
            ]);

            $photo_url = '';
            if (!empty($photo_image_id)) {
                $photo_url = 'uploads/' . $this->email . '/photo_id-' . $tutor->id;
                // Retrieve the file from Filepond, move to a temporary location
                $tmp_file_path = Filepond::field($photo_image_id)->moveTo($photo_url);
                // Create a path for the file in the temporary storage
                $tmp_storage_path = "app/public/" . $tmp_file_path['location'];
                $extension = pathinfo($tmp_storage_path,    PATHINFO_EXTENSION);
                $photo_url = $photo_url . '.' . $extension;
            }
            $photo_back_url = '';
            if (!empty($photo_back_image_id)) {
                $photo_back_url = 'uploads/' . $this->email . '/photo_id_back-' . $tutor->id;
                // Retrieve the file from Filepond, move to a temporary location
                $tmp_file_path = Filepond::field($photo_back_image_id)->moveTo($photo_back_url);
                // Create a path for the file in the temporary storage
                $tmp_storage_path = "app/public/" . $tmp_file_path['location'];
                $extension = pathinfo($tmp_storage_path,    PATHINFO_EXTENSION);
                $photo_back_url = $photo_back_url . '.' . $extension;
            }
            $profile_img_url = '';
            if (!empty($profile_image_id)) {
                $profile_img_url = 'uploads/' . $this->email . '/profile-' . $tutor->id;
                // Retrieve the file from Filepond, move to a temporary location
                $tmp_file_path = Filepond::field($profile_image_id)->moveTo($profile_img_url);
                // Create a path for the file in the temporary storage
                $tmp_storage_path = "app/public/" . $tmp_file_path['location'];
                $extension = pathinfo($tmp_storage_path,    PATHINFO_EXTENSION);
                $profile_img_url = $profile_img_url . '.' . $extension;
            }

            //save signature to pdf file
            $path = storage_path('app/public/uploads/sign/');
            if (!file_exists($path . urlencode($this->email))) {
                mkdir($path . urlencode($this->email), 0777, true);
            }
            $exp = explode(',', $signature_img);
            $data = base64_decode($exp[1]);
            $sign_photo = urlencode($this->email) . '/' . urlencode($this->email) . '_signature.png';
            $file = $path . $sign_photo;
            file_put_contents($file, $data);

            $fullname = $this->first_name . ' ' . $this->last_name;

            $pdf = new Fpdi();
            $pdf->AddPage();
            $current_x = 20;
            $font_family = 'Helvetica';
            $pdf->SetMargins(20, 20, 20);
            $pdf->SetFont($font_family, 'B', 14);
            $pdf->Cell(0, 20, 'Alchemy Tuition Tutor Agreement', 0, 1, 'C');
            $pdf->SetFont('Times', '', 12);
            $pdf->Write(7, 'The individual named below agrees to all terms and conditions of the Alchemy Tuition tutor agreement.');
            $pdf->Cell(0, 10, '', 0, 1);
            $pdf->Cell(0, 20, 'Subcontractor name:' . '   ' . $fullname, 0, 1);
            $pdf->Cell(0, 20, 'On date:' . '   ' . $current_date, 0, 1);
            $pdf->Cell(0, 20, 'Signature:   ', 0, 1);
            $pdf->Image($file, 45, 100);
            $signature = 'uploads/sign/' . urlencode($this->email) . '/' . urlencode($this->email) . '_signature_pdf.pdf';
            $pdf_path = storage_path('app/public/' . $signature);
            $pdf->Output('F', $pdf_path);
            unlink($file);

            $referral_key = '1' . $tutor->id;
            $tutor->update([
                'id_photo' => $photo_url,
                'id_photo_back' => $photo_back_url,
                'photo' => $profile_img_url,
                'signature' => $signature,
                'referral_key' => $referral_key
            ]);

            TutorSpecialReferralEmail::create([
                'tutor_id' => $tutor->id
            ]);

            $smsParams = [
                'name' => $this->first_name,
                'phone' => $this->phone
            ];
            $title = "Hi " . $this->first_name . ", welcome to Alchemy! Please save this number in your phone in case you need anything. You can text us at any time and a member of our team will respond ASAP! Let's do this!";
            $this->sendSms($smsParams, $title);

            TutorApplicationStatus::where('application_id', $this->application->id ?? '')->update([
                'application_status' => 5
            ]);

            if (!empty($this->application->tutor_referral)) {
                $tutor_referral = Tutor::where('referral_key', $this->application->tutor_referral)->whereNot('tutor_email', $this->email)->first();
                if (!empty($tutor_referral)) {
                    $today = new \DateTime('now');
                    $created = \DateTime::createFromFormat('d/m/Y', trim($tutor_referral->tutor_creat));
                    $interval = $today->diff($created);
                    if ($today > $created) {
                        if ($interval->days > 30) $this->referralXeroBill($tutor_referral, $tutor->tutor_name);
                        else {
                            if ($this->getReferralSpecial($tutor_referral->id)) {
                                $this->referralXeroBill($tutor_referral, $tutor->tutor_name, 1);
                            }
                        }
                    }
                }
            }
            if (!empty($this->application->tag)) {
                $tutor->update(['tag' => $this->application->tag]);
            }

            // if (empty($tutor->tutor_stripe_user_id)) $this->insertStripe();
            //add user to MailChimp

            DB::commit();

            // Create user in tutorhub -------------
            $user_name = env('TUTORHUB_USERNAME');
            $token = env('TUTORHUB_TOKEN');
            $endpoint = env('TUTORHUB_ENDPOINT');

            $unique_password = env('TUTORHUB_UNIQUE_PASSWORD');

            $user_info = array(
                'username' => preg_replace('/[^a-zA-Z0-9\']/u', '_', $this->email),
                'name' => $fullname,
                'email' => $this->email,
                'password' => $unique_password,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $user_name . ":" . $token);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($user_info));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            // Receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);

            $this->redirect(env('ONBOARDING_SITE'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tutor.auth.register');
    }
}
