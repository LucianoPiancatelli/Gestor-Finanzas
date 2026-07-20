<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-slate-700 mt-8 mb-12 transition-colors duration-200">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="fa-solid fa-plus"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Nueva Operación</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Registra un nuevo ingreso o gasto</p>
    </div>
    
    <?php if (!empty($error)): ?>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500 text-red-600 dark:text-red-400 p-4 rounded-xl mb-6 flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-3 text-lg"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/transaction/create" method="POST" class="space-y-8" id="transactionForm">
        
        <!-- Tab selector Ingreso / Gasto -->
        <div class="flex p-1 bg-slate-100 dark:bg-slate-900/50 rounded-2xl" id="typeSelector">
            <button type="button" class="flex-1 py-3 text-sm font-semibold rounded-xl transition-all shadow-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-white" onclick="switchType('gasto', this)">
                <i class="fa-solid fa-arrow-trend-down mr-2 text-red-500 dark:text-red-400"></i> Gasto
            </button>
            <button type="button" class="flex-1 py-3 text-sm font-medium rounded-xl transition-all text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300" onclick="switchType('ingreso', this)">
                <i class="fa-solid fa-arrow-trend-up mr-2 text-emerald-500 dark:text-emerald-400"></i> Ingreso
            </button>
        </div>

        <!-- Oculto para la selección real -->
        <input type="hidden" name="categoria_id" id="categoria_id" required>

        <!-- Selector de Categorías -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Categoría</label>
            
            <!-- Gastos -->
            <div id="grid-gasto" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                <?php foreach ($categorias as $c): ?>
                    <?php if ($c['tipo'] == 'gasto'): 
                        $icono = 'fa-tag';
                        $nombre = strtolower($c['nombre']);
                        if (strpos($nombre, 'comida') !== false || strpos($nombre, 'super') !== false) $icono = 'fa-basket-shopping';
                        elseif (strpos($nombre, 'servicio') !== false) $icono = 'fa-plug';
                        elseif (strpos($nombre, 'entrete') !== false) $icono = 'fa-ticket';
                        elseif (strpos($nombre, 'transporte') !== false || strpos($nombre, 'auto') !== false) $icono = 'fa-car';
                        elseif (strpos($nombre, 'salud') !== false) $icono = 'fa-notes-medical';
                        elseif (strpos($nombre, 'alquiler') !== false || strpos($nombre, 'hogar') !== false) $icono = 'fa-house';
                    ?>
                    <button type="button" onclick="selectCategory('<?= $c['id'] ?>', this)" class="category-btn bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-slate-800 rounded-2xl p-4 flex flex-col items-center justify-center transition-all group">
                        <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:scale-110 transition-transform mb-2">
                            <i class="fa-solid <?= $icono ?>"></i>
                        </div>
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400 text-center line-clamp-1"><?= htmlspecialchars($c['nombre']) ?></span>
                    </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Ingresos -->
            <div id="grid-ingreso" class="grid grid-cols-3 sm:grid-cols-4 gap-3 hidden">
                <?php foreach ($categorias as $c): ?>
                    <?php if ($c['tipo'] == 'ingreso'): 
                        $icono = 'fa-arrow-down';
                        $nombre = strtolower($c['nombre']);
                        if (strpos($nombre, 'sueldo') !== false || strpos($nombre, 'salario') !== false) $icono = 'fa-building-columns';
                        elseif (strpos($nombre, 'venta') !== false) $icono = 'fa-shop';
                        elseif (strpos($nombre, 'invers') !== false) $icono = 'fa-chart-line';
                        elseif (strpos($nombre, 'regalo') !== false) $icono = 'fa-gift';
                    ?>
                    <button type="button" onclick="selectCategory('<?= $c['id'] ?>', this)" class="category-btn bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-slate-800 rounded-2xl p-4 flex flex-col items-center justify-center transition-all group">
                        <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:scale-110 transition-transform mb-2">
                            <i class="fa-solid <?= $icono ?>"></i>
                        </div>
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400 text-center line-clamp-1"><?= htmlspecialchars($c['nombre']) ?></span>
                    </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <p id="cat-error" class="text-red-500 text-sm mt-2 hidden">Debes seleccionar una categoría.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="monto" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Monto</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-400 dark:text-slate-500 font-medium">$</span>
                    </div>
                    <input type="number" step="0.01" min="0" id="monto" name="monto" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl pl-8 pr-4 py-3 text-slate-800 dark:text-white font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" required placeholder="0.00">
                </div>
            </div>
            
            <div>
                <label for="cuenta_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Cuenta</label>
                <select id="cuenta_id" name="cuenta_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none" required>
                    <option value="">Seleccione una cuenta...</option>
                    <?php foreach ($cuentas as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?> ($<?= number_format($c['saldo'], 2) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div>
            <label for="descripcion" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Descripción (Opcional)</label>
            <input type="text" id="descripcion" name="descripcion" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" required placeholder="Ej. Compra de supermercado">
        </div>

        <div>
            <label for="fecha" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Fecha</label>
            <input type="date" id="fecha" name="fecha" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="bg-blue-50/50 dark:bg-blue-900/10 p-5 rounded-2xl border border-blue-100 dark:border-blue-900/30">
            <div class="flex items-center">
                <input type="checkbox" id="es_recurrente" name="es_recurrente" class="w-5 h-5 text-blue-600 bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-700 rounded focus:ring-blue-500 focus:ring-2" onchange="document.getElementById('freq-container').classList.toggle('hidden')">
                <label for="es_recurrente" class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">Esta es una transacción recurrente</label>
            </div>
            <div id="freq-container" class="mt-4 hidden pl-8">
                <label for="frecuencia" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Frecuencia de repetición</label>
                <select id="frecuencia" name="frecuencia" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-slate-800 dark:text-white focus:outline-none focus:border-blue-500 transition">
                    <option value="mensual">Mensual</option>
                    <option value="anual">Anual</option>
                </select>
            </div>
        </div>
        
        <div class="flex gap-4 pt-4 border-t border-gray-100 dark:border-slate-700">
            <a href="/dashboard/index" class="w-1/3 text-center px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">Cancelar</a>
            <button type="submit" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-600/20 dark:shadow-blue-900/20" onclick="return validateCategory()">Registrar Operación</button>
        </div>
    </form>
</div>

<script>
    function switchType(type, btn) {
        // Reset category selection when switching type
        document.getElementById('categoria_id').value = '';
        document.querySelectorAll('.category-btn').forEach(b => {
            b.classList.remove('border-blue-500', 'dark:border-blue-500', 'bg-blue-50', 'dark:bg-slate-800', 'ring-2', 'ring-blue-500/20');
            b.classList.add('border-slate-200', 'dark:border-slate-700', 'bg-slate-50', 'dark:bg-slate-900');
            b.querySelector('div').classList.remove('text-blue-600', 'dark:text-blue-400');
            b.querySelector('div').classList.add('text-slate-500', 'dark:text-slate-400');
        });

        // Tabs styling
        const buttons = btn.parentElement.querySelectorAll('button');
        buttons.forEach(b => {
            b.classList.remove('bg-white', 'dark:bg-slate-700', 'shadow-sm', 'text-slate-800', 'dark:text-white', 'font-semibold');
            b.classList.add('text-slate-500', 'dark:text-slate-400', 'font-medium');
        });
        btn.classList.add('bg-white', 'dark:bg-slate-700', 'shadow-sm', 'text-slate-800', 'dark:text-white', 'font-semibold');
        btn.classList.remove('text-slate-500', 'dark:text-slate-400', 'font-medium');

        // Show/Hide grids
        if (type === 'gasto') {
            document.getElementById('grid-gasto').classList.remove('hidden');
            document.getElementById('grid-ingreso').classList.add('hidden');
        } else {
            document.getElementById('grid-ingreso').classList.remove('hidden');
            document.getElementById('grid-gasto').classList.add('hidden');
        }
    }

    function selectCategory(id, btn) {
        document.getElementById('categoria_id').value = id;
        document.getElementById('cat-error').classList.add('hidden');
        
        // Remove active state from all buttons
        document.querySelectorAll('.category-btn').forEach(b => {
            b.classList.remove('border-blue-500', 'dark:border-blue-500', 'bg-blue-50', 'dark:bg-slate-800', 'ring-2', 'ring-blue-500/20');
            b.classList.add('border-slate-200', 'dark:border-slate-700', 'bg-slate-50', 'dark:bg-slate-900');
            b.querySelector('div').classList.remove('text-blue-600', 'dark:text-blue-400');
            b.querySelector('div').classList.add('text-slate-500', 'dark:text-slate-400');
        });

        // Add active state to clicked
        btn.classList.remove('border-slate-200', 'dark:border-slate-700', 'bg-slate-50', 'dark:bg-slate-900');
        btn.classList.add('border-blue-500', 'dark:border-blue-500', 'bg-blue-50', 'dark:bg-slate-800', 'ring-2', 'ring-blue-500/20');
        btn.querySelector('div').classList.remove('text-slate-500', 'dark:text-slate-400');
        btn.querySelector('div').classList.add('text-blue-600', 'dark:text-blue-400');
    }

    function validateCategory() {
        if (!document.getElementById('categoria_id').value) {
            document.getElementById('cat-error').classList.remove('hidden');
            return false;
        }
        return true;
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
