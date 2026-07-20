<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="mb-8 mt-4 flex justify-between items-center opacity-0 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Inversiones</h1>
        <p class="text-slate-500 dark:text-slate-400">Haz crecer tu patrimonio y monitorea tus rendimientos.</p>
    </div>
    <a href="/investment/create" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl transition shadow-lg shadow-blue-600/20"><i class="fa-solid fa-plus mr-2"></i>Nueva Inversión</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10 stagger-list">
    <?php if (empty($investments)): ?>
        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-200 dark:border-slate-700">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500 mx-auto mb-4">
                <i class="fa-solid fa-seedling text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Aún no tienes inversiones registradas. ¡Empieza hoy!</p>
        </div>
    <?php else: ?>
        <?php foreach ($investments as $inv): 
            $rendimiento_absoluto = $inv['valor_actual'] - $inv['monto_invertido'];
            $rendimiento_porcentaje = ($inv['monto_invertido'] > 0) ? ($rendimiento_absoluto / $inv['monto_invertido']) * 100 : 0;
            
            $icono = 'fa-chart-pie';
            switch ($inv['tipo']) {
                case 'cripto': $icono = 'fa-bitcoin'; break;
                case 'acciones': $icono = 'fa-arrow-trend-up'; break;
                case 'plazo_fijo': $icono = 'fa-building-columns'; break;
                case 'fondo': $icono = 'fa-layer-group'; break;
            }
        ?>
            <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 opacity-0 animate-fade-in-up hover:scale-[1.03] hover:-translate-y-2 hover:shadow-2xl hover:shadow-emerald-500/10 hover:z-10 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-12">
                            <i class="fa-brands <?= $icono ?> fa-solid <?= $icono ?>"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($inv['nombre']) ?></h3>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300"><?= ucfirst(str_replace('_', ' ', $inv['tipo'])) ?></span>
                        </div>
                    </div>
                    <form action="/investment/delete/<?= $inv['id'] ?>" method="POST" onsubmit="return confirm('¿Eliminar esta inversión?');">
                        <button type="submit" class="text-slate-300 hover:text-red-500 dark:text-slate-500 dark:hover:text-red-400 transition" title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
                
                <div class="mb-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-semibold tracking-wider mb-1">Valor Actual</p>
                    <div class="text-3xl font-bold text-slate-800 dark:text-white sensitive-balance">
                        $<?= number_format($inv['valor_actual'], 2) ?>
                    </div>
                </div>

                <div class="flex justify-between items-end mb-4">
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Invertido: <span class="font-semibold sensitive-balance">$<?= number_format($inv['monto_invertido'], 2) ?></span></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Desde: <span class="font-semibold"><?= date('d M Y', strtotime($inv['fecha_inicio'])) ?></span></p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold <?= $rendimiento_absoluto >= 0 ? 'text-emerald-500' : 'text-red-500' ?>">
                            <?= $rendimiento_absoluto >= 0 ? '+' : '' ?>$<?= number_format($rendimiento_absoluto, 2) ?>
                        </div>
                        <div class="text-xs font-semibold <?= $rendimiento_porcentaje >= 0 ? 'text-emerald-500 bg-emerald-50 dark:bg-emerald-900/30 animate-pulse-glow' : 'text-red-500 bg-red-50 dark:bg-red-900/30' ?> px-2 py-0.5 rounded-md inline-block">
                            <?= $rendimiento_porcentaje >= 0 ? '+' : '' ?><?= number_format($rendimiento_porcentaje, 2) ?>%
                        </div>
                    </div>
                </div>

                <!-- Update value form -->
                <form action="/investment/update" method="POST" class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700 flex gap-2">
                    <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-400 font-medium text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" name="nuevo_valor" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg pl-7 pr-3 py-1.5 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" placeholder="Actualizar valor" required>
                    </div>
                    <button type="submit" class="bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-lg px-3 py-1.5 text-sm font-semibold transition" title="Actualizar">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
