<div wire:init="loadPosts">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 ">
        <!-- This example requires Tailwind CSS v2.0+ -->

        <x-table>

            <div class="px-6 py-4 flex items-center">

                <div class="flex items-center">
                    <span> Mostrar</span>
                    <select wire:model.live="cant" class="mx-2 form-control">
                        <option value=10>10</option>
                        <option value=25>25</option>
                        <option value=50>50</option>
                        <option value=100>100</option>
                    </select>

                    <span>entradas</span>
                </div>

                <x-input class="flex-1 mx-4" placeholder="Escriba que quiere buscar" type="search"
                    wire:model.live.debounce.500ms="search" />

                <livewire:create-post />
            </div>

            @if (count($posts))

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="w-24 cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                wire:click="order('id')">
                                ID

                                {{-- Sort --}}
                                @if ($sort == 'id')

                                    @if ($direction == 'asc')
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

                                    @if ($direction == 'asc')
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

                                    @if ($direction == 'asc')
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

                        @foreach ($posts as $item)
                            <tr>

                                <td class="px-6 py-4 ">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 ">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 ">
                                    <div class="text-sm text-gray-900">
                                       {!! $item->content !!}
                                    </div>
                                </td>

                                <td class="px-6 py-4  text-sm font-medium flex">
                                    <a class="btn btn-green" wire:click="edit({{ $item->id }})">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a class="btn btn-red ml-2" wire:click="$dispatch('delete', { post: {{ $item->id }} })">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach



                        <!-- More people... -->
                    </tbody>
                </table>

                @if ($posts->hasPages())
                    <div class="px-6 py-3">
                        {{ $posts->links() }}
                    </div>
                @endif

            @else

                <div class="px-6 py-4">
                    No existe ningun registro coincidente
                </div>

            @endif




        </x-table>

    </div>

    <x-dialog-modal wire:model.live="open_edit">
        <x-slot name=title>
            Editar el post
        </x-slot>
        <x-slot name=content>

            <div wire:loading wire:target="image"
                class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">¡Imagen cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen se haya procesado</span>
            </div>

            @if ($image)
                <img class="mb-4" src="{{ $image->temporaryUrl() }}" alt="">
            @elseif($post->image)
                <img src="{{ Storage::url($post->image) }}" alt="">
            @endif

            <div class="mb-4">
                <x-label value="Titulo del post" />
                <x-input wire:model="post_title" type="text" class="w-full" />
            </div>

            <div>
                <x-label value="Contenido del post" />
                <textarea wire:model="post_content" rows="6" class="form-control w-full"></textarea>
            </div>

            <div>
                <input type="file" wire:model="image" id="{{ $identificador }}">
                <x-input-error for="image" />
            </div>
        </x-slot>

        <x-slot name=footer>
            <x-secondary-button wire:click="$set('open_edit', false)">
                Cancelar
            </x-secondary-button>

            <x-danger-button wire:click="update" wire:loading.attr="disabled" wire:target="update, image" class="disabled:opacity-25">
                Actualizar
            </x-danger-button>
        </x-slot>

    </x-dialog-modal>

</div>
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('deletePost', postId => {
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "No podras revertir esta accion!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('delete', { post: postId });
                        Swal.fire(
                            'Borrado!',
                            'Tu registro fue eliminado.',
                            'success'
                        )
                    }
                })
            });
        });
    </script>
@endpush
