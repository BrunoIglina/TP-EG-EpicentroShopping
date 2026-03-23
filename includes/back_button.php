<?php if (has_history()) : ?>
    <a href="<?php echo htmlspecialchars(get_back_url()); ?>" class="btn btn-outline-secondary btn-back-custom" title="Volver a la página anterior">
        &larr; <span class="d-none d-md-inline">Volver</span>
    </a>
<?php endif; ?>