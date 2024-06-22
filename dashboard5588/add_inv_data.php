<?php
include "header.php";

if (isset($_COOKIE['edit_id']) || isset($_COOKIE['view_id'])) {
    $mode = (isset($_COOKIE['edit_id'])) ? 'edit' : 'view';
    $Id = (isset($_COOKIE['edit_id'])) ? $_COOKIE['edit_id'] : $_COOKIE['view_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `investors_data` WHERE id=?");
    $stmt->bind_param('i', $Id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (isset($_REQUEST["save"])) {
    $name = $_REQUEST["menu"];
    $title = $_REQUEST["title"];
    $doc = $_FILES['doc']['name'];
    $doc = str_replace(' ', '_', $doc);
    $doc_path = $_FILES['doc']['tmp_name'];
    $date = $_REQUEST["date"];
    $status = $_REQUEST["radio"];

    if ($doc != "") {
        if (file_exists("documents/inv_data/" . $doc)) {
            $i = 0;
            $DocFileName = $doc;
            $Arr1 = explode('.', $DocFileName);

            $DocFileName = $Arr1[0] . $i . "." . $Arr1[1];
            while (file_exists("documents/inv_data/" . $DocFileName)) {
                $i++;
                $DocFileName = $Arr1[0] . $i . "." . $Arr1[1];
            }
        } else {
            $DocFileName = $doc;
        }
    }

    try {
        $stmt = $obj->con1->prepare("INSERT INTO `investors_data`(`inv_menu_id`,`title`,`filename`,`date`, `status`) VALUES (?,?,?,?,?)");
        $stmt->bind_param("issss", $name, $title, $DocFileName, $date, $status);
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
        move_uploaded_file($doc_path, "documents/inv_data/" . $DocFileName);
        setcookie("msg", "data", time() + 3600, "/");
        header("location:investors_data.php");
    } else {
        setcookie("msg", "fail", time() + 3600, "/");
        header("location:investors_data.php");
    }
}

if (isset($_REQUEST["update"])) {
    $e_id = $_COOKIE['edit_id'];
    $name = $_REQUEST["menu"];
    $title = $_REQUEST["title"];
    $doc = $_FILES['doc']['name'];
    $doc = str_replace(' ', '_', $doc);
    $doc_path = $_FILES['doc']['tmp_name'];
    $date = $_REQUEST["date"];
    $status = $_REQUEST["radio"];
    $old_img = $_REQUEST["old_file"];

    if ($doc != "") {
        if (file_exists("documents/inv_data/" . $doc)) {
            $i = 0;
            $DocFileName = $doc;
            $Arr1 = explode('.', $DocFileName);

            $DocFileName = $Arr1[0] . $i . "." . $Arr1[1];
            while (file_exists("documents/inv_data/" . $DocFileName)) {
                $i++;
                $DocFileName = $Arr1[0] . $i . "." . $Arr1[1];
            }
        } else {
            $DocFileName = $doc;
        }
        unlink("documents/inv_data/" . $old_img);
        move_uploaded_file($doc_path, "documents/inv_data/" . $DocFileName);
    } else {
        $DocFileName = $old_img;
    }

    try {
        $stmt = $obj->con1->prepare("UPDATE `investors_data` SET `inv_menu_id`=?,`title`=?,`filename`=?,`date`=?, `status`=? WHERE `id`=?");
        $stmt->bind_param("issssi", $name, $title, $DocFileName, $date, $status, $e_id);
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
        header("location:investors_data.php");
    } else {
        setcookie("msg", "fail", time() + 3600, "/");
        header("location:investors_data.php");
    }
}
?>
<!-- <a href="javascript:go_back();"><i class="bi bi-arrow-left"></i></a> -->
<div class="pagetitle">
    <h1>Investors Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Investors Data</li>
            <li class="breadcrumb-item active">
                <?php echo (isset($mode)) ? (($mode == 'view') ? 'View' : 'Edit') : 'Add' ?>-Investors Data</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <!-- Multi Columns Form -->
                    <form class="row g-3 pt-3" method="post" enctype="multipart/form-data">

                        <div class="col-md-12">
                            <label for="menu" class="form-label">Investors Menu Name</label>
                            <select id="inputMenu" class="form-select" name="menu">
                                <option selected>Choose Menu</option>
                                <?php
                                        $stmt_list = $obj->con1->prepare("SELECT * FROM `investors_menu` WHERE `status`= 'Enable'");
                                        $stmt_list->execute();
                                        $result = $stmt_list->get_result();
                                        $stmt_list->close();
                                        $i=1;
                                        while($state=mysqli_fetch_array($result))
                                        {
                                    ?>
                                <option value="<?php echo $state["id"]?>"
                                    <?php echo isset($mode) && $data['inv_menu_id'] == $state["id"] ? 'selected' : '' ?>>
                                    <?php echo $state["menu_name"]?></option>
                                <?php 
                                        }
                                    ?>
                            </select>
                        </div>


                        <div class="col-md-12">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?php echo (isset($mode)) ? $data['title'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>>
                        </div>

                        <div class="col-md-12" <?php echo (isset($mode) && $mode == 'view') ? 'hidden' : '' ?>>
                            <label for="doc" class="col-sm-2 col-form-label">File</label>
                            <input class="form-control" type="file" id="doc" name="doc" data_btn_text="Browse"
                                onchange="readURL(this,'PreviewFile')" />
                        </div>

                        <div>
                            <h4 class="font-bold text-primary mt-2 mb-3"
                                style="display:<?php echo (isset($mode)) ? 'block' : 'none' ?>">
                                Preview
                            </h4>
                            <?php if(isset($mode) && !empty($data["filename"])): ?>
                            <?php $file_ext = pathinfo($data["filename"], PATHINFO_EXTENSION); ?>
                            <?php if(in_array($file_ext, ['jpg', 'jpeg', 'png', 'bmp'])): ?>
                            <img src="documents/inv_data/<?php echo $data["filename"]; ?>" name="PreviewFile"
                                id="PreviewFile" height="300" class="object-cover shadow rounded">
                            <?php else: ?>
                            <div style="display: flex; align-items: center;">
                                <p id="PreviewFile" style="margin: 0; margin-right: 10px;">
                                    <?php echo $data["filename"]; ?></p>
                                <a href="documents/career/<?php echo $data["filename"]; ?>" class="btn btn-success"
                                    download>
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                            <div id="filediv" style="color:red"></div>
                            <input type="hidden" name="old_file" id="old_file"
                                value="<?php echo (isset($mode) && $mode == 'edit') ? $data["filename"] : '' ?>" />
                        </div>


                        <div class="col-md-12">
                            <label for="inputDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="<?php echo (isset($mode)) ? $data['date'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>>
                        </div>

                        <div class="col-md-6">
                            <label for="inputEmail5" class="form-label">Status</label> <br />
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" name="radio" id="radio1"
                                    <?php echo isset($mode) && $data['status'] == 'Enable' ? 'checked' :'' ?>
                                    class="form-radio text-primary" value="enable" checked required
                                    <?php echo isset($mode) && $mode == 'view' ? 'disabled' : '' ?> />
                                <label class="form-check-label" for="radio1">Enable</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" name="radio" id="radio2"
                                    <?php echo isset($mode) && $data['status'] == 'Disable' ? 'checked' : '' ?>
                                    class="form-radio text-danger" value="disable" required
                                    <?php echo isset($mode) && $mode == 'view' ? 'disabled' : '' ?> />
                                <label class="form-check-label" for="radio2">Disable</label>
                            </div>
                        </div>
                        <div class="text-left mt-4">
                            <button type="submit"
                                name="<?php echo isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>" id="save"
                                class="btn btn-success <?php echo isset($mode) && $mode == 'view' ? 'd-none' : '' ?>"><?php echo isset($mode) && $mode == 'edit' ? 'Update' : 'Save' ?>
                            </button>
                            <button type="button" class="btn btn-danger" onclick="window.location='investors_data.php'">
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
    window.location = "investors_data.php";
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