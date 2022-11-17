<!doctype html>
<html lang="en">

<head>
    <title>Nieuwe veiling</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/maakVeilingAan.css">
</head>

<body>

    <?php require_once __DIR__ . "/../componenten/navigationbar.php" ?>

    <div class="container">
        <div class="card my-4 mx-5">
            <div class="card-body">
                <form>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-12 col-md-6">
                            <label for="titel">Titel</label>
                            <input type="text" class="form-control" name="titel" id="titel" placeholder="titel">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-12 col-md-6">
                            <label for="beschrijving">Beschrijving</label>
                            <input type="text" class="form-control" name="beschrijving" id="beschrijving" placeholder="beschrijving">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-12 col-md-6">
                            <label for="startprijs">Startprijs</label>
                            <input type="text" class="form-control" name="startprijs" id="startprijs" placeholder="startprijs">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-12 col-md-6">
                            <label for="betalingswijze">Betalingswijze</label>
                            <input type="text" class="form-control" name="betalingswijze" id="betalingswijze" placeholder="betalingswijze">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-12 col-md-6">
                            <label for="betalingsinstructie">Betalingsinstructie</label>
                            <input type="text" class="form-control" name="betalingsinstructie" id="betalingsinstructie" placeholder="betalingsinstructie">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-12 col-md-6">
                            <label for="plaats">Plaats</label>
                            <input type="text" class="form-control" name="plaats" id="plaats" placeholder="plaats">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-12 col-md-6">
                            <label for="land">Land</label>
                            <input type="text" class="form-control" name="land" id="land" placeholder="land">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-12 col-md-6">
                            <label for="looptijdbegindag">Looptijd begin dag</label>
                            <input type="text" class="form-control" name="looptijdbegindag" id="looptijdbegindag" placeholder="yyyy-mm-dd">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-12 col-md-6">
                            <label for="looptijdbegintijdstip">Looptijd begin tijdstip</label>
                            <input type="text" class="form-control" name="looptijdbegintijdstip" id="looptijdbegintijdstip" placeholder="hh:mm:ss">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    require '../componenten/footer.php';
    ?>
</body>

</html>