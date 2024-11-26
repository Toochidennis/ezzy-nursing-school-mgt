let url = '../controller/courseRegistrationController.php';

// Array to hold selected student IDs
let selectedStudentIds = [];
let selectedLevel = 1;

$(document).ready(function () {
    // add modal to main html
    $('body').append('<div id="modalContainer"></div>');
    $('#modalContainer').load('../view/printOptionsModal.html', function () {

        // Attach event handlers
        $('#firstSemesterOption').on('click', function () {
            handleOptionSelection('1');
        });

        $('#secondSemesterOption').on('click', function () {
            handleOptionSelection('2');
        });

        $('#bothSemestersOption').on('click', function () {
            handleOptionSelection('both');
        });
    });

    // Set default level to 100L on page load
    fetchStudentsByLevel(selectedLevel);

    // Change event for level selection dropdown
    $('#levelSelect').on('change', function () {
        selectedLevel = $(this).val();
        fetchStudentsByLevel(selectedLevel);
    });

    // Handle click events
    $(document).on('change', '.row-checkbox', function () {
        updateSelectedIds();
    });

    // Handle print button click
    $(document).on('click', '.print-btn', function (e) {
        e.preventDefault();
        const studentId = $(this).closest('tr').find('.row-checkbox').data('student-id');
        $('#printOptionsModal').data('student-id', studentId);
        $('#printOptionsModal').modal('show');
    });
});

async function fetchStudentsByLevel(level) {
    showLoading();

    makeRequest({
        url: url,
        type: 'GET',
        data: {
            action: 'get_students',
            level: level
        },
        onSuccess: function (response) {
            // Clear any existing rows in the table
            $('#studentTable').bootstrapTable('removeAll');

            // Check if the students array is empty
            if (response.data.length === 0) {
                $('#studentTable').bootstrapTable('append', [{
                    index: '',
                    matricNumber: '',
                    fullname: 'No students found for this level',
                    noOfCourses: '',
                    action: ''
                }]);
                return;
            }

            response.data.map(async (student, index) => {
                const fullname = `${student.lastname} ${student.othername} ${student.firstname}`;
                const count = await fetchCourseCount(student.student_id);

                $('#studentTable').bootstrapTable('append', [{
                    state: `<input type="checkbox" data-student-id="${student.student_id}" class="row-checkbox">`,
                    index: index + 1,
                    matricNumber: student.matric_number,
                    fullname: fullname,
                    noOfCourses: `${count} courses`,
                    action: `<a class='print-btn' title='Print course form'><i class='fa fa-print fa-1x'></i></a>`
                }]);
            });
        }
    });
}

function fetchCourseCount(studentId) {
    return new Promise((resolve, _) => {
        makeRequest({
            url: url,
            type: 'GET',
            data: {
                action: 'count',
                student_id: studentId
            },
            onSuccess: response => (resolve(response.data))
        });
    });
}

function updateSelectedIds() {
    selectedStudentIds = $('#studentTable input.row-checkbox:checked')
        .map((_, checkbox) => $(checkbox).data('studentId'))
        .get();

    // Update the 'Select All' checkbox status based on individual selections
    const allChecked = $('#studentTable input.row-checkbox:checked').length === $('#studentTable input.row-checkbox').length;
    $('#selectAll').prop('checked', allChecked);

    updateFABVisibility();
}

// Toggle select all students
function toggleSelectAll(selectAllCheckbox) {
    const isChecked = selectAllCheckbox.checked;
    selectedStudentIds = []; // Clear current selection

    $('.row-checkbox').each((_, checkbox) => {
        $(checkbox).prop('checked', isChecked)
        if ($(checkbox).prop('checked')) {
            selectedStudentIds.push($(checkbox).data('studentId'));
        }
    });
    updateFABVisibility();
}

// Update FAB visibility based on selected students
function updateFABVisibility() {
    const registerButton = $('#registerButton');
    if (selectedStudentIds.length > 0) {
        registerButton.css('display', 'block');
    } else {
        registerButton.css('display', 'none');
    }
}

// Handle registering selected students
function registerSelectedStudents() {
    if (selectedStudentIds.length > 0) {
        const data = {
            level: selectedLevel,
            studentIds: selectedStudentIds
        };

        sessionStorage.setItem('data', JSON.stringify(data));
        window.location.href = '../view/courseRegistration.php';
        clearSelections();
    }
}

function clearSelections() {
    // Clear all selections and reset the UI
    selectedCourseIds = [];
    $('.row-checkbox').prop('checked', false);
    $('#selectAll').prop('checked', false);

    updateFABVisibility();
}

function handleOptionSelection(option) {
    const studentId = $('#printOptionsModal').data('student-id');
    const studentData = { studentId: studentId, semester: option };

    sessionStorage.setItem('studentData', JSON.stringify(studentData));
    $('#printOptionsModal').modal('hide');
    window.location.href = '../view/printCourseForm.php';
}