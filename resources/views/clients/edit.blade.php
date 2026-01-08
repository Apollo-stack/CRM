<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- MUDANÇA 1: A Rota aponta para 'update' e passamos o ID do cliente --}}
                    <form action="{{ route('clients.update', $client->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- MUDANÇA 2: Importante! HTML só aceita GET/POST, isso 'finge' um PUT --}}

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nome do Cliente</label>
                            {{-- MUDANÇA 3: Adicionamos o 'value' para vir preenchido --}}
                            <input type="text" name="name" id="name" value="{{ $client->name }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ $client->email }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                            <input type="text" name="phone" id="phone" value="{{ $client->phone }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Empresa</label>
                            <input type="text" name="company_name" id="company_name" value="{{ $client->company_name }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Atualizar Cliente
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>