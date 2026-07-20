<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto mb-8 mt-4 opacity-0 animate-fade-in-up">
    <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Mi Perfil</h1>
    <p class="text-slate-500 dark:text-slate-400">Gestiona tu información personal y la seguridad de tu cuenta.</p>
</div>

<div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-slate-700 mb-10 transition-all duration-300 opacity-0 animate-scale-in hover:shadow-xl">
    <div class="flex items-center gap-6 mb-8 border-b border-slate-100 dark:border-slate-700 pb-8">
        <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-3xl font-bold transition-transform duration-300 hover:scale-110 hover:rotate-12 cursor-default">
            <?= strtoupper(substr(htmlspecialchars($usuario['nombre']), 0, 1)) ?>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($usuario['nombre']) ?></h2>
            <p class="text-slate-500 dark:text-slate-400"><?= htmlspecialchars($usuario['email']) ?></p>
            <p class="text-xs text-slate-400 mt-2">Miembro desde: <?= date('d M Y', strtotime($usuario['creado_en'])) ?></p>
        </div>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4"><i class="fa-solid fa-shield-halved mr-2 text-blue-500"></i> Seguridad</h3>
        
        <div class="bg-slate-50 dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 opacity-0 animate-slide-in-right [animation-delay:300ms] hover:border-blue-500/50 transition-colors">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white">Autenticación de Dos Factores (2FA)</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Añade una capa extra de seguridad a tu cuenta.</p>
                </div>
                <div>
                    <?php if (isset($usuario['two_factor_enabled']) && $usuario['two_factor_enabled']): ?>
                        <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Activo</span>
                    <?php else: ?>
                        <span class="bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-400 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Inactivo</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($usuario['two_factor_enabled']) && $usuario['two_factor_enabled']): ?>
                <form action="/profile/disable2fa" method="POST" onsubmit="return confirm('¿Estás seguro de desactivar 2FA? Tu cuenta será menos segura.');">
                    <button type="submit" class="text-red-500 hover:text-red-600 font-medium text-sm transition">Desactivar 2FA</button>
                </form>
            <?php else: ?>
                <form action="/profile/enable2fa" method="POST">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 shadow-sm text-sm hover:shadow-blue-600/30">Activar 2FA</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
