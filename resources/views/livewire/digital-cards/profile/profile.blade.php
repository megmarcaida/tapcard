<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component
{
    public string $card = '';

    /**
     * Mount the component.
     */
    public function mount($card): void
    {
        $this->card = $card;
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }} - {{ $card }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>
</section>
