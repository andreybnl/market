console.log('appjs');

var $manual_request_url = $("#manual_request_url")

$manual_request_url.change(function () {
    var $form = $(this).closest('form')
    $.post($form.attr('action'), data).then(function ($response) {
        $("#manual_request_url").replaceWith(
            'test'
        )
    })
})
