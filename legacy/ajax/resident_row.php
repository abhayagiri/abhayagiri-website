<div class='media residents'>
    <span class='pull-left'>
        <img class='media-object img-residents' src="/media/images/residents/<?= e($resident->photo) ?>">
    </span>
    <div class='media-body'>
        <a href='<?= e($_lang['base']) ?>/community/residents<?= $_language == 'Thai' ? '-thai' : '' ?>/<?= e($resident->url_title) ?>' onclick='navResident("<?= e($resident->url_title) ?>");
                    return false;' class='title'>
           <?= e($resident->title) ?>
        </a>
        <br><br><?= $resident->body ?>
    </div>
</div>
