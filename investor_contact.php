<?php 

include "header.php";

$stmt_product = $obj->con1->prepare("SELECT * FROM `investors_contact`");
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
                            <span class="wow charsAnimIn" data-splitting="chars">Investor Contact</span>
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
                while($data=mysqli_fetch_array($res))
                {
            ?>

                <!-- Post -->
                <div class="blog-item box-shadow round p-4 p-md-5">

                    <!-- Post Title -->
                    <h2 class="blog-item-title"><a href="main-blog-single-sidebar-right.html"><?php echo $data["title"]?></a></h2>

                    <!-- Author, Categories, Comments -->
                    <!-- <div class="blog-item-data">
                        <a href="#"><i class="mi-clock size-16"></i> <?php echo date("M d Y",strtotime($data["date"]))?></a>
                        <span class="separator">&nbsp;</span>
                    </div> -->

                    <!-- Additional Details -->
                    <div class="mb-10">
                        <p><?php echo $data["name"]?></p>
                        <p><?php echo $data["designation"]?></p>
                        <p><?php echo $data["address"]?></p>
                        <p><strong>Email:</strong> <?php echo $data["email"]?></p>
                        <?php if (!empty($data["website"])) { ?>
                            <p><strong>Website:</strong> <a href="<?php echo $data["website"]?>" target="_blank"><?php echo $data["website"]?></a></p>
                        <?php } ?>
                        <?php if (!empty($data["contact"])) { ?>
                            <p><strong>Contact:</strong> <?php echo $data["contact"]?></p>
                        <?php } ?>
                    </div>
                </div>
                <!-- End Post -->
                 <?php
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
