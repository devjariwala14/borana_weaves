<?php 
 include "header.php";
 include "alert.php";

 if(isset($_REQUEST["btndelete"]))
{
  $id = $_REQUEST['delete_id'];
  try
  {
    $stmt_del = $obj->con1->prepare("DELETE FROM `contact_us` WHERE id = ?");
    $stmt_del->bind_param("i",$id);
    $Resp=$stmt_del->execute();
    if(!$Resp)
    {
      throw new Exception("Problem in deleting! ". strtok($obj->con1-> error,  '('));
    }
    $stmt_del->close();
  }
  catch(\Exception  $e) {
    setcookie("sql_error", urlencode($e->getMessage()),time()+3600,"/");
  }

  if($Resp)
  {
    setcookie("msg", "data_del",time()+3600,"/");
    header("location:contact_us.php");
  }
  else
  {
   setcookie("msg", "fail",time()+3600,"/");
   header("location:contact_us.php");
 }
}
?>
<script type="text/javascript">
function add_data() {
    eraseCookie("edit_id");
    eraseCookie("view_id");
    window.location = "add_contact_us.php";
}
function deletedata(id){
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
        <form method="post" action = "contact_us.php">
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
  <h1>Contact Us</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Contact Us</li>
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
            <!-- <a href="javascript:adddata();"><button type="button" class="btn btn-success"><i class="bi bi-plus me-1"></i> Add</button></a> -->
        </div>
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Sr no.</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Phone No.</th>
                                                <th scope="col">Message</th>
                                                <th scope="col">Date-Time</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                            $stmt = $obj->con1->prepare("SELECT * FROM `contact_us` ORDER BY `id` DESC");
                            $stmt->execute();
                            $Resp = $stmt->get_result();
                            $i = 1;
                            while ($row = mysqli_fetch_array($Resp)) { ?>
                                            <tr>

                                                <th scope="row"><?php echo $i; ?></th>

                                                <td><?php echo $row["name"] ?></td>

                                                <td><?php echo $row["email"] ?></td>

                                                <td><?php echo $row["phone_no"] ?></td>

                                                <td><?php echo $row["message"] ?></td>

                                                <td><?php echo $row["date_time"] ?></td>

                                                <td>
                                                    <a href="javascript:deletedata('<?php echo $row["id"]?>');"><i
                                                            class="bx bx-trash bx-sm me-2 text-danger"></i> </a>
                                                    </h4>
                                                </td>
                                            </tr>
                                            <?php $i++;
                                                  }
                                             ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>
</section>


<?php
include "footer.php";
?>