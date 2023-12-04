<?php
    session_start();
    require '../function.php';
    require 'redirect.php';

    $nip = $_SESSION["nip"];
    $id = $_GET["id"];
    // var_dump($id);
    // die();
    // var_dump($_SESSION);
    // die();

    if(!isset($_SESSION["login"])) {
        header("Location: ../index.php");
        exit;
    }
    $user = query("SELECT * FROM lectures WHERE nip = '$nip'")[0];
    // var_dump($user);
    $listSubmission = query("SELECT * FROM submissions as sub JOIN students as std ON sub.user_nrp = std.nrp JOIN assignments as a ON a.id = sub.assignment_id WHERE sub.assignment_id = '$id'");
    // var_dump($listSubmission[0]["user_nrp"]);
    // die();
    // $nrp = $_POST["user_nrp"];
    // var_dump($nrp);
    // die();
    if(isset($_POST["viewSubmission"])){
        $id = $_GET['id'];
        $nrp = $_POST["user_nrp"];
        // var_dump($nrp);
        // die();
        $listUpdate = query("SELECT * FROM submissions as sub JOIN students as std ON sub.user_nrp = std.nrp WHERE sub.assignment_id = '$id' AND sub.user_nrp = '$nrp'");
        $file = $listUpdate[0]['file'];
        // var_dump($listUpdate);
        // die(); 
        header("Content-type: application/pdf");
        // header("Content-Disposition: inline; filename='$file'");
        // header("Content-Transfer-Encoding: binary");
        // header("Content-Length: " . filesize("../assignments/$file"));
        // header("Accept-Ranges: bytes");
        readfile("../submissions/$file");
    }
    else if (isset($_POST["downloadSubmission"])) {
        $id = $_GET['id'];
        $nrp = $_POST["user_nrp"];
        // var_dump($id);
        // die();
        $listUpdate = query("SELECT * FROM submissions as sub JOIN students as std ON sub.user_nrp = std.nrp WHERE sub.assignment_id = '$id' AND sub.user_nrp = '$nrp'")[0];
        $file = $listUpdate['file'];
        // var_dump($file);
        // die(); 
        header("Content-Disposition: File Transfer");
        header("Content-Disposition: attachment; filename=$file");
        header("Content-Type: application/octet-stream");
        // header("Content-Transfer-Encoding: binary");
        // header("Content-Length: " . filesize("../assignments/$file"));
        // header("Accept-Ranges: bytes");
        readfile("../submissions/$file");
        exit;
    }
    // var_dump($status);
    // die();
    if(isset($_POST["status"])) {
        $score = htmlspecialchars($_POST["score"]);
        $nrp = $_POST["user_nrp"];
        $id = $_POST["id"];
        $status = $_POST["status"];
        // var_dump($nrp);
        // die();
        // var_dump($_POST);
        // die();
        $query = "UPDATE submissions SET score = '$score', status = '$status' WHERE assignment_id = '$id' AND user_nrp = '$nrp'";
        mysqli_query($connect, $query);
        // var_dump(mysqli_query($connect, $query));
        // die();
        header("Location: valuationDetail.php?id=$id");
        // var_dump(mysqli_query($connect, $query));
        // die();
    }
    // } else if(isset($_POST["reject"])) {
    //     $score = htmlspecialchars($_POST["score"]);
    //     $nrp = $_POST["nrp"];
    //     $query = "UPDATE submissions SET score = '$score', status = 'rejected' WHERE assignment_id = '$id' AND user_nrp = '$nrp'";
    //     mysqli_query($connect, $query);
    //     header("Location: valuationDetail.php?id=$id");
    // }
    // die();

    $listTask = query("SELECT * FROM assignments WHERE id = '$id'");
    // var_dump($listSubmission);
    // die();
    
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Valuation Task Detail</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="sidebar-brand-text mx-3">E-Learning <sup>PENS</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Activity
            </div>

            <!-- Nav Item - Tasks -->
            <li class="nav-item">
                <a class="nav-link" href="assignment.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Tasks</span>
                </a>
            </li>
            
            <!-- Nav Item - Charts -->
            <li class="nav-item active">
                <a class="nav-link" href="valuation.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Valuation</span>
                </a>
            </li>
            

            <!-- Nav Item - Tables -->
            <!-- <li class="nav-item">
                <a class="nav-link" href="tables.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span>
                </a>
            </li> -->

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user["name"]; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Valuations</h1>
                        <a href="valuation.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Kembali</a>
                    </div>
                    
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Task List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php if($listSubmission !== null) {?>
                                    <?php $i = 1; ?>
                                    <?php foreach ($listSubmission as $row) : ?>
                                    <?php $nrp = $row["user_nrp"];?>
                                    <?php $id = $row["id"];?> 
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <form action="" method="POST">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <?= $row["name"]; ?>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <input type="hidden" name="user_nrp" value="<?= $nrp?>">
                                                <button style="float: right; margin: 0.1rem;" type="submit" name="downloadSubmission" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                <button style="float: right; margin: 0.1rem;" type="submit" class="btn btn-secondary btn-sm" name="viewSubmission">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tittle</th>
                                                <th>Description</th>
                                                <th>Message</th>
                                                <th>Score</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                    <tr>
                                                        <td><?= $i; ?></td>
                                                        <td><?= $row["title"]; ?></td>
                                                        <td><?= $row["description"]; ?></td>
                                                        <td><?= $row["message"]; ?></td>
                                                        <?php $row["score"] = (float)$row["score"] ?>
                                                        <?php if($row["score"] == 0) {?>
                                                            <td align="center" style="width: 8rem;">
                                                                <input type="number" class="form-control" name="score" id="score" placeholder="Ex: 90">
                                                            </td>
                                                        <?php } else { ?>
                                                            <td><?= $row["score"]; ?></td>
                                                        <?php } ?>
                                                        <td>
                                                            <?= date('D, d - M', strtotime($row["updated_at"])); ?>
                                                            <strong>at</strong>
                                                            <?= date('h:i', strtotime($row["updated_at"])); ?>
                                                        </td>
                                                        <?php if($row["score"] == 0) {?>
                                                            <td class="col-xs-1" align="center">
                                                                <input type="hidden" name="user_nrp" value="<?= $nrp?>">
                                                                <input type="hidden" name="id" value="<?= $id; ?>">
                                                                <button type="submit" name="status" class="btn btn-success" value="approved">Approved</button>
                                                                <button type="submit" name="status" class="btn btn-danger" value="rejected">Rejected</button>
                                                            </td>
                                                        <?php } else { ?>
                                                            <?php if($row["status"] == "approved") {?>
                                                                <td class="col-xs-1" align="center">
                                                                    <button class="btn btn-success">Graded</button>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td class="col-xs-1" align="center">
                                                                    <button class="btn btn-danger">Graded</button>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </tr>
                                                    
                                                </tbody>
                                            </form>
                                        </table>
                                        <?php $i++; ?>
                                <?php endforeach ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../Authentication/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

</body>

</html>