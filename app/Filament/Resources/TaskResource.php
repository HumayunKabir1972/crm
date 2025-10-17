<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Deal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->options([
                        'call' => 'Call',
                        'meeting' => 'Meeting',
                        'email' => 'Email',
                        'todo' => 'To Do',
                        'deadline' => 'Deadline',
                        'follow_up' => 'Follow Up',
                    ])
                    ->required()
                    ->default('todo'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Select::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ])
                    ->required()
                    ->default('medium'),
                Forms\Components\DateTimePicker::make('start_date'),
                Forms\Components\DateTimePicker::make('due_date'),
                Forms\Components\DateTimePicker::make('completed_at'),
                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->suffix('minutes'),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('created_by')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\MorphToSelect::make('taskable')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(\App\Models\Customer::class)
                            ->titleAttribute('first_name'),
                        Forms\Components\MorphToSelect\Type::make(\App\Models\Lead::class)
                            ->titleAttribute('first_name'),
                        Forms\Components\MorphToSelect\Type::make(\App\Models\Deal::class)
                            ->titleAttribute('title'),
                    ]),
                Forms\Components\DateTimePicker::make('reminder_at'),
                Forms\Components\Toggle::make('reminder_sent')
                    ->default(false),
                Forms\Components\TextInput::make('progress')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->default(0),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('attachments'),
                Forms\Components\KeyValue::make('custom_fields'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taskable_type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
            'view' => Pages\ViewTask::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Task';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tasks';
    }
}
