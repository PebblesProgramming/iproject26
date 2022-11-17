<?php
session_start();

require_once __DIR__ . "/../classes/Product.php";
require_once __DIR__ . "/../classes/Offer.php";

?>

<head>
  <title>Verkopen</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- <div class="row"> -->
  </nav>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/forms.css">
  <link rel="stylesheet" href="../css/nav.css">
  <link rel="stylesheet" href="../css/main.css">

</head>

<body>
  <?php require_once '../componenten/navigationbar.php' ?> 



<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>


  <br>
  <div class="container">
                <br>
                <br>
            <div class="page-header">
                        <h2>Voeg een Voorwerp toe</h2>
                    </div>
                    <p>Vul alle onderstaande data in om het voorwerp toe te voegen aan de verkoop</p>
                    <br>
                    <label>Upload een foto voor je voorwerp</label>
                    <br>
                    <form action="" method="POST" enctype="multipart/form-data">      
                        <input type="file" name="image" />
                        <input type="submit"/>
                    </form>
                    <br>
                    <?php
                        if(isset($_FILES['image'])){
                            $errors= array();
                            $file_name = $_FILES['image']['name'];
                            $file_size = $_FILES['image']['size'];
                            $file_tmp = $_FILES['image']['tmp_name'];
                            $file_type = $_FILES['image']['type'];
                            $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
                            
                            $extensions= array("jpeg","jpg","png");
                            
                            if(in_array($file_ext,$extensions)=== false){
                                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                            }
                            
                            if($file_size > 2097152) {
                                $errors[]='File size must be excately 2 MB';
                            }
                            
                            if(empty($errors)==true) {
                                move_uploaded_file($file_tmp,"images/".$file_name);
                                echo "Success";
                            }else{
                                print_r($errors);
                            }
                        }
                        ?>
                    <form action="verkopersPagina.php" method="post">
                        <div class="form-group">
                            <label>Titel</label>
                            <input type="text" name="titel" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Beschrijving</label>
                            <input type="text" name="beschrijving" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Startprijs</label>
                            <input type="number" name="startprijs" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Betalingswijze</label>
                            <input type="text" name="betalingswijze" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Plaats</label>
                            <input type="text" name="plaats" class="form-control">
                        </div>
                        <?php
                    function genereerLandenDropDown()
                    {
                        require_once(__DIR__ ."/../classes/Land.php");
                        $landen = new Land();

                        $landen = $landen->all();
                        foreach ($landen??[] as $land) {
                            echo ("<option value=\"$land->landnaam\">$land->landnaam</option>");
                        }
                    }
                    ?>

                    <div class="form-group">
                        <label for="land">Land</label>
                        <select class="form-control" id='land' name='land'>
                            <?php
                            echo genereerLandenDropDown() ?>
                        </select>
                    </div>
                        <div class="form-group">
                            <label>Begin Looptijd</label>
                            <input type="date" name="begin_looptijd" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Einde Looptijd</label>
                            <input type="date" name="einde_looptijd" class="form-control" >
                        </div>
                       
                        <input type="submit" class="btn btn-danger" name="submit" value="Submit">
                        <?php
                            if(isset($_POST['submit']))
                            {
                                $titel=$_POST['titel'];
                                $beschrijving =$_POST['beschrijving'];
                                $startprijs = $_POST['startprijs'];
                                $betalingswijze = $_POST['betalingswijze'];
                                $plaats = $_POST['plaats'];
                                $land = $_POST['land'];
                                $begin_looptijd = $_POST['begin_looptijd'];
                                $einde_looptijd = $_POST['einde_looptijd'];
                                $sql = "INSERT INTO Voorwerp()
                                VALUES('$titel','$beschrijving','$startprijs','$betalingswijze','$plaats','$land','$begin_looptijd','$einde_looptijd')";
                            
                            }
                        ?>
                    </form>
                    <br>
                </div>


           


            </body>
            
                <?php
                require '../componenten/footer.php';
                ?>
            </html>