<?php
include "header.php";
include "alert.php";

if (isset($_REQUEST["btndelete"])) {
    $id = $_REQUEST['delete_id'];
 
     try {
         $stmt_doc = $obj->con1->prepare("SELECT * FROM `primary_benifits` WHERE id=?");
         $stmt_doc->bind_param("i",$id);
         $stmt_doc->execute();
         $Resp_doc = $stmt_doc->get_result()->fetch_assoc();
         $stmt_doc->close();
 
         if (file_exists("images/primary_benifits/" . $Resp_doc["icon"])) {
             unlink("images/primary_benifits/" . $Resp_doc["icon"]);
         }
 
         $stmt_del = $obj->con1->prepare("DELETE FROM `primary_benifits` WHERE id=?");
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
     header("location:primary_benifits.php");
  }
 ?>
<script type="text/javascript">
function adddata(id) {
    eraseCookie("edit_id");
    eraseCookie("view_id");
    window.location = "add_primary_benifits.php";
}

function editdata(id) {
    eraseCookie("view_id");
    createCookie("edit_id", id, 1);
    window.location = "add_primary_benifits.php";
}

function viewdata(id) {
    eraseCookie("edit_id");
    createCookie("view_id", id, 1);
    window.location = "add_primary_benifits.php";
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
            <form method="post" action="primary_benifits.php">
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
    <h1>Primary Benifits</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Primary Benifits</li>
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
                                <th scope="col">Icon</th>
                                <th scope="col">Title</th>
                                <th scope="col">Content</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                $stmt_list = $obj->con1->prepare("SELECT * FROM `primary_benifits` ORDER BY `id` DESC;");
                $stmt_list->execute();
                $result = $stmt_list->get_result();
                $stmt_list->close();
                $i=1;
                while($row=mysqli_fetch_array($result))
                {
            ?>
                            <tr>
                                <td><?php echo $i?></td>
                                <td>
                                    <?php
                                        $img_array = array("jpg", "jpeg", "png", "bmp");
                                        $extn = strtolower(pathinfo($row["icon"], PATHINFO_EXTENSION));
                                        if (in_array($extn, $img_array)) {
                                        ?>
                                    <img src="images/primary_benifits/<?php echo $row["icon"]; ?>" width="200"
                                        height="200"
                                        style="display:<?php (in_array($extn, $img_array)) ? 'block' : 'none' ?>"
                                        class="object-cover shadow rounded">
                                    <?php
                                        } ?>
                                </td>
                                <td><?php echo $row["title"]?></td>
                                <td><?php echo $row["content"]?></td>
                                
                               
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
<!-- <script> 
function redirectToNewPage(link) { window.location.href = link; 
// Replace with your desired URL 
} 
</script> -->



<?php
include "footer.php";
?>