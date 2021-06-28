import constants from './constants';

const $siteUrl = constants.$siteUrl;
$(document).ready(function () {
    $('.honari-delete-row-btn').click(function () {
        let relativeUrl = $(this).data('url')
        $('.honari-delete-row-form').attr('action', $siteUrl + relativeUrl + $(this).data('id'));
        $('#deleteModal').modal('show')
    })
    $('.honari-restore-row-btn').click(function () {
        let relativeUrl = $(this).data('url')
        $('.honari-restore-row-form').attr('action', $siteUrl + relativeUrl + $(this).data('id'));
        $('#restoreModal').modal('show')
    })
})


