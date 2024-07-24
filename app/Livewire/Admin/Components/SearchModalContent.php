<?php

namespace App\Livewire\Admin\Components;

use App\Models\Session;
use App\Trait\WithLeads;
use Livewire\Component;

class SearchModalContent extends Component
{
    use WithLeads;

    public $search_text;
    public $tutor_results = [];
    public $parent_results = [];
    public $child_results = [];

    public function searchAllMembers()
    {
        $tutor_results = [];
        $parent_results = [];
        $child_results = [];
        if (!empty($this->search_text)) {
            $tutors = $this->searchTutors($this->search_text, 5);
            if (!empty($tutors)) {
                foreach ($tutors as $tutor) {
                    $tutor->sessions = Session::whereNot('session_status', 6)->where('tutor_id', $tutor->id)->count();
                    $tutor_results[] = $tutor;
                }
            }

            $parents = $this->searchParents($this->search_text, 5);
            if (!empty($parents)) {
                foreach ($parents as $parent) {
                    $parent->sessions = Session::whereNot('session_status', 6)->where('parent_id', $parent->id)->count();
                    $parent_results[] = $parent;
                }
            }

            $children = $this->searchChildren($this->search_text, 5);
            if (!empty($children)) {
                foreach ($children as $child) {
                    $child->sessions = Session::whereNot('session_status', 6)->where('child_id', $child->id)->count();
                    $child_results[] = $child;
                }
            }
        }

        $this->tutor_results = $tutor_results;
        $this->parent_results = $parent_results;
        $this->child_results = $child_results;
    }


    public function render()
    {
        return view('livewire.admin.components.search-modal-content');
    }
}
