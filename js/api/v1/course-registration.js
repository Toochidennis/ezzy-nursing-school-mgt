let url = '../controller/courseRegistrationController.php';
let selectedCourseIds = [];
let selectedStudentIds = [];
let selectedLevel = 0;
let selectedSemester = 1;

$(document).ready(function () {
    const data = JSON.parse(sessionStorage.getItem('data') || '{}');

    if (data.studentIds && data.studentIds.length > 0) {
        selectedLevel = data.level;
        selectedStudentIds = data.studentIds

        // Load first semester courses by default
        fetchCourses(selectedLevel, 1);

        $('#semesterSelect').on('change', function () {
            selectedSemester = $(this).val();
            fetchCourses(selectedLevel, selectedSemester);
        });

        $(document).on('change', '.row-checkbox', function () {
            updateSelectedIds();
        });
    }
});

function fetchCourses(level, semester) {
    showLoading();

    makeRequest({
        url: url,
        type: 'GET',
        data: {
            action: 'get_courses',
            level: level,
            semester: semester
        },
        onSuccess: function (response) {
            $('#studentTable').bootstrapTable('removeAll');
            // Check if the courses array is empty
            if (response.data.length === 0) {
                $('#studentTable').bootstrapTable('append', [{
                    index: '',
                    courseName: 'No courses found for this semester',
                    courseCode: '',
                    courseUnit: ''
                }]);
                return;
            }

            response.data.map((course, index) => {
                $('#studentTable').bootstrapTable('append', [{
                    state: `<input type="checkbox" class="row-checkbox" data-course-id="${course.course_id}">`,
                    index: index + 1,
                    courseName: course.course_name,
                    courseCode: course.course_code,
                    courseUnit: course.course_unit
                }]);
            });

            selectedStudentIds.forEach(studentId => {
                preSelectRegisteredCourses(studentId);
            });
        }
    });
}

function preSelectRegisteredCourses(studentId) {
    makeRequest({
        url: url,
        type: 'GET',
        data: {
            action: 'get_registered_courses',
            student_id: studentId,
            semester: selectedSemester,
        },
        onSuccess: function (response) {
            const courses = response.data
            if (courses.length !== 0) {
                courses.map((course, _) => {
                    $(`input[data-course-id="${course.course_id}"]`).prop('checked', true);
                    updateSelectedIds();
                });
            }
        }
    });
}

function updateSelectedIds() {
    selectedCourseIds = $('#studentTable input.row-checkbox:checked')
        .map((_, checkbox) => $(checkbox).data("courseId"))
        .get();

    // Update the 'Select All' checkbox status based on individual selections
    const allChecked = $('#studentTable input.row-checkbox:checked').length === $('#studentTable input.row-checkbox').length;
    $('#selectAll').prop('checked', allChecked);

    updateFABVisibility();
}

// Toggle select all students
function toggleSelectAll(selectAllCheckbox) {
    selectedCourseIds = []; // Clear current selection

    $('.row-checkbox').each((_, checkbox) => {
        $(checkbox).prop('checked', selectAllCheckbox.checked)
        if ($(checkbox).prop('checked')) {
            selectedCourseIds.push($(checkbox).data('courseId'));
        }
    });

    updateFABVisibility();
}

// Update FAB visibility based on selected students
function updateFABVisibility() {
    const registerButton = $('#registerButton');
    if (selectedCourseIds.length > 0) {
        registerButton.css('display', 'block');
    } else {
        registerButton.css('display', 'none');
    }
}

function clearSelections() {
    // Clear all selections and reset the UI
    selectedCourseIds = [];
    $('.row-checkbox').prop('checked', false);
    $('#selectAll').prop('checked', false);
    updateFABVisibility();
}

// Handle registering selected students
function registerSelectedCourses() {
    if (selectedCourseIds.length > 0 && selectedStudentIds.length > 0 && selectedLevel != 0) {
        makeRequest({
            url: url,
            type: 'POST',
            data: {
                action: 'register_courses',
                semester: selectedSemester,
                level: selectedLevel,
                student_ids: JSON.stringify(selectedStudentIds),
                course_ids: JSON.stringify(selectedCourseIds),
                csrf_token: $('#csrf_token').val()
            },
            onSuccess: function (response) {
                showToast(response.message);
            }
        });
    }
}