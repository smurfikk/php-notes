const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

$(document).ready(function () {
    $('#hideBtn').click(function () {
        $('.sidebar').toggleClass('d-none');
        $('.content').toggleClass('col-9');
    });
});

$(document).ready(function () {
    let changeTimer = false;

    $('#noteText').on('input', function () {
        const newTextValue = $(this).val();
        if (changeTimer !== false) clearTimeout(changeTimer);
        changeTimer = setTimeout(function () {
            $.ajax({
                url: 'handler_api.php',
                method: 'POST',
                data: {
                    method: "updateNote",
                    id: $('#noteId').val(),
                    content: newTextValue,
                    userId: $('#userId').val(),
                },
                success: function (response) {
                    const responseObject = JSON.parse(response);
                    if (responseObject.error !== undefined && responseObject.error !== null) {
                        console.error(responseObject);
                    } else {
                        console.log(responseObject.id)
                        $('#noteId').val(responseObject.id);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
            changeTimer = false;
        }, 300);
    });
    $('#btnRemove').on('click', function () {

        $.ajax({
            url: 'handler_api.php',
            method: 'POST',
            data: {
                method: "removeNote",
                id: $('#noteId').val(),
            },
            success: function (response) {
                if (response.error !== undefined && response.error !== null) {
                    console.error(response);
                } else {
                    location.href = 'index.php';
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });
    $('#btnCopyLink').on('click', function () {
        let url = window.location.href.replace("index", "note");
        navigator.clipboard.writeText(url);
    });

});
const toastTrigger = document.getElementById('btnCopyLink')
const toastLiveExample = document.getElementById('liveCopyLink')

if (toastTrigger) {
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
    toastTrigger.addEventListener('click', () => {
        toastBootstrap.show();
        setTimeout(function () {
            toastBootstrap.hide();
        }, 3000);
    })
}