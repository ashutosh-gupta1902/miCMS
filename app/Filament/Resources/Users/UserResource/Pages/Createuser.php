<?php

namespace App\Filament\Resources\Users\UserResource\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class Createuser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
