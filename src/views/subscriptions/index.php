<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="mb-8 mt-4 flex justify-between items-center opacity-0 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Suscripciones y Pagos Fijos</h1>
        <p class="text-slate-500 dark:text-slate-400">Administra tus servicios recurrentes y evita sorpresas.</p>
    </div>
    <a href="/subscription/create" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl transition shadow-lg shadow-blue-600/20"><i class="fa-solid fa-plus mr-2"></i>Nueva Suscripción</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10 stagger-list">
    <?php if (empty($subscriptions)): ?>
        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-200 dark:border-slate-700 opacity-0 animate-fade-in-up">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500 mx-auto mb-4">
                <i class="fa-solid fa-rotate text-2xl"></i>
            </div>
            <p class="text-slate-500 dark:text-slate-400 font-medium">No tienes suscripciones registradas. ¡Añade Netflix o tu alquiler!</p>
        </div>
    <?php else: ?>
        <?php foreach ($subscriptions as $s): ?>
            <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 opacity-0 animate-fade-in-up hover:scale-[1.03] hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 hover:z-10 group <?= !$s['activo'] ? 'opacity-60 grayscale hover:opacity-100 hover:grayscale-0' : '' ?>">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center transition-transform duration-300 group-hover:rotate-[360deg] group-hover:scale-110">
                            <?php
                                $icono_suscripcion = 'fa-tag';
                                $nombre_cat_s = strtolower($s['categoria_nombre']);
                                if (strpos($nombre_cat_s, 'servicio') !== false) $icono_suscripcion = 'fa-bolt';
                                if (strpos($nombre_cat_s, 'entretenimiento') !== false || strpos($nombre_cat_s, 'suscrip') !== false) $icono_suscripcion = 'fa-play';
                                if (strpos(strtolower($s['nombre']), 'netflix') !== false) $icono_suscripcion = 'fa-video';
                                if (strpos(strtolower($s['nombre']), 'spotify') !== false) $icono_suscripcion = 'fa-music';
                            ?>
                            <i class="fa-solid <?= $icono_suscripcion ?>"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($s['nombre']) ?></h3>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300"><?= ucfirst(htmlspecialchars($s['frecuencia'])) ?></span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form action="/subscription/toggle/<?= $s['id'] ?>" method="POST">
                            <button type="submit" class="text-slate-400 hover:text-emerald-500 transition" title="<?= $s['activo'] ? 'Desactivar' : 'Activar' ?>">
                                <i class="fa-solid <?= $s['activo'] ? 'fa-toggle-on text-emerald-500' : 'fa-toggle-off' ?> text-xl"></i>
                            </button>
                        </form>
                        <form action="/subscription/delete/<?= $s['id'] ?>" method="POST" onsubmit="return confirm('¿Eliminar esta suscripción?');">
                            <button type="submit" class="text-slate-300 hover:text-red-500 dark:text-slate-500 dark:hover:text-red-400 transition ml-2" title="Eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="text-3xl font-bold text-slate-800 dark:text-white sensitive-balance">
                        $<?= number_format($s['monto'], 2) ?>
                    </div>
                </div>

                <div class="border-t border-gray-100 dark:border-slate-700 pt-4 mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400"><i class="fa-regular fa-credit-card mr-1"></i> <?= htmlspecialchars($s['cuenta_nombre']) ?></span>
                        <span class="font-semibold <?= (strtotime($s['proximo_cobro']) <= strtotime('+3 days')) && $s['activo'] ? 'text-orange-500 dark:text-orange-400' : 'text-slate-600 dark:text-slate-300' ?>">
                            Próx: <?= date('d M Y', strtotime($s['proximo_cobro'])) ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
