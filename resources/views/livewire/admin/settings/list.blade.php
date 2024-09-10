<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Settings;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $setting = '';
    public string $name = '';
    public string $valueData = '';
    public string $status = '';
    public string $type = 'text';
    public bool $isEdit = false;
    public int $id = 0;

    #[Rule('file|max:5560')]
    public $photo;

    public function with(): array
    {
        $user_id = Auth::user()->id;

        return [
            'settings' => Settings::where('name','LIKE','%'.$this->setting.'%')->paginate(10),
        ];
    }

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
            $settings->value = $this->valueData;
        } else if ($this->type == "image") {
            $settings->value = str_replace('public', '', $this->photo->storePublicly('public/images'));
        }

        $settings->type = $this->type;
        $settings->status = $this->status;
        $settings->save();

        $this->dispatch('setting-created', name: $user->name);
    }

    public function edit($id): void
    {
        if ($this->id == $id) {
            $this->isEdit = $this->isEdit ? false : true;
        } else {
            $this->isEdit = true;
        }

        if ($this->isEdit == true) {
            $settings = Settings::find($id);
            $this->id = $id;
            $this->name = $settings->name;
            $this->valueData = $settings->value;
            $this->type = $settings->type;
            $this->status = $settings->status;
        } else {
            $this->defaultData();
        }
    }

    public function updateSetting(): void
    {
        $user = Auth::user();
        $settings = Settings::find($this->id);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['string', 'max:255'],
        ]);
        
        
        $settings->name = $this->name;

        if ($this->type == "text") {
            $settings->value = $this->valueData;
        } else if ($this->type == "image") {
            $settings->value = str_replace('public', '', $this->photo->storePublicly('public/images'));
        }

        $settings->type = $this->type;
        $settings->status = $this->status;
        $settings->save();

        $this->dispatch('setting-created', name: $user->name);
    }

    public function defaultData()
    {
        $this->id = 0;
        $this->name = "";
        $this->valueData = "";
        $this->type = "text";
        $this->status = "";
        $this->isEdit = false;
        $this->photo = null;
    }

}; ?>

<div>
<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Settings') }}
        </h2>
        <br>    
        <x-text-input wire:model.live="setting" id="name" placeholder="Search by settings name..." name="setting" type="text" style="border-radius:0px;" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required autofocus autocomplete="setting" />
    </header>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Settings Name</th>
                    <th scope="col" class="px-6 py-3">Value</th>
                    <th scope="col" class="px-6 py-3">Type</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $key => $value)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4"><p>{{ $value['name'] }}</p></td>
                        <td class="px-6 py-4">
                        @if($value['type'] == 'text')
                        <p>{{ $value['value'] }}</p>
                        @elseif($value['type'] == 'image')
                        <a href="{{ asset('storage/'.$value['value']) }}" target="_blank">
                            <img style="width:100px;" src="{{ asset('storage/'.$value['value']) }}"></p>
                        </a>
                        @endif    
                        </td>
                        <td class="px-6 py-4"><p>{{ $value['type'] }}</p></td>
                        <td class="px-6 py-4">
                            <button wire:click="edit({{$value['id']}})">Edit</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $settings->links() }}
    </div>
</section>
<br>
@if($isEdit)
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Edit Digital card') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Edit your profile/card basic information.") }}
        </p>
    </header>

    <form wire:submit="updateSetting" class="mt-6 space-y-6">

        <div>
            <x-input-label for="name" :value="__('Setting Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        @if ($type == "text")
        <div>
            <x-input-label for="valueData" :value="__('Value')" />
            <x-text-input wire:model="valueData" id="valueData" name="valueData" type="text" class="mt-1 block w-full" required autofocus autocomplete="valueData" />
            <x-input-error class="mt-2" :messages="$errors->get('valueData')" />
        </div>
        @elseif($type == "image")
        <div>
            <x-input-label for="photo" :value="__('Upload')" />
            @if ($valueData && !$photo)
                Photo Preview:
                <a href="{{ asset('storage/'.$valueData) }}" target="_blank">
                    <img style="width:200px;" src="{{ asset('storage/'.$valueData) }}"></p>
                </a>
            @elseif ($photo)
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

            <a style="cursor:pointer;" wire:click="edit({{$id}})">{{ __('Cancel') }}</a>
            <x-action-message class="me-3" on="setting-created">
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">Successfully saved!</span>
                </div>
            </x-action-message>
        </div>
    </form>
</section>
<br>
@endif
@if(!$isEdit)
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
            <x-input-label for="valueData" :value="__('Value')" />
            <x-text-input wire:model="valueData" id="valueData" name="valueData" type="text" class="mt-1 block w-full" required autofocus autocomplete="valueData" />
            <x-input-error class="mt-2" :messages="$errors->get('valueData')" />
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
@endif
</div>
</div>
</div>
</div>