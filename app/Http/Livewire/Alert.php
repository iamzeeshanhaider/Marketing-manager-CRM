<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Alert extends Component
{
    public $show = false;
    public $alertStatus, $here;
    public $alertMessage;
    public $timeout;

    protected $listeners = ['displayAlert'];

    public function displayAlert($status, $message)
    {
        $this->alertStatus = $status;
        $this->alertMessage = $message;
        $this->show = true;
        $this->timeout = 200;
    }

    public function closeAlert()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.alert');
    }
}
