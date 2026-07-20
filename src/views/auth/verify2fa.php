<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación 2FA - Gestor de Finanzas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 flex items-center justify-center min-h-screen transition-colors duration-200">
    
    <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 w-full max-w-md transition-colors duration-200">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mb-4">
                <i class="fa-solid fa-shield-halved text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Verificación de Seguridad</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Ingresa el código de 6 dígitos (simulación: ingresa cualquiera)</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500 text-red-600 dark:text-red-400 p-4 rounded-xl mb-6 text-sm flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/auth/verify2fa" method="POST" class="space-y-6">
            <div>
                <label for="code" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Código de 6 dígitos</label>
                <input type="text" id="code" name="code" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-center tracking-widest text-2xl font-bold" required maxlength="6" pattern="\d{6}" placeholder="000000">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-600/20 dark:shadow-blue-900/20">Verificar y Entrar</button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="/auth/login" class="text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 text-sm font-medium transition">Cancelar y volver al inicio</a>
        </div>
    </div>

</body>
</html>
