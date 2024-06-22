<?php
include "db_connect.php";
$obj = new DB_Connect();
date_default_timezone_set("Asia/Kolkata");

session_start();

// if (!isset($_SESSION["userlogin"])) {
//     header("location:index.php");
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Borana Weaves</title>
    <meta name="description" content="Borana Weaves">
    <meta charset="utf-8">
    <meta name="author" content="https://themeforest.net/user/bestlooker/portfolio">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" href="images/borana_favi.png" type="image/png" sizes="any">
    <link rel="icon" href="images/borana_favi.png" type="image/svg+xml">

    <!-- CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-responsive.css">
    <link rel="stylesheet" href="css/vertical-rhythm.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/splitting.css">
    <link rel="stylesheet" href="css/YTPlayer.css">
    <link rel="stylesheet" href="css/demo-main/demo-main.css">
    <link rel="stylesheet" href="fonts/stylesheet.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet">

</head>

<body class="appear-animate">
    <!-- Page Loader -->
    <div class="borana-loader ">
        <div class="spinner2 text-center">
            <img src="./images/logo-design.png" alt="" class="d-block m-auto" class="img-fluid">
            <div class="name">
                <div class="letter">B</div>
                <div class="letter">O</div>
                <div class="letter">R</div>
                <div class="letter">A</div>
                <div class="letter">N</div>
                <div class="letter">A</div>
            </div>
            <p class="m-0">WEAVES PVT LTD</p>
            <h4 class="m-0">WEAVING DREAMS</h4>
        </div>
    </div>

    <!--<section class=" main-wrapper d-none">-->
        <!-- End Page Loader -->
        <!-- Skip to Content -->
        <a href="#main" class="btn skip-to-content">Skip to Content</a>
        <!-- End Skip to Content -->

        <!-- Page Wrap -->
        <div class="page" id="top">

            <!-- Navigation Panel -->
            <nav class="main-nav transparent stick-fixed wow-menubar">
                <div class="main-nav-sub full-wrapper">
                    <div class="nav-logo-wrap local-scroll">
                        <a href="index.php" class="logo">
                            <img src="images/Group 1068.png" alt="Your Company Logo" width="105" height="34">
                        </a>
                    </div>
                    <!-- Mobile Menu Button -->
                    <div class="mobile-nav" role="button" tabindex="0">
                        <i class="mobile-nav-icon"></i>
                        <span class="visually-hidden">Menu</span>
                    </div>

                    <!-- Main Menu -->
                    <div class="inner-nav desktop-nav">
                        <ul class="clearlist local-scroll">

                            <!-- Item With Sub -->
                            <li><a href="index.php">Home </a> </li>
                            <!-- End Item With Sub -->

                            <!-- Item With Sub -->
                            <!-- End Item With Sub -->
                            <li><a href="#" class="mn-has-sub">About <i class="mi-chevron-down"></i></a>
                                <!-- Sub Megamenu -->
                                <ul class="mn-sub mn-has-multi">
                                    <!-- Sub Column -->
                                    <li class="mn-sub-multi">
                                        <ul>
                                            <li><a href="about.php">About Us</a></li>
                                            <li><a href="timeline.php">Our Journey</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <!-- End Item With Sub -->
                            <li><a href="#" class="mn-has-sub">Manufacturing Unit <i class="mi-chevron-down"></i></a>
                                <!-- Sub Megamenu -->
                                <ul class="mn-sub mn-has-multi">
                                    <!-- Sub Column -->
                                    <li class="mn-sub-multi">
                                        <ul>
                                            <li><a href="unit1.php">Manufacturing Unit 1</a></li>
                                            <li><a href="unit2.php">Manufacturing Unit 2</a></li>
                                            <li><a href="unit3.php">Manufacturing Unit 3</a></li>
                                            <li><a href="unit4.php">Manufacturing Unit 4</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="sustainability.php">Sustainablility </a></li>
                            <li><a href="#" class="mn-has-sub">Investors Speak <i class="mi-chevron-down"></i></a>
                                <!-- Sub Megamenu -->
                                <ul class="mn-sub mn-has-multi">
                                    <li class="mn-sub-multi">
                                        <ul>
                                            <li><a href="announcement.php">Announcement</a></li>
                                            <li><a href="notice.php">Notices</a></li>
                                            <li><a href="cor_gov_report.php">Corporate Governance Report</a></li>
                                            <li><a href="#">Shareholding Pattern</a></li>
                                            <li><a href="#">Financial Results</a></li>
                                            <li><a href="#">Annual Report</a></li>
                                            <li><a href="#">Annual Secretarial Compliance Report</a></li>
                                            <li><a href="#">Subsidiary Financial Report</a></li>
                                            <li><a href="#">Publication</a></li>
                                            <li><a href="#">Investor Presentation</a></li>
                                            <li><a href="#">Policies</a></li>                                                                                      
                                        </ul>
                                    </li>
                                    <li class="mn-sub-multi">
                                        <ul>
                                            <li><a href="#">Annual Return</a></li>
                                            <li><a href="#">Forms and Applications</a></li>
                                            <li><a href="#">IPO</a></li>
                                            <li><a href="#">Credit Rating</a></li>
                                            <li><a href="#">Company Information</a></li>
                                            <li><a href="#">Investor Contact</a></li>
                                            <li><a href="#">Disclosures under Regulation 46 of SEBI (LODR) Regulations</a></li>
                                            <li><a href="#">Preferential Issue</a></li>
                                            <li><a href="#">Investors Complaint</a></li>
                                            <li><a href="#">Reconciliation of share capital audit report</a></li>
                                        </ul>
                                    </li>
                                </ul>
                                <!-- End Sub Megamenu -->
                            </li>
                            <li><a href="career.php">Career</a></li>
                            <li><a href="contact.php">Contact Us</a></li>

                        </ul>
                    </div>
                    <!-- End Main Menu -->
                </div>
            </nav>
            <!-- End Navigation Panel -->