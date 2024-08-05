<?php
include "header.php";

if (isset($_COOKIE['view_id'])) {
    $mode = 'view';
    $viewId = (int) $_COOKIE['view_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `career` WHERE id = ?");
    $stmt->bind_param('i', $viewId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<div class="pagetitle">
    <h1>Career</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Career</li>
            <li class="breadcrumb-item active"><?php echo (isset($mode)) ? 'View' : 'Add'; ?> Info</li>
        </ol>
    </nav>
</div>
<!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body mt-3">
                    <form method="post" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <input type="text" name="name" class="form-control"
                                value="<?php echo (isset($data['name'])) ? htmlspecialchars($data['name']) : ''; ?>"
                                readonly />
                        </div>
                        <div class="col-md-12">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?php echo (isset($data['email'])) ? htmlspecialchars($data['email']) : ''; ?>"
                                readonly />
                        </div>
                        <div class="col-md-12">
                            <label class="col-sm-2 col-form-label">Number</label>
                            <input type="text" name="number" class="form-control"
                                value="<?php echo (isset($data['number'])) ? htmlspecialchars($data['number']) : ''; ?>"
                                readonly />
                        </div>
                        <div class="col-md-12">
                            <label class="col-sm-2 col-form-label">Message</label>
                            <textarea name="msg" class="form-control"
                                readonly><?php echo (isset($data['msg'])) ? htmlspecialchars($data['msg']) : ''; ?></textarea>
                        </div>
                        

                        <div class="col-md-12" <?php echo (isset($mode) && $mode == 'view') ? 'hidden' : '' ?>>
                            <label for="doc" class="col-sm-2 col-form-label">Resume</label>
                            <input class="form-control" type="file" id="doc" name="doc" data_btn_text="Browse"
                                onchange="readURL(this,'PreviewFile')" />
                        </div>

                        <div>
                            <h4 class="font-bold text-primary mt-2 mb-3"
                                style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>">
                                Preview
                            </h4>
                            <?php if(isset($mode) && !empty($data["download"])): ?>
                            <?php $file_ext = pathinfo($data["download"], PATHINFO_EXTENSION); ?>
                            <?php if(in_array($file_ext, ['jpg', 'jpeg', 'png', 'bmp'])): ?>
                            <img src="documents/career/<?php echo $data["download"]; ?>" name="PreviewFile"
                                id="PreviewFile" height="300" class="object-cover shadow rounded">
                            <?php else: ?>
                            <div style="display: flex; align-items: center;">
                                <p id="PreviewFile" style="margin: 0; margin-right: 10px;">
                                    <?php echo $data["download"]; ?></p>
                                <a href="documents/career/<?php echo $data["download"]; ?>" class="btn btn-success"
                                    download>
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                            <div id="filediv" style="color:red"></div>
                            <input type="hidden" name="old_file" id="old_file"
                                value="<?php echo (isset($mode) && $mode == 'edit') ? $data["download"] : '' ?>" />
                        </div>




                        <div class="text-left mt-4">
                            <button type="button" class="btn btn-danger"
                                onclick="<?php echo (isset($mode)) ? 'go_back()' : 'window.location.reload()'; ?>">Close</button>
                        </div>

                        <script>
                        function go_back() {
                            eraseCookie('view_id');
                            window.location = 'career.php';
                        }

                        function readURL(input, preview) {
                            if (input.files && input.files[0]) {
                                var filename = input.files.item(0).name;

                                var reader = new FileReader();
                                var extn = filename.split(".");

                                if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1]
                                    .toLowerCase() == "png" || extn[
                                        1].toLowerCase() == "bmp") {
                                    reader.onload = function(e) {
                                        $('#' + preview).attr('src', e.target.result);
                                        document.getElementById(preview).style.display = "block";
                                    };

                                    reader.readAsDataURL(input.files[0]);
                                    $('#imgdiv').html("");
                                    document.getElementById('save').disabled = false;
                                } else {
                                    $('#imgdiv').html("Please Select Image Only");
                                    document.getElementById('save').disabled = true;
                                }
                            }
                        }

                        function readURL(input, PreviewFile) {
                            if (input.files && input.files[0]) {
                                var filename = input.files.item(0).name;

                                var reader = new FileReader();
                                var extn = filename.split(".");

                                if (["jpg", "jpeg", "png", "bmp", "pdf", "doc", "docx"].includes(extn[1]
                                        .toLowerCase())) {
                                    reader.onload = function(e) {
                                        if (["jpg", "jpeg", "png", "bmp"].includes(extn[1].toLowerCase())) {
                                            $('#' + preview).attr('src', e.target.result).show();
                                        } else {
                                            $('#' + preview).html(filename).show();
                                        }
                                        $('#filediv').html("");
                                        document.getElementById('save').disabled = false;
                                    };

                                    reader.readAsDataURL(input.files[0]);
                                } else {
                                    $('#filediv').html("Please select a valid file (image, PDF, or DOC)");
                                    document.getElementById('save').disabled = true;
                                }
                            }
                        }
                        </script>


                        <?php
include "footer.php";
?>