const url = '../controller/courseController.php';

$(document).ready(function () {
    fetchCourses();
});

// create course
function createCourse(event) {
    event.preventDefault();
    const formData = $("#courseForm").serialize() + `&action=create`;

    makeRequest({
        url: url,
        type: 'POST',
        data: formData,
        onSuccess: function (response) {
            showToast(response.message);
            $('#courseForm')[0].reset();
            fetchCourses();
        }
    });
}

// Fetch courses
function fetchCourses() {
    makeRequest({
        url: url,
        type: 'GET',
        data: { 'action': 'getCourses' },
        onSuccess: function (response) {
            // Process and display fetched courses here.
            $('#courseTable').bootstrapTable('removeAll');

            if (response.data.length === 0) {
                $('#courseTable').bootstrapTable('append', [{
                    index: '',
                    title: '',
                    code: '',
                    unit: 'No courses found for this semester',
                    level: '',
                    semester: '',
                    actions: ''
                }]);
                return;
            }

            response.data.forEach((course, index) => {
                const levelNames = {
                    1: '100L',
                    2: '200L',
                    3: '300L',
                    4: '400L',
                };

                const levelName = levelNames[course.level] || 'Unknown Level';

                const semesters = {
                    '1': 'First semester',
                    '2': 'Second semester',
                };

                const semester = semesters[course.semester] || 'Unknown semester';

                $('#courseTable').bootstrapTable('append', [{
                    index: index + 1,
                    title: course.course_name,
                    code: course.course_code,
                    unit: course.course_unit,
                    level: levelName,
                    semester: semester,
                    actions: `
                    <a href="#" onclick="editCourse(${course.course_id})" title='Edit Details'><i class='fa fa-edit'></i></a>
                    <a href="#" onclick="deleteCourse(${course.course_id})" title='Delete Course'><i class='fa fa-trash'></i></a>
                `
                }]);
            });
        }
    });
}

function editCourse(courseId) {
    localStorage.setItem('courseId', JSON.stringify(courseId));
    window.location.href = '../view/editCourse.php';
}

function deleteCourse(courseId) {
    if (confirm('Are you sure you want to delete this course?')) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('course_id', courseId);
        formData.append('csrf_token', $('#csrf_token').val());

        makeRequest({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            onSuccess: function (response) {
                showToast(response.message);
                fetchCourses();
            }
        });
    }
}