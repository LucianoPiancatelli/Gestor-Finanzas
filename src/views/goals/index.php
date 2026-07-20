<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="mb-8 mt-4 flex justify-between items-center opacity-0 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Objetivos de Ahorro</h1>
        <p class="text-slate-500 dark:text-slate-400">Define tus metas financieras y sigue tu progreso.</p>
    </div>
    <a href="/goal/create" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl transition shadow-lg shadow-blue-600/20"><i class="fa-solid fa-plus mr-2"></i>Nuevo Objetivo</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10 stagger-list">
    <?php if (empty($goals)): ?>
        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-200 dark:border-slate-700 opacity-0 animate-fade-in-up">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500 mx-auto mb-4">
                <i class="fa-solid fa-bullseye text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-slate-400 font-medium">No tienes objetivos de ahorro. ¡Crea el primero!</p>
        </div>
    <?php else: ?>
        <?php foreach ($goals as $g): 
            $porcentaje = ($g['monto_objetivo'] > 0) ? ($g['monto_actual'] / $g['monto_objetivo']) * 100 : 0;
            if ($porcentaje > 100) $porcentaje = 100;
        ?>
            <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 opacity-0 animate-fade-in-up hover:scale-[1.03] hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/20 hover:z-10 group">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($g['nombre']) ?></h3>
                    <form action="/goal/delete/<?= $g['id'] ?>" method="POST" onsubmit="return confirm('¿Eliminar este objetivo?');">
                        <button type="submit" class="text-slate-300 hover:text-red-500 dark:text-slate-500 dark:hover:text-red-400 transition" title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Circular Progress Bar approximation using horizontal for simplicity, or we can use custom SVG -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm font-semibold mb-2">
                        <span class="text-blue-600 dark:text-blue-400 sensitive-balance">$<?= number_format($g['monto_actual'], 2) ?></span>
                        <span class="text-slate-500 dark:text-slate-400 sensitive-balance">de $<?= number_format($g['monto_objetivo'], 2) ?></span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-900 rounded-full h-3 overflow-hidden">
                        <div class="bg-blue-500 h-3 rounded-full transition-all duration-1000 ease-out <?= $porcentaje >= 100 ? 'animate-pulse-glow bg-emerald-500' : '' ?>" style="width: 0%;" data-width="<?= $porcentaje ?>%"></div>
                        <script>
                            setTimeout(() => {
                                document.querySelectorAll('[data-width]').forEach(el => {
                                    el.style.width = el.getAttribute('data-width');
                                });
                            }, 300);
                        </script>
                    </div>
                    <p class="text-right text-xs font-bold text-slate-400 mt-1"><?= number_format($porcentaje, 1) ?>% completado</p>
                </div>

                <?php if (!empty($g['fecha_limite'])): ?>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4"><i class="fa-regular fa-calendar mr-1"></i> Límite: <?= date('d M Y', strtotime($g['fecha_limite'])) ?></p>
                <?php endif; ?>

                <form action="/goal/addFunds" method="POST" class="flex gap-2">
                    <input type="hidden" name="id" value="<?= $g['id'] ?>">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-400 font-medium text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" name="monto" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg pl-7 pr-3 py-2 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" placeholder="Añadir fondos" required>
                    </div>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 active:scale-90 text-white rounded-lg px-3 py-2 text-sm font-semibold transition-transform duration-200 hover:shadow-lg shadow-emerald-500/30"><i class="fa-solid fa-plus group-hover:animate-bounce"></i></button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
