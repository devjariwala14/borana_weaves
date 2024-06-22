<?php
include "header.php"
?>
<main id="main">

    <!-- Header Section -->
    <section class="page-section pb-100 pb-sm-60 bg-gray-light-1 bg-light-alpha-90 parallax-5" style="background-image: url(images/full-width-images/page-title-bg-4.jpg)">
        <div class="position-absolute top-0 bottom-0 start-0 end-0 bg-gradient-white"></div>
        <div class="container position-relative pt-50">

            <!-- Section Content -->
            <div class="text-center">
                <div class="row">

                    <!-- Page Title -->
                    <div class="col-md-8 offset-md-2">

                        <h2 class="section-caption-border mb-30 mb-xs-20 wow fadeInUp" data-wow-duration="1.2s">
                            Career
                        </h2>

                        <h1 class="hs-title-1 mb-0">
                            <span class="wow charsAnimIn" data-splitting="chars">Submit your resume to start your journey with us.</span>
                        </h1>

                    </div>
                    <!-- End Page Title -->

                </div>
            </div>
            <!-- End Section Content -->

        </div>
    </section>
    <!-- End Header Section -->


    <!-- Contact Section -->
    <section class="page-section pt-0" id="contact">
        <div class="container">
            <!-- Contact Form -->
            <div class="row">
                <div class="col-md-10 offset-md-1">

                    <form class="form contact-form wow fadeInUp wch-unset" data-wow-delay=".5s" data-wow-offset="0" id="contact_form">

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name -->
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="input-lg round form-control" placeholder="Enter your name" pattern=".{3,100}" required aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="input-lg round form-control" placeholder="Enter your email" pattern=".{5,100}" required aria-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- phone -->
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="input-lg round form-control" placeholder="Enter your phone" pattern=".{3,100}" required aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- file -->
                                <div class="form-group">
                                    <label for="file">Upload Resume</label>
                                    <input type="file" name="file" id="file" class="input-lg file-p round form-control" style="padding: 13px 0px;" placeholder="Choose File" pattern=".{5,100}" required aria-required="true">
                                </div>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="input-lg round form-control" style="height: 130px;" placeholder="Enter your message"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <!-- Send Button -->
                                <div class="text-start pt-20 pt-xs-40">
                                    <button class="submit_btn btn btn-mod btn-large btn-round btn-hover-anim" id="submit_btn" aria-controls="result">
                                        <span>Apply</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="result" role="region" aria-live="polite" aria-atomic="true"></div>

                    </form>

                </div>
            </div>
            <!-- End Contact Form -->

        </div>
    </section>
    <!-- End Contact Section -->
</main>
<?php
include "footer.php"
?>