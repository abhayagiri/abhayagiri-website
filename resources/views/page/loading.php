<script>

// Loading UI

var _isLoading = false;

function showLoading() {
    _isLoading = true;
    $('#loading').show();
}

function hideLoading() {
    $('#loading').hide();
    _isLoading = false;
}

function isLoading() {
    return _isLoading || document.readyState != 'complete' ||
           (typeof $ !== 'undefined' && $.active > 0);
}

// setInterval(function() { console.log(isLoading()) }, 100);

</script>
