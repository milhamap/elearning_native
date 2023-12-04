<?php
    $connect = mysqli_connect("localhost", "root", "", "db-elearning");
    
    function query ($query) {
        global $connect;
        $result = mysqli_query($connect, $query);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function uploadFile () {
        $submissionFile = $_FILES['submissionFile'];
        $submissionFileName = $submissionFile['name'];
        $submissionFileSize = $submissionFile['size'];
        $submissionFileError = $submissionFile['error'];
        $submissionTmpName = $submissionFile['tmp_name'];
        // var_dump($submissionFile);
        // die();

        // cek apakah tidak ada file yang diupload
        if( $submissionFileError === 4 ) {
            echo "<script>
                    alert('Pilih gambar terlebih dahulu!');
                    document.location.href = 'submission.php';
                </script>";
            return false;
        }

        // cek apakah yang diupload adalah file
        $ekstensiValid = ['jpg', 'jpeg', 'doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx'];
        $ekstensiFile = explode('.', $submissionFileName);
        $ekstensiFile = strtolower(end($ekstensiFile));

        // cek ekstensi file
        if ( !in_array ($ekstensiFile, $ekstensiValid) ) {
            echo 
                "<script>
                    alert ('Yang anda upload bukan file!');      
                </script>
                ";
            return false;
        }

        // cek ukuran file yang boleh di upload
        if ( $submissionFileSize > 20971520 ) {
            echo 
                "<script>
                    alert ('Ukuran file anda terlalu besar!');      
                </script>
                ";
            return false;
        }

        // generate nama file baru untu mencegah file dengan nama sama
        $submissionNewNameFile = $submissionFileName;

        move_uploaded_file($submissionTmpName , '../submissions/'. $submissionNewNameFile);

        return $submissionNewNameFile;
    }

    function addSubmission ($data) {
        global $connect;

        $submissionDescription = htmlspecialchars($data['submissionDescription']);

        $submissionFile = uploadFile();
        if( !$submissionFile )
            return false;
        $nrp = $_SESSION["nrp"];
        $id = $_GET["id"];
        // $score = $_SESSION["score"];
        // $status = $_SESSION["status"];
        // $id = $_SESSION["id"];
        // var_dump($id);
        // die();
        $dateNow = date('Y-m-d H:i:s');
        $query = "INSERT INTO submissions(assignment_id, user_nrp, file, message, created_at, updated_at)
                    VALUES
                ('$id', '$nrp', '$submissionFile', '$submissionDescription', '$dateNow', '$dateNow')";

        mysqli_query($connect, $query);
        return mysqli_affected_rows($connect);
    }

    function updateSubmission ($data) {
        global $connect;

        $id = $_POST["submissionId"];
        $listUpdate = query("SELECT file FROM submissions WHERE id = '$id'")[0];
        // var_dump($listUpdate);
        // die();

        $submissionNewDescription = htmlspecialchars($data['submissionDescription']);
        $submissionNewFile = $_FILES['submissionFile'];
        $submissionNewFileName = $submissionNewFile['name'];
        $submissionNewFileSize = $submissionNewFile['size'];
        $submissionNewFileError = $submissionNewFile['error'];
        $submissionNewTmpName = $submissionNewFile['tmp_name'];
        // var_dump($submissionNewFile);
        // die();

        // cek apakah yang diupload adalah file
        $ekstensiValid = ['jpg', 'jpeg', 'doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx'];
        $ekstensiFile = explode('.', $submissionNewFileName);
        $ekstensiFile = strtolower(end($ekstensiFile));

        // cek ukuran file yang boleh di upload
        if ( $submissionNewFileSize > 20971520 ) {
            echo 
                "<script>
                    alert ('Ukuran file anda terlalu besar!');      
                </script>
                ";
            return false;
        }

        // generate nama file baru untu mencegah file dengan nama sama
        $submissionNewFileName = $submissionNewFileName;

        $dateNow = date('Y-m-d H:i:s');
        if($submissionNewFileError === 0) {
            unlink("../submissions/" . $listUpdate['file']);
            move_uploaded_file($submissionNewTmpName , '../submissions/'. $submissionNewFileName);

            if ( !in_array ($ekstensiFile, $ekstensiValid) ) {
                echo 
                    "<script>
                        alert ('Yang anda upload bukan file!');      
                    </script>
                    ";
                return false;
            } else {
                $query = "UPDATE submissions SET
                            message = '$submissionNewDescription',
                            file = '$submissionNewFileName',
                            updated_at = '$dateNow'
                            WHERE id = '$id'
                        ";
            }
        } else {
            $query = "UPDATE submissions SET
                        message = '$submissionNewDescription',
                        updated_at = '$dateNow'
                        WHERE id = '$id'
                    ";
        }
        mysqli_query($connect, $query);
        // var_dump(mysqli_query($connect, $query));
        // die();
        return mysqli_affected_rows($connect);
    }

    function viewAssignment () {
        $id = $_GET['id'];
        // var_dump($id);
        // die();
        $listUpdate = query("SELECT * FROM assignments WHERE id = '$id'")[0];
        $file = $listUpdate['file'];
        // var_dump($file);
        // die(); 
        header("Content-type: application/pdf");
        // header("Content-Disposition: inline; filename='$file'");
        // header("Content-Transfer-Encoding: binary");
        // header("Content-Length: " . filesize("../assignments/$file"));
        // header("Accept-Ranges: bytes");
        readfile("../assignments/$file");
    }

    function downloadAssignment () {
        $id = $_GET['id'];
        $listUpdate = query("SELECT * FROM assignments WHERE id = '$id'")[0];
        $file = $listUpdate['file'];
        // var_dump($file);
        // die(); 
        header("Content-Disposition: File Transfer");
        header("Content-Disposition: attachment; filename=$file");
        header("Content-Type: application/octet-stream");
        // header("Content-Transfer-Encoding: binary");
        // header("Content-Length: " . filesize("../assignments/$file"));
        // header("Accept-Ranges: bytes");
        readfile("../assignments/$file");
        exit;
    }

    function viewSubmission () {
        $id = $_GET['id'];
        $nrp = $_SESSION["nrp"];
        // var_dump($nrp);
        // die();
        // var_dump($id);
        // die();
        $listUpdate = query("SELECT * FROM submissions WHERE assignment_id = '$id' AND user_nrp = '$nrp'")[0];
        $file = $listUpdate['file'];
        // var_dump($file);
        // die(); 
        header("Content-type: application/pdf");
        // header("Content-Disposition: inline; filename='$file'");
        // header("Content-Transfer-Encoding: binary");
        // header("Content-Length: " . filesize("../assignments/$file"));
        // header("Accept-Ranges: bytes");
        readfile("../submissions/$file");
    }

    function downloadSubmission () {
        $id = $_GET['id'];
        $nrp = $_SESSION["nrp"];
        // var_dump($id);
        // die();
        $listUpdate = query("SELECT * FROM submissions WHERE assignment_id = '$id' AND user_nrp = '$nrp'")[0];
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
?>