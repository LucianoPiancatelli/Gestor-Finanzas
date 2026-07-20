<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="mb-8 mt-4 flex justify-between items-center opacity-0 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-bold mb-2 text-slate-800 dark:text-white">Presupuestos</h1>
        <p class="text-slate-500 dark:text-slate-400">Establece límites de gasto y controla tus finanzas mensuales.</p>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500 text-red-600 dark:text-red-400 p-4 rounded-xl mb-6 flex items-center">
        <i class="fa-solid fa-circle-exclamation mr-3"></i>
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Formulario Crear/Editar -->
    <div class="col-span-1">
        <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm sticky top-6 transition-all duration-300 opacity-0 animate-slide-in-right [animation-delay:100ms] hover:shadow-md">
            <h2 class="text-xl font-bold mb-6 text-slate-800 dark:text-white">Asignar Presupuesto</h2>
            <form action="/budget/index?mes=<?= $mes ?>&anio=<?= $anio ?>" method="POST" class="space-y-5">
                <input type="hidden" name="action" value="save">
                
                <div>
                    <label for="categoria_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Categoría</label>
                    <select id="categoria_id" name="categoria_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none" required>
                        <option value="">Seleccione una categoría...</option>
                        <?php foreach ($categorias as $c): ?>
                            <?php if ($c['tipo'] == 'gasto'): // Presupuestos solo tienen sentido para gastos ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="monto_limite" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Límite Mensual ($)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" id="monto_limite" name="monto_limite" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl pl-8 pr-4 py-3 text-slate-800 dark:text-white font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" required placeholder="0.00">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40">Guardar Presupuesto</button>
            </form>
        </div>
    </div>

    <!-- Lista y Barras de Progreso -->
    <div class="col-span-1 lg:col-span-2">
        <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm mb-6 transition-all duration-300 opacity-0 animate-fade-in-up [animation-delay:300ms]">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-slate-700 pb-4">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Estado del Mes: <?= date('F', mktime(0, 0, 0, $mes, 1)) ?> <?= $anio ?></h2>
                <div class="flex gap-2">
                    <a href="/budget/index?mes=<?= $mes == 1 ? 12 : $mes - 1 ?>&anio=<?= $mes == 1 ? $anio - 1 : $anio ?>" class="px-3 py-1 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded text-sm text-slate-700 dark:text-slate-300 font-medium transition">&larr; Ant</a>
                    <a href="/budget/index?mes=<?= $mes == 12 ? 1 : $mes + 1 ?>&anio=<?= $mes == 12 ? $anio + 1 : $anio ?>" class="px-3 py-1 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded text-sm text-slate-700 dark:text-slate-300 font-medium transition">Sig &rarr;</a>
                </div>
            </div>

            <?php if (empty($presupuestos)): ?>
                <div class="text-center py-12 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                    <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-300 dark:text-slate-500 mx-auto mb-3 shadow-sm">
                        <i class="fa-solid fa-chart-pie text-2xl"></i>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">No has definido presupuestos para este mes.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4 stagger-list">
                    <?php foreach ($presupuestos as $p): ?>
                        <?php
                            $porcentaje = ($p['monto_limite'] > 0) ? ($p['gastado'] / $p['monto_limite']) * 100 : 0;
                            $porcentaje_ancho = min($porcentaje, 100);
                            
                            $color_barra = 'bg-emerald-500';
                            if ($porcentaje >= 80) {
                                $color_barra = 'bg-red-500';
                            } elseif ($porcentaje >= 50) {
                                $color_barra = 'bg-yellow-500';
                            }

                            // Cálculo del Gasto Seguro Diario
                            $is_current_month = (date('n') == $mes && date('Y') == $anio);
                            $safe_to_spend = 0;
                            if ($is_current_month) {
                                $days_in_month = date('t');
                                $current_day = date('j');
                                $days_left = $days_in_month - $current_day + 1; // Incluyendo el día actual
                                $remaining = $p['monto_limite'] - $p['gastado'];
                                if ($remaining > 0) {
                                    $safe_to_spend = $remaining / $days_left;
                                }
                            }
                        ?>
                        <div class="relative bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-xl hover:scale-[1.02] hover:-translate-y-1 hover:z-10 transition-all duration-300 opacity-0 animate-fade-in-up group">
                            <div class="flex justify-between items-end mb-3">
                                <div>
                                    <h3 class="font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($p['categoria_nombre']) ?></h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Gastado: $<?= number_format($p['gastado'], 2) ?> de $<?= number_format($p['monto_limite'], 2) ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-bold <?= $porcentaje >= 100 ? 'text-red-500 dark:text-red-400' : 'text-slate-700 dark:text-slate-300' ?>"><?= number_format($porcentaje, 1) ?>%</span>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-100 dark:bg-slate-900 rounded-full h-3 mb-2 overflow-hidden">
                                <div class="<?= $color_barra ?> h-3 rounded-full transition-all duration-1000 ease-out <?= $porcentaje >= 100 ? 'animate-pulse-glow' : '' ?>" style="width: 0%;" data-width="<?= $porcentaje_ancho ?>%"></div>
                                <script>
                                    setTimeout(() => {
                                        document.querySelectorAll('[data-width]').forEach(el => {
                                            el.style.width = el.getAttribute('data-width');
                                        });
                                    }, 400);
                                </script>
                            </div>

                            <?php if ($is_current_month && $porcentaje < 100): ?>
                                <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg p-3 text-sm flex justify-between items-center mt-3 border border-blue-100 dark:border-blue-800/50">
                                    <span class="font-medium"><i class="fa-solid fa-calendar-day mr-1"></i> Gasto Seguro Diario:</span>
                                    <span class="font-bold sensitive-balance">$<?= number_format($safe_to_spend, 2) ?> / día</span>
                                </div>
                            <?php elseif ($is_current_month && $porcentaje >= 100): ?>
                                <div class="bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-lg p-3 text-sm flex justify-between items-center mt-3 border border-red-100 dark:border-red-800/50">
                                    <span class="font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Presupuesto Excedido</span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Eliminar Presupuesto -->
                            <form action="/budget/index?mes=<?= $mes ?>&anio=<?= $anio ?>" method="POST" class="absolute top-4 right-4" onsubmit="return confirm('¿Eliminar este presupuesto?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="text-slate-300 dark:text-slate-500 hover:text-red-500 dark:hover:text-red-400 transition bg-slate-50 dark:bg-slate-900 w-8 h-8 rounded-full flex items-center justify-center hover:bg-red-50 dark:hover:bg-red-900/30">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
