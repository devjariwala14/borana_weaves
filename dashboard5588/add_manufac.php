<?php include "header.php" ; 

if (isset($_COOKIE['edit_id'])) { $mode='edit' ; $editId=$_COOKIE['edit_id'];
    $stmt=$obj->con1->prepare("SELECT * FROM `manufacturing` WHERE id=?");
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    }

    if (isset($_COOKIE['view_id'])) {
    $mode = 'view';
    $viewId = $_COOKIE['view_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `manufacturing` WHERE id=?");
    $stmt->bind_param('i', $viewId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    }

    if (isset($_REQUEST["save"])) {
    $description = $_REQUEST["description"];
    $unit = $_REQUEST["unit"];
    $title = $_REQUEST["title"];
    $img_title = $_REQUEST["img_title"];
    $manufac_img = $_FILES['image']['name'];
    $manufac_img = str_replace(' ', '_', $manufac_img);
    $manufac_img_path = $_FILES['image']['tmp_name'];


    if ($manufac_img != "") {
    if (file_exists("images/manu_img/" . $manufac_img)) {
    $i = 0;
    $PicFileName = $manufac_img;
    $Arr1 = explode('.', $PicFileName);
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    while (file_exists("images/manu_img/" . $PicFileName)) {
    $i++;
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    }
    } else {
    $PicFileName = $manufac_img;
    }
    }

    try {
    $stmt = $obj->con1->prepare("INSERT INTO `manufacturing`(`image`,`description`,`title`,`unit`,`image_title`) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $manufac_img, $description,$title, $unit, $img_title );
    $Resp = $stmt->execute();
    $insert_manu_id = mysqli_insert_id($obj->con1);

    if (!$Resp) {
        throw new Exception(
            "Problem in adding! " . strtok($obj->con1->error, "(")
        );
    }
    foreach ($_FILES["multiple_file_name"]['name'] as $key => $value)
    { 
        // rename for product images       
        if($_FILES["multiple_file_name"]['name'][$key]!=""){
            $PicSubImage = $_FILES["multiple_file_name"]["name"][$key];
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
            $SubImageTemp = $_FILES["multiple_file_name"]["tmp_name"][$key];
            $SubImageName = str_replace(' ', '_', $SubImageName);
        
            // sub images qry
            move_uploaded_file($SubImageTemp, "images/manu_img/".$SubImageName);
        }

        $img_array= array("jpg", "jpeg", "png", "bmp");
        $vd_array=array("mp4", "webm", "ogg","mkv");
        $extn = strtolower(pathinfo($SubImageName, PATHINFO_EXTENSION));
        $file_type = (in_array($extn,$img_array))?"image":"video";
        
        $stmt_image = $obj->con1->prepare("INSERT INTO `manu_images`(`p_id`, `file_name`, `file_type`) VALUES (?,?,?)");
        $stmt_image->bind_param("iss", $insert_manu_id, $SubImageName, $file_type);
        $Resp = $stmt_image->execute();
        $stmt_image->close();
    }


    
    } catch (Exception $e) {
    setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
    }

    if ($Resp) {
    move_uploaded_file($manufac_img_path, "images/manu_img/" . $PicFileName);
    setcookie("msg", "data", time() + 3600, "/");
    header("location:manufacturing.php");
    } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:manufacturing.php");
    }
    }

    if (isset($_REQUEST["update"])) {
    $e_id = $_COOKIE['edit_id'];

    $unit = $_REQUEST["unit"];
    $title = $_REQUEST["title"];
    $img_title = $_REQUEST["img_title"];
    $description = $_REQUEST["description"];
    $manufac_img = $_FILES['image']['name'];
    $manufac_img = str_replace(' ', '_', $manufac_img);
    $manufac_img_path = $_FILES['image']['tmp_name'];
    $old_img = $_REQUEST['old_img'];


    if ($manufac_img != "") {
    if (file_exists("images/manu_img/" . $manufac_img)) {
    $i = 0;
    $PicFileName = $manufac_img;
    $Arr1 = explode('.', $PicFileName);
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    while (file_exists("images/manu_img/" . $PicFileName)) {
    $i++;
    $PicFileName = $Arr1[0] . $i . "." . $Arr1[1];
    }
    } else {
    $PicFileName = $manufac_img;
    }
    if (file_exists("images/manu_img/" . $old_img)) {
    unlink("images/manu_img/" . $old_img);
    }
    move_uploaded_file($manufac_img_path, "images/manu_img/" . $PicFileName);
    } else {
    $PicFileName = $old_img;
    }

    try {
    $stmt = $obj->con1->prepare("UPDATE `manufacturing` SET `description`=?,`image`=?,`title`=?,unit=?,`image_title`=? WHERE `id`=?");
    $stmt->bind_param("sssssi", $description, $PicFileName,$title,$unit ,$img_title,$e_id);
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
    header("location:manufacturing.php");
    } else {
    setcookie("msg", "fail", time() + 3600, "/");
    header("location:manufacturing.php");
    }
    }

    if (isset($_REQUEST["btndelete"])) {
        $delete_id = $_REQUEST['delete_id'];
      
        try {
            $stmt_del = $obj->con1->prepare("DELETE FROM `manu_images` WHERE id=?");
            $stmt_del->bind_param("i", $delete_id);
            $Resp = $stmt_del->execute();
            if (!$Resp) {
                throw new Exception("Problem in deleting! " . strtok($obj->con1->error,  '('));
            }
            $stmt_del->close();
        } catch (\Exception $e) {
            setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
        }
      
        if ($Resp) {
            setcookie("msg", "data_del", time() + 3600, "/");
        }
        header("location:add_manufac.php");
      }
    ?>
<div class="pagetitle">
    <h1>Manufacturing</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Manufacturing</li>
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
                            <label for="unit" class="col-sm-2 col-form-label">Units</label>
                            <select class="form-select" aria-label="Default select example" id="unit" name="unit"
                                <?php echo isset($mode) && $mode == 'view' ? 'disabled' : '' ?> required>
                                <option value="">Choose Units</option>
                                <option value="Unit1"
                                    <?php echo isset($mode) && $data['unit'] == "unit1" ? "selected" : "" ?>>Unit 1
                                </option>
                                <option value="unit2"
                                    <?php echo isset($mode) && $data['unit'] == "unit2" ? "selected" : "" ?>>Unit 2
                                </option>
                                <option value="unit3"
                                    <?php echo isset($mode) && $data['unit'] == "unit3" ? "selected" : "" ?>>Unit 3
                                </option>
                                <option value="unit4"
                                    <?php echo isset($mode) && $data['unit'] == "unit4" ? "selected" : "" ?>>Unit 4
                                </option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="inputText" class="col-sm-2 col-form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['title'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
                        </div>
                        <div class="col-md-12">
                            <label for="inputText" class="col-sm-2 col-form-label">Image Title</label>
                            <input type="text" id="img_title" name="img_title" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['image_title'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
                        </div>
                        <div class="col-md-12">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
                            <textarea class="form-control" style="height: 100px" id="description" name="description"
                                required
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>><?php echo (isset($mode)) ? $data['description'] : '' ?></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="file_name" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image"
                                onchange="readURL(this,'PreviewImage')" <?php echo isset($mode)?'':'required' ?>
                                <?php echo isset($mode) && $mode=='view'?'disabled':'' ?>>
                        </div>
                        <div class="col-md-12">
                            <div id="PreviewImage">
                                <?php 
                                if(isset($mode)){
                                    $img_array= array("jpg", "jpeg", "png", "bmp");
                                    $vd_array=array("mp4", "webm", "ogg","mkv");
                                    $extn = strtolower(pathinfo($data["image"], PATHINFO_EXTENSION));
                                    if (in_array($extn,$img_array)) {
                            ?>
                                <td><img src="images/manu_img/<?php echo $data["image"]; ?>" width="200" height="200"
                                        style="display:<?php (in_array($extn, $img_array))?'block':'none' ?>"
                                        class="object-cover shadow rounded mt-3"></td>
                                <?php
                                    } if (in_array($extn,$vd_array )) {
                            ?>
                                <td><video src="images/manu_img/<?php echo $data["image"]; ?>" height="200" width="200"
                                        style="display:<?php (in_array($extn, $vd_array))?'block':'none' ?>"
                                        class="object-cover shadow rounded mt-3" controls></video></td>
                                <?php } } ?>
                            </div>
                            <div id="imgdiv" style="color:red"></div>
                            <input type="hidden" name="old_img" id="old_img"
                                value="<?php echo (isset($mode) && $mode=='edit')?$data["image"]:'' ?>" />
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



<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post">
          <input type="hidden" name="delete_id" id="delete_id">
          <div class="modal-body">
            Are you sure you want to delete this record?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="btndelete" id="btndelete">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<section class="section" <?php echo (isset($mode))?'':'hidden' ?>>
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <a href="javascript:addsubimages();"><button type="button" class="btn btn-success"
                                <?php echo ($mode=='edit')?'':'hidden' ?>><i class="bi bi-plus me-1"></i> Add
                                Images</button></a>
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">Sr.No</th>
                                <th scope="col">File Type</th>
                                <th scope="col">File</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                    $stmt_images = $obj->con1->prepare("SELECT * FROM `manu_images` WHERE m_id=? ORDER BY id DESC");
                    $m_id = $data['id'];
                    $stmt_images->bind_param("i",$m_id);
                    $stmt_images->execute();
                    $result = $stmt_images->get_result();
                    $stmt_images->close();
                    $i=1;
                    while($row=mysqli_fetch_array($result))
                    {
                  ?>
                            <tr>
                                <th scope="row"><?php echo $i ?></th>
                                <td><?php echo $row["file_type"] ?></td>
                                <?php
                        $img_array= array("jpg", "jpeg", "png", "bmp");
                        $vd_array=array("mp4", "webm", "ogg","mkv");
                        $extn = strtolower(pathinfo($row["file_name"], PATHINFO_EXTENSION));
                        if (in_array($extn,$img_array)) {
                    ?>
                                <td><img src="images/manu_img/<?php echo $row["file_name"]; ?>" width="200"
                                        height="200"
                                        style="display:<?php (in_array($extn, $img_array))?'block':'none' ?>"
                                        class="object-cover shadow rounded"></td>
                                <?php
                        } if (in_array($extn,$vd_array )) {
                    ?>
                                <td><video src="images/manu_img/<?php echo $row["file_name"]; ?>" height="200"
                                        width="200" style="display:<?php (in_array($extn, $vd_array))?'block':'none' ?>"
                                        class="object-cover shadow rounded" controls></video></td>
                                <?php } ?>
                                <td>
                                    <a href="javascript:viewsubimages('<?php echo $row["id"]?>');"><i
                                            class="bx bx-show-alt bx-sm me-2"></i></a>
                                    <a href="javascript:editsubimages('<?php echo $row["id"]?>');"
                                        <?php echo ($mode=='edit')?'':'hidden' ?>><i
                                            class="bx bx-edit-alt bx-sm text-success me-2"></i></a>
                                    <a href="javascript:deletesubimages('<?php echo $row["id"]?>');"
                                        <?php echo ($mode=='edit')?'':'hidden' ?>><i
                                            class="bx bx-trash bx-sm text-danger"></i></a>
                                </td>
                            </tr>
                            <?php
                      $i++;
                    }
                  ?>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->

                </div>
            </div>

        </div>
    </div>
</section>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
function editsubimages(id) {
    eraseCookie("view_subimg_id");
    createCookie("edit_subimg_id", id, 1);
    window.location = "manu_sub_img.php";
}

function viewsubimages(id) {
    eraseCookie("edit_subimg_id");
    createCookie("view_subimg_id", id, 1);
    window.location = "manu_sub_img.php";
}

function deletesubimages(id) {
    $('#deleteModal').modal('toggle');
    $('#delete_id').val(id);
}

function addsubimages(id) {
    window.location = "manu_sub_img.php";
}

function readURL(input, preview) {
    if (input.files && input.files[0]) {
        var filename = input.files.item(0).name;

        var reader = new FileReader();
        var extn = filename.split(".");

        if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1].toLowerCase() == "png" || extn[
                1].toLowerCase() == "bmp" || extn[1].toLowerCase() == "mp4" || extn[1].toLowerCase() == "webm" || extn[
                1].toLowerCase() == "ogg") {
            if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1].toLowerCase() == "png" ||
                extn[1].toLowerCase() == "bmp") {
                reader.onload = function(e) {
                    $('#' + preview).html('<img src="' + e.target.result +
                        '" name="PreviewImage" id="PreviewImage" width="400" height="400" class="object-cover shadow rounded" style="display:inline-block; margin:2%;">'
                        );
                };
            } else if (extn[1].toLowerCase() == "mp4" || extn[1].toLowerCase() == "webm" || extn[1].toLowerCase() ==
                "ogg") {
                reader.onload = function(e) {
                    $('#' + preview).html('<video src="' + e.target.result +
                        '" name="PreviewVideo" id="PreviewVideo" width="400" height="400" style="display:inline-block" class="object-cover shadow rounded" controls></video>'
                        );
                }
            }

            reader.readAsDataURL(input.files[0]);
            $('#imgdiv').html("");
            document.getElementById('save').disabled = false;
        } else {
            $('#imgdiv').html("Please Select Image Or Video Only");
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
            if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1].toLowerCase() == "png" ||
                extn[1].toLowerCase() == "bmp" || extn[1].toLowerCase() == "mp4" || extn[1].toLowerCase() == "webm" ||
                extn[1].toLowerCase() == "ogg") {
                if (extn[1].toLowerCase() == "jpg" || extn[1].toLowerCase() == "jpeg" || extn[1].toLowerCase() ==
                    "png" || extn[1].toLowerCase() == "bmp") {
                    reader.onload = function(e) {
                        $('#preview_image_div').append('<img src="' + e.target.result + '" name="PreviewImage' + i +
                            '" id="PreviewImage' + i +
                            '" width="400" height="400" class="object-cover shadow rounded" style="display:inline-block; margin:2%;">'
                            );
                    };
                } else if (extn[1].toLowerCase() == "mp4" || extn[1].toLowerCase() == "webm" || extn[1].toLowerCase() ==
                    "ogg") {
                    reader.onload = function(e) {
                        $('#preview_image_div').append('<video src="' + e.target.result + '" name="PreviewVideo' +
                            i + '" id="PreviewVideo' + i +
                            '" width="400" height="400" style="display:inline-block" class="object-cover shadow rounded" controls></video>'
                            );
                    }
                }

                reader.readAsDataURL(input.files[i]);
                $('#imgdiv_multiple').html("");
                document.getElementById('save').disabled = false;
            } else {
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