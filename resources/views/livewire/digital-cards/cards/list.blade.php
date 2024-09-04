<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\DigitalCards;

new class extends Component
{
    use WithPagination;
 
    public string $digital_card = '';

    public function with(): array
    {
        $user_id = Auth::user()->id;

        return [
            'digital_cards' => DigitalCards::where('digital_card','LIKE','%'.$this->digital_card.'%')->where('user_id', $user_id)->paginate(2),
        ];
    }

}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Cards List') }}
        </h2>
        
        <x-text-input wire:model.live="digital_card" id="digital_card" placeholder="Search posts by digital card..." name="digital_card" type="text" style="border-radius:0px;" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required autofocus autocomplete="digital_card" />
    </header>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Title</th>
                    <th scope="col" class="px-6 py-3">Digital Card Url</th>
                    <th scope="col" class="px-6 py-3">Sub Title</th>
                    <th scope="col" class="px-6 py-3">Company</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($digital_cards as $key => $value)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><p>{{ $value['title'] }}</p></th>
                        <!-- <td style=" border: 1px solid black; text-align: center;">
                            <input type="text" wire:model="digitalCards.{{ $key }}.digital_card">
                        </td> -->
                        <td class="px-6 py-4"><p><a href="/{{ $value['digital_card'] }}" target="_blank">{{ $_SERVER['HTTP_HOST'] }}/{{ $value['digital_card'] }}</a></p></td>
                        <td class="px-6 py-4"><p>{{ $value['sub_title'] }}</p></td>
                        <td class="px-6 py-4"><p>{{ $value['company'] }}</p></td>
                        <td class="px-6 py-4"><p>Edit</p></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $digital_cards->links() }}
    </div>
</section>
