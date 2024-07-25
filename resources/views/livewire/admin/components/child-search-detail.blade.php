<div>
    <div class="">
        <p class="fw-bold fs-4">{{$child->child_name}}</p>
        <p class="text-muted">Child</p>
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
                    <livewire:admin.components.child-personal-information child_id="{{$child->id}}" />
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
                    <livewire:admin.components.sessions-search-table child_id="{{$child->id}}"/>
                </div>
            </div>
        </div>
    </div>
</div>