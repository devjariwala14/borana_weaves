<?php
include "header.php";

if (isset($_COOKIE['edit_id'])) {
    $mode = 'edit';
    $editId = $_COOKIE['edit_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `about_us` WHERE id=?");
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (isset($_COOKIE['view_id'])) {
    $mode = 'view';
    $viewId = $_COOKIE['view_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `about_us` WHERE id=?");
    $stmt->bind_param('i', $viewId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (isset($_REQUEST['update'])) {
    $e_id = $_COOKIE['edit_id'];
    $title=$_REQUEST['title'];
    $desc = $_REQUEST['description'];
    $a_image = $_FILES['a_image']['name'];
    $a_image = str_replace(' ', '_', $a_image);
    $a_image_path = $_FILES['a_image']['tmp_name'];
    $old_img = $_REQUEST['old_img'];
    $status = $_REQUEST["radio"];


    if ($a_image != "") {
		if (file_exists("images/about/" . $a_image)) {
			$i = 0;
			$PicFileName = $a_image;
			$Arr1 = explode('.', $PicFileName);

			$PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
			while (file_exists("images/about/" . $PicFileName)) {
				$i++;
				$PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
			}
		} else {
			$PicFileName = $a_image;
		}
        if (file_exists("images/about/" . $old_img)) {
		unlink("images/about/" . $old_img);}
		move_uploaded_file($a_image_path, "images/about/" . $PicFileName);
	} else {
		$PicFileName = $old_img;
	}
    try {
        // echo ("UPDATE `about_us` SET `description`= $desc , `image`= $PicFileName  WHERE `id`= $e_id");
        $stmt = $obj->con1->prepare("UPDATE `about_us` SET `title`=?,`description`=?,`image`=? ,`status`=? WHERE `id`=?");
        $stmt->bind_param("ssssi",$title, $desc, $PicFileName,$status, $e_id);
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
        header("location:about_us.php");
    } else {
        setcookie("msg", "fail", time() + 3600, "/");
        header("location:about_us.php");
    }
}
if (isset($_REQUEST["save"])) {
   // $desc = $_REQUEST['quill_content'];
   $title=$_REQUEST['title'];
    $desc = $_REQUEST['description'];
    $a_image = $_FILES['a_image']['name'];
    $a_image = str_replace(' ', '_', $a_image);
    $a_image_path = $_FILES['a_image']['tmp_name'];
    $status = $_REQUEST["radio"];

    if ($a_image != "") {
        if (file_exists("images/about/" . $a_image)) {
            $i = 0;
            $PicFileName = $a_image;
            $Arr1 = explode('.', $PicFileName);

            $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
            while (file_exists("images/about/" . $PicFileName)) {
                $i++;
                $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
            }
        } else {
            $PicFileName = $a_image;
        }
    }

    try {
        // echo ("INSERT INTO `about_us`(`description`, `image`,`status`) VALUES ( $desc, $PicFileName,$status)");
        $stmt = $obj->con1->prepare("INSERT INTO `about_us`(`title`,`description`, `image`,`status`) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss",$title, $desc, $PicFileName, $status );
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
        move_uploaded_file($a_image_path, "images/about/" . $PicFileName);
        setcookie("msg", "data", time() + 3600, "/");
        header("location:about_us.php");
    } else {
        setcookie("msg", "fail", time() + 3600, "/");
        header("location:about_us.php");
    }
}

?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Add Information -
            <?php echo (isset($mode)) ? (($mode == 'view') ? 'View' : 'Edit') : 'Add' ?></h5>

        <!-- General Form Elements -->
        <form method="post" enctype="multipart/form-data">
            <div class="col-md-12">
                <label for="title" class="col-sm-2 col-form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control"
                    value="<?php echo (isset($mode)) ? $data['title'] : '' ?>"
                    <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
            </div>
            <div class="col-md-12">
                <label for="discription" class="col-sm-2 col-form-label">Description</label>
                <textarea class="tinymce-editor" name="description" id="description"
                    <?php echo isset($mode) && $mode == 'view' ? 'disabled' : '' ?>><?php echo (isset($mode)) ? $data['description'] : '' ?></textarea>
                <!-- <input type="hidden" name="quill_content" id="quill_content"> -->
            </div>
            <div class="col-md-12" <?php echo (isset($mode) && $mode == 'view') ? 'hidden' : '' ?>>
                <label for="inputNumber" class="col-sm-2 col-form-label  mt-4">Image</label>
                <input class="form-control" type="file" id="a_image" name="a_image" type="file" data_btn_text="Browse"
                    onchange="readURL(this,'PreviewImage')" onchange="readURL(this,'PreviewImage')" />
            </div>

            <div>
                <label class="font-bold text-primary mt-2  mb-3"
                    style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>">Preview</label>
                <img src="<?php echo (isset($mode)) ? 'images/about/' . $data["image"] : '' ?>" name="PreviewImage"
                    id="PreviewImage" height="300" style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>"
                    class="object-cover shadow rounded  mt-3  mb-3">
                <div id="imgdiv" style="color:red"></div>
                <input type="hidden" name="old_img" id="old_img"
                    value="<?php echo (isset($mode) && $mode == 'edit') ? $data["image"] : '' ?>" />
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
                <button type="submit" name="<?php echo isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>" id="save"
                    class="btn btn-success  <?php echo isset($mode) && $mode == 'view' ? 'd-none' : '' ?>">
                    <?php echo isset($mode) && $mode == 'edit' ? 'Update' : 'Save' ?>
                </button>
                <!-- onclick="return setQuillInput()" -->
                <button type="button" class="btn btn-danger"
                    onclick="<?php echo (isset($mode)) ? 'javascript:go_back()' : 'window.location.reload()' ?>">
                    Close</button>
            </div>
        </form><!-- End General Form Elements -->
    </div>
</div>
</div>
</div>
</section>
<script>
function go_back() {
    eraseCookie("edit_id");
    eraseCookie("view_id");
    window.location = "about_us.php";
}

function readURL(input, preview) {
    if (input.files && input.files[0]) {
        var filename = input.files.item(0).name;

        var reader = new FileReader();
        var extn = filename.split(".");

        if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1].toLowerCase() == "png" || extn[
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