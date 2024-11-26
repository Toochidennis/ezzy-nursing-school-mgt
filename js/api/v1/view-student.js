$(document).ready(function () {
    fetchStudentsByLevel();

    // Change event for level selection dropdown
    $('#levelSelect').on('change', function () {
        selectedLevel = $(this).val();
        fetchStudentsByLevel(selectedLevel);
    });
});


function fetchData({ action, studentId = null, level = null, onSuccess }) {
    showLoading();
    $.ajax({
        url: '../controller/studentController.php',
        type: 'GET',
        data: {
            action: action,
            student_id: studentId ?? '',
            level: level ?? ''
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                onSuccess(response)
            } else {
                showToast(response.message, 'error');
                console.error('Data processing error:', response);
            }
        },
        error: function (xhr, status, error) {
            showToast('Error occurred processing request.', 'error');
            console.error('Request error:', xhr.responseText || status);
        },
        complete: function () {
            hideLoading();
        }
    });
}

function fetchStudentsByLevel(level = null) {
    fetchData({
        action: 'getStudents',
        level: level ?? '',
        onSuccess: function (response) {
            // Process and display fetched courses here.
            $('#studentTable').bootstrapTable('removeAll');

            // Check if the courses array is empty
            if (response.data.length === 0) {
                $('#studentTable').bootstrapTable('append', [{
                    index: '',
                    matric: '',
                    fullname: '',
                    level: 'No students found for this level',
                    gender: '',
                    department: '',
                    actions: ''
                }]);

                return;
            }

            response.data.map((student, index) => {
                const fullname = `${student.lastname} ${student.othername} ${student.firstname}`;
                const levelNames = {
                    1: '100L',
                    2: '200L',
                    3: '300L',
                    4: '400L',
                };
                const levelName = levelNames[student.level] || 'Invalid level';

                $('#studentTable').bootstrapTable('append', [{
                    index: index + 1,
                    matric: student.matric_number,
                    fullname: fullname,
                    level: levelName,
                    gender: student.gender,
                    department: student.department,
                    actions: `
                        <a href="#" onclick="editStudent(${student.student_id})" title='Edit details'><i class='fa fa-edit'></i></a>
                        <a href="#" onclick="deleteStudent(${student.student_id})" title='Delete Student Details'><i class='fa fa-trash fa-1x'></i></a>
                    `
                }]);
            });
        }
    });
}

function editStudent(studentId) {
    console.log('student-id', studentId);
    localStorage.setItem('studentId', JSON.stringify(studentId));
    window.location.href = '../view/editStudent.php';
}

function deleteStudent(studentId) {
    if (confirm('Are you sure you want to delete this student?')) {
        const formData = new FormData()
        formData.append('action', 'delete');
        formData.append('student_id', studentId);

        $.ajax({
            url: '../controller/studentController.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    showToast(response.message);
                    fetchStudents();
                } else {
                    showToast(response.message, 'error');
                    console.error('Data processing error:', response);
                }
            },
            error: function (xhr, status) {
                console.error('Request error:', xhr.responseText || status);
            }
        });
    }
}
