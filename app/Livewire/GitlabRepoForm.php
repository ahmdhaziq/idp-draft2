<?php

namespace App\Livewire;

use App\Handlers\AccessHandlers\GitlabHandlers;
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
use Filament\Notifications\Notification;
use Livewire\Component;

class GitlabRepoForm extends Component implements HasForms
{

    use InteractsWithForms;

    public function mount(): void
    {
        $this->form->fill();
    }

    public ?array $data;

    public bool $manualGitlabId = false;


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
        ->reactive()
        ->required(),

        Select::make('access_type')
                ->label('Access Type')
                ->options([
                    'Permanent' => 'Permanent',
                    'Temporary' => 'Temporary'
                ])
                ->reactive()
                ->required(),
                Select::make('access_action')
                ->label('Access Actions')
                ->options([
                    'New' => 'New',
                    'Modify' => 'Modify'
                ])
                ->required(),

        Select::make('access_level')
        ->label('Access Level')
        ->options(
            
            /*  function(Get $get){
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
            }    */
            
          [
             '10'=>'Guest',
             '15'=>'Planner',
             '20'=>'Reporter',
             '30'=>'Developer',
             '40'=>'Maintainer'
          ] 
    )
    ->required(),

        TextInput::make('context')
        ->required(),
        DateTimePicker::make('duration')
        ->visible(function(Get $get){
            $accessType = $get('access_type');
            if ($accessType=='Temporary'){
                return true;
            };
        })
        ->required(),

        TextInput::make('gitlabuserId')
        ->label('Gitlab User ID (If empty, system will attempt to find your ID using email registered with IDP.)')
        
       
        
]) 

->statePath('data');
    
}
   
    public function save(){
        $data = $this->form->getState();
        /*$gitlabuserId= ' ';
        if (empty($data['gitlabuserId'])){
          $gitlabuserId = RequestsController::getUserId($data['userid']);
        }else{
          $gitlabuserId = $data['gitlabuserId'];
        }

        if ($gitlabuserId == null){
            $this->manualGitlabId = true;
        session()->flash('success','No Gitlab User ID found with email, please fill in the form.');
            return;
        }else{
            $data['gitlabuserId'] = $gitlabuserId;
            RequestsController::newRequests($data);
            session()->flash('success',value: 'Requests Submitted Successfully');
        }*/

        $response = RequestsController::requestGitlabRepo($data)->getData(true);
        $message = $response['message'];

        if(isset($response['error'])){
            Notification::make()
        ->title($response['error'])
        ->body($message)
        ->danger()
        ->send();
        }else{
             Notification::make()
        ->title('Request Successfully Sent!')
        ->body($message)
        ->success()
        ->send();
        }
       
    
}



    public function render()
    {
        return view('livewire.gitlab-repo-form');
    }
}
