function showProgress() {
    document.body.classList.add('js-loading-showing');
    document.body.classList.add('js-loading');
    window.setTimeout(function () {
        document.body.classList.remove('js-loading-showing');
    }, 100);
}

function hideProgress() {
    document.body.classList.add('js-loading-hiding');
    window.setTimeout(function () {
        document.body.classList.remove('js-loading');
        document.body.classList.remove('js-loading-hiding');
    }, 500);
}
