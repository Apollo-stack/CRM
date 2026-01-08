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

                        <div class="mb-4">
                            <x-input-label for="client_id" :value="__('Cliente')" />
                            <select name="client_id" id="client_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Selecione um cliente...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Título do Negócio')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="value" :value="__('Valor (R$)')" />
                            <x-text-input id="value" class="block mt-1 w-full" type="number" step="0.01" name="value" :value="old('value')" />
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Endereço de Instalação/Entrega</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="cep" :value="__('CEP')" />
                                    <x-text-input id="cep" class="block mt-1 w-full bg-gray-50" type="text" name="cep" />
                                </div>
                                <div>
                                    <x-input-label for="address" :value="__('Endereço')" />
                                    <x-text-input id="address" class="block mt-1 w-full bg-gray-50" type="text" name="address" />
                                </div>
                                <div>
                                    <x-input-label for="city" :value="__('Cidade')" />
                                    <x-text-input id="city" class="block mt-1 w-full bg-gray-50" type="text" name="city" />
                                </div>
                                <div>
                                    <x-input-label for="state" :value="__('Estado')" />
                                    <x-text-input id="state" class="block mt-1 w-full bg-gray-50" type="text" name="state" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-primary-button>
                                {{ __('Criar Negócio') }}
                            </x-primary-button>
                        </div>

                        <script>
                            document.getElementById('client_id').addEventListener('change', function() {
                                var clientId = this.value;
                                
                                if (clientId) {
                                    // Busca os dados na rota que criamos antes
                                    fetch(`/clientes/${clientId}/endereco`)
                                        .then(response => response.json())
                                        .then(data => {
                                            // Preenche os inputs automaticamente
                                            // Nota: 'data.address' depende de como você retorna no ClientController
                                            document.getElementById('cep').value = data.cep || '';
                                            document.getElementById('address').value = data.address || data.endereco || ''; 
                                            document.getElementById('city').value = data.city || data.cidade || '';
                                            document.getElementById('state').value = data.state || data.estado || '';
                                        })
                                        .catch(error => console.error('Erro ao buscar endereço:', error));
                                } else {
                                    // Limpa se não tiver cliente
                                    document.getElementById('cep').value = '';
                                    document.getElementById('address').value = '';
                                    document.getElementById('city').value = '';
                                    document.getElementById('state').value = '';
                                }
                            });
                        </script>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>