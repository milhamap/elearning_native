<?php
    
    function viewSubmission () {
        $id = $_GET['id'];
        $nrp = $_POST["user_nrp"];
        var_dump($nrp);
        die();
        $listUpdate = query("SELECT * FROM submissions as sub JOIN students as std ON sub.user_nrp = std.nrp WHERE sub.assignment_id = '$id' AND ")[0];

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
        // var_dump($id);
        // die();
        $listUpdate = query("SELECT * FROM submissions WHERE assignment_id = '$id'")[0];
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