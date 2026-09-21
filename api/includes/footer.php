    </main>

    <footer class="site-footer">
        <div><strong>SIMPUS-Mini</strong><p>Satu buku, satu pengetahuan baru.</p></div>
        <p>&copy; <?php echo date('Y'); ?> SIMPUS-Mini. Perpustakaan Kampus.</p>
        <a class="text-link" href="#konten">Kembali ke atas &uarr;</a>
    </footer>
    <script src="<?php echo $base; ?>assets/js/app.js"></script>
    <?php if (!empty($extra_scripts)): foreach ($extra_scripts as $src): ?>
    <script src="<?php echo $src; ?>"></script>
    <?php endforeach;
    endif; ?>
</body>
</html>
