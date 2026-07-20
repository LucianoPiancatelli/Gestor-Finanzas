<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Gestor de Finanzas') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'scale-in': 'scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards',
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.6s ease-out forwards',
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2s infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(15px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.95)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideInRight: {
                            '0%': { opacity: '0', transform: 'translateX(30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { opacity: '1', filter: 'drop-shadow(0 0 2px rgba(16, 185, 129, 0.4))' },
                            '50%': { opacity: '0.7', filter: 'drop-shadow(0 0 10px rgba(16, 185, 129, 0.8))' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .stagger-list > *:nth-child(1) { animation-delay: 50ms; }
        .stagger-list > *:nth-child(2) { animation-delay: 100ms; }
        .stagger-list > *:nth-child(3) { animation-delay: 150ms; }
        .stagger-list > *:nth-child(4) { animation-delay: 200ms; }
        .stagger-list > *:nth-child(5) { animation-delay: 250ms; }
        .stagger-list > *:nth-child(6) { animation-delay: 300ms; }
        .stagger-list > *:nth-child(7) { animation-delay: 350ms; }
        .stagger-list > *:nth-child(8) { animation-delay: 400ms; }
        .stagger-list > *:nth-child(9) { animation-delay: 450ms; }
        .stagger-list > *:nth-child(10) { animation-delay: 500ms; }
        .stagger-list > *:nth-child(n+11) { animation-delay: 550ms; }
    </style>
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col transition-colors duration-200">
    <nav class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 py-4 shadow-sm transition-colors duration-200">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-blue-600 no-underline"><i class="fa-solid fa-wallet"></i> FinanzasPro</a>
            <div class="flex space-x-6 items-center">
                <button id="theme-toggle" class="text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition" title="Cambiar tema">
                    <i id="theme-toggle-dark-icon" class="fa-solid fa-moon hidden"></i>
                    <i id="theme-toggle-light-icon" class="fa-solid fa-sun hidden"></i>
                </button>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="/dashboard/index" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition">Dashboard</a>
                    <a href="/account/index" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition">Cuentas</a>
                    <a href="/budget/index" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition">Presupuestos</a>
                    <a href="/goal/index" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition">Objetivos</a>
                    <a href="/subscription/index" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition">Suscripciones</a>
                    <a href="/investment/index" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition">Inversiones</a>
                    <div class="relative group pb-4 -mb-4">
                        <span class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition cursor-pointer">Operaciones ▾</span>
                        <div class="absolute right-0 top-full pt-2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-50">
                            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden">
                                <a href="/transaction/create" class="block px-4 py-3 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 border-b border-gray-100 dark:border-slate-700"><i class="fa-solid fa-plus mr-2"></i> Nueva Transacción</a>
                                <a href="/transaction/transfer" class="block px-4 py-3 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 border-b border-gray-100 dark:border-slate-700"><i class="fa-solid fa-right-left mr-2"></i> Transferir a mis cuentas</a>
                                <a href="/transaction/transfer_p2p" class="block px-4 py-3 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 border-b border-gray-100 dark:border-slate-700"><i class="fa-solid fa-paper-plane mr-2"></i> Enviar a otro usuario</a>
                                <a href="/transaction/index" class="block px-4 py-3 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400"><i class="fa-solid fa-list mr-2"></i> Historial</a>
                            </div>
                        </div>
                    </div>
                    <div class="relative group pb-4 -mb-4 ml-2">
                        <span class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition cursor-pointer"><i class="fa-solid fa-user"></i> Mi Cuenta ▾</span>
                        <div class="absolute right-0 top-full pt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-50">
                            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden flex flex-col">
                                <a href="/profile/index" class="px-4 py-3 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 border-b border-gray-100 dark:border-slate-700"><i class="fa-solid fa-user-gear mr-2"></i> Perfil</a>
                                <a href="/auth/logout" class="px-4 py-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 transition"><i class="fa-solid fa-right-from-bracket mr-2"></i> Cerrar Sesión</a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Ocultos para el modo demo del portfolio -->
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-6 py-8 flex-1">
    <script>
        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('SW registered: ', registration);
                }).catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
            });
        }

        // Privacy Mode Logic
        function updatePrivacyMode() {
            const privacyIcon = document.getElementById('privacy-icon');
            if (localStorage.getItem('privacy_mode') === 'enabled') {
                document.body.classList.add('privacy-enabled');
                if (privacyIcon) {
                    privacyIcon.classList.remove('fa-eye-slash');
                    privacyIcon.classList.add('fa-eye');
                }
                document.querySelectorAll('.sensitive-balance').forEach(el => {
                    el.classList.add('blur-md', 'select-none', 'transition-all', 'duration-300');
                });
            } else {
                document.body.classList.remove('privacy-enabled');
                if (privacyIcon) {
                    privacyIcon.classList.remove('fa-eye');
                    privacyIcon.classList.add('fa-eye-slash');
                }
                document.querySelectorAll('.sensitive-balance').forEach(el => {
                    el.classList.remove('blur-md', 'select-none', 'transition-all', 'duration-300');
                });
            }
        }

        // Apply immediately to body to prevent flash
        updatePrivacyMode();

        document.addEventListener('DOMContentLoaded', function() {
            // Apply again once all elements are loaded
            updatePrivacyMode();

            const privacyToggleBtn = document.getElementById('privacy-toggle');
            privacyToggleBtn?.addEventListener('click', function() {
                if (localStorage.getItem('privacy_mode') === 'enabled') {
                    localStorage.setItem('privacy_mode', 'disabled');
                } else {
                    localStorage.setItem('privacy_mode', 'enabled');
                }
                updatePrivacyMode();
            });
        });
    </script>
