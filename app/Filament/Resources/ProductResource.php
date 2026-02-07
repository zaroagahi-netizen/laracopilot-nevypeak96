<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Term;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Information')
                    ->schema([
                        Forms\Components\TextInput::make('name.tr')
                            ->label('Name (Turkish)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name.ku-latn')
                            ->label('Name (Kurdish Latin)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name.en')
                            ->label('Name (English)')
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description.tr')
                            ->label('Description (Turkish)')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description.ku-latn')
                            ->label('Description (Kurdish Latin)')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description.en')
                            ->label('Description (English)')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pricing & Type')
                    ->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('Product Type')
                            ->options([
                                'physical' => 'Physical',
                                'digital' => 'Digital',
                            ])
                            ->required()
                            ->reactive()
                            ->default('physical'),
                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix('₺')
                            ->minValue(0),
                        Forms\Components\TextInput::make('compare_at_price')
                            ->label('Compare at Price')
                            ->numeric()
                            ->prefix('₺')
                            ->minValue(0)
                            ->helperText('Original price for discount display'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Stock & Inventory')
                    ->schema([
                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('Stock Quantity')
                            ->required(fn (Forms\Get $get) => $get('type') === 'physical')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->visible(fn (Forms\Get $get) => $get('type') === 'physical'),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'physical'),

                Forms\Components\Section::make('Digital Product')
                    ->schema([
                        Forms\Components\FileUpload::make('digital_file_path')
                            ->label('Digital File')
                            ->directory('digital-products')
                            ->visibility('private')
                            ->maxSize(102400),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'digital'),

                Forms\Components\Section::make('Age Range')
                    ->schema([
                        Forms\Components\TextInput::make('min_age')
                            ->label('Minimum Age')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(18)
                            ->suffix('years'),
                        Forms\Components\TextInput::make('max_age')
                            ->label('Maximum Age')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(18)
                            ->suffix('years'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Taxonomies')
                    ->schema([
                        Forms\Components\Select::make('age_groups')
                            ->label('Age Groups')
                            ->multiple()
                            ->relationship('terms', 'name')
                            ->options(
                                Term::byTaxonomy('age-group')
                                    ->ordered()
                                    ->pluck('name', 'id')
                            )
                            ->preload(),
                        Forms\Components\Select::make('development_areas')
                            ->label('Development Areas')
                            ->multiple()
                            ->relationship('terms', 'name')
                            ->options(
                                Term::byTaxonomy('development-area')
                                    ->ordered()
                                    ->pluck('name', 'id')
                            )
                            ->preload(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Product Images')
                            ->multiple()
                            ->image()
                            ->reorderable()
                            ->maxFiles(5)
                            ->directory('products')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_new')
                            ->label('Mark as New')
                            ->default(false),
                        Forms\Components\Toggle::make('is_customizable')
                            ->label('Customizable')
                            ->default(false),
                        Forms\Components\Toggle::make('active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primary_image')
                    ->label('Image')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'physical',
                        'info' => 'digital',
                    ]),
                Tables\Columns\TextColumn::make('price')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->is_digital ? '∞' : $record->stock_quantity)
                    ->color(fn ($record) => match($record->stock_status) {
                        'out_of_stock' => 'danger',
                        'low_stock' => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\IconColumn::make('is_new')
                    ->label('New')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_customizable')
                    ->label('Custom')
                    ->boolean(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'physical' => 'Physical',
                        'digital' => 'Digital',
                    ]),
                Tables\Filters\TernaryFilter::make('active'),
                Tables\Filters\TernaryFilter::make('is_new')
                    ->label('New Products'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}