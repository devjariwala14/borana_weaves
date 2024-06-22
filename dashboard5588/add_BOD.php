<?php
include "header.php";

if (isset($_COOKIE['edit_id'])) {
	$mode = 'edit';
	$editId = $_COOKIE['edit_id'];
	$stmt = $obj->con1->prepare("SELECT * FROM `b_directors` where id=?");
	$stmt->bind_param('i', $editId);
	$stmt->execute();
	$data = $stmt->get_result()->fetch_assoc();
	$stmt->close();
}

if (isset($_COOKIE['view_id'])) {
	$mode = 'view';
	$viewId = $_COOKIE['view_id'];
	$stmt = $obj->con1->prepare("SELECT * FROM `b_directors` where id=?");
	$stmt->bind_param('i', $viewId);
	$stmt->execute();
	$data = $stmt->get_result()->fetch_assoc();
	$stmt->close();
}

if (isset($_REQUEST["save"])) {
	$name = $_REQUEST["name"];
	$designation = $_REQUEST["designation"];
	$BOD_img = $_FILES['BOD_img']['name'];
	$BOD_img = str_replace(' ', '_', $BOD_img);
	$BOD_img_path = $_FILES['BOD_img']['tmp_name'];
	$status = $_REQUEST["radio"];
	if ($BOD_img != "") {
		if (file_exists("images/board_of_directors/" . $BOD_img)) {
			$i = 0;
			$PicFileName = $BOD_img;
			$Arr1 = explode('.', $PicFileName);

			$PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
			while (file_exists("images/board_of_directors/" . $PicFileName)) {
				$i++;
				$PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
			}
		} else {
			$PicFileName = $BOD_img;
		}
	}

	try {
		// echo "INSERT INTO `b_directors`(`name`,`designation`, `image`, `status`) VALUES ('".$name."','".$designation."','".$PicFileName."','".$status."')";
		$stmt = $obj->con1->prepare("INSERT INTO `b_directors`(`name`,`designation`, `image`, `status`) VALUES (?,?,?,?)");
		$stmt->bind_param("ssss", $name, $designation, $PicFileName, $status);
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
		move_uploaded_file($BOD_img_path, "images/board_of_directors/" . $PicFileName);
		setcookie("msg", "data", time() + 3600, "/");
		header("location:board_of_director.php");
	} else {
		setcookie("msg", "fail", time() + 3600, "/");
		header("location:board_of_director.php");
	}
}

if (isset($_REQUEST["update"])) {
	$e_id = $_COOKIE['edit_id'];
	$name = $_REQUEST["name"];
	$designation = $_REQUEST["designation"];
	$BOD_img = $_FILES['BOD_img']['name'];
	$BOD_img = str_replace(' ', '_', $BOD_img);
	$BOD_img_path = $_FILES['BOD_img']['tmp_name'];
	$status = $_REQUEST["radio"];
	$old_img = $_REQUEST['old_img'];

	if ($BOD_img != "") {
		if (file_exists("images/board_of_directors/" . $BOD_img)) {
			$i = 0;
			$PicFileName = $BOD_img;
			$Arr1 = explode('.', $PicFileName);

			$PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
			while (file_exists("images/board_of_directors/" . $PicFileName)) {
				$i++;
				$PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
			}
		} else {
			$PicFileName = $BOD_img;
		}
		unlink("images/board_of_directors/" . $old_img);
		move_uploaded_file($BOD_img_path, "images/board_of_directors/" . $PicFileName);
	} else {
		$PicFileName = $old_img;
	}

	try {
		$stmt = $obj->con1->prepare("UPDATE `b_directors` SET `name`=?,`designation`=?,`image`=?,`status`=? WHERE `id`=?");
		$stmt->bind_param("ssssi", $name, $designation, $PicFileName, $status, $e_id);
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
		header("location:board_of_director.php");
	} else {
		setcookie("msg", "fail", time() + 3600, "/");
		header("location:board_of_director.php");
	}
}
?>

<div class="pagetitle">
    <h1>Board Of Directors</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Board Of Directors</li>
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
                            <label for="inputText" class="col-sm-2 col-form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['name'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
                        </div>
                        <div class="col-md-12">
                            <label for="inputText" class="col-sm-2 col-form-label">Designation</label>
                            <input type="text" id="designation" name="designation" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['designation'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
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
                        <div class="col-md-12" <?php echo (isset($mode) && $mode == 'view') ? 'hidden' : '' ?>>
                            <label for="inputNumber" class="col-sm-2 col-form-label">Image</label>
                            <input class="form-control" type="file" id="BOD_img" name="BOD_img" type="file"
                                data_btn_text="Browse" onchange="readURL(this,'PreviewImage')"
                                onchange="readURL(this,'PreviewImage')" />
                        </div>
                        <div class="col-md-12 mt-2 mb-3">
                            <label class="font-bold text-primary "
                                style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>">Preview</label>
                            <img src="<?php echo (isset($mode)) ? 'images/board_of_directors/' . $data["image"] : '' ?>"
                                name="PreviewImage" id="PreviewImage" height="300"
                                style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>"
                                class="object-cover shadow rounded mt-3 mb-3">
                            <div id="imgdiv" style="color:red"></div>
                            <input type="hidden" name="old_img" id="old_img"
                                value="<?php echo (isset($mode) && $mode == 'edit') ? $data["image"] : '' ?>" />
                        </div>

                        <div class="text-left mt-4">
                            <button type="submit"
                                name="<?php echo isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>" id="save"
                                class="btn btn-success <?php echo isset($mode) && $mode == 'view' ? 'd-none' : '' ?>">
                                <?php echo isset($mode) && $mode == 'edit' ? 'Update' : 'Save' ?>
                            </button>
                            <button type="button" class="btn btn-danger"
                                onclick="<?php echo (isset($mode)) ? 'javascript:go_back()' : 'window.location.reload()' ?>">
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
    window.location = "board_of_director.php";
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