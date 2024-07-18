<?php
include "header.php";

if (isset($_COOKIE['edit_id']) || isset($_COOKIE['view_id'])) {
    $mode = (isset($_COOKIE['edit_id']))?'edit':'view';
    $Id = (isset($_COOKIE['edit_id']))?$_COOKIE['edit_id']:$_COOKIE['view_id'];
    $stmt = $obj->con1->prepare("SELECT * FROM `investors_contact` WHERE  id=?");
    $stmt->bind_param('i',$Id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (isset($_REQUEST["save"])) {

    $title=$_REQUEST["title"];
    $name=$_REQUEST["name"];
    $designation=$_REQUEST["designation"];
    $address=$_REQUEST["address"];
    $email=$_REQUEST["email"];
    $website=$_REQUEST["website"];
    $contact=$_REQUEST["contact"];
    try {
        $stmt = $obj->con1->prepare("INSERT INTO `investors_contact`(`title`,`name`,`designation`,`address`,`email`,`website`,`contact`) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssss", $title, $name, $designation, $address, $email, $website,$contact);
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
        	header("location:investor_contact.php");
        } else {
            	setcookie("msg", "fail", time() + 3600, "/");
            	header("location:investor_contact.php");
            }
        }
        
        if (isset($_REQUEST["update"])) {
            $e_id = $_COOKIE['edit_id'];
            $title=$_REQUEST["title"];
            $name=$_REQUEST["name"];
            $designation=$_REQUEST["designation"];
            $address=$_REQUEST["address"];
            $email=$_REQUEST["email"];
            $website=$_REQUEST["website"];
            $contact=$_REQUEST["contact"];
            try {
                $stmt = $obj->con1->prepare("UPDATE `investors_contact` SET `title`=?,`name`=?,`designation`=?,`address`=?,`email`=?,`website`=?,`contact`=? WHERE `id`=?");
                $stmt->bind_param("sssssssi", $title, $name, $designation, $address, $email, $website,$contact, $e_id);
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
                header("location:investor_contact.php");
            } else {
                setcookie("msg", "fail", time() + 3600, "/");
                header("location:investor_contact.php");
            }
        }
        ?>

<div class="pagetitle">
    <h1>Investors Contact</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Investors Contact</li>
            <li class="breadcrumb-item active">
                <?php echo (isset($mode)) ? (($mode == 'view') ? 'View' : 'Edit') : 'Add' ?>-Investors Contact</li>
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
                            <label for="inputText" class="col-sm-2 col-form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['title'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
                        </div>
                        <div class="col-md-12">
                            <label for="inputText" class="col-sm-2 col-form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['name'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
                        </div>
                        <div class="col-md-12">
                            <label for="inputText" class="col-sm-2 col-form-label">Designation</label>
                            <input type="text" id="designation" name="designation" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['designation'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
                        </div>
                        <div class="col-md-12">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Address</label>
                            <textarea class="form-control" style="height: 100px" id="address" name="address"
                                required
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>><?php echo (isset($mode)) ? $data['address'] : '' ?></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                            <input type="text" id="email" name="email" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['email'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?> required />
                        </div>
                        <div class="col-md-12">
                            <label for="inputText" class="col-sm-2 col-form-label">Website</label>
                            <input type="text" id="website" name="website" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['website'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>  />
                        </div>
                        <div class="col-md-12">
                            <label for="inputText" class="col-sm-2 col-form-label">Contact</label>
                            <input type="text" id="contact" name="contact" class="form-control"
                                value="<?php echo (isset($mode)) ? $data['contact'] : '' ?>"
                                <?php echo isset($mode) && $mode == 'view' ? 'readonly' : '' ?>  />
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
    window.location = "investor_contact.php";
}
</script>
<?php
        include "footer.php";
        ?>