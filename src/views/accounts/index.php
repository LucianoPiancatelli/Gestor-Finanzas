<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="mb-8 mt-4 flex justify-between items-center opacity-0 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Mis Cuentas</h1>
        <p class="text-slate-500 dark:text-slate-400">Administra tus billeteras, tarjetas y cuentas bancarias.</p>
    </div>
    <a href="/account/create" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl transition shadow-lg shadow-blue-600/20"><i class="fa-solid fa-plus mr-2"></i>Nueva Cuenta</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 stagger-list">
    <?php if (empty($cuentas)): ?>
        <p class="text-slate-400 dark:text-slate-500 col-span-3">No tienes cuentas registradas.</p>
    <?php else: ?>
        <?php foreach ($cuentas as $c): ?>
            <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm relative overflow-hidden group hover:shadow-2xl hover:scale-[1.04] hover:-translate-y-2 hover:shadow-blue-500/20 hover:z-10 transition-all duration-300 opacity-0 animate-fade-in-up">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mb-3 inline-block transition-transform duration-300 group-hover:scale-110 group-hover:bg-blue-100"><?= htmlspecialchars($c['tipo']) ?></span>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($c['nombre']) ?></h3>
                    </div>
                    <form action="/account/delete/<?= $c['id'] ?>" method="POST" onsubmit="return confirm('¿Eliminar esta cuenta? Se eliminarán todas sus transacciones asociadas.');">
                        <button type="submit" class="text-slate-300 dark:text-slate-500 hover:text-red-500 dark:hover:text-red-400 transition" title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
                <div class="text-3xl font-bold sensitive-balance <?= $c['saldo'] >= 0 ? 'text-emerald-500' : 'text-red-500' ?> mt-2">
                    $<?= number_format($c['saldo'], 2) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
