<?php
include "header.php";

if (isset($_COOKIE['edit_id']) || isset($_COOKIE['view_id'])) {
    $mode = (isset($_COOKIE['edit_id'])) ? 'edit' : 'view';
    $Id = (isset($_COOKIE['edit_id'])) ? $_COOKIE['edit_id'] : $_COOKIE['view_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `corporate_announcement` WHERE id=?");
    $stmt->bind_param('i', $Id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (isset($_REQUEST["save"])) {
    $particulars = $_REQUEST["particulars"];
    $applicability = $_REQUEST["applicability"];
    $link = $_REQUEST["link"];
    $doc = $_FILES['link']['name'];
    $doc = str_replace(' ', '_', $doc);
    $doc_path = $_FILES['link']['tmp_name'];
    $status = $_REQUEST["radio"];

    if ($doc != "") {
        if (file_exists("documents/corporate_announcement/" . $doc)) {
            $i = 0;
            $DocFileName = $doc;
            $Arr1 = explode('.', $DocFileName);

            $DocFileName = $Arr1[0] . $i . "." . $Arr1[1];
            while (file_exists("documents/corporate_announcement/" . $DocFileName)) {
                $i++;
                $DocFileName = $Arr1[0] . $i . "." . $Arr1[1];
            }
        } else {
            $DocFileName = $doc;
        }
    }

    try {
        $stmt = $obj->con1->prepare("INSERT INTO `corporate_announcement`(`particulars`, `applicability`,`link`, `status`) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $particulars, $applicability, $link, $status);
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
        move_uploaded_file($doc_path, "documents/corporate_announcement/" . $DocFileName);
        setcookie("msg", "data", time() + 3600, "/");
        header("location:corporate_announcement.php");
    } else {
        setcookie("msg", "fail", time() + 3600, "/");
        header("location:corporate_announcement.php");
    }
}

if (isset($_REQUEST["update"])) {
    $id = $_COOKIE['edit_id'];
    $particulars = $_REQUEST["particulars"];
    $applicability = $_REQUEST["applicability"];
    $link = $_REQUEST["link"];
    $status = $_REQUEST["radio"];

    

    try {
        "UPDATE `corporate_announcement` SET`particulars`= '".$particulars."', `applicability`='".$applicability."', `link`= '".$link."', `status`= '".$status."' WHERE `id`='".$id."'";


        $stmt = $obj->con1->prepare("UPDATE `corporate_announcement` SET `particulars`=?,`applicability`=?, `link`=? ,`status`=? WHERE `id`=?");
        $stmt->bind_param("ssssi", $particulars, $applicability, $link , $status, $id);
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
        header("location:corporate_announcement.php");
    } else {
        setcookie("msg", "fail", time() + 3600, "/");
        header("location:corporate_announcement.php");
    }
}
?>
<!-- <a href="javascript:go_back();"><i class="bi bi-arrow-left"></i></a> -->
<div class="pagetitle">
    <h1>Corporate Announcement</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Corporate Announcement</li>
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
                                <label for="particulars" class="form-label">Particulars</label>
                                <input type="text" class="form-control" id="particulars" name="particulars"
                                    value="<?php echo (isset($mode)) ? $data['particulars'] : '' ?>"
                                    <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>>
                            </div>
                      
                            <div class="col-md-12" <?php echo (isset($mode) && $mode == 'view') ? 'hidden' : '' ?>>
                                <label for="doc" class="col-sm-2 col-form-label">Applicability</label>
                                <input type="text" class="form-control" id="applicability" name="applicability"
                                    value="<?php echo (isset($mode)) ? $data['applicability'] : '' ?>"
                                    <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>>
                            </div>
                        
                        <div class="col-md-12">
                                <label for="Link" class="form-label">Link</label>
                                <input type="text" class="form-control" id="link" name="link"
                                    value="<?php echo (isset($mode)) ? $data['link'] : '' ?>"
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
                            <button type="button" class="btn btn-danger" onclick="window.location='corporate_announcement.php'">
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
    window.location = "corporate_announcement.php";
}

function readURL(input, preview) {
    if (input.files && input.files[0]) {
        var filename = input.files.item(0).name;

        var reader = new FileReader();
        var extn = filename.split(".");

        if (["jpg", "jpeg", "png", "bmp", "pdf", "doc", "docx"].includes(extn[1].toLowerCase())) {
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
