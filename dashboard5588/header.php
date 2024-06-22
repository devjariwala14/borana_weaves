<?php
ob_start();
include "db_connect.php";
$obj = new DB_Connect();
date_default_timezone_set('Asia/Kolkata');

session_start();

if (!isset($_SESSION["userlogin"])) {
  header("location:login.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard-Borana Weaves</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favi_32.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">


  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: May 30 2023 with Bootstrap v5.3.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <script>
    function createCookie(name, value, days) {
      var expires;
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
      } else {
        expires = "";
      }
      document.cookie = (name) + "=" + String(value) + expires + ";path=/ ";
    }
    function readCookie(name) {
      var nameEQ = (name) + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return (c.substring(nameEQ.length, c.length));
      }
      return null;
    }
    function eraseCookie(name) {
      createCookie(name, "", -1);
    }

  </script>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/borana_54.jpg" alt="">
        <span class="d-none d-lg-block">Borana Weaves</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->



    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->





        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/user.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION["username"] ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li>
              <a class="dropdown-item d-flex align-items-center" href="change_password.php">
                <i class="bi bi-key"></i>
                <span>Change Password</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">


      <!-- Board Members -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="board_of_director.php">
          <i class="bi bi-person-add"></i>
          <span>Board Members</span>
        </a>
      </li>

      <!-- Downloads -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="downloads.php">
          <i class="bi bi-download"></i>
          <span>Downloads</span>
        </a>
      </li>
      
      <!-- manufacturing -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="manufacturing.php">
          <i class="bi bi-building"></i>
          <span>Manufacturing Unit</span>
        </a>
      </li>

      <!-- Sustainability -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="sustainability.php">
          <i class="bi bi-columns-gap"></i>
          <span>Sustainability</span>
        </a>
      </li>

      <!-- services -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="services.php">
          <i class="bi bi-filter-left"></i>
          <span>Services </span>
        </a>
      </li>

      <!-- Videos -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="videos.php">
          <i class="bi bi-tv"></i>
          <span>Videos</span>
        </a>
      </li>

      <!-- Contact us -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="contact_us.php">
          <i class="bi bi-telephone"></i>
          <span>Contact Us</span>
        </a>
      </li>

      <!-- About us -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="about_us.php">
          <i class="bi bi-info-circle"></i>
          <span>About Us</span>
        </a>
      </li>

      <!-- Career -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="career.php">
          <i class="bi bi-person-exclamation"></i>
          <span>Career</span>
        </a>
      </li>

      <!-- Products -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="products.php">
          <i class="bi bi-bag"></i>
          <span>Products</span>
        </a>
      </li>

       <!-- Investors Menu -->
       <li class="nav-item">
        <a class="nav-link collapsed" href="investors_menu.php">
          <i class="bi bi-list"></i>
          <span>Investors Menu</span>
        </a>
      </li>

       <!-- Investors Data -->
       <li class="nav-item">
        <a class="nav-link collapsed" href="investors_data.php">
          <i class="bi bi-journal-text"></i>
          <span>Investors Data</span>
        </a>
      </li>

      <!-- Forms And Application -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="forms_and_apps.php">
          <i class="bi bi-file-earmark-text"></i>
          <span>Forms And Application</span>
        </a>
      </li>

       <!-- IPO -->
       <li class="nav-item">
        <a class="nav-link collapsed" href="ipo.php">
          <i class="bi bi-clipboard-data"></i>
          <span>IPO</span>
        </a>
      </li>

       <!-- Corporate Announcement -->
       <li class="nav-item">
        <a class="nav-link collapsed" href="corporate_announcement.php">
          <i class="bi bi-megaphone"></i>
          <span>Corporate Announcement</span>
        </a>
      </li>
      <!-- Primary Benifits -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="primary_benifits.php">
          <i class="bi bi-patch-question"></i>
          <span>Primary Benifits</span>
        </a>
      </li>

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main min-vh-100">