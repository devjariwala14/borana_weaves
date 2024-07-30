<?php 
 include "header.php";
 include "alert.php";

 if (isset($_REQUEST["btndelete"])) {
    $s_id = $_REQUEST['delete_id'];
 
     try {
         $stmt_subimg = $obj->con1->prepare("SELECT * FROM `sustainability` WHERE id=?");
         $stmt_subimg->bind_param("i",$s_id);
         $stmt_subimg->execute();
         $Resp_subimg = $stmt_subimg->get_result()->fetch_assoc();
         $stmt_subimg->close();
 
         if (file_exists("images/sus_img/" . $Resp_subimg["image"])) {
             unlink("images/sus_img/" . $Resp_subimg["image"]);
         }
 
         $stmt_del = $obj->con1->prepare("DELETE FROM `sustainability` WHERE id=?");
         $stmt_del->bind_param("i", $s_id);
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
     header("location:sustainability.php");
  }
?>
<script type="text/javascript">
function add_data() {
    eraseCookie("edit_id");
    eraseCookie("view_id");
    window.location = "add_sus.php";
}

function editdata(id) {
    eraseCookie("view_id");
    createCookie("edit_id", id, 1);
    window.location = "add_sus.php";
}

function viewdata(id) {
    eraseCookie("edit_id");
    createCookie("view_id", id, 1);
    window.location = "add_sus.php";
}

function deletedata(id) {
    $('#deleteModal').modal('toggle');
    $('#delete_id').val(id);
}
</script>
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
<div class="pagetitle">
    <h1>Sustainability</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item">Sustainability</li>
            <li class="breadcrumb-item active">Data</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <a href="javascript:add_data();"><button type="button" class="btn btn-success"><i
                                    class="bi bi-plus me-1"></i> Add </button></a>
                    </div>


                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">Sr.no</th>
                                <th scope="col">Description</th>
                                <th scope="col">Image</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $obj->con1->prepare("SELECT * FROM `sustainability` order by id desc");
                            $stmt->execute();
                            $Resp = $stmt->get_result();
                            $i = 1;
                            while ($row = mysqli_fetch_array($Resp)) { ?>
                            <tr>
                                <th scope="row"><?php echo $i ?></th>
                                <td><?php echo mb_strlen($row["description"]) > 50 ? mb_substr($row["description"], 0, 50) . '...' : $row["description"]; ?></td>
                                <td>
                                    <?php
                                        $img_array = array("jpg", "jpeg", "png", "bmp");
                                        $extn = strtolower(pathinfo($row["image"], PATHINFO_EXTENSION));
                                        if (in_array($extn, $img_array)) {
                                        ?>
                                    <img src="images/sus_img/<?php echo $row["image"]; ?>" width="200" height="200"
                                        style="display:<?php (in_array($extn, $img_array)) ? 'block' : 'none' ?>"
                                        class="object-cover shadow rounded">
                                    <?php
                                        } ?>
                                </td>
                                <td>
                                    <a href="javascript:viewdata('<?php echo $row["id"]?>');"><i
                                            class="bx bx-show-alt bx-sm me-2"></i></a>
                                    <a href="javascript:editdata('<?php echo $row["id"]?>');"><i
                                            class="bx bx-edit-alt bx-sm text-success me-2"></i></a>
                                    <a href="javascript:deletedata('<?php echo $row["id"]?>');"><i
                                            class="bx bx-trash bx-sm text-danger"></i></a>
                                </td>
                            </tr>
                            <?php $i++; 
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

<?php
     include "footer.php"; 
    ?>