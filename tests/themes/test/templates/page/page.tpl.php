<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title ?></title>
    <?php echo $this->getPageStyles() ?>
</head>

<body>
    <?php echo $content ?>
    <?php echo $this->getPageScripts() ?>
</body>

</html>
