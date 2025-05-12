<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Team Settings') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Team Name') }}</h3>

                <div class="mt-5">
                    <p class="text-sm text-gray-500">{{ $team->name }}</p>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Team Owner') }}</h3>
                    <div class="mt-4 flex items-center">
                        <div class="flex-shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-full" src="{{ $team->owner->profile_photo_url }}" alt="{{ $team->owner->name }}">
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $team->owner->name }}</div>
                            <div class="text-sm text-gray-500">{{ $team->owner->email }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Team Members') }}</h3>
                    <div class="mt-4 space-y-6">
                        @foreach ($team->users as $user)
                            @if ($user->id !== $team->owner->id)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img class="w-8 h-8 rounded-full mr-3" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
