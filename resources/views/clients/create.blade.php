<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- O formulário aponta para a rota 'store' que salva os dados --}}
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf {{-- Proteção obrigatória do Laravel contra ataques --}}

                        {{-- Nome --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nome do Cliente</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Telefone --}}
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                            <input type="text" name="phone" id="phone"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Empresa --}}
                        <div class="mb-4">
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Empresa</label>
                            <input type="text" name="company_name" id="company_name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="mt-4">
                            <h3 class="text-lg font-medium text-gray-900">Endereço</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                
                                <div>
                                    <x-input-label for="cep" :value="__('CEP')" />
                                    <x-text-input id="cep" class="block mt-1 w-full" type="text" name="cep" :value="old('cep')" />
                                    <x-input-error :messages="$errors->get('cep')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="city" :value="__('Cidade')" />
                                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="state" :value="__('Estado (UF)')" />
                                    <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state')" maxlength="2" />
                                    <x-input-error :messages="$errors->get('state')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Endereço')" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                            </div>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Salvar Cliente
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        // Quando o campo de cliente (que tem id="cliente_id") mudar...
        document.getElementById('cliente_id').addEventListener('change', function() {
            
            var idDoCliente = this.value;

            // Se não tiver cliente selecionado, para por aqui
            if (!idDoCliente) return;

            // Chama a rota que criamos no Passo 1
            fetch('/clientes/' + idDoCliente + '/endereco')
                .then(response => response.json())
                .then(data => {
                    // Aqui a mágica acontece: preenche os campos sozinhos
                    // Ajuste os IDs ('cep', 'endereco') conforme os seus inputs
                    if(document.getElementById('cep')) document.getElementById('cep').value = data.cep || '';
                    if(document.getElementById('endereco')) document.getElementById('endereco').value = data.endereco || '';
                    if(document.getElementById('numero')) document.getElementById('numero').value = data.numero || '';
                    if(document.getElementById('cidade')) document.getElementById('cidade').value = data.cidade || '';
                });
        });
    </script>
</x-app-layout>