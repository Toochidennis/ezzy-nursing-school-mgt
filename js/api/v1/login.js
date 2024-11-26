
function login(event) {
    event.preventDefault();
    showLoading();

    const formData = $('#loginForm').serialize() + `&action=login`;

    $.ajax({
        url: '../controller/loginController.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                showToast(response.message);
                //console.log("error", response.redirect);
                window.location.href = response.redirect;
            } else if (response.redirect) {
                window.location.href = response.redirect;
              // console.error("error", response.redirect);
            } else {
                showToast(response.message, 'error');
                //console.error("error", response.message);
            }
        },
        error: function (xhr) {
            if (xhr.status === '403' || xhr.status === '401' || xhr.status === '405') {
                window.location.href = '../view/login.php'
            } else {
                showToast('Error occurred during the request', 'error');
            }
        },
        complete: function () {
            hideLoading();
        }
    });
}
