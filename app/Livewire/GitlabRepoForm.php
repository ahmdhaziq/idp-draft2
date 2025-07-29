<?php

namespace App\Livewire;

use App\Http\Controllers\RequestsController;
use App\Models\ServiceAssets;
use App\Models\services;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Livewire\Component;

class GitlabRepoForm extends Component implements HasForms
{

    use InteractsWithForms;

    public function mount(): void
    {
        $this->form->fill();
    }
    public ?array $data;

    

    public function form(Form $form) {
        
        $userid = auth()->user()->id;
        $serviceId = services::where('service_identifier','gitlab_repo')->value('id');
        return $form
        ->schema([
            
        Hidden::make('userid')
        ->default($userid),

        Hidden::make('assets')
        ->default($serviceId),

        Select::make('assetId')
        ->label('Repository')
        ->options(
            ServiceAssets::query()
            ->where('serviceId','=',1)
            ->pluck('resource_name','id')
            ->toArray()
        )
        ->searchable()
        ->reactive(),

        Select::make('access_level')
        ->options(
            
            /*function(Get $get){
            $assetId = $get('assetId');
            if (!$assetId){
                return [];
            }
            $asset = ServiceAssets::find($assetId);
            $access = $asset->metadata;
            $accessLevel = $access['access_level'];
            return collect($accessLevel)
            ->mapWithKeys(function ($value, $key) {
                return [$value => $key]; 
            })
            ->toArray(); 
            }*/
            
          [
             '10'=>'Guest',
             '15'=>'Planner',
             '20'=>'Reporter',
             '30'=>'Developer',
             '40'=>'Maintainer'
          ] 
    ),

        TextInput::make('context'),
        DateTimePicker::make('duration')
]) 

->statePath('data');
    
}

    public function save(){
        $data = $this->form->getState();
        RequestsController::newRequests($data);
        session()->flash('success',value: 'Requests Submitted Successfully');
    }

    public function render()
    {
        return view('livewire.gitlab-repo-form');
    }
}
