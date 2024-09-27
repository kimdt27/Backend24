<html>
<body>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="imgfile">
    <input type="submit" name="submit">
</form>
</body>
</html>
<?php
if (isset($_POST['submit'])){
    if(($_FILES['imgfile']['type']=="image/jpeg" ||
            $_FILES['imgfile']['type']=="image/pjpeg" ||
            $_FILES['imgfile']['type']=="image/gif" ||
            $_FILES['imgfile']['type']=="image/jpg")&& (
            $_FILES['imgfile']['size']< 3000000
        )){
        if ($_FILES['imgfile']['error']>0){
            echo "Error: ". $_FILES['imgfile']['error'];
        }else{
            echo "Name: ".$_FILES['imgfile']['name']."<br>";
            echo "Type: ".$_FILES['imgfile']['type']."<br>";
            echo "Size: ".($_FILES['imgfile']['size']/1024)."<br>";
            echo "Tmp_name: ".$_FILES['imgfile']['tmp_name']."<br>";

            if (file_exists("img/".$_FILES['imgfile']['name'])){
                echo "can't upload: ". $_FILES['imgfile']['name']. " Exists";
            } else {
                // Move uploaded file to the "upload" directory
                move_uploaded_file($_FILES['imgfile']['tmp_name'], "img/" . $_FILES['imgfile']['name']);
                echo "Stored in: img/" . $_FILES['imgfile']['name'] . "<br>";
                try {
                    $dsn = 'mysql:host=localhost;dbname=image;charset=utf8';
                    $username = 'kim';
                    $password = '123456';
                    $con = new PDO($dsn, $username, $password);

                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "INSERT INTO img (filename) VALUES (:filename)";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':filename', $_FILES['imgfile']['name']);
                    if($stmt->execute()){
                        echo "good job!!!!";
                    }
                    else{
                        echo "NAAAAAAAAAA!!!!!!";
                    }
                }catch (PDOException $e){
                    echo $e->getMessage();
                }
                $con = null;

            }
        }
    } else {
        echo "Invalid file type or size too large.";
    }
}