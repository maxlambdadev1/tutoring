<?php

namespace App\Livewire\Admin\Payments;

use App\Exports\AuditExport;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class PriceAudit extends Component
{
    public $isLoading = false;

    /**
     * @param $start_date : string('d/m/Y'), $end_date: string('d/m/Y')
     */
    public function generateAudit($start_date, $end_date)
    {
        try {
            $excel = new AuditExport($start_date, $end_date);
            $this->isLoading = false;
            return $excel->download('audit.csv');
        } catch (\Exception $e) {
            $this->isLoading = false;
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.payments.price-audit');
    }
}
