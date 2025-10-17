<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company')
                    ->maxLength(255),
                Forms\Components\TextInput::make('job_title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'unqualified' => 'Unqualified',
                        'lost' => 'Lost',
                        'converted' => 'Converted',
                    ])
                    ->required()
                    ->default('new'),
                Forms\Components\Select::make('stage')
                    ->options([
                        'awareness' => 'Awareness',
                        'interest' => 'Interest',
                        'consideration' => 'Consideration',
                        'intent' => 'Intent',
                        'evaluation' => 'Evaluation',
                        'purchase' => 'Purchase',
                    ])
                    ->required()
                    ->default('awareness'),
                Forms\Components\TextInput::make('lead_score')
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('quality')
                    ->options([
                        'hot' => 'Hot',
                        'warm' => 'Warm',
                        'cold' => 'Cold',
                    ])
                    ->default('cold'),
                Forms\Components\TextInput::make('industry')
                    ->maxLength(255),
                Forms\Components\TextInput::make('estimated_value')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('budget_range')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('expected_close_date'),
                Forms\Components\TextInput::make('source')
                    ->maxLength(255),
                Forms\Components\TextInput::make('campaign')
                    ->maxLength(255),
                Forms\Components\TextInput::make('medium')
                    ->maxLength(255),
                Forms\Components\TextInput::make('referring_url')
                    ->maxLength(255),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->nullable(),
                Forms\Components\Textarea::make('address')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('last_contacted_at'),
                Forms\Components\DateTimePicker::make('next_followup_at'),
                Forms\Components\Select::make('converted_customer_id')
                    ->relationship('convertedCustomer', 'first_name')
                    ->nullable(),
                Forms\Components\DateTimePicker::make('converted_at'),
                Forms\Components\KeyValue::make('custom_fields'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stage')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lead_score')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quality')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estimated_value')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_close_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('convertedCustomer.first_name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('converted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
            'view' => Pages\ViewLead::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Lead';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Leads';
    }
}
