<div>
    @php
    $title = "Update subjects";
    $breadcrumbs = ["Your detail", "Update subjects"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row" x-data="update_subjects_init">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="container">
                        <div class="row mb-3">
                            @foreach ($all_subjects as $subject)
                            <div class="col-12 col-md-4 py-1">
                                <div class="p-0 w-100">
                                    <input type="checkbox" class="d-none" id="subject-{{$loop->index}}" value="{{$subject}}" x-model="subjects" />
                                    <label for="subject-{{$loop->index}}" class="text-center border border-info cursor-pointer py-2 rounded-4 w-100">{{$subject}}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm" x-on:click="updateSubjects">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        input:checked+label {
            background-color: rgb(80, 165, 241);
            color: white;
        }
    </style>
</div>

<script>
    var subjects = @json($subjects);

    var update_subjects_init = () => {
        Alpine.data('update_subjects_init', () => ({
            subjects: subjects,
            updateSubjects() {
                @this.call('updateSubjects', this.subjects);
            }
        }))
    }
    
    if (typeof Alpine == 'undefined') {
        document.addEventListener('alpine:init', () => { update_subjects_init(); });
    } else {
        update_subjects_init();
    }
</script>