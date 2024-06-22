<?php
include "header.php";


if (isset($_COOKIE['edit_id'])) {
  $mode = 'edit';
  $editId = $_COOKIE['edit_id'];
  $stmt = $obj->con1->prepare("SELECT * FROM `videos` WHERE id=?");
  $stmt->bind_param('i', $editId);
  $stmt->execute();
  $data = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

if (isset($_COOKIE['view_id'])) {
  $mode = 'view';
  $viewId = $_COOKIE['view_id'];
  $stmt = $obj->con1->prepare("SELECT * FROM `videos` WHERE id=?");
  $stmt->bind_param('i', $viewId);
  $stmt->execute();
  $data = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

if (isset($_REQUEST['update'])) {
  $e_id = $_COOKIE['edit_id'];
  $video = $_FILES['video']['name'];
  $video = str_replace(' ', '_', $video);
  $video_path = $_FILES['video']['tmp_name'];
  $old_vid = $_REQUEST['old_vid'];


  if ($video != "") {
      if (file_exists("images/videos/" . $video)) {
          $i = 0;
          $VidFileName = $video;
          $Arr1 = explode('.', $VidFileName);

          $VidFileName = $Arr1[0] . $i . "." . $Arr1[1];
          while (file_exists("images/videos/" . $VidFileName)) {
              $i++;
              $VidFileName = $Arr1[0] . $i . "." . $Arr1[1];
          }
      } else {
          $VidFileName = $video;
      }
      unlink("images/videos/" . $old_vid);
      move_uploaded_file($video_path, "images/videos/" . $VidFileName);
  } else {
      $VidFileName = $old_vid;
  }
  try {
      // echo ("UPDATE `videos` SET `description`= $desc , `video`= $VidFileName WHERE `id`= $e_id");
      $stmt = $obj->con1->prepare("UPDATE `videos` SET `video`=? WHERE `id`=?");
      $stmt->bind_param("si", $VidFileName, $e_id);
      $Resp = $stmt->execute();
      $stmt->close();
      if (!$Resp) {
          throw new Exception(
              "Problem in updating! " . strtok($obj->con1->error, "(")
          );
      }
  } catch (\Exception $e) {
      setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
  }
  if ($Resp) {
      setcookie("edit_id", "", time() - 3600, "/");
      setcookie("msg", "update", time() + 3600, "/");
      header("location:videos.php");
  } else {
      setcookie("msg", "fail", time() + 3600, "/");
      header("location:videos.php");
  }
}


if(isset($_REQUEST["save"])) {
  $video = $_FILES['video']['name'];
  $video = str_replace(' ', '_', $video);
  $video_path = $_FILES['video']['tmp_name'];

  if ($video != "") {
      if (file_exists("images/videos/" . $video)) {
          $i = 0;
          $VidFileName = $video;
          $Arr1 = explode('.', $VidFileName);

          $VidFileName = $Arr1[0] . $i . "." . $Arr1[1];
          while (file_exists("images/videos/" . $VidFileName)) {
              $i++;
              $VidFileName = $Arr1[0] . $i . "." . $Arr1[1];
          }
      } else {
          $VidFileName = $video;
      }
  } 
  try {
      // echo ("INSERT INTO `videos`(`description`, `video`) VALUES ( $desc, $VidFileName)");
      $stmt = $obj->con1->prepare("INSERT INTO `videos`(`video`) VALUES (?)");
      $stmt->bind_param("s", $VidFileName);
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
    echo "if";
      move_uploaded_file($video_path, "images/videos/" . $VidFileName);
      setcookie("msg", "data", time() + 3600, "/");
      header("location:videos.php");
  } else {
      setcookie("msg", "fail", time() + 3600, "/");
      header("location:videos.php");
  }
}
function is_image($filename)
{
$allowed_extensions = array('jpg', 'jpeg', 'png', 'bmp');
$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
return in_array($extension, $allowed_extensions);
}
?>

<div class="pagetitle">
    <h1>Videos</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Videos</li>
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
                            <label for="inputNumber" class="col-sm-2 col-form-label">Video</label>
                            <input class="form-control" type="file" id="video" name="video"
                                onchange="readURL(this,'PreviewImage')" />
                        </div>


                        <div id="mediaPreviewContainer" style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>">
                            <video
                                src="<?php echo (isset($mode) && !is_image($data["video"])) ? 'images/videos/' . $data["video"] : '' ?>"
                                name="PreviewVideo" id="PreviewVideo" width="300" height="300"
                                style="display:<?php echo (isset($mode) && !is_image($data["video"])) ? 'block' : 'none' ?>"
                                class="object-cover shadow rounded  mt-3  mb-3" controls></video>
                            <div id="imgdiv" style="color:red"></div>
                            <input type="hidden" name="old_vid" id="old_vid"
                                value="<?php echo (isset($mode) && $mode == 'edit') ? $data["video"] : '' ?>" />
                        </div>


                        <div class="text-left mt-4">
                            <button type="submit"
                                name="<?php echo isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>" id="save"
                                class="btn btn-success  <?php echo isset($mode) && $mode == 'view' ? 'd-none' : '' ?>">
                                <?php echo isset($mode) && $mode == 'edit' ? 'Update' : 'Save' ?>
                            </button>
                            <button type="button" class="btn btn-danger"
                                onclick="<?php echo (isset($mode)) ? 'javascript:go_back()' : 'window.location.reload()' ?>">
                                Close</button>
                        </div>
                    </form>
                    <!-- End General Form Elements -->
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function go_back() {
    eraseCookie("view_id");
    eraseCookie("edit_id");
    var loc = "videos.php";
    window.location = loc;
}

function readURL(input, preview) {
    if (input.files && input.files[0]) {
        var filename = input.files.item(0).name;
        var extn = filename.split(".").pop().toLowerCase();

        if (["jpg", "jpeg", "png", "bmp"].includes(extn)) {
            // Handle image preview
            console.log("image");
            displayImagePreview(input, preview);
        } else if (["mp4", "webm", "ogg"].includes(extn)) {
            // Handle video preview
            console.log("video");
            displayVideoPreview(input, preview);
        } else {
            // Display error message for unsupported file types
            $('#imgdiv').html("Unsupported file type. Please select an image or video.");
            document.getElementById('mediaPreviewContainer').style.display = "none";
        }
    }
}

function displayVideoPreview(input, preview) {
    var reader = new FileReader();
    reader.onload = function(e) {
        let file = input.files.item(0);
        let blobURL = URL.createObjectURL(file);
        document.getElementById('mediaPreviewContainer').style.display = "block";
        $('#PreviewVideo').attr('src', blobURL);
        document.getElementById('PreviewVideo').style.display = "block";

        document.getElementById('preview_lable').style.display = "block";
        document.getElementById('PreviewMedia').style.display = "none";
    };
    reader.readAsDataURL(input.files[0]);
    $('#imgdiv').html("");
    document.getElementById('save').disabled = false;
}
</script>

<?php
include "footer.php";
?>