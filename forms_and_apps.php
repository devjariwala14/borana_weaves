<?php 

include "header.php";

$stmt_product = $obj->con1->prepare("SELECT * FROM `forms_and_apps`");
$stmt_product->execute();
$res = $stmt_product->get_result();
$stmt_product->close();

?>
<main id="main">

    <!-- Header Section -->
    <section class="page-section bg-gray-light-1 bg-light-alpha-90 parallax-5"
        style="background-image: url(images/full-width-images/section-bg-1.jpg)" id="home">
        <div class="container position-relative pt-30 pt-sm-50">

            <!-- Section Content -->
            <div class="text-center">
                <div class="row">

                    <!-- Page Title -->
                    <div class="col-md-8 offset-md-2">

                        <h1 class="hs-title-1 mb-20">
                            <span class="wow charsAnimIn" data-splitting="chars">Forms and Applications</span>
                        </h1>

                        <div class="row">
                            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                                <p class="section-descr mb-0 wow fadeIn" data-wow-delay="0.2s" data-wow-duration="1.2s">
                                    Discovering our brilliant insights and inspiration.
                                </p>
                            </div>
                        </div>

                    </div>
                    <!-- End Page Title -->

                </div>
            </div>
            <!-- End Section Content -->

        </div>
    </section>
    <!-- End Header Section -->


    <!-- Section -->
    <section class="page-section">
        <div class="container relative">

            <div class="row">
                
            <?php
                $counter = 0;
                while($data = mysqli_fetch_array($res)) {
                    if ($counter % 2 == 0) {
                        echo '<div class="row">';
                    }
            ?>

                <!-- Post -->
                <div class="col-md-6">
                    <div class="blog-item box-shadow round p-4 p-md-5">

                        <!-- Post Title -->
                        <h2 class="blog-item-title"><a href="main-blog-single-sidebar-right.html"><?php echo $data["title"]?></a></h2>

                        <!-- Text Intro -->
                        <div class="mb-10">
                            <p>
                                Please download the PDF below to view the details.
                            </p>
                        </div>

                        <!-- Read More Link -->
                        <div class="col-lg-5">
                            <!-- Send Button -->
                            <div class="pt-10">
                                <a href="documents/announcement/<?php echo $data["file"]?>" download="download" class="btn btn-mod btn-large btn-round btn-hover-anim"><span>Download</span></a>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End Post -->
            <?php
                    $counter++;
                    if ($counter % 2 == 0) {
                        echo '</div>';
                    }
                }
                if ($counter % 2 != 0) {
                    echo '</div>';
                }
            ?>

            </div>

        </div>
    </section>
    <!-- End Section -->

</main>
<?php

include "footer.php";

?>
