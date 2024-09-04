<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use App\Models\DigitalCards;

new class extends Component
{
    public string $digital_card = '';
    public string $title = '';
    public string $sub_title = '';
    public string $company = '';
    public string $about_us = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function createCard(): void
    {
        $user = Auth::user();
        $digitalCard = new DigitalCards();

        $validated = $this->validate([
            'digital_card' => ['required', 'string','unique:digital_cards', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'sub_title' => ['string', 'max:255'],
            'company' => ['string', 'max:255'],
            'about_us' => ['string', 'max:255']
        ]);

        // $digitalCard->fill($validated);
        $digitalCard->digital_card = $this->digital_card;
        $digitalCard->title = $this->title;
        $digitalCard->sub_title = $this->sub_title;
        $digitalCard->company = $this->company;
        $digitalCard->about_us = $this->about_us;
        $digitalCard->user_id = $user->id;
        $digitalCard->save();

        $this->dispatch('profile-created', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
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

    <form wire:submit="createCard" class="mt-6 space-y-6">
        <div>
            <x-input-label for="digital_card" :value="__('Digital Card Slug')" />
        </div>
        <div class="flex">
            <span class="inline-flex items-center px-3 text-sm border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            {{ $_SERVER['HTTP_HOST'] }}/me
            </span>
            <x-text-input wire:model="digital_card" id="digital_card" name="digital_card" type="text" style="border-radius:0px;" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required autofocus autocomplete="digital_card" />
        </div>
        <div><x-input-error class="mt-2" :messages="$errors->get('digital_card')" /></div>

        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input wire:model="title" id="title" name="title" type="text" class="mt-1 block w-full" required autofocus autocomplete="title" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <div>
            <x-input-label for="sub_title" :value="__('Sub Title')" />
            <x-text-input wire:model="sub_title" id="sub_title" name="sub_title" type="text" class="mt-1 block w-full" required autofocus autocomplete="sub_title" />
            <x-input-error class="mt-2" :messages="$errors->get('sub_title')" />
        </div>

        <div>
            <x-input-label for="company" :value="__('Company')" />
            <x-text-input wire:model="company" id="company" name="company" type="text" class="mt-1 block w-full" required autofocus autocomplete="company" />
            <x-input-error class="mt-2" :messages="$errors->get('company')" />
        </div>

        <div>
            <x-input-label for="about_us" :value="__('About Us')" />
            <x-text-input wire:model="about_us" id="about_us" name="about_us" type="text" class="mt-1 block w-full" required autofocus autocomplete="about_us" />
            <x-input-error class="mt-2" :messages="$errors->get('about_us')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-created">
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">Successfully saved!</span>
                </div>
            </x-action-message>
        </div>
    </form>
</section>
