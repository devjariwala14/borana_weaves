<?php include "header.php" ; 

if (isset($_COOKIE['edit_id'])) { $mode='edit' ; $editId=$_COOKIE['edit_id'];
    $stmt=$obj->con1->prepare("SELECT * FROM `primary_benifits` WHERE id=?");
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    }

    if (isset($_COOKIE['view_id'])) {
    $mode = 'view';
    $viewId = $_COOKIE['view_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `primary_benifits` WHERE id=?");
    $stmt->bind_param('i', $viewId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    }

    if (isset($_REQUEST["save"])) {
    $title = $_REQUEST["title"];
    $content = $_REQUEST["content"];
    $icon = $_FILES['icon']['name'];
    $icon = str_replace(' ', '_', $icon);
    $primary_benifits_img_path = $_FILES['icon']['tmp_name'];


    if ($icon != "") {
    if (file_exists("images/primary_benifits/" . $icon)) {
    $i = 0;
    $PicFileName = $icon;
    $Arr1 = explode('.', $PicFileName);
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    while (file_exists("images/primary_benifits/" . $PicFileName)) {
    $i++;
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    }
    } else {
    $PicFileName = $icon;
    }
    }

    try {
        
    $stmt = $obj->con1->prepare("INSERT INTO `primary_benifits`(`icon`,`title`,`content`) VALUES (?,?,?)");
    $stmt->bind_param("sss", $icon, $title ,$content);
    $Resp = $stmt->execute();
    if (!$Resp) {
    throw new Exception("Problem in adding! " . strtok($obj->con1->error, "("));
    }
    $stmt->close();
    } catch (Exception $e) {
    setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
    }

    if ($Resp) {
    move_uploaded_file($primary_benifits_img_path, "images/primary_benifits/" . $PicFileName);
    setcookie("msg", "data", time() + 3600, "/");
    header("location:primary_benifits.php");
    } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:primary_benifits.php");
    }
    }

    if (isset($_REQUEST["update"])) {
    $id = $_COOKIE['edit_id'];

    $title = $_REQUEST["title"];
    $content = $_REQUEST["content"];
    $icon = $_FILES['icon']['name'];
    $icon = str_replace(' ', '_', $icon);
    $primary_benifits_img_path = $_FILES['icon']['tmp_name'];
    $old_img = $_REQUEST['old_img'];


    if ($icon != "") {
    if (file_exists("images/primary_benifits/" . $icon)) {
    $i = 0;
    $PicFileName = $icon;
    $Arr1 = explode('.', $PicFileName);
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    while (file_exists("images/primary_benifits/" . $PicFileName)) {
    $i++;
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    }
    } else {
    $PicFileName = $icon;
    }
    if (file_exists("images/primary_benifits/" . $old_img)) {
    unlink("images/primary_benifits/" . $old_img);
    }
    move_uploaded_file($primary_benifits_img_path, "images/primary_benifits/" . $PicFileName);
    } else {
    $PicFileName = $old_img;
    }

    try {
        // echo"UPDATE `primary_benifits` SET `icon`=".$icon.",`title`=".$title.",`content`=".$content." WHERE `id`=".$id."";
    $stmt = $obj->con1->prepare("UPDATE `primary_benifits` SET `icon`=?,`title`=?,`content`=? WHERE `id`=?");
    $stmt->bind_param("sssi", $icon, $title , $content, $id);
    $Resp = $stmt->execute();
    if (!$Resp) {
    throw new Exception("Problem in updating! " . strtok($obj->con1->error, "("));
    }
    $stmt->close();
    } catch (Exception $e) {
    setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
    }

    if ($Resp) {
    setcookie("edit_id", "", time() - 3600, "/");
    setcookie("msg", "update", time() + 3600, "/");
    header("location:primary_benifits.php");
    } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:primary_benifits.php");
    }
    }
    ?>
    <div class="pagetitle">
        <h1>Primary Benifits</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item">Primary Benifits</li>
                <li class="breadcrumb-item active">
                    <?php echo (isset($mode)) ? (($mode == 'view') ? 'View' : 'Edit') : 'Add' ?> Info</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body mt-3">
                        <!-- General Form Elements -->
                        <form method="post" enctype="multipart/form-data">

                            
                            <div class="col-md-12" <?php echo (isset($mode) && $mode == 'view') ? 'hidden' : '' ?>>
                                <label for="inputNumber" class="col-sm-2 col-form-label">Icon</label>
                                <input class="form-control" type="file" id="icon" name="icon"
                                    onchange="readURL(this,'PreviewImage')" />
                            </div>
                            <div>
                                <label class="font-bold text-primary mt-2  mb-3"
                                    style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>">Preview</label>
                                <img src="<?php echo (isset($mode)) ? 'images/primary_benifits/' . $data["icon"] : '' ?>"
                                    name="PreviewImage" id="PreviewImage" height="300"
                                    style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>"
                                    class="object-cover shadow rounded  mt-3  mb-3">
                                <div id="imgdiv" style="color:red"></div>
                                <input type="hidden" name="old_img" id="old_img"
                                    value="<?php echo (isset($mode) && $mode == 'edit') ? $data["icon"] : '' ?>" />
                            </div>

                            <div class="row pt-3">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?php echo (isset($mode)) ? $data['title'] : '' ?>"
                                    <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>>
                            </div>
                        </div>

                        <div class="col-md-12">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Content</label>
                                <textarea class="form-control" style="height: 100px" id="content" name="content"
                                    required
                                    <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>><?php echo (isset($mode)) ? $data['content'] : '' ?></textarea>
                            </div>

                            <div class="text-left mt-4">
                                <button type="submit"
                                    name="<?php echo isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>" id="save"
                                    class="btn btn-success <?php echo isset($mode) && $mode == 'view' ? 'd-none' : '' ?>">
                                    <?php echo isset($mode) && $mode == 'edit' ? 'Update' : 'Save' ?>
                                </button>
                                <button type="button" class="btn btn-danger"
                                    onclick="<?php echo (isset($mode)) ? 'javascript:go_back()' : 'window.location.reload()' ?>">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        
    function go_back() {
        eraseCookie("edit_id");
        eraseCookie("view_id");
        window.location = "primary_benifits.php";
    }

    function readURL(input, preview) {
        if (input.files && input.files[0]) {
            var filename = input.files.item(0).name;
            var reader = new FileReader();
            var extn = filename.split(".");

            if (["jpg", "jpeg", "png", "bmp"].includes(extn[1].toLowerCase())) {
                reader.onload = function(e) {
                    document.getElementById(preview).src = e.target.result;
                    document.getElementById(preview).style.display = "block";
                };
                reader.readAsDataURL(input.files[0]);
                document.getElementById('imgdiv').innerHTML = "";
                document.getElementById('save').disabled = false;
            } else {
                document.getElementById('imgdiv').innerHTML = "Please Select Image Only";
                document.getElementById('save').disabled = true;
            }
        }
    }
    </script>

    <?php
include "footer.php";
?>