<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoResource\Pages;
use App\Filament\Resources\ProdutoResource\RelationManagers;
use App\Models\Categoria;
use App\Models\Produto;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)->schema([
                    Forms\Components\Select::make('categoria_id')
                        ->label('Categoria')
                        ->relationship('categoria', 'nome')
                        ->required(),
                    Forms\Components\TextInput::make('nome')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255),
                        Forms\Components\Grid::make(1)->schema([
                            Forms\Components\TextInput::make('quantidade_de_variacoes')
                                ->numeric()
                                ->label('Quantidade de Variações')
                                ->reactive()
                                ->required(),
                            Forms\Components\Repeater::make('varicoes')
                                ->label('Variações')
                                ->hidden(fn (Closure $get) => $get('quantidade_de_variacoes') === 0)
                                ->schema([
                                    Forms\Components\TextInput::make('nome')
                                        ->label('Nome')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('preco')
                                        ->numeric()
                                        ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator(',')
                                            ->mapToDecimalSeparator([','])
                                            ->minValue(0)
                                            ->maxValue(999999.99)
                                            ->normalizeZeros()
                                            ->padFractionalZeros()
                                            ->autofix()
                                            ->thousandsSeparator('.'),
                                        )
                                        ->label('Preço')
                                        ->required(),
                                ])
                                ->defaultItems(fn (Closure $get) => $get('quantidade_de_variacoes') ?? 0)
                                ->collapsible()
                        ]),
                        Forms\Components\Textarea::make('descricao')->label('Descrição'),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('categoria.nome')->label('Categoria'),
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome')
                    ->copyable()
                    ->copyMessage('Copiado!')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('descricao')->label('Descrição')->limit(50),
                Tables\Columns\TextColumn::make('preco')
                    ->money('BRL')
                    ->label('Preço'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i:s'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Excluído em')
                    ->dateTime('d/m/Y H:i:s'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('categoria_id')
                    ->multiple()
                    ->label('Categoria')
                    ->relationship('categoria', 'nome'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProdutos::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }
}
