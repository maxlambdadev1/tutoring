<div x-data="init" class="bg-light" style="min-height: 100vh;">
    <div x-show="step == 0" class="w-100 h-100 position-fixed p-3" style="background-image: url(/images/recruiter_register.jpg); background-size: cover; background-position: center;">
        <div class="text-center mt-0 mt-md-2">
            <img src="/images/logo_white.png" width="200" />
        </div>
        <div class="row text-center text-white">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h3 class="mb-4 fst-italic">Register as an Alchemy recruiter.</h3>
                <p class="mb-0">Refer your friends to tutor with us and get paid when they come on board!</p>
                <p class="mb-0">You don't have to be an Alchemy tutor to be a recruiter - simply share your unique referral code far and wide and earn referral fees from everyone that uses it!</p>
                <p class="mb-4">Registration will take 5 minutes, followed by a 5 minute video taking you through how it all works.</p>
                <p class="fw-bold mb-0">To register you will need:</p>
                <p class="mb-0">Your personal details such as name, phone number, email and address.</p>
                <p class="mb-0">Your BSB and account number to receive payments.</p>
                <p class="mb-0">As a commission-only contractor, you'll require an ABN(Australian Business Number). If you don’t have this already, we will talk you through it.</p>
                <p class="mb-4">It takes just a few minutes to get online and is totally free!</p>
                <button type="button" class="btn btn-outline-light" x-on:click="toStep1()"><span class="fs-3 fst-italic">Get Started</span></button>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row mb-5 bg-white">
            <div class="col-md-3 text-center py-2 py-md-3" :class="{'bg-info' : step == 1, 'cursor-pointer' : 1 <= max_step}" x-on:click="changeStep(1)">
                <div class="fs-4 fw-bold">Step 1</div>
                <div>Login details</div>
            </div>
            <div class="col-md-3 text-center py-2 py-md-3" :class="{'bg-info' : step == 2, 'cursor-pointer' : 2 <= max_step}" x-on:click="changeStep(2)">
                <div class="fs-4 fw-bold">Step 2</div>
                <div>Payment information</div>
            </div>
            <div class="col-md-3 text-center py-2 py-md-3" :class="{'bg-info' : step == 3, 'cursor-pointer' : 3 <= max_step}" x-on:click="changeStep(3)">
                <div class="fs-4 fw-bold">Step 3</div>
                <div>Recruiter Agreement</div>
            </div>
            <div class="col-md-3 text-center py-2 py-md-3" :class="{'bg-info' : step == 4, 'cursor-pointer' : 4 <= max_step}" x-on:click="changeStep(4)">
                <div class="fs-4 fw-bold">Step 4</div>
                <div>Confirm your detail</div>
            </div>
        </div>
    </div>
    <div x-show="step == 1" class="container-fluid bg-white py-3">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="fs-2 fw-bold text-center mb-1">Your details</div>
                <p class="text-center">You will use these details to access your account.</p>
                <div class="border p-3">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="first_name" class="form-label">First name</label>
                            <input type="text" class="form-control " name="first_name" id="first_name" wire:model="first_name" x-model="first_name">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="last_name" class="form-label">Last name</label>
                            <input type="text" class="form-control " name="last_name" id="last_name" wire:model="last_name" x-model="last_name">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control " name="email" id="email" wire:model="email" x-model="email">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control " name="phone" id="phone" wire:model="phone" x-model="phone">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control " name="address" id="address" wire:model="address" x-model="address">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="suburb" class="form-label">Suburb</label>
                            <input type="text" class="form-control " name="suburb" id="suburb" wire:model="suburb" x-model="suburb">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="postcode" class="form-label">Postcode</label>
                            <input type="text" class="form-control " name="postcode" id="postcode" wire:model="postcode" x-model="postcode">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control " name="state" id="state" wire:model="state" x-model="state">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-group input-group-merge">
                                <input wire:model="password" x-model="password" type="password" id="password" class="form-control" name="password" required>
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="password" class="form-label">{{ __('Confirm password') }}</label>
                            <div class="col-md-6 mb-2 input-group input-group-merge">
                                <input wire:model="password_confirmation" x-model="password_confirmation" type="password" id="password_confirmation" class="form-control " name="password_confirmation">
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep2">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-show="step == 2" class="container-fluid bg-white py-3">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="fs-2 fw-bold text-center mb-1">Payment information</div>
                <div class="border p-3">
                    <p class="fw-bold mb-3">
                        As you are engaged on a commission only basis, we require an ABN (an Australian Business Number) to process payments to your account. This is not an offer of employment - you have complete freedom to put in as much or as little work as you choose, and your payments are based entirely on the effort you put in. Having an ABN gives you this flexibility.
                    </p>
                    <p>
                        Obtaining an ABN online is free of charge and should take no more than a few minutes. In most cases, you will establish yourself as a sole-trader using your own name as the business.
                    </p>
                    <p>
                        <a href="https://drive.google.com/file/d/1dMeM-qFadRQDk4yOg0xK3JVuEYpG-hiB/view?usp=sharing" target="_blank">Click here for a step by step guide to obtaining your ABN</a>
                    </p>
                    <p>
                        Please note that payments can not be processed without an ABN. If you are unable to provide an ABN at this time, please return to register again once you have it sorted.
                    </p>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="ABN" class="form-label">ABN</label>
                            <input type="text" class="form-control " name="ABN" id="ABN" wire:model="ABN" x-model="ABN">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="bank_account_name" class="form-label">Bank account name</label>
                            <input type="text" class="form-control " name="bank_account_name" id="bank_account_name" wire:model="bank_account_name" x-model="bank_account_name">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="bsb" class="form-label">BSB</label>
                            <input type="text" class="form-control " name="bsb" id="bsb" wire:model="bsb" x-model="bsb">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="bank_account_number" class="form-label">Account number</label>
                            <input type="text" class="form-control " name="bank_account_number" id="bank_account_number" wire:model="bank_account_number" x-model="bank_account_number">
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep3">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-show="step == 3" class="container-fluid bg-white py-3">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="fs-2 fw-bold text-center mb-1">Recruiter Agreement</div>
                <div class="border p-3">
                    <div class="text-center mb-3">
                        This is our recruiter agreement which outlines payment conditions, do's and dont's, and other important details you will need to know in working with us. Please take a few minutes to read through the agreement and add your signature at the bottom.
                    </div>
                    <iframe loading="lazy" width="100%" height="900" src="https://tutorhub.alchemytuition.com.au/wp-content/plugins/pdf-poster/pdfjs-new/web/viewer.html?file=https://tutorhub.alchemytuition.com.au/wp-content/uploads/2021/12/Alchemy-Tuition-Recruiter-Agreement.pdf&download=true&print=false&openfile=false"></iframe>
                    <div class="signature mb-3 text-center mx-auto" style="width:200px;">
                        <div class="sigPad">
                            <p>Please sign below to show you have read and accept the terms of the recruiter agreement</p>
                            <p class="drawItDesc border-top pt-2">Please draw your signature</p>
                            <div class="sig sigWrapper border">
                                <canvas class="pad" width="198" height="55"></canvas>
                                <input type="hidden" name="output" class="output" id="sign_output" x-ref="sign_output">
                            </div>
                            <div class="clearButton mt-2"><a href="#clear">Clear</a></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep4">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-show="step == 4" class="container-fluid bg-white py-3">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="fs-2 fw-bold text-center mb-1">Confirm your details</div>
                <div class="border p-3">
                    <p class="fw-bold mb-3">Please review all your details and if correct, hit submit</p>
                    <div class="row">
                        <div class="col-md-6 fw-bold">First name</div>
                        <div class="col-md-6" x-text="first_name"></div>
                        <div class="col-md-6 fw-bold">Last name</div>
                        <div class="col-md-6" x-text="last_name"></div>
                        <div class="col-md-6 fw-bold">Email</div>
                        <div class="col-md-6" x-text="email"></div>
                        <div class="col-md-6 fw-bold">Phone</div>
                        <div class="col-md-6" x-text="phone"></div>
                        <div class="col-md-6 fw-bold">State</div>
                        <div class="col-md-6" x-text="state"></div>
                        <div class="col-md-6 fw-bold">Address</div>
                        <div class="col-md-6" x-text="address"></div>
                        <div class="col-md-6 fw-bold">Suburb</div>
                        <div class="col-md-6" x-text="suburb"></div>
                        <div class="col-md-6 fw-bold">Postcode</div>
                        <div class="col-md-6" x-text="postcode"></div>
                        <div class="col-md-6 fw-bold">ABN</div>
                        <div class="col-md-6" x-text="ABN"></div>
                        <div class="col-md-6 fw-bold">Bank account name</div>
                        <div class="col-md-6" x-text="bank_account_name"></div>
                        <div class="col-md-6 fw-bold">BSB</div>
                        <div class="col-md-6" x-text="bsb"></div>
                        <div class="col-md-6 fw-bold">Bank account number</div>
                        <div class="col-md-6" x-text="bank_account_number"></div>
                        <div class="col-md-6 fw-bold">Signature</div>
                        <div class="col-md-6 review_sigPad">
                            <canvas class="pad" id="pad_review" width="198" height="55" x-ref="pad_review"></canvas>
                            <input type="hidden" name="signature_img" id="signature_img" x-model="signature_img">
                        </div>
                        <div class="col-md-12 text-center mt-3">
                            <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }

    function checkPhone(phone) {
        // phone = '+61' + phone
        var pattern = /^(?:\+?(61))? ?(?:\((?=.*\)))?(0?[2-57-8])\)? ?(\d\d(?:[- ](?=\d{3})|(?!\d\d[- ]?\d[- ]))\d\d[- ]?\d[- ]?\d{3})$/;
        return pattern.test(phone);
    }

    function isNumeric(value) {
        return /^-?\d+$/.test(value);
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('init', () => ({
            step: 0,
            max_step: 0,
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            state: '',
            address: '',
            suburb: '',
            postcode: '',
            ABN: '',
            bank_account_name: '',
            bsb: '',
            bank_account_number: '',
            password: '',
            password_confirmation: '',
            signature_img: '',
            init() {
                $('.sigPad').signaturePad({
                    drawOnly: true
                });
            },
            goStep(step) {
                this.step = step;
            },
            changeStep(step) {
                if (step <= this.max_step) this.goStep(step);
            },
            toStep1() {
                this.goStep(1);
                this.max_step = 1;
            },
            async toStep2() {
                try {

                    if (!this.first_name) throw new Error('Please enter your first name');
                    if (!this.last_name) throw new Error('Please enter your last name');
                    if (!isValidEmailAddress(this.email)) throw new Error('Please enter a valid email address');
                    if (!checkPhone(this.phone)) throw new Error('Please enter a valid phone number');
                    if (!this.address) throw new Error('Please enter your address');
                    if (!this.suburb) throw new Error('Please enter valid suburb');
                    if (!this.postcode || !isNumeric(this.postcode)) throw new Error('Please enter a valid postcode');
                    if (!this.state) throw new Error('Please enter your state');
                    if (!this.password || this.password != this.password_confirmation) throw new Error("Passwords don't match");

                    let isExistingUser = await @this.call('checkUser');
                    if (!isExistingUser) {
                        this.goStep(2);
                        this.max_step = 2;
                    } else throw new Error('The user already existed.');

                } catch (error) {
                    toastr.error(error.message);
                }
            },
            toStep3() {
                try {
                    if (!this.ABN) throw new Error('Please enter a valid ABN');
                    if (!this.bank_account_name) throw new Error('Please enter a valid bank account');
                    if (!this.bsb) throw new Error('Please enter a valid BSB');
                    if (!this.bank_account_number) throw new Error('Please enter valid bank account number');

                    this.goStep(3);
                    this.max_step = 3;
                } catch (error) {
                    toastr.error(error.message);
                }
            },
            toStep4() {
                try {
                    if (!this.$refs.sign_output.value) throw new Error('Please add your signature.');

                    $('.review_sigPad').signaturePad({displayOnly:true, bgColour:'transparent', penColour:'#000'}).regenerate(this.$refs.sign_output.value);
                    var canvas = this.$refs.pad_review;
                    this.signature_img = canvas.toDataURL("image/png");

                    this.goStep(4);
                    this.max_step = 4;
                } catch (error) {
                    toastr.error(error.message);
                }
            },
            submit() {
                try {
                    if (!this.signature_img) throw new Error('Please add your signature.');
                    
                    @this.call('registerRecruiter', this.signature_img);
                } catch (error) {
                    toastr.error(error.message);
                }
            }
        }))
    })
</script>