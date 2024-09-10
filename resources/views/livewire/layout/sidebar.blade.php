<!-- Sidebar -->
<aside :class="{ '-translate-x-full': !open }" class="z-10 bg-blue-800 text-blue-100 w-64 px-2 py-4 absolute inset-y-0 left-0 md:relative transform md:translate-x-0 overflow-y-auto transition ease-in-out duration-200 shadow-lg">
    <!-- Logo -->
        <div class="flex items-center justify-between px-2">
            <div class="flex items-center space-x-2">
                <a href="#">
                    <x-application-logo class="block h-9 w-auto fill-current text-blue-100 dark:text-blue-100" />
                </a>
                <span class="text-2x1 font-extrabold">Admin</span>
            </div>
            <button type="button" @click="open = !open" class="md:hidden inline-flex p-2 items-center justify-center rounded-md text-blue-100 hover:bg-blue-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <button></button>

    <!-- Navigation Links -->
    <nav>
        <x-side-nav-link :href="route('admin')" :active="request()->routeIs('admin')" wire:navigate>{{ __('Dashboard') }}</x-side-nav-link>
        <x-side-nav-link :href="route('admin/users')" :active="request()->routeIs('admin/users')" wire:navigate>{{ __('Users') }}</x-side-nav-link>
        <x-side-nav-link :href="route('admin/digital-cards')" :active="request()->routeIs('admin/digital-cards')" wire:navigate>{{ __('Digital Cards') }}</x-side-nav-link>
        <x-side-nav-link :href="route('admin/settings')" :active="request()->routeIs('admin/settings')" wire:navigate>{{ __('Settings') }}</x-side-nav-link>
        <x-side-nav-link :href="route('admin/link-cards')" :active="request()->routeIs('admin/link-cards')" wire:navigate>{{ __('Link Cards') }}</x-side-nav-link>
        <x-side-nav-link :href="route('admin/reports')" :active="request()->routeIs('admin/reports')" wire:navigate>{{ __('Reports') }}</x-side-nav-link>
    </nav>
</aside>