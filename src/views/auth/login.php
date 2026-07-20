<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto mt-16 mb-20 px-4 opacity-0 animate-fade-in-up">
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-3xl mb-6 shadow-sm hover:scale-110 transition-transform duration-300">
            <i class="fa-solid fa-rocket text-4xl"></i>
        </div>
        <h1 class="text-4xl font-extrabold text-slate-800 dark:text-white mb-4 tracking-tight">Finanzas Personales <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-emerald-500">Demo</span></h1>
        <p class="text-lg text-slate-600 dark:text-slate-400 max-w-xl mx-auto">
            Explora esta aplicación web de gestión financiera. Al entrar, se generará un entorno privado y seguro (sandbox) con datos de prueba para que puedas experimentar libremente.
        </p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 transition-colors duration-200 relative overflow-hidden opacity-0 animate-scale-in [animation-delay:200ms]">
        <!-- Decoración de fondo -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-blue-50 dark:bg-blue-900/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-emerald-50 dark:bg-emerald-900/10 blur-3xl"></div>

        <div class="relative z-10">
            <h2 class="text-xl font-bold mb-6 text-slate-800 dark:text-white text-center">¿Qué incluye este Demo?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="flex items-start">
                    <div class="mt-1 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 p-2 rounded-lg mr-4">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Dashboard Analítico</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gráficos interactivos y cálculo de tasa de ahorro en tiempo real.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="mt-1 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 p-2 rounded-lg mr-4">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Metas de Ahorro</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Seguimiento visual del progreso hacia tus objetivos financieros.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="mt-1 bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 p-2 rounded-lg mr-4">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Suscripciones</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gestión de gastos recurrentes con fechas de próximo cobro.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="mt-1 bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 p-2 rounded-lg mr-4">
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Modo Privacidad</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Oculta saldos sensibles con un solo clic para mayor seguridad.</p>
                    </div>
                </div>
            </div>

            <form action="/auth/login" method="POST" class="text-center">
                <!-- Un input oculto para indicar que es un login de demo -->
                <input type="hidden" name="demo_login" value="1">
                
                <button type="submit" class="inline-flex items-center justify-center w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-10 rounded-2xl transition-all transform hover:-translate-y-1 hover:shadow-xl shadow-blue-600/30 dark:shadow-blue-900/30 text-lg">
                    <span>Entrar a la Demo Interactiva</span>
                    <i class="fa-solid fa-arrow-right ml-3"></i>
                </button>
            </form>
            
            <p class="text-center mt-6 text-xs text-slate-400 dark:text-slate-500">
                Al ingresar, se creará un perfil temporal único para ti. Los datos se reiniciarán periódicamente.
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
