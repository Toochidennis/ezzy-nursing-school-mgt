$(document).ready(function () {
    populateDropDown();
    fetchSessions();
});

function makeRequest({ type, data, processData, contentType, onSuccess }) {
    // showLoading();
    $.ajax({
        url: '../controller/academicSessionController.php',
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
            }
        },
        error: function (xhr, status, error) {
            console.error('Request error: ', xhr.responseText || status)
            showToast('Request error', 'error');
        },
        complete: function () {
            //      hideLoading();
        }
    });
}

function createSession(event) {
    event.preventDefault();
    const formData = $('#sessionForm').serialize() + `&action=create`;

    makeRequest({
        type: 'POST',
        data: formData,
        onSuccess: function (response) {
            showToast(response.message);
            $('#sessionForm')[0].reset();
            fetchSessions();
        }
    });
}

function fetchSessions() {
    makeRequest({
        type: 'GET',
        data: { action: 'getSessions' },
        onSuccess: function (response) {
            $('#sessionTable').bootstrapTable('removeAll');

            if (response.data.length === 0) {
                $('#sessionTable').bootstrapTable('append', [{
                    index: '',
                    session: '',
                    status: 'No sessions found',
                    makeActive: '',
                    action: ''
                }]);
                return;
            }

            response.data.map((session, index) => {
                const isActive = session.is_current == 1;
                const checkIcon = isActive ? "<i class='fa fa-check fa-1x'></i>" : "";
                let activeHolder = '';

                if (!isActive) {
                    activeHolder = `
                        <form class='updateForm' method='POST' onsubmit="updateSession(event)" onstyle='display:inline;'>
                            <input type='hidden' class='session_id' name='session_id' value='${session.session_id}'>
                            <button type='submit' class='make-active-btn'>Make Active</button>
                        </form>
                        `;
                } else {
                    activeHolder = checkIcon;
                }

                $('#sessionTable').bootstrapTable('append', [{
                    index: index + 1,
                    session: session.session,
                    status: session.is_current == 1 ? 'Yes' : 'No',
                    makeActive: activeHolder,
                    action: `
                    <a href="#" class='delete-btn' title='Delete Session' onclick="deleteSession(${session.session_id})">
                    <i class='fa fa-trash fa-1x'></i>
                    </a>
                    `
                }]);
            });
        }
    });
}

function updateSession(event) {
    event.preventDefault();

    const form = $(event.target); // Get the specific form that triggered the event
    const sessionId = form.find('.session_id').val(); // Find the session_id within this form
    const token = $('#csrf_token').val(); // CSRF token (if needed)
    const formData = `action=update&csrf_token=${token}&session_id=${sessionId}`;

    makeRequest({
        type: 'POST',
        data: formData,
        onSuccess: function (response) {
            showToast(response.message);
            fetchSessions();
        }
    });
}

function deleteSession(sessionId) {
    if (confirm('Are you sure you want to delete this session?')) {
        const formData = new FormData()
        formData.append('action', 'delete');
        formData.append('session_id', sessionId);
        formData.append('csrf_token', $('#csrf_token').val());

        makeRequest({
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            onSuccess: function (response) {

                showToast(response.message);
                fetchSessions();
            }
        });
    }
}

function populateDropDown() {
    const sessionSelect = $('#session');
    const baseYear = 2024; // Start year
    const currentYear = new Date().getFullYear();

    // Always generate sessions from baseYear to currentYear + a number of future years
    const futureYears = 5; // Include 10 years into the future from the current year
    const maxYear = currentYear + futureYears;

    for (let year = baseYear; year <= maxYear; year++) {
        const startYear = year;
        const endYear = year + 1;
        const session = `${startYear}/${endYear}`;
        const option = new Option(session, session);
        sessionSelect.append(option);
    }
}