<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Settings;

new class extends Component
{
    use WithPagination;
 
    public string $setting = '';

    public function with(): array
    {
        $user_id = Auth::user()->id;

        return [
            'settings' => Settings::where('name','LIKE','%'.$this->setting.'%')->paginate(2),
        ];
    }

}; ?>

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
                        <td class="px-6 py-4"><p>Edit</p></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $settings->links() }}
    </div>
</section>
