<?php
require_once '../includes2/config.php';
?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'includes/title.php'; ?>
    <meta name="description" content="Ezzy Nursing">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="../assets/img/icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="http://localhost/admin/assets/css/style4.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>

<body>
    <div class="download-btn">
        <button id="download-btn" class="btn btn-primary">Print</button>
    </div>
    <div id="content" class="container">
        <div class="header">
            <img src="http://localhost/schoolms/assets/img/icon.png" alt="School Logo" class="logo">
            <div class="title">
                <h3>EZZY INTERNATIONAL COLLEGE OF NURSING SCIENCES</h3>
                <p>OFFICE OF THE REGISTRAR - COURSE REGISTRATION FORM</p>
            </div>
            <div class="passport">PASSPORT</div>
        </div>

        <div class="info-section">
            <div class="info-field">
                <label>REG. NO:</label>
                <div class="info-placeholder" id="reg-no"></div>
            </div>
            <div class="info-field">
                <label>DEPARTMENT:</label>
                <div class="info-placeholder" id="department"></div>
            </div>
            <div class="info-field">
                <label>FIRSTNAME:</label>
                <div class="info-placeholder" id="firstname"></div>
            </div>
            <div class="info-field">
                <label>LASTNAME:</label>
                <div class="info-placeholder" id="lastname"></div>
            </div>
            <div class="info-field">
                <label>MIDDLENAME:</label>
                <div class="info-placeholder" id="middlename"></div>
            </div>
            <div class="info-field">
                <label>LEVEL:</label>
                <div class="info-placeholder" id="level"></div>
            </div>
        </div>

        <div class="courses-title-container">
            <div class="courses-title" id="course-title"></div>
        </div>

        <table id="courses-table"
            data-toggle="table"

            style="width: 100%;">
            <thead>
                <th data-field="index">S/N</th>
                <th data-field="courseCode">COURSE CODE</th>
                <th data-field="courseTitle">COURSE TITLE</th>
                <th data-field="courseUnit">UNIT</th>
            </thead>
            <tbody></tbody>
        </table>

        <div class="footer-container">
            <div class="footer-row">
                <label>HOD's Name & Signature</label>
                <span class="line"></span>
                <label>Date</label>
                <span class="line small"></span>
            </div>
            <div class="footer-row">
                <label>Registrar's Name & Signature</label>
                <span class="line"></span>
                <label>Date</label>
                <span class="line small"></span>
            </div>

            <!-- <div class="date-placeholder" id="date-bottom-left"></div> -->
        </div>

    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/print/printThis.js"></script>
    <script src="../js/api/v1/uiHelpers.js"></script>
    <script src="../js/api/v1/print-course-form.js"></script>
</body>

</html>