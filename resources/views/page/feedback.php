<?php
    if (!Config::get('abhayagiri.feedback_token')) {
        return;
    }
?>
<script>
    Userback = window.Userback || {};

    (function(id) {
        if (document.getElementById(id)) {return;}
        var s = document.createElement('script');
        s.id = id;
        s.src = 'https://app.userback.io/widget.js';
        var parent_node = document.head || document.body;
        parent_node.appendChild(s);
    })('userback-sdk');

    Userback.access_token = '<?php echo Config::get('abhayagiri.feedback_token') ?>';
</script>
