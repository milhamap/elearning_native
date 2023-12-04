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

    function registrationStudents ($data) {
        global $connect;

        $firstName = mysqli_escape_string($connect ,$data["firstName"]);
        $lastName = mysqli_escape_string($connect ,$data["lastName"]);
        $nrp = strtolower(stripcslashes($data["nrp"]));
        $email = strtolower(stripcslashes($data["email"]));
        $password = mysqli_real_escape_string($connect, $data["password"]);
        $confirm = mysqli_real_escape_string($connect, $data['confirm']);

        //cek apakah nrp sudah ada atau belum
        $query = mysqli_query($connect, "SELECT email FROM students WHERE email = '$email'");

        if(mysqli_fetch_assoc($query)) {
            echo "<script>
                    alert('Email sudah terdaftar!');
                  </script>";
            return false;
        }

        //cek konfirmasi password
        if($password !== $confirm) {
            echo "<script>
                    alert('Konfirmasi password tidak sama!');
                  </script>";
            return false;
        } else
            echo mysqli_error($connect);
    
        //enkripsi password
        $password = password_hash($password, PASSWORD_DEFAULT);

        //tambahkan user baru ke database
        mysqli_query($connect, "INSERT INTO students (name, nrp, email, password) VALUES (CONCAT('$firstName', ' ', '$lastName'), '$nrp', '$email', '$password')");
        
        return mysqli_affected_rows($connect);
    }

    function registrationLecture ($data) {
        global $connect;
        $firstName = mysqli_escape_string($connect ,$data["firstName"]);
        $lastName = mysqli_escape_string($connect ,$data["lastName"]);
        $nip = strtolower(stripcslashes($data["nip"]));
        $email = strtolower(stripcslashes($data["email"]));
        $password = mysqli_real_escape_string($connect, $data["password"]);
        $confirm = mysqli_real_escape_string($connect, $data['confirm']);

        //cek apakah nip sudah ada atau belum
        $query = mysqli_query($connect, "SELECT email FROM lectures WHERE email = '$email'");

        if(mysqli_fetch_assoc($query)) {
            echo "<script>
                    alert('Email sudah terdaftar!');
                  </script>";
            return false;
        }

        //cek konfirmasi password
        if($password !== $confirm) {
            echo "<script>
                    alert('Konfirmasi password tidak sama!');
                  </script>";
            return false;
        } else
            echo mysqli_error($connect);

        //enkripsi password
        $password = password_hash($password, PASSWORD_DEFAULT);

        //tambahkan user baru ke database
        mysqli_query($connect, "INSERT INTO lectures (name, nip, email, password) VALUES (CONCAT('$firstName', ' ', '$lastName'), '$nip', '$email', '$password')");

        return mysqli_affected_rows($connect);
    }
?>