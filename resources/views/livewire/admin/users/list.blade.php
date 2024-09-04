<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\{state, computed, action};
state(['newTodoTitle' => '']);
$users = computed(function () {
    return User::where('role', 'user')->get();
});
?>
<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Full Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Email
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($this->users as $todo)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4">
                        {{ $todo->id }}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $todo->name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $todo->email }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>