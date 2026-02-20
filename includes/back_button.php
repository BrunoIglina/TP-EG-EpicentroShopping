<?php
if (has_history()): 
    $back_url = get_back_url();
?>
<div class="back-button-container">
    <a href="<?php echo htmlspecialchars($back_url); ?>" class="back-button" title="Volver a la página anterior">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        <span>Volver</span>
    </a>
</div>
<?php endif; ?>