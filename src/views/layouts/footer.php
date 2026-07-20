    </div> <!-- Fin del container -->

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        if (themeToggleBtn) {
            // Mostrar icono correcto inicialmente
            if (document.documentElement.classList.contains('dark')) {
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
            }

            themeToggleBtn.addEventListener('click', function() {
                // Alternar iconos
                darkIcon.classList.toggle('hidden');
                lightIcon.classList.toggle('hidden');

                // Si ya estaba en oscuro, volver a claro
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
                
                // Disparar un evento por si Chart.js necesita repintarse
                window.dispatchEvent(new Event('theme-changed'));
            });
        }
    </script>
</body>
</html>
