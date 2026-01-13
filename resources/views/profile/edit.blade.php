@extends('layouts.app')

@section('content')
    {{-- HEADER COM GRADIENTE E ESTATÍSTICAS --}}
    <div class="relative bg-gradient-to-r from-gray-900 to-gray-800 pb-40 pt-12 overflow-hidden">
        {{-- Background Decoration (Optional) --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20 pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-blue-500 blur-3xl mix-blend-multiply"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-green-500 blur-3xl mix-blend-multiply"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                {{-- AVATAR GRANDE --}}
                <div class="relative">
                    <div class="h-28 w-28 rounded-full bg-blue-600 flex items-center justify-center text-4xl font-bold text-white border-4 border-gray-800 shadow-2xl">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="absolute bottom-1 right-1 bg-green-500 h-6 w-6 rounded-full border-4 border-gray-800" title="Online"></div>
                </div>

                {{-- INFO DO USUÁRIO --}}
                <div class="text-center md:text-left text-white flex-1">
                    <h1 class="text-4xl font-bold tracking-tight">{{ $user->name }}</h1>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 text-gray-400 mt-2">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>{{ $user->email }}</span>
                        </div>
                        <span class="hidden md:inline">•</span>
                        <span class="bg-blue-600/20 text-blue-300 px-3 py-1 rounded-full text-xs border border-blue-500/30 uppercase tracking-wide font-bold">
                            {{ $user->role ?? 'Salesperson' }}
                        </span>
                    </div>
                </div>

                {{-- STATS CARDS (Premium) --}}
                <div class="grid grid-cols-3 gap-4 w-full md:w-auto mt-6 md:mt-0">
                    <div class="bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 text-center min-w-[110px] shadow-lg">
                        <div class="text-[10px] text-blue-200 uppercase font-bold tracking-wider mb-1">Vendas</div>
                        <div class="text-xl font-black text-white">
                            <span class="text-sm font-normal text-blue-300 align-top mt-1 inline-block">R$</span> {{ number_format($stats['total_sales'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 text-center min-w-[110px] shadow-lg">
                        <div class="text-[10px] text-yellow-200 uppercase font-bold tracking-wider mb-1">Leads</div>
                        <div class="text-xl font-black text-white">
                            {{ $stats['active_leads'] }}
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 text-center min-w-[110px] shadow-lg">
                        <div class="text-[10px] text-green-200 uppercase font-bold tracking-wider mb-1">Conversão</div>
                        <div class="text-xl font-black text-white">
                            {{ number_format($stats['conversion_rate'], 1) }}<span class="text-sm font-normal text-green-300 align-top">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTEÚDO PRINCIPAL (LAYOUT GRID) --}}
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-12 z-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- COLUNA DA ESQUERDA: NAVEGAÇÃO / IDENTIDADE --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Cartão de Identidade --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Detalhes da Conta</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Membro desde</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->created_at->format('M, Y') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Última atualização</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->updated_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dica Rápida --}}
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                    <svg class="absolute -bottom-4 -right-4 w-24 h-24 text-white/10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                    <h4 class="font-bold text-lg mb-2 relative z-10">Dica Pro 🚀</h4>
                    <p class="text-blue-100 text-sm relative z-10">Mantenha seu perfil atualizado para que seu gerente possa entrar em contato facilmente.</p>
                </div>
            </div>

            {{-- COLUNA DA DIREITA: FORMULÁRIOS --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Informações do Perfil --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                👤 Informações Pessoais
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Atualize seu nome e endereço de email.</p>
                        </div>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Segurança --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                         <div class="mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                🔒 Segurança da Conta
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Recomendamos usar uma senha forte e única.</p>
                        </div>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Zona de Perigo --}}
                <div class="bg-red-50 dark:bg-red-900/10 rounded-xl shadow-inner border border-red-100 dark:border-red-900/30">
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-red-600 dark:text-red-400">Zona de Perigo</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ações irreversíveis para sua conta.</p>
                        </div>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
