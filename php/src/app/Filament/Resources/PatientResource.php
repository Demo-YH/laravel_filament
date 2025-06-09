<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;
    protected static ?string $modelLabel = 'ペット情報';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                ->label('名前')
                ->required()        // 入力必須
                ->maxLength(255),   // 最長255文字

            // Selectbox
            Forms\Components\Select::make('type')
                ->label('種別')
                ->options([
                    'cat' => 'cat',
                    'dog' => 'dog',
                    'rabbit' => 'rabbit',
                    'snake' => 'snake',
                    'hamster' => 'hamster',
                    'bird' => 'bird',
                ])
                ->required(),

            // 日付ピッカー
            Forms\Components\DatePicker::make('date_of_birth')
                ->label('生年月日')
                ->required()        // 入力必須
                ->maxDate(now()),   // 最大日付：現在

            Forms\Components\Select::make('owner_id')
                // モデル：Patient::owner() / ラベル：name
                ->label('飼い主')
                ->relationship('owner', 'name')
                ->searchable()  // 検索対象に設定
                ->preload()     // 事前に取得50件

                // オーナー新規追加用のモーダルフォーム
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->label('名前')             // ラベル
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('メールアドレス')   // ラベル
                        ->email()                  // email形式チェック
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('電話番号')        // ラベル
                        ->tel()                   // 電話番号形式チェック
                        ->required(),
                ])
                ->required(),   // 入力必須
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //患者一覧表の表示列定義
                Tables\Columns\TextColumn::make('name')->label('名前')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('種別'),
                Tables\Columns\TextColumn::make('date_of_birth')->label('生年月日')->sortable(),
                Tables\Columns\TextColumn::make('owner.name')->label('飼い主')->searchable(),
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('type')
                    ->label('種別')
                    ->options([
                        'cat' => 'cat',
                        'dog' => 'dog',
                        'rabbit' => 'rabbit',
                        'snake' => 'snake',
                        'hamster' => 'hamster',
                        'bird' => 'bird',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //リレーションマネージャー：親リソースの編集画面に既存のリソースの関連レコードを表示するテーブル
            RelationManagers\TreatmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
