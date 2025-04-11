<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Livewire\Component;
use Livewire\WithPagination;

class TrainersIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    protected $queryString = ['search', 'status'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $trainers = Trainer::when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('specialization', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status === 'approved', function ($query) {
                $query->where('is_approved', true);
            })
            ->when($this->status === 'pending', function ($query) {
                $query->where('is_approved', false);
            })
            ->orderBy('name')
            ->paginate(10);
        
        return view('livewire.admin.trainers-index', [
            'trainers' => $trainers
        ])->layout('layouts.admin', [
            'header' => 'Zarządzanie trenerami'
        ]);
    }

    public function approveTrainer($id)
    {
        $trainer = Trainer::findOrFail($id);
        $trainer->is_approved = true;
        $trainer->save();
        
        session()->flash('success', "Trener {$trainer->name} został zatwierdzony.");
    }

    public function deleteTrainer($id)
    {
        $trainer = Trainer::find($id);
        if ($trainer) {
            $trainer->delete();
            session()->flash('success', 'Trener został usunięty.');
        }
    }
} 