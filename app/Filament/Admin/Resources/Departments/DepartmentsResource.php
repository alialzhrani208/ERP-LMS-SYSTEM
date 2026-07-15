<?php

namespace App\Filament\Admin\Resources\Departments;

use App\Filament\Admin\Resources\Departments\Pages\CreateDepartments;
use App\Filament\Admin\Resources\Departments\Pages\EditDepartments;
use App\Filament\Admin\Resources\Departments\Pages\ListDepartments;
use App\Filament\Admin\Resources\Departments\Pages\ViewDepartments;
use App\Filament\Admin\Resources\Departments\Schemas\DepartmentsForm;
use App\Filament\Admin\Resources\Departments\Schemas\DepartmentsInfolist;
use App\Filament\Admin\Resources\Departments\Tables\DepartmentsTable;
use App\Models\Departments;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DepartmentsResource extends Resource
{
    protected static ?string $model = Departments::class;
    
    protected static ?string $pluralModelLabel = 'الاقسام';
    protected static ?string $modelLabel = 'القسم';


    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return DepartmentsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DepartmentsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDepartments::route('/'),
            //'create' => CreateDepartments::route('/create'),
            //'view' => ViewDepartments::route('/{record}'),
            //'edit' => EditDepartments::route('/{record}/edit'),
        ];
    }
}
