<?php 
include "header.php" ; 

if (isset($_COOKIE['edit_id'])) { $mode='edit' ; $editId=$_COOKIE['edit_id'];
    $stmt=$obj->con1->prepare("SELECT * FROM `sustainability` WHERE id=?");
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    }

    if (isset($_COOKIE['view_id'])) {
    $mode = 'view';
    $viewId = $_COOKIE['view_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `sustainability` WHERE id=?");
    $stmt->bind_param('i', $viewId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    }

    if (isset($_REQUEST["save"])) {
    $description = $_REQUEST["description"];
    $sus_img = $_FILES['image']['name'];
    $sus_img = str_replace(' ', '_', $sus_img);
    $sus_img_path = $_FILES['image']['tmp_name'];


    if ($sus_img != "") {
    if (file_exists("images/sus_img/" . $sus_img)) {
    $i = 0;
    $PicFileName = $sus_img;
    $Arr1 = explode('.', $PicFileName);
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    while (file_exists("images/sus_img/" . $PicFileName)) {
    $i++;
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    }
    } else {
    $PicFileName = $sus_img;
    }
    }

    try {
    $stmt = $obj->con1->prepare("INSERT INTO `sustainability`(`image`,`description`) VALUES (?,?)");
    $stmt->bind_param("ss", $sus_img, $description);
    $Resp = $stmt->execute();
    if (!$Resp) {
    throw new Exception("Problem in adding! " . strtok($obj->con1->error, "("));
    }
    $stmt->close();
    } catch (Exception $e) {
    setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
    }

    if ($Resp) {
    move_uploaded_file($sus_img_path, "images/sus_img/" . $PicFileName);
    setcookie("msg", "data", time() + 3600, "/");
    header("location:sustainability.php");
    } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:sustainability.php");
    }
    }

    if (isset($_REQUEST["update"])) {
    $e_id = $_COOKIE['edit_id'];

    $description = $_REQUEST["description"];
    $sus_img = $_FILES['image']['name'];
    $sus_img = str_replace(' ', '_', $sus_img);
    $sus_img_path = $_FILES['image']['tmp_name'];
    $old_img = $_REQUEST['old_img'];


    if ($sus_img != "") {
    if (file_exists("images/sus_img/" . $sus_img)) {
    $i = 0;
    $PicFileName = $sus_img;
    $Arr1 = explode('.', $PicFileName);
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    while (file_exists("images/sus_img/" . $PicFileName)) {
    $i++;
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    }
    } else {
    $PicFileName = $sus_img;
    }
    if (file_exists("images/sus_img/" . $old_img)) {
    unlink("images/sus_img/" . $old_img);
    }
    move_uploaded_file($sus_img_path, "images/sus_img/" . $PicFileName);
    } else {
    $PicFileName = $old_img;
    }

    try {
    $stmt = $obj->con1->prepare("UPDATE `sustainability` SET `description`=?,`image`=? WHERE `id`=?");
    $stmt->bind_param("ssi", $description, $PicFileName, $e_id);
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
    header("location:sustainability.php");
    } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:sustainability.php");
    }
    }
    ?>
<div class="pagetitle">
    <h1>Sustainability</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Sustainability</li>
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

                    <div class="col-md-12">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
                        <textarea class="form-control" style="height: 100px" id="description" name="description"
                            required
                            <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>><?php echo (isset($mode)) ? $data['description'] : '' ?></textarea>
                    </div>

                    <div class="col-md-12" <?php echo (isset($mode) && $mode == 'view') ? 'hidden' : '' ?>>
                        <label for="inputNumber" class="col-sm-2 col-form-label">Image</label>
                        <input class="form-control" type="file" id="image" name="image"
                            onchange="readURL(this,'PreviewImage')" />
                    </div>

                    <div>
                        <label class="font-bold text-primary mt-2  mb-3"
                            style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>">Preview</label>
                        <img src="<?php echo (isset($mode)) ? 'images/sus_img/' . $data["image"] : '' ?>"
                            name="PreviewImage" id="PreviewImage" height="300"
                            style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>"
                            class="object-cover shadow rounded  mt-3  mb-3">
                        <div id="imgdiv" style="color:red"></div>
                        <input type="hidden" name="old_img" id="old_img"
                            value="<?php echo (isset($mode) && $mode == 'edit') ? $data["image"] : '' ?>" />
                    </div>

                    <div class="text-left mt-4">
                        <button type="submit" name="<?php echo isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>"
                            id="save"
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
    window.location = "sustainability.php";
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