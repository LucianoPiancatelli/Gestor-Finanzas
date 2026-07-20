<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-slate-700 mt-8 mb-12 transition-colors duration-200">
    <div class="flex justify-center mb-6 text-blue-600">
        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-3xl">
            <i class="fa-solid fa-bullseye"></i>
        </div>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-slate-800 dark:text-white text-center">Nuevo Objetivo de Ahorro</h2>
    
    <?php if (!empty($error)): ?>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500 text-red-600 dark:text-red-400 p-4 rounded-xl mb-6 flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-3"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/goal/create" method="POST" class="space-y-5">
        <div>
            <label for="nombre" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nombre del Objetivo</label>
            <input type="text" id="nombre" name="nombre" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" required placeholder="Ej. Viaje a Brasil, Fondo de Emergencia">
        </div>

        <div>
            <label for="monto_objetivo" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Monto Objetivo ($)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-slate-400 dark:text-slate-500 font-medium">$</span>
                </div>
                <input type="number" step="0.01" id="monto_objetivo" name="monto_objetivo" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl pl-8 pr-4 py-3 text-slate-800 dark:text-white font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" placeholder="0.00" required>
            </div>
        </div>

        <div>
            <label for="fecha_limite" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Fecha Límite (Opcional)</label>
            <input type="date" id="fecha_limite" name="fecha_limite" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
        </div>
        
        <div class="flex gap-4 pt-6">
            <a href="/goal/index" class="w-1/3 text-center px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">Cancelar</a>
            <button type="submit" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-600/20 dark:shadow-blue-900/20">Crear Objetivo</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
