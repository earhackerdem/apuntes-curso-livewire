<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Team') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Team Details') }}</h3>

                <form method="POST" action="{{ route('teams.store') }}" class="mt-5 space-y-6">
                    @csrf

                    <div>
                        <x-jet-label for="name" value="{{ __('Team Name') }}" />
                        <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-jet-button>
                            {{ __('Create') }}
                        </x-jet-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
