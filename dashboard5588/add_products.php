<?php
include "header.php";

if (isset($_COOKIE['edit_id'])) {
  $mode = 'edit';
  $editId = $_COOKIE['edit_id'];
  $stmt = $obj->con1->prepare("SELECT * FROM `products` where id=?");
  $stmt->bind_param('i', $editId);
  $stmt->execute();
  $data = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

if (isset($_COOKIE['view_id'])) {
  $mode = 'view';
  $viewId = $_COOKIE['view_id'];
  $stmt = $obj->con1->prepare("SELECT * FROM `products` where id=?");
  $stmt->bind_param('i', $viewId);
  $stmt->execute();
  $data = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

if (isset($_REQUEST["save"])) {
  $name = $_REQUEST["name"];
  $short_desc = $_REQUEST["short_desc"];
  $long_desc = $_REQUEST["long_desc"];
  $img_path = $_FILES['img_path']['name'];
  $img_path = str_replace(' ', '_', $img_path);
  $temp_img_path = $_FILES['img_path']['tmp_name'];

  if ($img_path != "") {
    if (file_exists("images/products/" . $img_path)) {
      $i = 0;
      $PicFileName = $img_path;
      $Arr1 = explode('.', $PicFileName);

      $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
      while (file_exists("images/products/" . $PicFileName)) {
        $i++;
        $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
      }
    } else {
      $PicFileName = $img_path;
    }
  }

  try {
    $stmt = $obj->con1->prepare("INSERT INTO `products`(`name`,`short_desc`, `long_desc`, `img_path`) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $short_desc, $long_desc, $img_path);
    $Resp = $stmt->execute();
    if (!$Resp) {
      throw new Exception(
        "Problem in adding! " . strtok($obj->con1->error, "(")
      );
    }
    $stmt->close();
  } catch (\Exception $e) {
    setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
  }

  if ($Resp) {
    move_uploaded_file($temp_img_path, "images/products/" . $PicFileName);
    setcookie("msg", "data", time() + 3600, "/");
    header("location:products.php");
  } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:products.php");
  }
}

if (isset($_REQUEST["update"])) {
  $e_id = $_COOKIE['edit_id'];
  $name = $_REQUEST["name"];
  $short_desc = $_REQUEST["short_desc"];
  $long_desc = $_REQUEST["long_desc"];
  $img_path = $_FILES['img_path']['name'];
  $img_path = str_replace(' ', '_', $img_path);
  $temp_img_path = $_FILES['img_path']['tmp_name'];
  $old_img = $_REQUEST['old_img'];

  if ($img_path != "") {
    if (file_exists("images/products/" . $img_path)) {
      $i = 0;
      $PicFileName = $img_path;
      $Arr1 = explode('.', $PicFileName);

      $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
      while (file_exists("images/products/" . $PicFileName)) {
        $i++;
        $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
      }
    } else {
      $PicFileName = $img_path;
    }
    unlink("images/products/" . $old_img);
    move_uploaded_file($temp_img_path, "images/products/" . $PicFileName);
  } else {
    $PicFileName = $old_img;
    $img_path = $old_img;
  }

  try {
    $stmt = $obj->con1->prepare("UPDATE `products` SET `name`=?,`short_desc`=?,`long_desc`=?,`img_path`=? WHERE `id`=?");
    $stmt->bind_param("ssssi", $name, $short_desc, $long_desc, $img_path, $e_id);
    $Resp = $stmt->execute();
    if (!$Resp) {
      throw new Exception(
        "Problem in updating! " . strtok($obj->con1->error, "(")
      );
    }
    $stmt->close();
  } catch (\Exception $e) {
    setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
  }

  if ($Resp) {
    setcookie("edit_id", "", time() - 3600, "/");
    setcookie("msg", "update", time() + 3600, "/");
    header("location:products.php");
  } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:products.php");
  }
}
?>


<div class="pagetitle">
    <h1>Products</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Products</li>
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
                        <!-- Name -->
                        <div class="col-md-12">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" required
                                value="<?= (isset($mode)) ? $data['name'] : '' ?>"
                                <?= isset($mode) && $mode == 'view' ? 'readonly' : '' ?> />
                        </div>

                        <!-- Short Description -->
                        <div class="col-md-12">
                            <label class="col-sm-2 col-form-label">Short Description</label>
                            <textarea name="short_desc" class="form-control"
                                <?= isset($mode) && $mode == 'view' ? 'readonly' : '' ?>><?= (isset($mode)) ? $data['short_desc'] : '' ?></textarea>
                        </div>

                        <!-- Long Description -->
                        <div class="col-md-12">
                            <label class="col-sm-2 col-form-label">Long Description</label>
                            <textarea name="long_desc" class="form-control"
                                <?= isset($mode) && $mode == 'view' ? 'readonly' : '' ?>><?= (isset($mode)) ? $data['long_desc'] : '' ?></textarea>
                        </div>

                        <!-- Image -->
                        <div class="col-md-12" <?= (isset($mode) && $mode == 'view') ? 'hidden' : '' ?>>
                            <label for="inputNumber" class="col-sm-2 col-form-label">Image</label>
                            <input class="form-control" id="img_path" name="img_path" type="file" data_btn_text="Browse"
                                onchange="readURL(this,'PreviewImage')" onchange="readURL(this,'PreviewImage')"
                                <?= !isset($mode) ? 'required' : '' ?> />
                        </div>
                        <div>
                            <label class="font-bold text-primary mt-2  mb-3"
                                style="display:<?= (isset($mode)) ? 'block' : 'none' ?>">
                                Preview</label>
                            <img src="<?= (isset($mode)) ? 'images/products/' . $data["img_path"] : '' ?>"
                                name="PreviewImage" id="PreviewImage" height="300"
                                style="display:<?= (isset($mode)) ? 'block' : 'none' ?>"
                                class="object-cover shadow rounded">
                            <div id="imgdiv" style="color:red"></div>
                            <input type="hidden" name="old_img" id="old_img"
                                value="<?= (isset($mode) && $mode == 'edit') ? $data["img_path"] : '' ?>" />
                        </div>

                        <div class="text-left mt-4">
                            <button type="submit" name="<?= isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>"
                                id="save"
                                class="btn btn-success <?= isset($mode) && $mode == 'view' ? 'd-none' : '' ?>">
                                <?= isset($mode) && $mode == 'edit' ? 'Update' : 'Save' ?>
                            </button>
                            <button type="button" class="btn btn-danger"
                                onclick="<?= (isset($mode)) ? 'javascript:go_back()' : 'window.location.reload()' ?>">
                                Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    function go_back() {
        eraseCookie("edit_id");
        eraseCookie("view_id");
        window.location = "products.php";
    }


    function readURL(input, preview) {
        if (input.files && input.files[0]) {
            var filename = input.files.item(0).name;

            var reader = new FileReader();
            var extn = filename.split(".");

            if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1].toLowerCase() ==
                "png" || extn[
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
    </script>
    <?php
include "footer.php";
?>