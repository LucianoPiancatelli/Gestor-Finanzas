<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="mb-8 mt-4 opacity-0 animate-fade-in-up">
    <h1 class="text-3xl font-bold mb-2 text-slate-800 dark:text-white">Historial de Transacciones</h1>
    <p class="text-slate-500 dark:text-slate-400">Revisa y filtra todos tus movimientos financieros.</p>
</div>

<!-- Filtros -->
<div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm mb-8 transition-all duration-300 opacity-0 animate-fade-in-up [animation-delay:100ms] hover:shadow-md">
    <form action="/transaction/index" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($filtros['fecha_inicio']) ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Fecha Fin</label>
            <input type="date" name="fecha_fin" value="<?= htmlspecialchars($filtros['fecha_fin']) ?>" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Categoría</label>
            <select name="categoria_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none">
                <option value="">Todas</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $filtros['categoria_id'] == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tipo</label>
            <select name="tipo" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none">
                <option value="">Todos</option>
                <option value="ingreso" <?= $filtros['tipo'] === 'ingreso' ? 'selected' : '' ?>>Ingreso</option>
                <option value="gasto" <?= $filtros['tipo'] === 'gasto' ? 'selected' : '' ?>>Gasto</option>
            </select>
        </div>
        <div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40">Filtrar</button>
        </div>
    </form>
</div>

<!-- Tabla -->
<div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden mb-8 transition-all duration-300 opacity-0 animate-fade-in-up [animation-delay:300ms]">
    <?php if (empty($transacciones)): ?>
        <p class="text-slate-500 dark:text-slate-400 text-center py-8">No se encontraron transacciones con estos filtros.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-sm bg-gray-50 dark:bg-slate-800/50">
                        <th class="py-4 px-6 font-semibold rounded-tl-3xl">Fecha</th>
                        <th class="py-4 px-6 font-semibold">Descripción</th>
                        <th class="py-4 px-6 font-semibold">Categoría</th>
                        <th class="py-4 px-6 font-semibold">Monto</th>
                        <th class="py-4 px-6 font-semibold rounded-tr-3xl">Acciones</th>
                    </tr>
                </thead>
                <tbody class="stagger-list">
                    <?php foreach ($transacciones as $t): ?>
                        <tr class="border-b border-gray-50 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 opacity-0 animate-fade-in-up hover:-translate-y-1 hover:shadow-md hover:z-10 relative bg-white dark:bg-slate-800 cursor-pointer">
                            <td class="py-4 px-6 text-sm text-slate-600 dark:text-slate-400"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($t['creado_en']))) ?></td>
                            <td class="py-4 px-6 font-medium text-slate-800 dark:text-white"><?= htmlspecialchars($t['descripcion']) ?></td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $t['categoria_tipo'] == 'ingreso' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400' ?>">
                                    <?= htmlspecialchars($t['categoria_nombre']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 font-bold sensitive-balance <?= $t['categoria_tipo'] == 'ingreso' ? 'text-emerald-500 dark:text-emerald-400' : 'text-slate-800 dark:text-white' ?>">
                                <?= $t['categoria_tipo'] == 'ingreso' ? '+' : '-' ?>$<?= number_format($t['monto'], 2) ?>
                            </td>
                            <td class="py-4 px-6 text-sm">
                                <div class="flex gap-4">
                                    <a href="/transaction/receipt/<?= $t['id'] ?>" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition" title="Ver Detalle">
                                        <i class="fa-solid fa-file-pdf"></i> Detalle
                                    </a>
                                    <form action="/transaction/delete/<?= $t['id'] ?>" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta transacción? Se revertirá el saldo de la cuenta.');" class="inline">
                                        <button type="submit" class="text-slate-300 hover:text-red-500 dark:text-slate-500 dark:hover:text-red-400 transition" title="Eliminar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Paginación -->
<?php if ($totalPages > 1): ?>
<div class="flex justify-center items-center space-x-2 mb-10">
    <!-- Helper para mantener los filtros en la URL -->
    <?php 
        $queryString = http_build_query(array_filter($filtros));
        $baseUrl = "/transaction/index" . (!empty($queryString) ? "?$queryString&" : "?");
    ?>
    
    <?php if ($page > 1): ?>
        <a href="<?= $baseUrl . 'page=' . ($page - 1) ?>" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">Anterior</a>
    <?php else: ?>
        <button disabled class="px-4 py-2 bg-gray-50 dark:bg-slate-800/50 border border-gray-100 dark:border-slate-700/50 text-gray-400 dark:text-slate-500 rounded-xl cursor-not-allowed">Anterior</button>
    <?php endif; ?>

    <div class="hidden sm:flex space-x-2">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?= $baseUrl . 'page=' . $i ?>" class="px-4 py-2 rounded-xl border <?= $i == $page ? 'bg-blue-600 border-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700' ?> transition font-medium">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <?php if ($page < $totalPages): ?>
        <a href="<?= $baseUrl . 'page=' . ($page + 1) ?>" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">Siguiente</a>
    <?php else: ?>
        <button disabled class="px-4 py-2 bg-gray-50 dark:bg-slate-800/50 border border-gray-100 dark:border-slate-700/50 text-gray-400 dark:text-slate-500 rounded-xl cursor-not-allowed">Siguiente</button>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
