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
    <link rel="stylesheet" href="../assets/css/loading-bar.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

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
    include 'includes/leftMenu.php'; ?>

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <?php include 'includes/header.php'; ?>
        <!-- /header -->

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
                                    <li class="active">Edit Student</li>
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
                                    <h2 align="center">Edit Student</h2>
                                </strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                        <div id="responseMessage"></div>
                                        <form id="studentForm" method="post" onsubmit="updateStudent(event)">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="cc-exp" class="control-label mb-1">Firstname</label>
                                                        <input id="firstname" name="firstname" type="text" class="form-control cc-exp" value="" required data-val="true" placeholder="Firstname">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="x_card_code" class="control-label mb-1">Lastname</label>
                                                    <input id="lastname" name="lastname" type="text" class="form-control cc-cvc" value="" required data-val="true" data-val-required="Please enter the security code" data-val-cc-cvc="Please enter a valid security code" placeholder="Lastname">
                                                </div>
                                            </div>
                                            <div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="cc-exp" class="control-label mb-1">Othername</label>
                                                            <input id="othername" name="othername" type="text" class="form-control cc-exp" value="" data-val="true" data-val-required="Please enter the card expiration" data-val-cc-exp="Please enter a valid month and year" placeholder="Othername">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="x_card_code" class="control-label mb-1">Level</label>
                                                            <select required id="level" name="level" class="form-control" class="form-control cc-exp">

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="x_card_code" class="control-label mb-1">Department</label>
                                                            <select required id="department" name="department" class="form-control" class="form-control cc-exp">

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
                                                        <label for="gender" class="control-label mb-1">Gender</label>
                                                        <select id="gender" name="gender" class="form-control" required>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="dob" class="control-label mb-1">D.O.B</label>
                                                        <input id="dob" name="dob" type="date" class="form-control" value="" required>
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
                                                        <label for="residentialAddress" class="control-label mb-1">Residential Address</label>
                                                        <input id="residential_address" name="residential_address" type="text" class="form-control" value="" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="homeAddress" class="control-label mb-1">Home Address</label>
                                                        <input id="home_address" name="home_address" type="text" class="form-control" value="" required>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="email" class="control-label mb-1">Email</label>
                                                        <input id="email" name="email" type="email" class="form-control" value="" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="phoneNumber" class="control-label mb-1">Phone Number</label>
                                                        <input id="phone_number" name="phone_number" type="number" class="form-control" value="" required>
                                                    </div>
                                                </div>

                                                <h3 class="mt-4">Special Needs/Disabilities</h3><br>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>Are you Asthmatic:</label>
                                                        <input id="asthmatic" type="checkbox" name="asthmatic" value="yes">
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Speech Problem:</label>
                                                        <input id="speech" type="checkbox" name="speech" value="yes">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="hearing">Hearing/Ear Issues</label>
                                                        <input id="hearing" name="hearing" type="checkbox" value="yes">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="glasses">Sight/Contact Glasses</label>
                                                        <input id="sight" name="sight" type="checkbox" value="yes">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="walking">Walking Problem</label>
                                                        <input id="walking" name="walking" type="checkbox" value="yes">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="sickle">Sickle Cell Disease</label>
                                                        <input id="sickle_cell" name="sickle_cell" type="checkbox" value="yes">
                                                    </div>
                                                </div>
                                                <h3>Sponsorship Details</h3><br>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="sponsorName" class="control-label mb-1">Sponsor Name</label>
                                                        <input id="sponsor_name" name="sponsor_name" type="text" class="form-control" value="" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="relationship" class="control-label mb-1">Relationship</label>
                                                        <select required id="sponsor_relationship" name="sponsor_relationship" class="form-control" class="form-control cc-exp" required data-val="true" data-val-required="Please enter the card expiration" placeholder="Relationship">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="sponsorAdress" class="control-label mb-1">Address</label>
                                                            <input id="sponsor_address" name="sponsor_address" type="text" class="form-control cc-exp" value="<?php echo $sponsor['address']; ?>" required data-val="true" placeholder="Address">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="sponsorNumber" class="control-label mb-1">Phone Number</label> <!-- Updated -->
                                                        <input id="sponsor_phone_number" name="sponsor_phone_number" type="number" class="form-control" value="<?php echo $sponsor['phone_number']; ?>" required placeholder="Phone number of Sponsor"> <!-- Updated -->
                                                    </div>

                                                </div>
                                                <h3>Next of Kin Details</h3><br>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="nextofkin" class="control-label mb-1">Full Name</label>
                                                        <input id="kin_name" name="kin_name" type="text" class="form-control" value="" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="nokrelationship" class="control-label mb-1">Relationship</label>
                                                        <select required id="kin_relationship" name="kin_relationship" class="form-control" class="form-control cc-exp" value="" required data-val="true" placeholder="Relationship">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="nextofkinaddress" class="control-label mb-1">Address</label>
                                                        <input id="kin_address" name="kin_address" type="text" class="form-control cc-exp" value="<?php echo $kin['address']; ?>" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="noknumber" class="control-label mb-1">Phone number</label>
                                                        <input id="kin_phone_number" name="kin_phone_number" type="number" class="form-control cc-cvc" value="<?php echo $kin['phone_number']; ?>" required>
                                                    </div>
                                                    <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    <input type="hidden" id="matric_number" name="matric_number" value="">
                                                </div>
                                                <br>
                                                <button type="submit" class="btn btn-success">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .card -->
                    </div><!--/.col-->

                </div>
            </div><!-- .animated -->
        </div><!-- .content -->

        <div class="clearfix"></div>

        <?php include 'includes/footer.php'; ?>

    </div><!-- /#right-panel -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../js/api/v1/uiHelpers.js"></script>
    <script src="../js/api/v1/create-student.js"></script>
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

</body>

</html>