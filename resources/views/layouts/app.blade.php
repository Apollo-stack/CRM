<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
        {{-- Loading Overlay Global --}}
        <div id="page-loader" class="page-loading hidden">
            <div class="text-center">
                <div class="spinner mx-auto mb-4"></div>
                <p class="text-white text-lg">Carregando...</p>
            </div>
        </div>

        {{-- JavaScript para Loading States --}}
        <script>
        // ===== LOADING GLOBAL =====
        function showPageLoader() {
            document.getElementById('page-loader').classList.remove('hidden');
        }

        function hidePageLoader() {
            document.getElementById('page-loader').classList.add('hidden');
        }

        // ===== LOADING EM FORMS =====
        document.addEventListener('DOMContentLoaded', function() {
            // Quando qualquer formulário for enviado
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.classList.contains('btn-loading')) {
                        submitBtn.classList.add('btn-loading');
                        submitBtn.disabled = true;
                        
                        // Salva o texto original
                        const originalText = submitBtn.textContent;
                        submitBtn.setAttribute('data-original-text', originalText);
                        submitBtn.textContent = 'Processando...';
                    }
                });
            });
            
            // ===== LOADING EM LINKS DE NAVEGAÇÃO =====
            document.querySelectorAll('a:not([target="_blank"])').forEach(link => {
                // Ignora âncoras (#) e javascript:
                if (link.href && !link.href.includes('#') && !link.href.includes('javascript:')) {
                    link.addEventListener('click', function(e) {
                        // Não mostrar loading em links de exclusão ou com data-no-loading
                        if (!this.closest('form') && !this.hasAttribute('data-no-loading')) {
                            showPageLoader();
                        }
                    });
                }
            });
            
            // Remove loading quando a página carregar
            window.addEventListener('load', function() {
                hidePageLoader();
            });
        });

        // ===== LOADING HELPER FUNCTIONS =====
        function addButtonLoading(button, text = 'Processando...') {
            if (!button.hasAttribute('data-original-text')) {
                button.setAttribute('data-original-text', button.textContent);
            }
            button.classList.add('btn-loading');
            button.disabled = true;
            button.textContent = text;
        }

        function removeButtonLoading(button) {
            button.classList.remove('btn-loading');
            button.disabled = false;
            const originalText = button.getAttribute('data-original-text');
            if (originalText) {
                button.textContent = originalText;
            }
        }
        </script>
    </body>
</html>
