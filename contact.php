<?php
include "header.php";

if (isset($_REQUEST["submit_btn"])) {
    $name = $_REQUEST["name"];
    $email = $_REQUEST["email"];
    $message = $_REQUEST["message"];
    $contact = $_REQUEST["contact"];

    $stmt_contact = $obj->con1->prepare("INSERT INTO `contact_us`( `name`, `email`, `message`,`phone_no`) VALUES (?,?,?,?)");
    $stmt_contact->bind_param("ssss", $name, $email, $message, $contact);
    $stmt_contact->execute();

    $stmt_contact->close();
}
?>

<main id="main">

    <!-- Header Section -->
    <section class="page-section pb-100 pb-sm-60 bg-gray-light-1 bg-light-alpha-90 parallax-5"
        style="background-image: url(images/full-width-images/page-title-bg-4.jpg)">
        <div class="position-absolute top-0 bottom-0 start-0 end-0 bg-gradient-white"></div>
        <div class="container position-relative pt-50">

            <!-- Section Content -->
            <div class="text-center">
                <div class="row">

                    <!-- Page Title -->
                    <div class="col-md-8 offset-md-2">

                        <h2 class="section-caption-border mb-30 mb-xs-20 wow fadeInUp" data-wow-duration="1.2s">
                            Contact Us
                        </h2>



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
        <div class="container position-relative wow fadeInUp">

            <div class="row mb-60 mb-sm-50">

                <div class="col-lg-6 mb-sm-50">

                    <div class="pe-lg-5">

                        <h2 class="h5">Don't hesitate to reach out to us.</h2>

                        <h3 class="section-title mb-0"><span class="wow charsAnimIn" data-splitting="chars">Let’s start
                                the productive work.</span></h3>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="row">

                        <!-- Contact Item -->
                        <div class="col-sm-6 mb-xs-30 d-flex align-items-stretch">
                            <div class="alt-features-item border-left mt-0">
                                <div class="alt-features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                                        clip-rule="evenodd">
                                        <path
                                            d="M24 21h-24v-18h24v18zm-23-16.477v15.477h22v-15.477l-10.999 10-11.001-10zm21.089-.523h-20.176l10.088 9.171 10.088-9.171z">
                                        </path>
                                    </svg>
                                    <div class="alt-features-icon-s">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path
                                                d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3.445 17.827c-3.684 1.684-9.401-9.43-5.8-11.308l1.053-.519 1.746 3.409-1.042.513c-1.095.587 1.185 5.04 2.305 4.497l1.032-.505 1.76 3.397-1.054.516z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <h4 class="alt-features-title">Say hello</h4>
                                <div class="alt-features-descr clearlinks">
                                    <div><a href="mailto:support@boranaweaves.com">support@boranaweaves.com</a></div>
                                    <div>+91 99250 44300</div>
                                </div>
                            </div>
                        </div>
                        <!-- End Contact Item -->

                        <!-- Contact Item -->
                        <div class="col-sm-6 d-flex align-items-stretch">
                            <div class="alt-features-item border-left mt-0">
                                <div class="alt-features-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                                        clip-rule="evenodd">
                                        <path
                                            d="M12 10c-1.104 0-2-.896-2-2s.896-2 2-2 2 .896 2 2-.896 2-2 2m0-5c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3m-7 2.602c0-3.517 3.271-6.602 7-6.602s7 3.085 7 6.602c0 3.455-2.563 7.543-7 14.527-4.489-7.073-7-11.072-7-14.527m7-7.602c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602">
                                        </path>
                                    </svg>
                                </div>
                                <h4 class="alt-features-title">Location</h4>
                                <div class="alt-features-descr">
                                    PLOT NO.AA/93, SACHIN UDYOGNAGAR SAHAKARI SANGH LTD VILLAGE SACHIN/LAJPOR,
                                    TALUKA, CHORYASI, Surat, Gujarat 394230.
                                </div>
                            </div>
                        </div>
                        <!-- End Contact Item -->

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-sm-50">

                    <!-- Contact Form -->
                    <form class="form contact-form pe-lg-5" method="post">

                        <div class="row">
                            <div class="col-lg-6">

                                <!-- Name -->
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="input-lg round form-control"
                                        placeholder="Enter your name" required="" aria-required="true">
                                </div>
                            </div>

                            <div class="col-lg-6">

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="input-lg round form-control"
                                        placeholder="Enter your email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required aria-required="true">
                                </div>

                            </div>
                        </div>

                        <!-- Message -->
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="input-lg round form-control"
                                style="height: 130px;" placeholder="Enter your message"></textarea>
                        </div>

                        <!-- Contact -->
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <input type="text" name="contact" id="contact" class="input-lg round form-control"
                                placeholder="Enter your Phone number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10" required aria-required="true">
                        </div>

                        <div class="row">
                            <div class="col-lg-5">

                                <!-- Send Button -->
                                <div class="pt-20">
                                    <button type="submit" class=" btn btn-mod btn-large btn-round btn-hover-anim"
                                        id="submit_btn" name="submit_btn">
                                        <span>Send Message</span></button>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <!-- Inform Tip -->
                                <div class="form-tip pt-20 pt-sm-0 mt-sm-20">
                                    <i class="icon-info size-16"></i>
                                    All the fields are required. By sending the form you agree to the <a href="#">Terms
                                        &amp; Conditions</a> and <a href="#">Privacy Policy</a>.
                                </div>
                            </div>
                        </div>
                        <div id="result" role="region" aria-live="polite" aria-atomic="true"></div>
                    </form>
                    <!-- End Contact Form -->

                </div>

                <div class="col-md-6 d-flex align-items-stretch">

                    <!-- Google Map -->
                    <div class="map-boxed">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d145702.30952706104!2d72.81764310714274!3d21.201308277896395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be05b75164b153b%3A0x4130aa30bb928e6a!2sBorana%20Weaves%20Pvt.Ltd.!5e0!3m2!1sen!2sin!4v1716209212215!5m2!1sen!2sin"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <!-- End Google Map -->

                </div>

            </div>

        </div>
    </section>
    <!-- End Contact Section -->
</main>

<?php
include "footer.php";
?>