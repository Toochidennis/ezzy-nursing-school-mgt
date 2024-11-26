let studentId = JSON.parse(localStorage.getItem('studentId') ?? '0');

if (studentId && studentId !== 0) {
    fetchStudent(studentId);
} else {
    fetchSession();
    loadStatesAndLGAs();
}

function makeRequest({ type, data, onSuccess }) {
    showLoading();
    $.ajax({
        url: '../controller/studentController.php',
        method: type, // 'GET' or 'POST'
        data: data,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                onSuccess(response);
            } else {
                showToast(response.message, 'error');
                console.error('Data processing error:', response);
            }
        },
        error: function (xhr, status, error) {
            console.error('Request error:', xhr.responseText || status);
            showToast('Error occurred processing request.', 'error');
        },
        complete: function () {
            hideLoading();
        }
    });
}

// create student
function createStudent(event) {
    event.preventDefault();
    const formData = $('#studentForm').serialize() + `&action=create`;

    makeRequest({
        type: 'POST',
        data: formData,
        onSuccess: function (response) {
            showToast(response.message);
            $('#studentForm')[0].reset();
            fetchSession();
        }
    });
}

// Update student
function updateStudent(event) {
    event.preventDefault();
    const formData = $('#studentForm').serialize() + `&action=update&student_id=${studentId}`;

    makeRequest({
        type: 'POST',
        data: formData,
        onSuccess: function (response) {
            showToast(response.message);
            window.location.href = '../view/viewStudents.php';
            localStorage.removeItem('studentId');
        }
    });
}

function fetchSession() {
    makeRequest({
        type: 'GET',
        data: { action: 'session' },
        onSuccess: function (response) {
            $('#session').val(response.data[0].session);
        }
    });
}

function fetchStudent(studentId) {
    makeRequest({
        type: 'GET',
        data: {
            action: 'getStudent',
            student_id: studentId
        },
        onSuccess: function (response) {
            populateEditForm(response.data[0])
        }
    });
}

// Populate the form with student data
function populateEditForm(student) {
    const fieldMapping = {
        '#firstname': student.firstname,
        '#lastname': student.lastname,
        '#othername': student.othername,
        '#dob': student.dob,
        '#residential_address': student.residential_address,
        '#home_address': student.home_address,
        '#email': student.email,
        '#phone_number': student.phone_number,
        '#session': student.session,
        '#matric_number': student.matric_number,
    };

    // Populate simple fields
    for (const [selector, value] of Object.entries(fieldMapping)) {
        $(selector).val(value);
    }

    // Populate dropdowns and checkboxes
    loadLevels(student.level);
    loadDepartments(student.department);
    loadGenders(student.gender);
    loadStatesAndLGAs(student.state, student.lga);


    // Ability info
    const ability = JSON.parse(student.ability_info);
    ['asthmatic', 'speech', 'hearing', 'walking', 'sight', 'sickle_cell'].forEach(key => {
        $(`#${key}`).prop('checked', ability[`is_${key}`] === 'yes');
    });

    // Sponsor info
    const sponsor = JSON.parse(student.sponsor_info);
    $('#sponsor_name').val(sponsor.name);
    loadRelationShips(sponsor.relationship, 'sponsor_relationship')
    $('#sponsor_address').val(sponsor.address);
    $('#sponsor_phone_number').val(sponsor.phone_number);

    // Next of kin info
    const nok = JSON.parse(student.next_of_kin_info);
    $('#kin_name').val(nok.name);
    loadRelationShips(nok.relationship, 'kin_relationship')
    $('#kin_address').val(nok.address);
    $('#kin_phone_number').val(nok.phone_number);
}

// Populate dropdowns with levels
function loadLevels(selectedLevel) {
    levels = [
        { value: "1", name: "100L" },
        { value: "2", name: "200L" },
        { value: "3", name: "300L" },
        { value: "4", name: "400L" }
    ];
    populateDropDown($('#level'), levels, selectedLevel);
}

// Populate dropdowns with departments
function loadDepartments(selectedDepartment) {
    departments = [
        { value: "nursing", name: "Nursing" },
        { value: "midwifery", name: "Midwifery" }
    ];
    populateDropDown($('#department'), departments, selectedDepartment);
}

// Populate dropdowns with genders
function loadGenders(selectedGender) {
    genders = [
        { value: 'Male', name: "Male" },
        { value: 'Female', name: "Female" }
    ];
    populateDropDown($('#gender'), genders, selectedGender);
}

// Populate dropdowns with relationships
function loadRelationShips(selectedRelationship, id) {
    realtionships = [
        { value: "brother", name: "Brother" },
        { value: "sister", name: "Sister" },
        { value: "mother", name: "Mother" },
        { value: "father", name: "Father" },
        { value: "guardian", name: "Guardian" }
    ];
    populateDropDown($(`#${id}`), realtionships, selectedRelationship);
}


function loadStatesAndLGAs(selectedState = null, selectedLGA = null) {
    const stateSelect = $('#state');
    const lgaSelect = $('#lga')

    populateStates(stateSelect);

    // Set the selected state and LGA on page load
    if (selectedState) {
        stateSelect.val(selectedState);
        populateLGAs(selectedState, lgaSelect, selectedLGA);
    }

    // Event listener for state dropdown to load LGAs
    stateSelect.on("change", function () {
        populateLGAs(this.value, lgaSelect);
    });
}

// Function to populate states
function populateStates(stateSelect) {
    stateSelect.empty();
    stateSelect.append('<option value="">Select State</option>');
    for (const state in stateLgas) {
        const option = new Option(state, state);
        stateSelect.append(option);
    }
}

// Function to populate LGAs based on selected state
function populateLGAs(state, lgaSelect, selectedLGA = null) {
    lgaSelect.empty();
    lgaSelect.append('<option value="">Select LGA</option>');
    if (state && stateLgas[state]) {
        stateLgas[state].forEach(lga => {
            const option = new Option(lga, lga);
            if (lga === selectedLGA) option.selected = true;
            lgaSelect.append(option);
        });
    }
}

function populateDropDown(selectElement, options, selectValue = null) {
    selectElement.empty();
    selectElement.append('<option value="">Select</option>');
    options.forEach(option => {
        selectElement.append(new Option(option.name, option.value, false, option.value == selectValue));
    });
}