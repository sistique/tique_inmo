<?php use config\views; ?>
<?php $titulo_sys   = strtoupper((new views())->titulo_sistema); ?>

<div class="footer-bottom"> <!--link-->
    <div class="container text-right">
        <span class=""><a href="#"><?php echo htmlspecialchars($titulo_sys); ?></a></span>
    </div>
</div><!-- /.footer-bottom -->