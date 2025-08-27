<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;

class ToastNotifications extends Component
{
    public array $toasts = [];
    public int $nextId = 1;

    #[On('show-toast')]
    public function showToast($type, $message)
    {
        $this->toasts[] = [
            'id' => $this->nextId++,
            'type' => $type,
            'message' => $message,
            'timestamp' => now()
        ];
    }

    public function removeToast($id)
    {
        $this->toasts = array_filter($this->toasts, function($toast) use ($id) {
            return $toast['id'] !== $id;
        });
    }

    public function render()
    {
        return view('livewire.admin.toast-notifications');
    }
}
