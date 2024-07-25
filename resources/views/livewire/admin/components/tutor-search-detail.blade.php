<div>
    <div class="d-flex align-items-center">
        <img src="{{$tutor->getPhoto() ?? ''}}" class="rounded-circle" width="50" />
        <div class="ms-3">
            <p class="fw-bold fs-4">{{$tutor->tutor_name}}</p>
            <p class="text-muted">Tutor</p>
        </div>
    </div>
    <div class="accordion mt-4" id="accordionSearchResult">
        <div class="accordion-item">
            <h2 class="accordion-header mt-0" id="headingOne">
                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    PERSONAL INFORMATION
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionSearchResult">
                <div class="accordion-body">
                    <livewire:admin.components.tutor-personal-information tutor_id="{{$tutor->id}}" />
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header mt-0" id="headingTwo">
                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    PROFILE INFORMATION
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionSearchResult">
                <div class="accordion-body">
                    <livewire:admin.components.tutor-profile-information tutor_id="{{$tutor->id}}" />
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header mt-0" id="headingThree">
                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    STUDENTS INFORMATION
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionSearchResult">
                <div class="accordion-body">                    
                    <livewire:admin.components.students-search-table tutor_id="{{$tutor->id}}"/>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header mt-0" id="headingFour">
                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    SESSIONS INFORMATION
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionSearchResult">
                <div class="accordion-body">                    
                    <livewire:admin.components.sessions-search-table tutor_id="{{$tutor->id}}"/>
                </div>
            </div>
        </div>
    </div>
</div>