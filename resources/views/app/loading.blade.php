<script>

// Loading UI

var _isLoading = false;
window._pendingXhrRequests = 0;

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
           window._pendingXhrRequests != 0 ||
           (typeof $ !== 'undefined' && $.active > 0);
}

// setInterval(function() { console.log(isLoading()) }, 100);

</script>
