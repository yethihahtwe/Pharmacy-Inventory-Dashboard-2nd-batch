<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Livewire\ItemBatchList;
use Filament\Infolists\Infolist;
use Filament\Actions\Action;
use App\Livewire\ItemTransactionList;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\TextEntry;

class ItemDetail extends Page
{
    public int $itemId;
    public string $itemName;
    public string $itemCategory;
    public int $itemTotalAmount;

    public function mount()
    {
        $this->itemId = request('itemId') ?? 0;
        $this->itemName = request('itemName') ?? '';
        $this->itemCategory = request('itemCategory') ?? '';
        $this->itemTotalAmount = request('itemTotalAmount') ?? 0;
    }
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.item-detail';

    protected static bool $shouldRegisterNavigation = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back-action')
                ->label('Back to My Inventory')
                ->outlined()
                ->color('gray')
                ->url(MyInventory::getUrl())
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->state([
                'itemId' => $this->itemId,
                'itemName' => $this->itemName,
                'itemCategory' => $this->itemCategory,
                'itemTotalAmount' => $this->itemTotalAmount,
            ])
            ->schema([
                Split::make([
                    Section::make([
                        TextEntry::make('itemName')
                            ->label('Item Name'),
                        TextEntry::make('itemCategory')
                            ->label('Category'),
                        TextEntry::make('itemTotalAmount')
                            ->label('Total Amount')
                            ->badge()
                            ->numeric(),
                    ])->grow(false),

                    Livewire::make(ItemBatchList::class, ['itemId' => $this->itemId])
                ]),
                Livewire::make(ItemTransactionList::class, ['itemId' => $this->itemId])
            ]);
    }
}
