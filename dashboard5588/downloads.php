<?php
include "header.php";
include "alert.php";

if (isset($_REQUEST["btndelete"])) {
  $member_id = $_REQUEST['delete_id'];

  try {
    $stmt_subimg = $obj->con1->prepare("SELECT `doc_name` FROM `downloads` WHERE id=?");
    $stmt_subimg->bind_param("i", $member_id);
    $stmt_subimg->execute();
    $Resp_subimg = $stmt_subimg->get_result();
    $doc_name = mysqli_fetch_array($Resp_subimg)["doc_name"];
    $stmt_subimg->close();

    // while ($row_subimg = mysqli_fetch_array($Resp_subimg)) {
    //   if (file_exists("images/downloads/" . $row_subimg["doc_name"])) {
    //     unlink("images/downloads/" . $row_subimg["doc_name"]);
    //   }
    // }

    // $stmt_subimg_del = $obj->con1->prepare("DELETE FROM   `downloads`  WHERE id=?");
    // $stmt_subimg_del->bind_param("i", $member_id);
    // $Resp_subimg_del = $stmt_subimg_del->execute();
    // $stmt_subimg_del->close();

    $stmt_del = $obj->con1->prepare("DELETE FROM `downloads` WHERE id=?");
    $stmt_del->bind_param("i", $member_id);
    $Resp = $stmt_del->execute();
    if (!$Resp) {
      throw new Exception("Problem in deleting! " . strtok($obj->con1->error, '('));
    }
    $stmt_del->close();
  } catch (\Exception $e) {
    setcookie("sql_error", urlencode($e->getMessage()), time() + 3600, "/");
  }

  if ($Resp) {
    if (file_exists("images/downloads/" . $doc_name)) {
      unlink("images/downloads/" . $doc_name);
    }
    setcookie("msg", "data_del", time() + 3600, "/");
  }
  header("location:downloads.php");
}
?>

<script type="text/javascript">
function add_data() {
    eraseCookie("edit_id");
    eraseCookie("view_id");
    window.location = "add_downloads.php";
}

function editdata(id) {
    createCookie("edit_id", id, 1);
    window.location = "add_downloads.php";
}

function viewdata(id) {
    createCookie("view_id", id, 1);
    window.location = "add_downloads.php";
}

function deletedata(id) {
    $('#deleteModal').modal('show');
    $('#confirmDelete').attr('data-id', id);
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
    <h1>Downloads</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item">Downloads</li>
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
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Document Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
              $stmt = $obj->con1->prepare("SELECT d1.*, f1.type FROM downloads d1 INNER JOIN file_type f1 ON d1.file_type_id = f1.id ORDER BY d1.id DESC");
              $stmt->execute();
              $Resp = $stmt->get_result();

              for ($i = 1; $row = mysqli_fetch_array($Resp); $i++) { ?>
                            <tr>
                                <th scope="row"><?= $i ?></th>
                                <td><?= $row["title"] ?></td>
                                <td><?= $row["doc_name"] ?></td>
                                <td><?= $row["type"] ?></td>
                                <td>
                                    <h4><span
                                            class="badge rounded-pill bg-<?php echo ($row['status']=='Enable')?'success':'danger'?>"><?php echo $row["status"]; ?></span>
                                    </h4>
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