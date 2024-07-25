<div>
    <div class="">
        <p class="fw-bold fs-4">{{$parent->parent_name}}</p>
        <p class="text-muted">Parent</p>
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
                    <livewire:admin.components.parent-personal-information parent_id="{{$parent->id}}" />
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
                    <livewire:admin.components.students-search-table parent_id="{{$parent->id}}"/>
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
                    <livewire:admin.components.sessions-search-table parent_id="{{$parent->id}}"/>
                </div>
            </div>
        </div>
    </div>
</div>