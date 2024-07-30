<?php
include "header.php";
include "alert.php";

if (isset($_REQUEST["btndelete"])) {
  $id = $_REQUEST['delete_id'];

   try {
       $stmt_doc = $obj->con1->prepare("SELECT * FROM `career` WHERE id=?");
       $stmt_doc->bind_param("i",$id);
       $stmt_doc->execute();
       $Resp_doc = $stmt_doc->get_result()->fetch_assoc();
       $stmt_doc->close();

       if (file_exists("documents/career/" . $Resp_doc["file_path"])) {
           unlink("documents/career/" . $Resp_doc["file_path"]);
       }

       $stmt_del = $obj->con1->prepare("DELETE FROM `career` WHERE id=?");
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
   header("location:career.php");
}
?>

<script type="text/javascript">
function viewdata(id) {
    createCookie("view_id", id, 1);
    window.location = "view_career.php";
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
            <form method="post" action="career.php">
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
    <h1>Career</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Career</li>
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
                        <!-- <a href="javascript:adddata();"><button type="button" class="btn btn-success"><i
                                    class="bi bi-plus me-1"></i> Add</button></a> -->
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">Sr.No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Message</th>
                                <th scope="col">Document</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
              $stmt = $obj->con1->prepare("SELECT * FROM `career` order by id desc");
              $stmt->execute();
              $Resp = $stmt->get_result();

              for ($i = 1; $row = mysqli_fetch_assoc($Resp); $i++) { ?>
                            <tr>
                                <th scope="row"><?= $i ?></th>
                                <td><?= $row["name"] ?></td>
                                <td><?= $row["email"] ?></td>
                                <td><?= $row["number"] ?></td>
                                <td><?= $row["msg"] ?></td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <a href="documents/career/<?= $row["download"] ?>" class="btn btn-primary me-2" download>
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <span><?= $row["download"] ?></span>
                                    </div>
                                </td>
                                <td>
                                    <a href="javascript:viewdata('<?= $row["id"]?>')"><i
                                            class="bx bx-show-alt bx-sm me-2"></i> </a>
                                    <a href="javascript:deletedata('<?= $row["id"]?>');"><i
                                            class="bx bx-trash bx-sm me-2 text-danger"></i> </a>
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