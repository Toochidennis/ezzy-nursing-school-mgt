$(document).ready(function () {
    const data = JSON.parse(sessionStorage.getItem('studentData') ?? '{}');

    if (data.studentId && data.semester) {
        fetchData({
            action: 'course_form',
            studentId: data.studentId,
            semester: data.semester
        })
    }

    printCourseForm();
});

function fetchData({ action, studentId, semester }) {
    //  $('#loadingSpinner').show();
    $.ajax({
        url: '../controller/courseFormController.php',
        type: 'GET',
        data: { action: action, studentId: studentId, semester: semester },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                populateForm(response.data);
            } else {
                console.error('Data processing error:', response);
            }
        },
        error: function (xhr, status, error) {
            console.error('Request error:', xhr.responseText || status);
        },
        complete: function () {
            // $('#loadingSpinner').hide();
        }
    });
}

function populateForm(data) {
    if (data.length !== 0) {
        // Populate bio data
        const student = data.bio_data
        $('#reg-no').text(student.matric_number);
        $('#department').text(student.department.toUpperCase());
        $('#firstname').text(student.firstname.toUpperCase());
        $('#lastname').text(student.lastname.toUpperCase());
        $('#middlename').text(student.othername.toUpperCase());
        $('#level').text(getLevelName(student.level));
        $('#course-title').text(`FIRST SEMESTER ${student.session} REGISTERED COURSES`)

        // Populate course table
        $('#courses-table').bootstrapTable('removeAll');

        const courses = data.courses;
        if (courses.length === 0) {
            $('#courses-table').bootstrapTable('append', [{
                index: '',
                courseCode: '',
                courseTitle: 'No courses registered for this student yet.',
                courseUnit: ''
            }]);
            return;
        }

        // Sort the courses by course code
        courses.sort((a, b) => {
            const extractNumber = (code) => parseInt(code.match(/\d+/)[0], 10); // Extract numeric part from the code
            const prefixA = a.course_code.replace(/\d+/g, ''); // Extract alphabetical part
            const prefixB = b.course_code.replace(/\d+/g, '');

            // First sort alphabetically, then numerically
            if (prefixA === prefixB) {
                return extractNumber(a.course_code) - extractNumber(b.course_code);
            }
            return prefixA.localeCompare(prefixB);
        });

        let totalUnits = 0;
        courses.map((course, index) => {
            $('#courses-table').bootstrapTable('append', [{
                index: index + 1,
                courseCode: course.course_code.toUpperCase(),
                courseTitle: course.course_name.toUpperCase(),
                courseUnit: course.course_unit
            }]);

            totalUnits += parseInt(course.course_unit, 10);
        });

        // Append the summary row 
        let summaryRow = `
            <tr>
                <td colspan="3"><strong>TOTAL CREDIT UNITS</strong></td>
                <td><strong>${totalUnits}</strong></td>
            </tr>
        `;

        // Add the row to the table
        $('#courses-table tbody').append(summaryRow);
    }
}

function getLevelName(level) {
    const levelNames = {
        1: '100L',
        2: '200L',
        3: '300L',
        4: '400L',
    };
    return levelNames[level];
}

function padTo2Digits(num) {
    return num.toString().padStart(2, '0');
}

function formatDate(date) {
    return [
        padTo2Digits(date.getDate()),
        padTo2Digits(date.getMonth() + 1),
        date.getFullYear(),
    ].join('/');
}

function printCourseForm() {
    $('#download-btn').on('click', function () {
        $('#content').printThis({
            footer: `<div>${formatDate(new Date())}</div>`
        });
    });
}
