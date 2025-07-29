<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GitlabRepoFormPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.gitlab-repo-form-page';

    protected static ?string $title = 'Request Gitlab Repository';
}
