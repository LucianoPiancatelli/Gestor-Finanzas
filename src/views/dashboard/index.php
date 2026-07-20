<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="mb-8 mt-4 flex justify-between items-center opacity-0 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-bold mb-2 text-slate-800 dark:text-white flex items-center gap-3">
            Hola, <?= htmlspecialchars($nombre) ?> 
            <button id="privacy-toggle" class="text-slate-400 hover:text-blue-600 dark:text-slate-500 dark:hover:text-blue-400 transition text-xl focus:outline-none" title="Modo Privacidad">
                <i id="privacy-icon" class="fa-solid fa-eye-slash"></i>
            </button>
        </h1>
        <p class="text-slate-500 dark:text-slate-400">Aquí está el resumen de tus finanzas.</p>
    </div>
    <div class="flex gap-4">
        <a href="/transaction/transfer" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium py-2 px-4 rounded-xl transition shadow-sm"><i class="fa-solid fa-right-left mr-2"></i> Transferir</a>
        <a href="/transaction/create" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-xl transition shadow-sm"><i class="fa-solid fa-plus mr-2"></i> Nueva Operación</a>
    </div>
</div>

<!-- Tarjetas de Resumen -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Balance Total -->
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 opacity-0 animate-fade-in-up [animation-delay:100ms] hover:scale-[1.03] hover:shadow-xl hover:-translate-y-1 cursor-default group">
        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Tu Dinero (Total)</h3>
        <div class="text-3xl font-bold sensitive-balance <?= $balance['balance'] >= 0 ? 'text-slate-800 dark:text-white' : 'text-red-500 dark:text-red-400' ?>">
            $<?= number_format($balance['balance'], 2) ?>
        </div>
    </div>
    
    <!-- Ingresos del Mes -->
    <div class="bg-white dark:bg-slate-800 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 opacity-0 animate-fade-in-up [animation-delay:200ms] hover:scale-[1.03] hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1 cursor-default group">
        <div class="absolute top-0 right-0 p-4 opacity-10 dark:opacity-20 group-hover:animate-float">
            <i class="fa-solid fa-arrow-trend-up fa-3x text-emerald-500"></i>
        </div>
        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Ingresos del Mes</h3>
        <div class="text-3xl font-bold text-emerald-500 sensitive-balance">
            $<?= number_format($balance['ingresos_mes'], 2) ?>
        </div>
    </div>

    <!-- Gastos del Mes -->
    <div class="bg-white dark:bg-slate-800 border border-red-100 dark:border-red-900/30 rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 opacity-0 animate-fade-in-up [animation-delay:300ms] hover:scale-[1.03] hover:shadow-xl hover:shadow-red-500/10 hover:-translate-y-1 cursor-default group">
        <div class="absolute top-0 right-0 p-4 opacity-10 dark:opacity-20 group-hover:animate-float">
            <i class="fa-solid fa-arrow-trend-down fa-3x text-red-500"></i>
        </div>
        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Gastos del Mes</h3>
        <div class="text-3xl font-bold text-red-500 sensitive-balance">
            $<?= number_format($balance['gastos_mes'], 2) ?>
        </div>
    </div>

    <!-- Tasa de Ahorro -->
    <div class="bg-white dark:bg-slate-800 border border-blue-100 dark:border-blue-900/30 rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 opacity-0 animate-fade-in-up [animation-delay:400ms] hover:scale-[1.03] hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 cursor-default group">
        <div class="absolute top-0 right-0 p-4 opacity-10 dark:opacity-20 group-hover:animate-float">
            <i class="fa-solid fa-piggy-bank fa-3x text-blue-500"></i>
        </div>
        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Tasa de Ahorro</h3>
        <div class="text-3xl font-bold <?= $tasaAhorro >= 20 ? 'text-blue-500' : ($tasaAhorro > 0 ? 'text-emerald-500' : 'text-slate-400') ?> sensitive-balance">
            <?= number_format($tasaAhorro, 1) ?>%
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
    
    <!-- Chart.js: Análisis de Gastos -->
    <div class="col-span-1 lg:col-span-1">
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm h-full transition-all duration-300 opacity-0 animate-slide-in-right [animation-delay:500ms] hover:shadow-lg">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Análisis de Gastos</h2>
            <?php if (empty($gastosPorCategoria)): ?>
                <p class="text-slate-400 text-center py-8">No hay gastos registrados este mes.</p>
            <?php else: ?>
                <div class="relative w-full aspect-square flex items-center justify-center">
                    <canvas id="gastosChart"></canvas>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('gastosChart').getContext('2d');
                        const data = <?= json_encode($gastosPorCategoria) ?>;
                        
                        const labels = data.map(item => item.categoria_nombre);
                        const values = data.map(item => parseFloat(item.total));
                        const totalGastos = values.reduce((a, b) => a + b, 0);
                        
                        // Generar paleta de colores moderna
                        const bgColors = [
                            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
                            '#8b5cf6', '#ec4899', '#14b8a6', '#6366f1'
                        ];

                        let myChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: values,
                                    backgroundColor: bgColors.slice(0, values.length),
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '70%',
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true,
                                            font: { family: "'Inter', sans-serif", size: 12 },
                                            color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let val = context.raw;
                                                let perc = ((val / totalGastos) * 100).toFixed(1);
                                                return ` $${val.toLocaleString()} (${perc}%)`;
                                            }
                                        },
                                        bodyFont: { family: "'Inter', sans-serif" },
                                        titleFont: { family: "'Inter', sans-serif" }
                                    }
                                }
                            }
                        });
                        
                        window.addEventListener('theme-changed', () => {
                            myChart.options.plugins.legend.labels.color = document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b';
                            myChart.update();
                        });
                    });
                </script>
            <?php endif; ?>
        </div>
    </div>

    <!-- Transacciones Recientes -->
    <div class="col-span-1 lg:col-span-2">
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm h-full transition-all duration-300 opacity-0 animate-fade-in-up [animation-delay:600ms] hover:shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Transacciones Recientes</h2>
                <a href="/transaction/index" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 text-sm font-medium">Ver Todas &rarr;</a>
            </div>
            
            <?php if (empty($recentTransactions)): ?>
                <p class="text-slate-400 text-center py-8">No tienes transacciones todavía. ¡Añade la primera!</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody class="stagger-list">
                            <?php foreach ($recentTransactions as $t): ?>
                                <tr class="border-b border-gray-100 dark:border-slate-700 last:border-0 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 opacity-0 animate-fade-in-up hover:-translate-y-1 hover:shadow-md hover:z-10 relative bg-white dark:bg-slate-800 cursor-pointer">
                                    <td class="py-4 px-2">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center <?= $t['categoria_tipo'] == 'ingreso' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' ?>">
                                                <?php
                                                    $icono = 'fa-tag';
                                                    $nombre_cat = strtolower($t['categoria_nombre']);
                                                    if (strpos($nombre_cat, 'comida') !== false) $icono = 'fa-burger';
                                                    if (strpos($nombre_cat, 'servicio') !== false) $icono = 'fa-bolt';
                                                    if (strpos($nombre_cat, 'transf') !== false) $icono = 'fa-money-bill-transfer';
                                                    if (strpos($nombre_cat, 'sueldo') !== false || strpos($nombre_cat, 'salario') !== false) $icono = 'fa-building-columns';
                                                    if ($t['categoria_tipo'] == 'ingreso' && $icono == 'fa-tag') $icono = 'fa-arrow-down';
                                                    if ($t['categoria_tipo'] == 'gasto' && $icono == 'fa-tag') $icono = 'fa-arrow-up';
                                                ?>
                                                <i class="fa-solid <?= $icono ?>"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($t['descripcion']) ?></div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars(date('d M Y', strtotime($t['fecha']))) ?> • <?= htmlspecialchars($t['categoria_nombre']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-2 text-right">
                                        <div class="font-bold sensitive-balance <?= $t['categoria_tipo'] == 'ingreso' ? 'text-emerald-500 dark:text-emerald-400' : 'text-slate-800 dark:text-white' ?>">
                                            <?= $t['categoria_tipo'] == 'ingreso' ? '+' : '-' ?>$<?= number_format($t['monto'], 2) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
