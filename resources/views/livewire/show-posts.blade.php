<div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 ">
        <!-- This example requires Tailwind CSS v2.0+ -->

        <x-table>

            <div class="px-6 py-4">
                <x-jet-input class="w-full" placeholder="Escriba que quiere buscar" type="search"
                    wire:model='search' />
            </div>

            @if ($posts->count())

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                wire:click="order('id')">
                                ID

                                {{-- Sort --}}
                                @if ($sort == 'id')

                                    @if($direction =='asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                    <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif


                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>

                                @endif

                            </th>
                            <th scope="col"
                                class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                wire:click="order('title')">
                                Title

                                {{-- Sort --}}
                                @if ($sort == 'title')

                                    @if($direction =='asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                    <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif


                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>

                                @endif




                            </th>
                            <th scope="col"
                                class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                wire:click="order('content')">
                                Content
                                {{-- Sort --}}
                                @if ($sort == 'content')

                                    @if($direction =='asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                    <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif


                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>

                                @endif

                            </th>

                            <th scope="col" class="relative px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                        @foreach ($posts as $post)
                            <tr>

                                <td class="px-6 py-4 ">
                                    <div class="text-sm text-gray-900">
                                        {{ $post->id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 ">
                                    <div class="text-sm text-gray-900">
                                        {{ $post->title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 ">
                                    <div class="text-sm text-gray-900">
                                        {{ $post->content }}
                                    </div>
                                </td>

                                <td class="px-6 py-4  text-right text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                </td>
                            </tr>
                        @endforeach



                        <!-- More people... -->
                    </tbody>
                </table>
            @else

                <div class="px-6 py-4">
                    No existe ningun registro coincidente
                </div>

            @endif




        </x-table>

    </div>
</div>
