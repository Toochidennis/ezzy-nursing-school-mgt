<?php
require_once '../includes2/session.php';
?>

<!DOCTYPE html>
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'includes/title.php'; ?>
    <meta name="description" content="Ezzy nursing">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="../assets/img/icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.css">
    <link rel="stylesheet" href="../assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../assets/css/style2.css">
    <link rel="stylesheet" href="../assets/css/style3.css">
    <link rel="stylesheet" href="../assets/css/loading-bar.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>

<body>

    <div class="loading-overlay" id="loadingOverlay"></div>
    <div class="loading-bar" id="loadingBar"></div>
    <div id="toast-box" class="toast-box"></div>

    <!-- Left Panel -->
    <?php $page = "courses";
    include 'includes/leftMenu.php'; ?>


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
                                    <li><a href="#">Courses</a></li>
                                    <li class="active">Course Registration</li>
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
                        </div> <!-- .card -->
                    </div><!--/.col-->

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="semesterSelect">Filter by Semester:</label>
                                        <select id="semesterSelect" class="form-control">
                                            <option value="1">First Semester</option>
                                            <option value="2">Second semester</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            </div>
                            <br>
                            <div class="card-body">
                                <table
                                    id="studentTable"
                                    data-toggle="table"
                                    data-pagination="true"
                                    data-search="false"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th data-field="state">
                                                <input class="text-center" type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                            </th>
                                            <th data-field="index">S/N</th>
                                            <th data-field="courseName">Course name</th>
                                            <th data-field="courseCode">Course code</th>
                                            <th data-field="courseUnit">Course unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end of datatable -->
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->

        <!-- Floating Action Button for Register -->
        <button id="registerButton" class="fab btn btn-primary tooltip-btn"
            style="position: fixed; bottom: 20px; right: 20px; display: none;"
            onclick="registerSelectedCourses()">
            <i class="fa fa-plus"></i>
            <span class="tooltip-text">Register Selected Courses</span>
        </button>

        <div class="clearfix"></div>

        <?php include 'includes/footer.php'; ?>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="../js/api/v1/uiHelpers.js"></script>
    <script src="../js/api/v1/course-registration.js"></script>
    <script src="../assets/js/main.js"></script>


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