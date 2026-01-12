<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Garante que o loader tenha estilo mesmo se o app.css falhar */
            .page-loading {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: opacity 0.3s ease;
            }
            .spinner {
                border: 4px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                border-top: 4px solid #ffffff;
                width: 40px;
                height: 40px;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                @yield('content')
            </main>
        </div>

        {{-- Loading Overlay Global (Começa oculto com style inline para segurança) --}}
        <div id="page-loader" class="page-loading" style="display: none;">
            <div class="text-center">
                <div class="spinner mx-auto mb-4"></div>
                <p class="text-white text-lg">Carregando...</p>
            </div>
        </div>

        {{-- JavaScript para Loading States --}}
        <script>
        // ===== LOADING GLOBAL =====
        function showPageLoader() {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.style.display = 'flex';
                // Trava de segurança: esconde automaticamente após 10 segundos se algo der errado
                setTimeout(hidePageLoader, 10000);
            }
        }

        function hidePageLoader() {
            const loader = document.getElementById('page-loader');
            if (loader) loader.style.display = 'none';
        }

        // ===== LOADING EM FORMS =====
        document.addEventListener('DOMContentLoaded', function() {
            // Garante que o loader suma assim que o JS carregar
            hidePageLoader();

            // Quando qualquer formulário for enviado
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Não mostra loader se o form tiver target="_blank"
                    if (this.target === '_blank') return;

                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.classList.contains('btn-loading')) {
                        submitBtn.classList.add('btn-loading');
                        // Salva o texto original
                        if (!submitBtn.hasAttribute('data-original-text')) {
                            submitBtn.setAttribute('data-original-text', submitBtn.textContent);
                        }
                        submitBtn.textContent = 'Processando...';
                        submitBtn.disabled = true;
                    }
                    showPageLoader();
                });
            });
            
            // ===== LOADING EM LINKS DE NAVEGAÇÃO =====
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    const target = this.getAttribute('target');
                    
                    // Condições para NÃO mostrar o loading
                    if (!href || 
                        href.startsWith('#') || 
                        href.startsWith('javascript:') || 
                        target === '_blank' ||
                        this.hasAttribute('data-no-loading') ||
                        e.ctrlKey || e.metaKey) { // Se segurar Ctrl/Cmd (abrir nova aba)
                        return;
                    }

                    // Se for apenas um link de download
                    if (this.hasAttribute('download')) return;

                    showPageLoader();
                });
            });
        });

        // Evento extra para garantir que esconde ao carregar a página (bfcache suporte)
        window.addEventListener('pageshow', function(event) {
            hidePageLoader();
        });
        
        window.addEventListener('load', function() {
            hidePageLoader();
        });
        </script>

        {{-- Busca Global com Loading --}}
        {{-- Nota: Removi o form duplicado que estava aqui, use o da navigation bar --}}
        
        {{-- Sistema de Toasts --}}
        <x-toast />

        {{-- Processar mensagens flash do Laravel --}}
        @if(session('success') || session('error') || session('warning') || session('info'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    @if(session('success')) toast.success('Sucesso!', '{{ session('success') }}'); @endif
                    @if(session('error')) toast.error('Erro!', '{{ session('error') }}'); @endif
                    @if(session('warning')) toast.warning('Atenção!', '{{ session('warning') }}'); @endif
                    @if(session('info')) toast.info('Informação', '{{ session('info') }}'); @endif
                });
            </script>
        @endif
    </body>
</html>