<?php

namespace App\Livewire;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class GitlabRepoForm extends Component implements HasForms
{

    use InteractsWithForms;
    public ?array $data = [];

    protected function getFormSchema() {
        return [
            TextInput::make('test'),
        ];
    }
    public function render()
    {
        return view('livewire.gitlab-repo-form');
    }
}
