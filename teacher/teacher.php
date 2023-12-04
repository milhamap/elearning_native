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
        $assignmentFile = $_FILES['assignmentFile'];
        $assignmentFileName = $assignmentFile['name'];
        $assignmentFileSize = $assignmentFile['size'];
        $assignmentFileError = $assignmentFile['error'];
        $assignmentTmpName = $assignmentFile['tmp_name'];
        // var_dump($assignmentFile);
        // die();

        // cek apakah tidak ada file yang diupload
        if( $assignmentFileError === 4 ) {
            echo "<script>
                    alert('Pilih gambar terlebih dahulu!');
                    document.location.href = 'assignment.php';
                </script>";
            return false;
        }

        // cek apakah yang diupload adalah file
        $ekstensiValid = ['jpg', 'jpeg', 'doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx'];
        $ekstensiFile = explode('.', $assignmentFileName);
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
        if ( $assignmentFileSize > 20971520 ) {
            echo 
                "<script>
                    alert ('Ukuran file anda terlalu besar!');      
                </script>
                ";
            return false;
        }

        // generate nama file baru untu mencegah file dengan nama sama
        $assignmentNewNameFile = $assignmentFileName;

        move_uploaded_file($assignmentTmpName , '../assignments/'. $assignmentNewNameFile);

        return $assignmentNewNameFile;
    }

    function addAssignment ($data) {
        global $connect;

        // $nip = htmlspecialchars($data["nip"]);
        $assignmentTittle = htmlspecialchars($data['assignmentTittle']);
        $assignmentDescription = htmlspecialchars($data['assignmentDescription']);
        $assignmentDeadline = htmlspecialchars($data['assignmentDeadline']);
        
        // $row = query("SELECT * FROM lectures WHERE nip = '$_SESSION[nip]'")[0];
        // var_dump($row);
        // die();

        $assignmentFile = uploadFile();
        if(!$assignmentFile)
            return false;
        
        $nip = $_SESSION['nip'];
        // var_dump($_SESSION);
        // die();
        $dateNow = date('Y-m-d H:i:s');
        $query = "INSERT INTO assignments
                    VALUES
                ('', '$nip', '$assignmentTittle', '$assignmentDescription', '$assignmentFile', '$assignmentDeadline', '$dateNow', '$dateNow')";
        mysqli_query($connect, $query);

        return mysqli_affected_rows($connect);
    }

    function viewAssignment () {
        $id = $_POST['id'];
        // var_dump($id);
        // die();
        $listUpdate = query("SELECT * FROM assignments WHERE id = '$id'")[0];
        $file = $listUpdate['file'];
        // var_dump($file);
        // die(); 
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename='$file'");
        // header("Content-Transfer-Encoding: binary");
        // header("Content-Length: " . filesize("../assignments/$file"));
        // header("Accept-Ranges: bytes");
        readfile("../assignments/$file");
    }

    function updateAssignment ($data) {
        global $connect;

        
        $id = $_POST['assignmentNewId'];
        $listUpdate = query("SELECT file FROM assignments WHERE id = '$id'")[0];
        // var_dump($listUpdate);
        // die();
        // var_dump($id);
        // die();
        $assignmentNewTittle = htmlspecialchars($data['assignmentNewTittle']);
        $assignmentNewDescription = htmlspecialchars($data['assignmentNewDescription']);
        $assignmentNewDeadline = htmlspecialchars($data['assignmentNewDeadline']);
        $dateNow = date('Y-m-d H:i:s');
        
        $assignmentNewFile = $_FILES['assignmentNewFile'];
        $assignmentNewFileName = $assignmentNewFile['name'];
        $assignmentNewFileSize = $assignmentNewFile['size'];
        $assignmentNewFileError = $assignmentNewFile['error'];
        $assignmentNewTmpName = $assignmentNewFile['tmp_name'];
        // var_dump($assignmentFile);
        // die();


        // cek apakah yang diupload adalah file
        $ekstensiValid = ['jpg', 'jpeg', 'doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx'];
        $ekstensiFile = explode('.', $assignmentNewFileName);
        $ekstensiFile = strtolower(end($ekstensiFile));

        // cek ukuran file yang boleh di upload
        if ( $assignmentNewFileSize > 20971520 ) {
            echo 
                "<script>
                    alert ('Ukuran file anda terlalu besar!');      
                </script>
                ";
            return false;
        }

        // generate nama file baru untu mencegah file dengan nama sama
        $assignmentNewNameFile = $assignmentNewFileName;

        // var_dump($assignmentNewFileError);
        // die();
        
        if($assignmentNewFileError === 0) {
            unlink("../assignments/" . $listUpdate['file']);
            move_uploaded_file($assignmentNewTmpName , '../assignments/'. $assignmentNewNameFile);
            // cek ekstensi file
            if ( !in_array ($ekstensiFile, $ekstensiValid) ) {
                echo 
                    "<script>
                        alert ('Yang anda upload bukan file!');      
                    </script>
                    ";
                return false;
            } else {
                $query = "UPDATE assignments SET
                            title = '$assignmentNewTittle',
                            description = '$assignmentNewDescription',
                            file = '$assignmentNewNameFile',
                            deadline = '$assignmentNewDeadline',
                            updated_at = '$dateNow'
                            WHERE id = '$id'
                        ";
            }
        } else {
            $query = "UPDATE assignments SET
                        title = '$assignmentNewTittle',
                        description = '$assignmentNewDescription',
                        deadline = '$assignmentNewDeadline',
                        updated_at = '$dateNow'
                        WHERE id = '$id'
                    ";
        }

        // var_dump($query);
        // die();
        mysqli_query($connect, $query);

        return mysqli_affected_rows($connect);
    }

    function deleteAssignment () {
        global $connect;

        $id = $_POST['id'];
        $listDelete = query("SELECT file FROM assignments WHERE id = '$id'")[0];
        unlink("../assignments/" . $listDelete['file']);
        // var_dump($id);
        // die();
        mysqli_query($connect, "DELETE FROM assignments WHERE id = '$id'");
        return mysqli_affected_rows($connect);
    }

?>