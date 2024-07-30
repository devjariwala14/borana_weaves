<?php
include "header.php";

if (isset($_COOKIE['edit_id']) || isset($_COOKIE['view_id'])) {
    $mode = (isset($_COOKIE['edit_id']))?'edit':'view';
    $Id = (isset($_COOKIE['edit_id']))?$_COOKIE['edit_id']:$_COOKIE['view_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `ldor` WHERE  id=?");
    $stmt->bind_param('i',$Id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (isset($_REQUEST["save"])) {

    $name=$_REQUEST["name"];
    $description=$_REQUEST["description"];
    try {
        $stmt = $obj->con1->prepare("INSERT INTO `ldor`(`name`,`description`) VALUES (?,?)");
        $stmt->bind_param("ss", $name,$description);
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
        
        	setcookie("msg", "data", time() + 3600, "/");
        	header("location:ldor.php");
        } else {
            	setcookie("msg", "fail", time() + 3600, "/");
            	header("location:ldor.php");
            }
        }
        
        if (isset($_REQUEST["update"])) {
            $e_id = $_COOKIE['edit_id'];
            $name=$_REQUEST["name"];
            $description=$_REQUEST["description"];
            try {
                $stmt = $obj->con1->prepare("UPDATE `ldor` SET `name`=?,`description`=? WHERE `id`=?");
                $stmt->bind_param("ssi", $name, $description, $e_id);
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
                header("location:ldor.php");
            } else {
                setcookie("msg", "fail", time() + 3600, "/");
                header("location:ldor.php");
            }
        }
        ?>

<div class="pagetitle">
    <h1>LDOR</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">LDOR</li>
            <li class="breadcrumb-item active">
                <?php echo (isset($mode)) ? (($mode == 'view') ? 'View' : 'Edit') : 'Add' ?>-LDOR</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <!-- Multi Columns Form -->
                    <form class="row g-3 pt-3" method="post">
                        <div class="col-md-12">
                            <label for="inputText" class="col-sm-2 col-form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['name'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
                        </div>
                        <div class="col-md-12">
                            <label for="discription" class="col-sm-2 col-form-label">Description</label>
                            <textarea class="tinymce-editor" name="description" id="description"
                                <?php echo isset($mode) && $mode == 'view' ? 'disabled' : '' ?>><?php echo (isset($mode)) ? $data['description'] : '' ?></textarea>
                            <!-- <input type="hidden" name="quill_content" id="quill_content"> -->
                        </div>
                        <div class="text-left mt-4">
                            <button type="submit"
                                name="<?php echo isset($mode) && $mode == 'edit' ? 'update' : 'save' ?>" id="save"
                                class="btn btn-success  <?php echo isset($mode) && $mode == 'view' ? 'd-none' : '' ?>">
                                <?php echo isset($mode) && $mode == 'edit' ? 'Update' : 'Save' ?>
                            </button>
                            <!-- onclick="return setQuillInput()" -->
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
    window.location = "ldor.php";
}
</script>
<?php
        include "footer.php";
        ?>