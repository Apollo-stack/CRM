<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Negócio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('leads.store') }}" method="POST">
                        @csrf 

                        {{-- Selecionar o Cliente --}}
                        <div class="mb-4">
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select name="client_id" id="client_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecione um cliente...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Título do Negócio --}}
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Título do Negócio</label>
                            <input type="text" name="title" id="title" placeholder="Ex: Venda de Consultoria" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Valor --}}
                        <div class="mb-4">
                            <label for="value" class="block text-sm font-medium text-gray-700">Valor (R$)</label>
                            <input type="number" step="0.01" name="value" id="value"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- BLOCO DE ENDEREÇO --}}
                        <div class="border-t pt-4 mt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Endereço</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">CEP</label>
                                    <input type="text" name="cep" value="{{ old('cep', $client->cep ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>

                                <div class="mb-4 col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Cidade/UF</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="city" placeholder="Cidade" value="{{ old('city', $client->city ?? '') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <input type="text" name="state" placeholder="UF" value="{{ old('state', $client->state ?? '') }}"
                                            class="mt-1 block w-16 rounded-md border-gray-300 shadow-sm">
                                    </div>
                                </div>

                                <div class="mb-4 col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Endereço Completo</label>
                                    <input type="text" name="address" value="{{ old('address', $client->address ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Criar Negócio
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>