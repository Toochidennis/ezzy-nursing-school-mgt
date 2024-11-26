<?php
require_once '../includes2/session.php';    
?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'includes/title.php'; ?>
    <meta name="description" content="Ezzy Nursing">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="../assets/img/icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../assets/css/style2.css">
    <link rel="stylesheet" href="../assets/css/style3.css">
    <link rel="stylesheet" href="../assets/css/loading-bar.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

    <script>
        function showValues(str) {
            if (str == "") {
                document.getElementById("txtHint").innerHTML = "";
                return;
            } else {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("txtHint").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "ajaxCall2.php?fid=" + str, true);
                xmlhttp.send();
            }
        }
    </script>
</head>

<body>
    <div class="loading-overlay" id="loadingOverlay"></div>
    <div class="loading-bar" id="loadingBar"></div>
    <div id="toast-box" class="toast-box"></div>

    <!-- Left Panel -->
    <?php $page = "student";
    include 'includes/leftMenu.php';
    ?>


    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <?php include 'includes/header.php'; ?>
        <!-- /header -->
        <!-- Header-->

        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>Dashboard</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="#">Dashboard</a></li>
                                    <li><a href="#">Student</a></li>
                                    <li class="active">Add Student</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">
                                    <h2 align="center">Add New Student</h2>
                                </strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                        <form id="studentForm" method="post" onsubmit="createStudent(event)">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="cc-exp" class="control-label mb-1">Firstname</label>
                                                        <input id="" name="firstname" type="text" class="form-control cc-exp" value="" required data-val="true" data-val-required="Please enter the card expiration" data-val-cc-exp="Please enter a valid month and year" placeholder="Firstname">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="x_card_code" class="control-label mb-1">Lastname</label>
                                                    <input id="" name="lastname" type="text" class="form-control cc-cvc" value="" required data-val="true" data-val-required="Please enter the security code" data-val-cc-cvc="Please enter a valid security code" placeholder="Lastname">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="cc-exp" class="control-label mb-1">Othername</label>
                                                        <input id="" name="othername" type="text" class="form-control cc-exp" value="" data-val="true" data-val-required="Please enter the card expiration" data-val-cc-exp="Please enter a valid month and year" placeholder="Othername">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="x_card_code" class="control-label mb-1">Level</label>
                                                        <select required id="level" name="level" class="form-control" class="form-control cc-exp">
                                                            <option value="">Select Level</option>
                                                            <option value="1">100L</option>
                                                            <option value="2">200L</option>
                                                            <option value="3">300L</option>
                                                            <option value="4">400L</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="x_card_code" class="control-label mb-1">Department</label>
                                                        <select required id="department" name="department" class="form-control" class="form-control cc-exp">
                                                            <option value="">Select Department</option>
                                                            <option value="nursing">Nursing</option>
                                                            <option value="midwifery">Midwifery</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="x_card_code" class="control-label mb-1">Session</label>
                                                        <input id="session" name="session" type="text" readonly class="form-control cc-exp"
                                                            value="" data-val="true" placeholder="Session">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="cc-exp" class="control-label mb-1">Gender</label>
                                                        <select required id="" name="gender" class="form-control" class="form-control cc-exp" data-val="true" placeholder="Gender">
                                                            <option value="">Select Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="dob" class="control-label mb-1">D.O.B</label>
                                                    <input id="" name="dob" type="date" class="form-control cc-cvc" value="" required data-val="true">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="x_card_code" class="control-label mb-1">State</label>
                                                        <select required id="state" name="state" class="form-control" class="form-control cc-exp" data-val="true" placeholder="State">
                                                            <option value="">Select State</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="x_card_code" class="control-label mb-1">LGA</label>
                                                    <select required id="lga" name="lga" class="form-control" class="form-control cc-exp" data-val="true" placeholder="LGA">
                                                        <option value="">Select LGA</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="cc-exp" class="control-label mb-1">Residential Area</label>
                                                        <input id="" name="residential_address" type="text" class="form-control cc-exp" value="" required data-val="true" data-val-required="Please enter the card expiration" data-val-cc-exp="Please enter a valid month and year" placeholder="Current Address">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="homeAddress" class="control-label mb-1">Home Address</label>
                                                    <input id="" name="home_address" type="text" class="form-control cc-cvc" value="" required data-val="true" data-val-required="Please enter the security code" data-val-cc-cvc="Please enter a valid security code" placeholder="Permanent Address">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="cc-exp" class="control-label mb-1">Email</label>
                                                        <input id="" name="email" type="email" class="form-control cc-exp" value="" required placeholder="Email address">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="phoneNumber" class="control-label mb-1">Phone Number</label>
                                                    <input id="" name="phone_number" type="number" class="form-control cc-cvc" value="" required data-val="true" data-val-cc-cvc="Please enter a valid security code" placeholder="Phone Contact">
                                                </div>
                                            </div>

                                            <h3 class="mt-4">Special Needs/Disabilities(Select if any)</h3><br>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="asthmatic" class="control-label mb-1">Are you Asthmatic:</label>
                                                        <input id="" name="asthmatic" type="checkbox" value="yes" style="margin-left: 33px;transform: scale(1.5);">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="speech" class="control-label mb-1">Speech Problem:</label>
                                                        <input id="" name="speech" type="checkbox" value="yes" style="margin-left: 70px; transform: scale(1.5);">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="hearing" class="control-label mb-1">Hearing/Ear issues:</label>
                                                        <input id="" name="hearing" type="checkbox" value="yes" style="margin-left: 30px;transform: scale(1.5);">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="glasses" class="control-label mb-1">Sight/Contact Glasses:</label>
                                                        <input id="" name="sight" type="checkbox" value="yes" style="margin-left: 30px;transform: scale(1.5);">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="walking" class="control-label mb-1">Walking Problem:</label>
                                                        <input id="" name="walking" type="checkbox" value="yes" style="margin-left: 41px;transform: scale(1.5);">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="sickle" class="control-label mb-1">Sickle Cell Disease:</label>
                                                        <input id="" name="sickle" type="checkbox" value="yes" style="margin-left: 55px;transform: scale(1.5);">
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="mt-4">Sponsorship Details</h3><br>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="sponsorName" class="control-label mb-1">Full Name</label>
                                                        <input id="" name="sponsor_name" type="text" class="form-control cc-exp" required data-val="true" placeholder="Full name">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="nokrelationship" class="control-label mb-1">Relationship</label>
                                                    <select required id="" name="sponsor_relationship" class="form-control" class="form-control cc-exp" required data-val="true" data-val-required="Please enter the card expiration" data-val-cc-exp="Please enter a valid month and year" placeholder="Relationship">
                                                        <option value="">Select Relationship</option>
                                                        <option value="brother">Brother</option>
                                                        <option value="sister">Sister</option>
                                                        <option value="mother">Mother</option>
                                                        <option value="guardian">Guardian</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="sponsorAdress" class="control-label mb-1">Address</label>
                                                            <input id="" name="sponsor_address" type="text" class="form-control cc-exp" value="" required data-val="true" placeholder="Address">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="sponsorNumber" class="control-label mb-1">Phone Number</label>
                                                        <input id="" name="sponsor_phone_number" type="number" class="form-control cc-cvc" value="" required placeholder="Phone number">
                                                    </div>
                                                </div>
                                                <h3 class="mt-4">Next of Kin Details</h3><br>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="nextofkin" class="control-label mb-1">Full Name</label>
                                                            <input id="" name="kin_name" type="text" class="form-control cc-exp" value="" required data-val="true" placeholder="Full name">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="nokrelationship" class="control-label mb-1">Relationship</label>
                                                        <select required id="" name="kin_relationship" class="form-control" class="form-control cc-exp" value="" required data-val="true" placeholder="Relationship">
                                                            <option value="">Select Relationship</option>
                                                            <option value="brother">Brother</option>
                                                            <option value="sister">Sister</option>
                                                            <option value="mother">Mother</option>
                                                            <option value="guardian">Guardian</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="nextofkinaddress" class="control-label mb-1">Address</label>
                                                                <input required id="" name="kin_address" type="text" class="form-control cc-exp" value="" data-val="true" placeholder="Address">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <label for="noknumber" class="control-label mb-1">Phone Number</label>
                                                            <input required id="" name="kin_phone_number" type="number" class="form-control cc-cvc" value="" placeholder="Phone number ">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    <br>

                                                    <button type="submit" class="btn btn-success" style="width:200px;">Register</button>
                                                    <br>

                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .card -->
                    </div><!--/.col-->

                    <br><br>

                    <div class="clearfix"></div>

                    <?php include 'includes/footer.php'; ?>

                </div><!-- /#right-panel -->

                <!-- Right Panel -->

                <!-- Scripts -->
                <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
                <script src="../js/api/v1/uiHelpers.js"></script>
                <script src="../js/api/v1/create-student.js"></script>
                <script src="../assets/js/main.js"></script>
                <script src="../js/states.js"></script>


                <script type="text/javascript">
                    // Menu Trigger
                    $('#menuToggle').on('click', function(event) {
                        var windowWidth = $(window).width();
                        if (windowWidth < 1010) {
                            $('body').removeClass('open');
                            if (windowWidth < 760) {
                                $('#left-panel').slideToggle();
                            } else {
                                $('#left-panel').toggleClass('open-menu');
                            }
                        } else {
                            $('body').toggleClass('open');
                            $('#left-panel').removeClass('open-menu');
                        }

                    });
                </script>

                <script>
                    // Populate states in the state dropdown
                    window.onload = () => {
                        const stateSelect = document.getElementById("state");
                        for (const state in stateLgas) {
                            const option = document.createElement("option");
                            option.value = state;
                            option.textContent = state;
                            stateSelect.appendChild(option);
                        }
                    };

                    // Event listener to populate LGAs based on selected state
                    document.getElementById("state").addEventListener("change", function() {
                        const lgaSelect = document.getElementById("lga");
                        const selectedState = this.value;

                        // Clear previous LGAs
                        lgaSelect.innerHTML = '<option value="">Select LGA</option>';

                        // Populate new LGAs if a state is selected
                        if (selectedState && stateLgas[selectedState]) {
                            stateLgas[selectedState].forEach(lga => {
                                const option = document.createElement("option");
                                option.value = lga;
                                option.textContent = lga;
                                lgaSelect.appendChild(option);
                            });
                        }
                    });
                </script>
</body>

</html>