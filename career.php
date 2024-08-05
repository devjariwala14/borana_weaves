<?php
include "header.php";

// Check for the cookie and initialize the success message
$successMessage = isset($_COOKIE['successMessage']) ? $_COOKIE['successMessage'] : '';
$fileErrorMessage = ''; // Variable to hold file error message

if (isset($_REQUEST["submit_btn"])) {
    $name = $_REQUEST["name"];
    $email = $_REQUEST["email"];
    $message = $_REQUEST["message"];
    $contact = $_REQUEST["contact"];

    $resume = $_FILES["resume"];
    $resumeName = $resume["name"];
    $resumeTmpName = $resume["tmp_name"];
    $resumeSize = $resume["size"];
    $resumeError = $resume["error"];
    $resumeType = $resume["type"];

    // File extension validation
    $resumeExt = strtolower(pathinfo($resumeName, PATHINFO_EXTENSION));
    $allowedExts = array('pdf', 'doc', 'docx');

    // Define upload directory
    $uploadDir = 'documents/career/';

    // Check if the directory exists and is writable
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            die("Failed to create upload directory.");
        }
    }

    if (in_array($resumeExt, $allowedExts)) {
        if ($resumeError === 0) {
            if ($resumeSize <= 5000000) { // 5MB file size limit
                $resumeNewName = uniqid('', true) . "." . $resumeExt;
                $resumeDestination = $uploadDir . $resumeNewName;

                if (move_uploaded_file($resumeTmpName, $resumeDestination)) {
                    // Insert data into the database
                    $stmt_contact = $obj->con1->prepare("INSERT INTO `career`(`name`, `email`, `msg`, `number`, `download`) VALUES (?, ?, ?, ?, ?)");
                    $stmt_contact->bind_param("sssss", $name, $email, $message, $contact, $resumeNewName);
                    $stmt_contact->execute();
                    $stmt_contact->close();

                    // Set the success message in a cookie
                    setcookie('successMessage', 'Your application has been submitted successfully!', time() + 10, "/"); // 10-second duration
                    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to avoid form resubmission
                    exit();
                } else {
                    $successMessage = "There was an error moving the uploaded file.";
                }
            } else {
                $successMessage = "Your file is too large. Maximum size allowed is 5MB.";
            }
        } else {
            $successMessage = "There was an error uploading your file.";
        }
    } else {
        $fileErrorMessage = "Invalid file type. Only PDF, DOC, and DOCX are allowed.";
    }
}

// Clear the cookie after displaying the message
if ($successMessage) {
    setcookie('successMessage', '', time() - 3600, "/");
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
                            Career
                        </h2>

                        <h1 class="hs-title-1 mb-0">
                            <span class="wow charsAnimIn" data-splitting="chars">Submit your resume to start your
                                journey with us.</span>
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

                    <!-- Display Success Message -->
                    <?php if ($successMessage): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?php echo $successMessage; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form class="form contact-form wow fadeInUp wch-unset" data-wow-delay=".5s" data-wow-offset="0"
                        id="contact_form" enctype="multipart/form-data" method="post" onsubmit="return validateFileType()">

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name -->
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="input-lg round form-control"
                                        placeholder="Enter your name" pattern=".{3,100}" required aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="input-lg round form-control"
                                        placeholder="Enter your email" pattern=".{5,100}" required aria-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- phone -->
                                <div class="form-group">
                                    <label for="contact">Phone</label>
                                    <input type="text" name="contact" id="contact" class="input-lg round form-control"
                                        placeholder="Enter your phone"
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10"
                                        required aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- file -->
                                <div class="form-group">
                                    <label for="file">Upload Resume</label>
                                    <input type="file" name="resume" id="file" class="input-lg file-p round form-control"
                                        style="padding: 13px 0px;" required aria-required="true">
                                    <!-- File format error message -->
                                    <div id="fileFormatError" class="text-danger mt-2">
                                        <?php echo $fileErrorMessage; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="input-lg round form-control"
                                style="height: 130px;" placeholder="Enter your message"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <!-- Send Button -->
                                <div class="text-start pt-20 pt-xs-40">
                                    <button class="submit_btn btn btn-mod btn-large btn-round btn-hover-anim"
                                        id="submit_btn" name="submit_btn" aria-controls="result">
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

<script>
// JavaScript for client-side file format validation
function validateFileType() {
    const fileInput = document.getElementById('file');
    const filePath = fileInput.value;
    const allowedExtensions = /(\.pdf|\.doc|\.docx)$/i;

    if (!allowedExtensions.exec(filePath)) {
        document.getElementById('fileFormatError').innerText = 'Invalid file type. Only PDF, DOC, and DOCX are allowed.';
        fileInput.value = ''; // Clear the input value
        return false;
    } else {
        document.getElementById('fileFormatError').innerText = ''; // Clear any previous error message
        return true;
    }
}
</script>

<?php
include "footer.php";
?>
