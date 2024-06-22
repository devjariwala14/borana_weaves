<?php
include "header.php";
include "alert.php";

if (isset($_REQUEST["btndelete"])) {
    $id = $_REQUEST['delete_id'];
 
     try {
         $stmt_doc = $obj->con1->prepare("SELECT * FROM `investors_data` WHERE id=?");
         $stmt_doc->bind_param("i",$id);
         $stmt_doc->execute();
         $Resp_doc = $stmt_doc->get_result()->fetch_assoc();
         $stmt_doc->close();
 
         if (file_exists("documents/inv_dat/" . $Resp_doc["filename"])) {
             unlink("documents/inv_dat/" . $Resp_doc["filename"]);
         }
 
         $stmt_del = $obj->con1->prepare("DELETE FROM `investors_data` WHERE id=?");
         $stmt_del->bind_param("i", $id);
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
     header("location:investors_data.php");
  }
 ?>
<script type="text/javascript">
function adddata(id) {
    eraseCookie("edit_id");
    eraseCookie("view_id");
    window.location = "add_inv_data.php";
}

function editdata(id) {
    eraseCookie("view_id");
    createCookie("edit_id", id, 1);
    window.location = "add_inv_data.php";
}

function viewdata(id) {
    eraseCookie("edit_id");
    createCookie("view_id", id, 1);
    window.location = "add_inv_data.php";
}

function deletedata(id) {
    $('#deleteModal').modal('toggle');
    $('#delete_id').val(id);
}
</script>

<!-- Basic Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="investors_data.php">
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
<!-- End Basic Modal-->

<div class="pagetitle">
    <h1>Investors Data</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Investors Data</li>
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
                        <a href="javascript:adddata();"><button type="button" class="btn btn-success"><i
                                    class="bi bi-plus me-1"></i> Add</button></a>
                    </div>
                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">Sr.no</th>
                                <th scope="col">Menu Name</th>
                                <th scope="col">Title</th>
                                <th scope="col">File</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                $stmt_list = $obj->con1->prepare("SELECT i1.*, m1.menu_name FROM investors_data i1 INNER JOIN investors_menu m1 ON i1.inv_menu_id = m1.id ORDER BY i1.id DESC;");
                $stmt_list->execute();
                $result = $stmt_list->get_result();
                $stmt_list->close();
                $i=1;
                while($row=mysqli_fetch_array($result))
                {
            ?>
                            <tr>
                                <td><?php echo $i?></td>
                                <td><?php echo $row["menu_name"]?></td>
                                <td><?php echo $row["title"]?></td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <a href="documents/inv_data/<?php echo $row["filename"] ?>"
                                            class="btn btn-primary me-2" download>
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <span><?php echo $row["filename"] ?></span>
                                    </div>
                                </td>
                                <td><?php echo date("d-m-Y", strtotime($row["date"]));?></td>
                                <td>
                                    <h5><span
                                            class="badge rounded-pill bg-<?php echo (($row["status"]=='enable')?  'success':'danger') ?>"><?php echo ucfirst($row["status"]) ?></span>
                                    </h5>
                                </td>
                                <td>
                                    <a href="javascript:viewdata('<?php echo $row["id"]?>')"><i
                                            class="bx bx-show-alt bx-sm me-2"></i> </a>
                                    <a href="javascript:editdata('<?php echo$row["id"]?>')"><i
                                            class="bx bx-edit-alt bx-sm me-2 text-success"></i> </a>
                                    <a href="javascript:deletedata('<?php echo $row["id"]?>');"><i
                                            class="bx bx-trash bx-sm me-2 text-danger"></i> </a>
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



<?php
include "footer.php";
?>