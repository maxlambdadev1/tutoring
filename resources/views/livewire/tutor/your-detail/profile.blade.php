<div>
    @php
    $title = "Your tutor profile";
    $breadcrumbs = ["Your detail", "Profile"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="float-end ">
                            <a type="button" href="/tutor/{{$tutor->id}}" target="_blank" class="btn btn-danger waves-effect waves-light me-auto d-inline">
                                <i class="uil-eye">&nbsp;</i>Profile
                            </a>
                        </div>
                        <p>Your tutor profile is sent to a parent when you accept a student and is the very first impression you give them. We want them to have the confidence that you are THE tutor for the job, which you then backup in your welcome call and by creating an incredible first lesson experience for the student. </p>
                        <p>Please complete your details below. You can view a sample profile here.</p>
                        <p>Try to avoid any specific mentions of your age, the area you live in or school you went to - we want the parent to meet you at the first lesson without any assumptions.</p>
                    </div>
                    <hr />
                    <div class="row" x-data="{current_status: {{$current_status}}}">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="preferred_first_name" class="form-label">Your preferred first name</label>
                                <input type="text" class="form-control " name="preferred_first_name" id="preferred_first_name" wire:model="preferred_first_name">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="fw-bold mb-1">Your current status</div>
                            <div class="form-check mb-1">
                                <input type="radio" id="current_status1" name="current_status" class="form-check-input" value="1" 
                                    x-on:change="current_status = 1"
                                    wire:model="current_status">
                                <label class="form-check-label" for="current_status1">Currently studying at University (or planning to attend in the next 12 months)</label>
                            </div>
                            <div class="form-check mb-1">
                                <input type="radio" id="current_status2" name="current_status" class="form-check-input" value="2" 
                                    x-on:change="current_status = 2"
                                    wire:model="current_status">
                                <label class="form-check-label" for="current_status2">Graduated from University in the last 3 years</label>
                            </div>
                            <div class="form-check mb-1">
                                <input type="radio" id="current_status3" name="current_status" class="form-check-input" value="3" 
                                    x-on:change="current_status = 3"
                                    wire:model="current_status">
                                <label class="form-check-label" for="current_status3">Other</label>
                            </div>
                        </div>
                        <div class="col-md-6" x-show="current_status == 1">
                            <div class="mb-3">
                                <label for="degree1" class="form-label">What is your degree</label>
                                <input type="text" class="form-control " name="degree1" id="degree1" wire:model="degree">
                            </div>
                        </div>
                        <div class="col-md-6" x-show="current_status == 1">
                            <div class="mb-3">
                                <label for="currentstudy1" class="form-label">What university are you attending</label>
                                <input type="text" class="form-control " name="currentstudy1" id="currentstudy1" wire:model="currentstudy">
                            </div>
                        </div>
                        <div class="col-md-6" x-show="current_status == 2">
                            <div class="mb-3">
                                <label for="degree2" class="form-label">What degree do you hold</label>
                                <input type="text" class="form-control " name="degree2" id="degree2" wire:model="degree">
                            </div>
                        </div>
                        <div class="col-md-6" x-show="current_status == 2">
                            <div class="mb-3">
                                <label for="currentstudy2" class="form-label">What University did you graduate from</label>
                                <input type="text" class="form-control " name="currentstudy2" id="currentstudy2" wire:model="currentstudy">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="career" class="form-label">What is your goal career:</label>
                                <input type="text" class="form-control " name="career" id="career" wire:model="career">
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <hr class="d-md-none" />
                        <div class="col-md-6 mb-3">
                            <div class="d-block d-md-flex justify-content-start align-items-center">
                                <div class="d-flex justify-content-start align-items-center mb-1">
                                    <span class="fw-bold" style="width:110px;">My favourite &nbsp;</span>
                                    <select name="favourite_item" id="favourite_item" class="form-select d-inline" wire:model="favourite_item" style="width:110px;">
                                        <option value="sport">sport</option>
                                        <option value="movie">movie</option>
                                        <option value="author">author</option>
                                        <option value="band">band</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-start align-items-center mb-1">
                                    <span class="fw-bold d-none d-md-block">&nbsp; is &nbsp;</span>
                                    <span class="fw-bold d-block d-md-none text-center" style="width:110px;">&nbsp; is &nbsp;</span>
                                    <input type="text" class="form-control d-inline" name="favourite_content" id="favourite_content" wire:model="favourite_content" style="width:110px;">
                                </div>
                            </div>
                        </div>
                        <hr class="d-md-none" />
                        <div class="col-12 fw-bold mb-2">
                            Your favourite book of all time:
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="book_author" class="form-label">Title</label>
                                <input type="text" class="form-control " name="book_author" id="book_author" wire:model="book_author">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="book_title" class="form-label">Author</label>
                                <input type="text" class="form-control " name="book_title" id="book_title" wire:model="book_title">
                            </div>
                        </div>
                        <hr class="d-md-none" />
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="achivement" class="form-label">When I was in school, my greatest accomplishment was...</label>
                                <textarea class="form-control" wire:model="achivement" id="achivement" rows="3"></textarea>
                            </div>
                        </div>
                        <hr class="d-md-none" />
                        <div class="col-12 fw-bold mb-2">
                            Your three biggest hobbies:
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label for="hobbies_1" class="form-label">1.</label>
                                <input type="text" class="form-control " name="hobbies_1" id="hobbies_1" wire:model="hobbies_1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label for="hobbies_2" class="form-label">2.</label>
                                <input type="text" class="form-control " name="hobbies_2" id="hobbies_2" wire:model="hobbies_2">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="hobbies_3" class="form-label">3.</label>
                                <input type="text" class="form-control " name="hobbies_3" id="hobbies_3" wire:model="hobbies_3">
                            </div>
                        </div>
                        <hr class="d-md-none" />
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="great_tutor" class="form-label">I think what makes me a great tutor is...</label>
                                <textarea class="form-control" wire:model="great_tutor" id="great_tutor" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="form-check">
                                    <input type="checkbox" id="vaccinated" name="vaccinated" class="form-check-input" wire:model="vaccinated">
                                    <label for="vaccinated" class="form-check-label">I am fully vaccinated against Covid 19</label>
                                </div>
                            </div>
                            <p>Choosing to disclose your vaccination status is optional, and we do not require proof of vaccination (but a student or family may want to see it at your first lesson). Indicating you have been vaccinated will add a banner to your profile and allow you to take student opportunities where a parent has specifically requested a tutor vaccinated against Covid 19.</p>
                        </div>
                        <div class="col-12">
                            <p class="fw-bold">Profile picture</p>
                            <p class="mb-3">Please select an image that is bright, clear and shows your positive attitude. Avoid using school photos, nightclub photos or anything that a parent may not want to see.</p>
                            <div style="width:200px;">
                                <input type="file" class="profile-pond" name="profile-image" id="profile-image" accept="image/*" data-max-file-size="10MB" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm" id="updateProfile">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script type="module">
        import {
            // // Image editor
            openEditor,
            processImage,
            createDefaultImageReader,
            createDefaultImageWriter,
            createDefaultImageOrienter,

            // // Only needed if loading legacy image editor data
            legacyDataToImageState,

            // // Import the editor default configuration
            getEditorDefaults,

        } from "{{asset('vendor/filepond/assets/pintura.js')}}";
    $(function() {
        let profile_server_id = '';
        // First register any plugins
        $.fn.filepond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginImageEditor,
            FilePondPluginFilePoster            
        );

        const filepondOptions = {
            // labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
            credits: false,
            server: {
                url: '/filepond',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            },
            imagePreviewHeight: 170,
            imageCropAspectRatio: '1:1',
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,
            stylePanelLayout: 'compact circle',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
            imageTransformOutputMimeType: 'image/webp',
            onaddfile: (error, file) => {
                console.log('add', file);
                if (error) {
                    console.error('Oh no', error);
                    return;
                }
                $("button[type=submit]").prop('disabled', true);
            },
            onprocessfile: (error, file) => {
                if (error) {
                    console.error('Oh no', error);
                    return;
                }

                $("button[type=submit]").prop('disabled', false);
                console.log('process', file);
                profile_server_id = file.serverId;
            },
            onremovefile: (error, file) => {
                if (error) {
                    console.log('Oh no', error);
                    return;
                }
                $("button[type=submit]").prop('disabled', false);
                console.log('deleted', file);
                profile_server_id = '';
            },
            // FilePond generic properties

            imageResizeTargetWidth: 1024,
            imageResizeMode: 'cover',
            imageResizeUpscale: false,
            allowFilePoster : true,
            allowImageEditor : true,
            filePosterMaxHeight: 256,

            // FilePond Image Editor plugin properties
            imageEditor: {
                // Maps legacy data objects to new imageState objects (optional)
                legacyDataToImageState: legacyDataToImageState,

                // Used to create the editor (required)
                createEditor: openEditor,

                // Used for reading the image data. See JavaScript installation for details on the `imageReader` property (required)
                imageReader: [
                    createDefaultImageReader,
                ],

                // Can leave out when not generating a preview thumbnail and/or output image (required)
                imageWriter: [
                    createDefaultImageWriter,
                    {
                        // We'll resize images to fit a 512 × 512 square
                        targetSize: {
                            width: 512,
                            height: 512,
                        },
                    },
                ],

                // Used to generate poster images, runs an invisible "headless" editor instance. (optional)
                imageProcessor: processImage,

                // Pintura Image Editor options
                editorOptions: {
                    ...getEditorDefaults(),
                    // // This will set a square crop aspect ratio
                    imageCropAspectRatio: 1,
                }
            }
        };

        $('.profile-pond').filepond(filepondOptions);

        $('#updateProfile').on('click', function() {
            @this.call('updateProfile', profile_server_id);
        })
    })
    
</script>