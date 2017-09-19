<div class="media residents">
    <span class="pull-left">
        <img class="media-object img-residents" src="<?= e($resident->image_url) ?>">
    </span>
    <div class="media-body">
        <a href="<?= e($resident->getPath()) ?>" onclick="navResident('<?= e($resident->slug) ?>');
                    return false;" class="title">
           <?= e(tp($resident, 'title')) ?>
        </a>
        <br><br>
        <?= tp($resident, 'description') ?>
    </div>
</div>
