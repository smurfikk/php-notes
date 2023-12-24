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
                        $('#hash').val(responseObject.hash);
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

    $('#ShareLinkNote').val(window.location.origin + "/note.php?hash=" + $('#hash').val());
    $('#btnCopyLink').on('click', function () {
        let url = window.location.origin + "/note.php?hash=" + $('#hash').val();
        navigator.clipboard.writeText(url);
    });

    let btnSwitchShareAccess = $('#btnSwitchShareAccess');
    btnSwitchShareAccess.on("change", function () {
        $.ajax({
            url: 'handler_api.php',
            method: 'POST',
            data: {
                method: "changeVisibilityNote",
                id: $('#noteId').val(),
                status: btnSwitchShareAccess.is(':checked'),
            },
            success: function (response) {
                if (response.error !== undefined && response.error !== null) {
                    console.error(response);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });

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

// const modalShareLink = document.getElementById('myModal')
// const myInput = document.getElementById('myInput')
//
// modalShareLink.addEventListener('shown.bs.modal', () => {
//     myInput.focus()
// })