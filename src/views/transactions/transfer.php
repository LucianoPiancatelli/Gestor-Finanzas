<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-slate-700 mt-8 mb-12 transition-colors duration-200">
    <div class="flex justify-center mb-6 text-blue-600">
        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-3xl">
            <i class="fa-solid fa-arrow-right-arrow-left"></i>
        </div>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-slate-800 dark:text-white text-center">Transferir Dinero</h2>
    
    <?php if (!empty($error)): ?>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500 text-red-600 dark:text-red-400 p-4 rounded-xl mb-6 flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-3"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/transaction/transfer" method="POST" class="space-y-5">
        <div>
            <label for="cuenta_origen_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Cuenta Origen (De donde sale el dinero)</label>
            <select id="cuenta_origen_id" name="cuenta_origen_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none" required>
                <option value="">Seleccione una cuenta...</option>
                <?php foreach ($cuentas as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?> (Saldo: $<?= number_format($c['saldo'], 2) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="cuenta_destino_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Cuenta Destino (Hacia donde va el dinero)</label>
            <select id="cuenta_destino_id" name="cuenta_destino_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none" required>
                <option value="">Seleccione una cuenta...</option>
                <?php foreach ($cuentas as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?> (Saldo: $<?= number_format($c['saldo'], 2) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="monto" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Monto a Transferir ($)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-slate-400 dark:text-slate-500 font-medium">$</span>
                </div>
                <input type="number" step="0.01" min="0" id="monto" name="monto" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl pl-8 pr-4 py-3 text-slate-800 dark:text-white font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" required placeholder="0.00">
            </div>
        </div>
        
        <div>
            <label for="fecha" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Fecha</label>
            <input type="date" id="fecha" name="fecha" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" value="<?= date('Y-m-d') ?>" required>
        </div>
        
        <div class="flex gap-4 pt-6 border-t border-gray-100 dark:border-slate-700">
            <a href="/dashboard/index" class="w-1/3 text-center px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">Cancelar</a>
            <button type="submit" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-600/20 dark:shadow-blue-900/20">Realizar Transferencia</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
