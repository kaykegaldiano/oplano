<?php

namespace App\Livewire;

use App\Models\Observation;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuickNote extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public string $body = '';
    public bool $pinned = false;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('body')
                    ->label('Observação')
                    ->rows(5)
                    ->placeholder('Digite aqui...')
                    ->required(),
                Checkbox::make('pinned')
                    ->label('Fixar no topo'),
            ]);
    }

    public function save(): void
    {
        $this->form->validate();

        Observation::create([
            'user_id' => auth()->id(),
            'body' => $this->body,
            'pinned' => $this->pinned,
        ]);

        $this->dispatch('close-modal', id: 'quick-note-modal');

        Notification::make()->title('Observação registrada!')->success()->send();

        $this->dispatch('refresh-page');

        $this->reset(['body', 'pinned']);
    }

    public function render(): View
    {
        return view('livewire.quick-note');
    }
}
