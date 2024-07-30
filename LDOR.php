<?php 

include "header.php";

$stmt_product = $obj->con1->prepare("SELECT * FROM `ldor`");
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
                            <span class="wow charsAnimIn" data-splitting="chars">Disclosures under Regulation 46 of SEBI (LODR) Regulations</span>
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
                
                <div class="col-12">
                   
                    <h1>DISCLOSURES UNDER REGULATION 46 OF SEBI (LODR) REGULATIONS</h1>

                    <?php
                                while($data = mysqli_fetch_array($res))
                                {
                                    $description = $data["description"];

                                     // Add class "table table-hover" to the first <table> tag
                                    $modifiedDescription = preg_replace('/<table\b(?![^>]*\bclass=)[^>]*>/i', '<table class="table table-hover"', $description, 1);
                            ?>
                                
                                <?php echo $modifiedDescription?>
                            <?php
                                }
                            ?>
               
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- End Section -->

</main>


<?php

include "footer.php";

?>
