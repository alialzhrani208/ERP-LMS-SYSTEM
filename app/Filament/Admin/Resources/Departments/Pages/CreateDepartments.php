<?php

namespace App\Filament\Admin\Resources\Departments\Pages;

use App\Filament\Admin\Resources\Departments\DepartmentsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartments extends CreateRecord
{
    protected static string $resource = DepartmentsResource::class;
}
