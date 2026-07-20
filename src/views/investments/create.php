<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-slate-700 mt-8 mb-12 transition-colors duration-200">
    <div class="flex justify-center mb-6 text-blue-600">
        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-3xl">
            <i class="fa-solid fa-seedling"></i>
        </div>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-slate-800 dark:text-white text-center">Registrar Nueva Inversión</h2>
    
    <?php if (!empty($error)): ?>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500 text-red-600 dark:text-red-400 p-4 rounded-xl mb-6 flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-3"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/investment/create" method="POST" class="space-y-5">
        <div>
            <label for="nombre" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nombre o Ticker</label>
            <input type="text" id="nombre" name="nombre" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" required placeholder="Ej. AAPL, Plazo Fijo Banco X, Bitcoin">
        </div>

        <div>
            <label for="tipo" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tipo de Inversión</label>
            <select id="tipo" name="tipo" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none" required>
                <option value="plazo_fijo">Plazo Fijo / Depósito</option>
                <option value="acciones">Acciones / CEDEARs</option>
                <option value="cripto">Criptomonedas</option>
                <option value="fondo">Fondo Común de Inversión</option>
                <option value="otro">Otro</option>
            </select>
        </div>

        <div>
            <label for="monto_invertido" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Monto Inicial ($)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-slate-400 dark:text-slate-500 font-medium">$</span>
                </div>
                <input type="number" step="0.01" id="monto_invertido" name="monto_invertido" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl pl-8 pr-4 py-3 text-slate-800 dark:text-white font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" placeholder="0.00" required>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">El valor actual será igual al monto inicial y podrás actualizarlo luego.</p>
        </div>

        <div>
            <label for="fecha_inicio" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Fecha de Inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" required value="<?= date('Y-m-d') ?>">
        </div>
        
        <div class="flex gap-4 pt-6">
            <a href="/investment/index" class="w-1/3 text-center px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">Cancelar</a>
            <button type="submit" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-600/20 dark:shadow-blue-900/20">Registrar Inversión</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
