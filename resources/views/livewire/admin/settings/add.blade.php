<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use App\Models\Settings;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $value = '';
    public string $status = '';
    public string $type = 'text';

    #[Rule('file|max:5560')]
    public $photo;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function createSetting(): void
    {
        $user = Auth::user();
        $settings = new Settings();

        $validated = $this->validate([
            'name' => ['required', 'string','unique:settings', 'max:255'],
            'status' => ['string', 'max:255'],
        ]);
        
        
        $settings->name = $this->name;

        if ($this->type == "text") {
            $settings->value = $this->value;
        } else if ($this->type == "image") {
            $settings->value = str_replace('public', '', $this->photo->storePublicly('public/images'));
        }

        $settings->type = $this->type;
        $settings->status = $this->status;
        $settings->save();

        $this->dispatch('setting-created', name: $user->name);
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Create Digital card') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Add your profile/card basic information.") }}
        </p>
    </header>

    <form wire:submit="createSetting" class="mt-6 space-y-6">

        <div>
            <x-input-label for="name" :value="__('Setting Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        @if ($type == "text")
        <div>
            <x-input-label for="value" :value="__('Value')" />
            <x-text-input wire:model="value" id="value" name="value" type="text" class="mt-1 block w-full" required autofocus autocomplete="value" />
            <x-input-error class="mt-2" :messages="$errors->get('value')" />
        </div>
        @elseif($type == "image")
        <div>
            <x-input-label for="photo" :value="__('Upload')" />
            @if ($photo)
                Photo Preview:
                <img style="width:200px" src="{{ $photo->temporaryUrl() }}">
            @endif
            <input type="file" wire:model="photo">
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>
        @endif

        <div>
            <x-input-label for="status" :value="__('Status')" />
            <x-text-input wire:model="status" id="status" name="status" type="text" class="mt-1 block w-full" required autofocus autocomplete="status" />
            <x-input-error class="mt-2" :messages="$errors->get('status')" />
        </div>
        

        <div>
            <x-input-label for="status" :value="__('Type')" />
            <select wire:model.change="type" id="type" name="type" class="mt-1 block w-full" required autocomplete="type">
                <option value="text">Text</option>
                <option value="image">Image</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('type')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="setting-created">
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">Successfully saved!</span>
                </div>
            </x-action-message>
        </div>
    </form>
</section>
