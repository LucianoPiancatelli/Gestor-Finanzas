<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-xl mx-auto bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl p-8 shadow-sm mt-8 transition-colors duration-200">
    <div class="flex justify-center mb-4 text-blue-600 dark:text-blue-400">
        <i class="fa-solid fa-paper-plane fa-3x"></i>
    </div>
    <h2 class="text-2xl font-bold mb-2 text-slate-800 dark:text-white text-center">Enviar Dinero</h2>
    <p class="text-slate-500 dark:text-slate-400 text-center mb-6 text-sm">Transfiere al instante a otras cuentas usando solo el email del destinatario.</p>
    
    <?php if (!empty($error)): ?>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500 text-red-600 dark:text-red-400 p-4 rounded-xl mb-6 flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-3"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/transaction/transfer_p2p" method="POST" class="space-y-5">
        <div>
            <label for="cuenta_origen_id" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Pagar con</label>
            <div class="relative">
                <i class="fa-solid fa-wallet absolute left-4 top-3 text-slate-400 dark:text-slate-500"></i>
                <select id="cuenta_origen_id" name="cuenta_origen_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-10 pr-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition appearance-none" required>
                    <option value="">Seleccione una cuenta...</option>
                    <?php foreach ($cuentas as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?> (Saldo: $<?= number_format($c['saldo'], 2) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div>
            <label for="email_destinatario" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Email del Destinatario</label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-4 top-3 text-slate-400 dark:text-slate-500"></i>
                <input type="email" id="email_destinatario" name="email_destinatario" class="w-full bg-slate-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-10 pr-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required placeholder="ejemplo@correo.com">
            </div>
        </div>

        <div>
            <label for="monto" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Monto ($)</label>
            <div class="relative">
                <i class="fa-solid fa-dollar-sign absolute left-4 top-3 text-slate-400 dark:text-slate-500"></i>
                <input type="number" step="0.01" min="0" id="monto" name="monto" class="w-full bg-slate-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-10 pr-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition font-bold text-xl" required placeholder="0.00">
            </div>
        </div>
        
        <input type="hidden" name="fecha" value="<?= date('Y-m-d') ?>">
        
        <div class="flex gap-4 pt-6">
            <a href="/dashboard/index" class="w-1/3 text-center px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 font-medium transition shadow-sm">Cancelar</a>
            <button type="submit" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md shadow-blue-500/20 dark:shadow-blue-900/20">Enviar Dinero</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
