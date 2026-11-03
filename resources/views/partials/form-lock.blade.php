{{-- Cegah submit ganda: tombol submit dinonaktifkan setelah form dikirim.
     Lewati dengan atribut data-no-lock pada <form>. --}}
<script>
    (function () {
        document.addEventListener('submit', function (e) {
            var form = e.target;

            // form dibatalkan (mis. confirm() ditolak) atau sengaja dikecualikan
            if (!(form instanceof HTMLFormElement) || e.defaultPrevented || form.hasAttribute('data-no-lock')) {
                return;
            }

            if (form.dataset.submitted === '1') {
                e.preventDefault();
                return;
            }

            form.dataset.submitted = '1';

            // ditunda agar nilai tombol tetap ikut terkirim
            setTimeout(function () {
                form.querySelectorAll('button[type="submit"], button:not([type]), input[type="submit"]').forEach(function (btn) {
                    btn.disabled = true;
                    btn.setAttribute('aria-busy', 'true');
                });
            }, 0);
        });

        // tombol "Back" dari bfcache: aktifkan lagi form yang terkunci
        window.addEventListener('pageshow', function (e) {
            if (!e.persisted) {
                return;
            }

            document.querySelectorAll('form[data-submitted="1"]').forEach(function (form) {
                delete form.dataset.submitted;
                form.querySelectorAll('[aria-busy="true"]').forEach(function (btn) {
                    btn.disabled = false;
                    btn.removeAttribute('aria-busy');
                });
            });
        });
    })();
</script>
