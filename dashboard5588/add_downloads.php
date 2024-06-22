<?php
include "header.php";

if (isset($_COOKIE['edit_id'])) {
  $mode = 'edit';
  $editId = $_COOKIE['edit_id'];
  $stmt = $obj->con1->prepare("SELECT * FROM `downloads` where id=?");
  $stmt->bind_param('i', $editId);
  $stmt->execute();
  $data = $stmt->get_result()->fetch_assoc();
  // var_dump($data);
  $old_doc = $data["doc_name"];
  $stmt->close();
}

if (isset($_COOKIE['view_id'])) {
  $mode = 'view';
  $viewId = $_COOKIE['view_id'];
  $stmt = $obj->con1->prepare("SELECT * FROM `downloads` where id=?");
  $stmt->bind_param('i', $viewId);
  $stmt->execute();
  $data = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

if (isset($_REQUEST["save"])) {

  $title = $_REQUEST["title"];
  $doc_name = $_FILES['doc']['name'];
  $doc_name = str_replace(' ', '_', $doc_name);
  $doc_path = $_FILES['doc']['tmp_name'];
  $status = $_REQUEST["radio"];
  $file_type_id = $_REQUEST["file_type_id"];

  if ($doc_name != "") {
    if (file_exists("images/downloads/" . $doc_name)) {
      $i = 0;
      $PicFileName = $doc_name;
      $Arr1 = explode('.', $PicFileName);

      $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
      while (file_exists("images/downloads/" . $PicFileName)) {
        $i++;
        $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
      }
    } else {
      $PicFileName = $doc_name;
    }
  }

  try {
    // echo "INSERT INTO `downloads`(`title`,`doc_name`,`file_type_id`, `status`) VALUES ('".$title."','".$PicFileName."',,'".$file_type_id."','".$status."')";
    $stmt = $obj->con1->prepare("INSERT INTO `downloads`(`title`,`doc_name`,`file_type_id`,`status`) VALUES (?,?,?,?)");
    $stmt->bind_param("ssis", $title, $PicFileName,$file_type_id ,$status );
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
    move_uploaded_file($doc_path, "images/downloads/" . $PicFileName);
    setcookie("msg", "data", time() + 3600, "/");
    header("location:downloads.php");
  } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:downloads.php");
  }
}

if (isset($_REQUEST["update"])) {
  $e_id = $_COOKIE['edit_id'];
  $title = $_REQUEST["title"];
  $doc_name = $_FILES['doc']['name'];
  $doc_name = str_replace(' ', '_', $doc_name);
  $doc_path = $_FILES['doc']['tmp_name'];
  $status = $_REQUEST["radio"];
  $file_type_id = $_REQUEST["file_type_id"];

  if ($doc_name != "") {
    if (file_exists("images/downloads/" . $doc_name)) {
      $i = 0;
      $PicFileName = $doc_name;
      $Arr1 = explode('.', $PicFileName);

      $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
      while (file_exists("images/downloads/" . $PicFileName)) {
        $i++;
        $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
      }
    } else {
      $PicFileName = $doc_name;
    }
    unlink("images/downloads/" . $old_doc);
    move_uploaded_file($doc_path, "images/downloads/" . $PicFileName);
  } else {
    $PicFileName = $old_doc;
    $doc_name = $old_doc;
  }

  try {
    $stmt = $obj->con1->prepare("UPDATE `downloads` SET `doc_name`=?,`title`=?,`status`=?,`file_type_id`=? WHERE `id`=?");
    $stmt->bind_param("ssi", $doc_name, $title, $status, $file_type_id, $e_id);
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
    header("location:downloads.php");
  } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:downloads.php");
  }
}

?>
<div class="pagetitle">
    <h1>Downloads</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Downloads</li>
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
                            <label for="inputText" class="col-sm-2 col-form-label">Title</label>
                            <input type="text" id="name" name="title" class="form-control"
                                value="<?= (isset($mode)) ? $data['title'] : '' ?>"
                                <?= isset($mode) && $mode == 'view' ? 'readonly' : '' ?> />
                        </div>

                        <div class="col-md-12" <?php echo (isset($mode) && $mode == 'view') ? 'hidden' : '' ?>>
                            <label for="inputNumber" class="col-sm-2 col-form-label">Image</label>
                            <input class="form-control" type="file" id="doc" name="doc" type="file"
                                data_btn_text="Browse" onchange="readURL(this,'PreviewImage')"
                                onchange="readURL(this,'PreviewImage')" />
                        </div>
                        <div class="col-md-12">
                            <label class="font-bold text-primary mt-2 mb-3"
                                style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>">Preview</label>
                            <img src="<?php echo (isset($mode)) ? 'images/downloads/' . $data["doc_name"] : '' ?>"
                                name="PreviewImage" id="PreviewImage" height="300"
                                style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>"
                                class="object-cover shadow rounded mt-3 mb-3">
                            <div id="imgdiv" style="color:red "></div>
                            <input type="hidden" name="old_img" id="old_img"
                                value="<?php echo (isset($mode) && $mode == 'edit') ? $data["doc_name"] : '' ?>" />
                        </div>
                        <div class="col-md-12">
                            <label class="col-sm-2 col-form-label">Type</label>
                            <select class="form-select" name="file_type_id" aria-label="Default select example"
                                <?= isset($mode) && $mode == 'view' ? 'disabled' : '' ?>>
                                <option>Select Type</option>
                                <?php
                                      $file_type_stmt = $obj->con1->prepare("SELECT * FROM `file_type`");
                                      $file_type_stmt->execute();
                                      $result = $file_type_stmt->get_result();
                                      $file_type_stmt->close();

                                         while ($row = mysqli_fetch_array($result)) {
                                 ?>
                                <option value="<?= $row['id'] ?>"
                                    <?= (isset($data) && $data["file_type_id"] == $row["id"]) ? "selected" : "" ?>>
                                    <?= $row["type"] ?>
                                </option>
                                <?php
                                  }
                                 ?>
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="inputEmail5" class="form-label">Status</label> <br />
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" name="radio" id="radio"
                                    <?php echo isset($mode) && $data['status'] == 'Enable' ? 'checked' : '' ?>
                                    class="form-radio text-primary" value="Enable" checked required
                                    <?php echo isset($mode) && $mode == 'view' ? 'disabled' : '' ?> />
                                <label class="form-check-label" for="gridRadios1">
                                    Enable
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" name="radio" id="radio"
                                    <?php echo isset($mode) && $data['status'] == 'Disable' ? 'checked' : '' ?>
                                    class="form-radio text-danger" value="Disable" required
                                    <?php echo isset($mode) && $mode == 'view' ? 'disabled' : '' ?> />
                                <label class="form-check-label" for="gridRadios2">
                                    Disable
                                </label>
                            </div>
                        </div>
                        <div class="text-left mt-4">
                            <button type="submit" name="<?= isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>"
                                id="save"
                                class="btn btn-success  <?= isset($mode) && $mode == 'view' ? 'd-none' : '' ?>">
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
</section>
<script>
function go_back() {
    eraseCookie("edit_id");
    eraseCookie("view_id");
    window.location = "downloads.php";
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