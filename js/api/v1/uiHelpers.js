
// Show the loading overlay and bar
function showLoading() {
    const loadingOverlay = $('#loadingOverlay');
    const loadingBar = $('#loadingBar');

    loadingOverlay.show();
    loadingBar.css('width', '0'); // Reset width
    setTimeout(() => {
        loadingBar.css('width', '100%'); // Simulate progress
    }, 100);
}

// Hide the loading overlay and bar
function hideLoading() {
    const loadingOverlay = $('#loadingOverlay');
    const loadingBar = $('#loadingBar');

    loadingBar.css('width', '100%'); // Ensure full width
    setTimeout(() => {
        loadingOverlay.hide();
        loadingBar.css('width', '0'); // Reset for next use
    }, 500); // Allow time for animation to finish
}

// Show a toast notification
function showToast(message, type = 'success') {
    let mesg = '';
    
    // Determine the message icon based on the type of message
    if (type === 'success') {
        mesg = `<i class="fa-solid fa-circle-check"></i> ${message}`;
    } else if (type === 'error') {
        mesg = `<i class="fa-solid fa-xmark"></i> ${message}`;
    } else {
        mesg = `<i class="fa-solid fa-circle-exclamation"></i> ${message}`;
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.classList.add('toast', type);
    toast.innerHTML = mesg;

    // Append toast to the toast container
    $('#toast-box').append(toast);

    // Show the toast
    setTimeout(() => {
        toast.classList.add('show'); // Add class to trigger animation
    }, 10); // Small delay to trigger CSS transition

    // Hide toast after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        // Remove toast element from DOM after animation
        setTimeout(() => {
            toast.remove();
        }, 500); // Ensure removal after fade-out animation
    }, 5000); // Remove after 3 seconds
}

function makeRequest({ url, type, data, processData, contentType, onSuccess }) {
    // showLoading();
    $.ajax({
        url: url,
        type: type,
        data: data,
        processData: processData,
        contentType: contentType,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                onSuccess(response);
            } else {
                showToast(response.message, 'error');
                console.error('Request error: ', response.data);
            }
        },
        error: function (xhr, status, error) {
            showToast('Request error', 'error');
        },
        complete: function () {
            hideLoading();
        }
    });
}
