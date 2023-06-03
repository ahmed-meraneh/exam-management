<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectResource\Pages;
use App\Models\Subject;
use Exception;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $label = 'Matière';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('ref')
                            ->label('Code')
                            ->required(),
                        TextInput::make('name')
                            ->required()
                            ->label('Intitulé'),
                        Select::make('branch')
                            ->label('Filière')
                            ->required()
                            ->relationship('branch', 'name'),
                        Select::make('teacher')
                            ->label('Enseignant')
                            ->required()
                            ->relationship('teacher', 'name', function (Builder $query) {
                                $query->whereRelation('roles', 'name', '=', 'professeur');
                            })
                            ->preload(),
                    ])->columns(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ref')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Intitulé')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Filière')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('teacher.name')
                    ->label('Enseignant'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
