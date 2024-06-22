<?php
include "header.php";

$product_id = isset($_COOKIE['edit_id']) ? $_COOKIE['edit_id'] : $_COOKIE['view_id'];

if (isset($_COOKIE['edit_subimg_id']) || isset($_COOKIE['view_subimg_id'])) {
	$mode = (isset($_COOKIE['edit_subimg_id']))?'edit':'view';
	$id = (isset($_COOKIE['edit_subimg_id']))?$_COOKIE['edit_subimg_id']:$_COOKIE['view_subimg_id'];
	$stmt = $obj->con1->prepare("SELECT * FROM `manu_images` where id=?");
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$data = $stmt->get_result()->fetch_assoc();
	$stmt->close();
}

if (isset($_REQUEST["save"])) {
	$file_type = $_REQUEST["file_type"];

	try {
        // multiple product images 
        foreach ($_FILES["file_name"]['name'] as $key => $value)
        { 
            // rename for product images       
            if($_FILES["file_name"]['name'][$key]!=""){
                $PicSubImage = $_FILES["file_name"]["name"][$key];
                if (file_exists("images/manu_img/" . $PicSubImage )) {
                    $i = 0;
                    $SubImageName = $PicSubImage;
                    $Arr = explode('.', $SubImageName);
                    $SubImageName = $Arr[0] . $i . "." . $Arr[1];
                    while (file_exists("images/manu_img/" . $SubImageName)) {
                        $i++;
                        $SubImageName = $Arr[0] . $i . "." . $Arr[1];
                    }
                } else {
                    $SubImageName = $PicSubImage;
                }
                $SubImageTemp = $_FILES["file_name"]["tmp_name"][$key];
                $SubImageName = str_replace(' ', '_', $SubImageName);
            
                // sub images qry
                move_uploaded_file($SubImageTemp, "images/manu_img/".$SubImageName);
            }
            
            $stmt_image = $obj->con1->prepare("INSERT INTO `manu_images`(`m_id`, `file_name`, `file_type`) VALUES (?,?,?)");
            $stmt_image->bind_param("iss", $product_id, $SubImageName, $file_type);
            $Resp = $stmt_image->execute();
            $stmt_image->close();
        }

		if (!$Resp) {
			throw new Exception(
				"Problem in adding! " . strtok($obj->con1->error, "(")
			);
		}
	} catch (\Exception $e) {
		setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
	}

	if ($Resp) {
		
		setcookie("msg", "data", time() + 3600, "/");
		header("location:add_manufac.php");
	} else {
		setcookie("msg", "fail", time() + 3600, "/");
		header("location:add_manufac.php");
	}
}

if (isset($_REQUEST["update"])) {
    $e_id = $_COOKIE['edit_subimg_id'];
	$file_type = $_REQUEST["file_type"];
    $file_name_one = $_FILES['file_name_one']['name'];
    $file_name_one = str_replace(' ', '_', $file_name_one);
    $file_path_one = $_FILES['file_name_one']['tmp_name'];
    $old_img = $_REQUEST['old_img'];
    
    //rename file for product image
    if ($file_name_one != "") {
        if (file_exists("images/manu_img/" . $file_name_one)) {
            $i = 0;
            $PicFileName = $file_name_one;
            $Arr1 = explode('.', $PicFileName);

            $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
            while (file_exists("images/manu_img/" . $PicFileName)) {
                $i++;
                $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
            }
        } else {
            $PicFileName = $file_name_one;
        }
        unlink("images/manu_img/" . $old_img);
        move_uploaded_file($file_path_one, "images/manu_img/" . $PicFileName);
    } else {
        $PicFileName = $old_img;
    }

	try {
		$stmt = $obj->con1->prepare("UPDATE `manu_images` SET `file_name`=?, `file_type`=? WHERE `id`=?");
		$stmt->bind_param("ssi", $PicFileName, $file_type, $e_id);
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
		setcookie("edit_subimg_id", "", time() - 3600, "/");
		setcookie("msg", "update", time() + 3600, "/");
		header("location:add_manufac.php");
	} else {
		setcookie("msg", "fail", time() + 3600, "/");
		header("location:add_manufac.php");
	}
}
?>

	<div class="pagetitle">
		<h1>Manufacture Images</h1>
		<nav>
			<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="index.php">Home</a></li>
			<li class="breadcrumb-item">Manufacture Images</li>
			<li class="breadcrumb-item active"><?php echo (isset($mode)) ? (($mode == 'view') ? 'View' : 'Edit') : 'Add' ?>Manufacture Images</li>
			</ol>
		</nav>
	</div>

<!-- End Page Title -->
<section class="section">
  <div class="row">
	<div class="col-lg-12">

		<div class="card">
			<div class="card-body mt-3">
			<!-- <button type="button" class="btn btn-primary"><i class="bi bi-arrow-left me-1"></i> Back</button>  -->
				
				<!-- Multi Columns Form -->
				<form class="row g-3 pt-3" method="post" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <label for="file_type" class="form-label">File Type</label> <br/>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="radio" name="file_type" id="file_type"
                                <?php echo isset($mode) && $data['file_type'] == 'image' ? 'checked' : '' ?>
                                class="form-radio text-primary" value="image" checked required
                                <?php echo isset($mode) && $mode == 'view' ? 'disabled' : '' ?> />
                            <label class="form-check-label" for="gridRadios1">Image</label>
                        </div>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="radio" name="file_type" id="file_type"
                                <?php echo isset($mode) && $data['file_type'] == 'video' ? 'checked' : '' ?>
                                class="form-radio text-danger" value="video" required
                                <?php echo isset($mode) && $mode == 'view' ? 'video' : '' ?> />
                            <label class="form-check-label" for="gridRadios2">Video</label>
                        </div>
                    </div> 
                    <div class="col-md-12" <?php echo (isset($mode)) ? 'hidden' : '' ?>>
                        <label for="file_name" class="form-label">Choose Files</label>
                        <input type="file" class="form-control" id="file_name" name="file_name[]" onchange="readURL_multiple(this)" multiple <?php echo (isset($mode)) ? '':'required' ?>>
                        <div id="preview_image_div"></div>
                        <div id="imgdiv_multiple" style="color:red"></div>
                    </div>
                    <div <?php echo (isset($mode) && $mode == 'edit') ? '' : 'hidden' ?>>
                        <label for="file_name_one">Choose File</label>
                        <input type="file" class="form-control" id="file_name_one" name="file_name_one" onchange="readURL(this,'PreviewImage')"/>
                    </div>
                    <div class="col-md-12">
                        <img src="<?php echo (isset($mode)) ? 'images/manu_img/' . $data["file_name"] : '' ?>"
                            name="PreviewImage" id="PreviewImage" height="300"
                            style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>"
                            class="object-cover shadow rounded">
                        <div id="imgdiv" style="color:red"></div>
                        <input type="hidden" name="old_img" id="old_img"
                            value="<?php echo (isset($mode) && $mode == 'edit') ? $data["file_name"] : '' ?>" />
                    </div>              
					<div class="text-left mt-4">
						<button type="submit" name="<?php echo isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>" id="save"
							class="btn btn-success <?php echo isset($mode) && $mode == 'view' ? 'd-none' : '' ?>">
							<?php echo isset($mode) && $mode == 'edit' ? 'Update' : 'Save' ?>
						</button>
						<button type="button" class="btn btn-danger" onclick="window.location='add_manufac.php'"> Close</button>
					</div>
              </form><!-- End Multi Columns Form -->
				
			</div>
		</div>
	</div>
</div>
</section>

<script type="text/javascript">
    function readURL(input, preview) {
        if (input.files && input.files[0]) {
            var filename = input.files.item(0).name;

            var reader = new FileReader();
            var extn = filename.split(".");

            if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1].toLowerCase() == "png" || extn[1].toLowerCase() == "bmp") {
                reader.onload = function (e) {
                    $('#' + preview).attr('src', e.target.result);
                    document.getElementById(preview).style.display = "block";
                };

                reader.readAsDataURL(input.files[0]);
                $('#imgdiv').html("");
                document.getElementById('save').disabled = false;
            }
            else {
                $('#imgdiv').html("Please Select Image Only");
                document.getElementById('save').disabled = true;
            }
        }
    }

    function readURL_multiple(input) {
        $('#preview_image_div').html("");
        var filesAmount = input.files.length;
        for (i = 0; i <= filesAmount; i++) {
            if (input.files && input.files[i]) {

                var filename = input.files.item(i).name;
                var reader = new FileReader();
                var extn = filename.split(".");
                if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1].toLowerCase() == "png" || extn[1].toLowerCase() == "bmp"|| extn[1].toLowerCase() == "mp4"|| extn[1].toLowerCase() == "webm"|| extn[1].toLowerCase() == "ogg") {
                    if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1].toLowerCase() == "png" || extn[1].toLowerCase() == "bmp") {
                        reader.onload = function (e) {
                            $('#preview_image_div').append('<img src="' + e.target.result + '" name="PreviewImage' + i + '" id="PreviewImage' + i + '" width="400" height="400" class="object-cover shadow rounded" style="display:inline-block; margin:2%;">');
                        };
                    }
                    else if(extn[1].toLowerCase() == "mp4"|| extn[1].toLowerCase() == "webm"|| extn[1].toLowerCase() == "ogg"){
                        reader.onload = function (e) {
                            $('#preview_image_div').append('<video src="' + e.target.result + '" name="PreviewVideo' + i + '" id="PreviewVideo' + i + '" width="400" height="400" style="display:inline-block" class="object-cover shadow rounded" controls></video>');
                        }
                    }

                    reader.readAsDataURL(input.files[i]);
                    $('#imgdiv_multiple').html("");
                    document.getElementById('save').disabled = false;
                }
                else {
                    $('#imgdiv_multiple').html("Please Select Image Or Video Only");
                    document.getElementById('save').disabled = true;
                }
            }
        }
    }
</script>

<?php
include "footer.php";
?>