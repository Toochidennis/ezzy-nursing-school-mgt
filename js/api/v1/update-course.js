const url = '../controller/courseController.php';
let courseId = JSON.parse(localStorage.getItem('courseId') ?? '0');

$(document).ready(function () {
    if (courseId && courseId !== 0) {
        fetchCourse(courseId);
    }
});

function updateCourse(event) {
    event.preventDefault();
    const formData = $("#courseForm").serialize() + `&action=update`;

    makeRequest({
        url: url,
        type: 'POST',
        data: formData,
        onSuccess: function (response) {
            showToast(response.message);
            $('#courseForm')[0].reset();
            window.location.href = '../view/createCourse.php';
            localStorage.removeItem('courseId');
        }
    });
}

function fetchCourse(courseId) {
    showLoading();
    makeRequest({
        url: url,
        type: 'GET',
        data: {
            action: 'getCourse',
            course_id: courseId
        },
        onSuccess: function (response) {
            const course = response.data[0];
            console.log("data: ", course);
            $('#course_id').val(course.course_id);
            $('#course_name').val(course.course_name);
            $('#course_code').val(course.course_code);
            $('#course_unit').val(course.course_unit);
            loadLevels(course.level);
            loadSemesters(course.semester);
        }
    });
}

function loadLevels(selectedLevel) {
    levels = [
        { value: "1", name: "100L" },
        { value: "2", name: "200L" },
        { value: "3", name: "300L" },
        { value: "4", name: "400L" }
    ];
    populateDropDown($('#level'), levels, selectedLevel);
}

function loadSemesters(selectedSemester) {
    semesters = [
        { value: "1", name: "First semester" },
        { value: "2", name: "Second semester" }
    ];
    populateDropDown($('#semester'), semesters, selectedSemester);
}

function populateDropDown(selectElement, options, selectValue = null) {
    selectElement.empty();
    selectElement.append('<option value="">Select</option>');
    options.forEach(option => {
        selectElement.append(new Option(option.name, option.value, false, option.value == selectValue));
    });
}