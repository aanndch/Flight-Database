<?php include 'logic.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="index.css">
    <title>Flight Schedule</title>
</head>

<body>
    <div class="jumbotron container">
        <h1 class="display-4 text-center">Flight Details</h1>
        <?php if($msg != ''): ?>
        <div class="alert <?php echo $msgClass; ?>">
        <?php echo $msg; ?>
        </div>
        <?php endif; ?>
        <form method="POST" action="index.php">
            <div class="form-group">
                <label>ID</label>
                <input name="id" class="form-control" placeholder="AMS" required>
            </div>
            <div class="form-group">
                <label>From Airport Code</label>
                <input name="fromCode" class="form-control" placeholder="BLR" required>
            </div>
            <div class="form-group">
                <label>To Airport Code</label>
                <input name="toCode" class="form-control" placeholder="MUM" required>
            </div>
            <div class="form-group">
                <label>Flight Number</label>
                <input name="flightNo" class="form-control" placeholder="FL-BLR" required>
            </div>
            <div class="form-group">
                <label>Depart Time</label>
                <input type="time" name="departTime" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Arrival Time</label>
                <input type="time" name="arrivalTime" class="form-control" required>
            </div>
            <br>
            <button type="submit" name="submit" class="btn btn-dark">Submit</button>
        </form>
    </div>
</body>

</html>