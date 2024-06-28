<?php
$redMessage = '';
$greenMessage = '';
$found_files = null;


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mask = $_POST['file_mask']; // Маска файлов
        $search_text = $_POST['search_text']; // Текст для поиска

        // Выбранный диск (из выпадающего списка)
        $selected_disk = $_POST['logical_disks'];

        // Формируем путь к директории на выбранном диске
        $directory_path = "$selected_disk/test/";

        // Получаем список файлов по маске
        $found_files = glob($directory_path . $mask);
        if (!$found_files) $redMessage = 'No "'.$directory_path.$mask.'" files were found';
            else $greenMessage = count($found_files).' files were found';

        // Если указан текст для поиска, ищем его в файлах
        if (!empty($search_text)) {
            foreach ($found_files as $file) {
                $file_content = file_get_contents($file);
                if (strpos($file_content, $search_text) !== false) {
                    $files[] = array($file, strpos($file_content, $search_text));
                }
            }
            $found_files = $files;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File system</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .error { color = red;}
        .info {color = green;}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top box-shadow">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbarResponsive">
        <form action="" method="post">
            <label for="file_mask">Маска файлов:</label>
            <input type="text" class="form-control" name="file_mask" id="file_mask" placeholder="* или ?">
            <label class="info">* - Matches zero or more characters</label><br>
            <label class="info">? - Matches exactly one character (any character)</label><br>
            <label for="search_text">Текст для поиска:</label>
            <input type="text" class="form-control" name="search_text" id="search_text"><br>
            <label for="logical_disks">Имена дисков:</label>
            <select name="logical_disks" id="logical_disks">
                <?php
                    for($i='A'; $i<='Z'; $i++ ){
                        if(@diskfreespace($i.":")) {
                            $disk = $i.":";
                            echo '<option value=' . $disk . '>' . $disk . '</option>';
                        }
                    }
                    ?>
            </select>
            <br>
            <input type="submit" class="btn btn-primary" value="Найти">
            <br>
            <label class="error"><?php echo $redMessage;?></label>
        </form>
        </div>
    </div>

    <div class="">
            <?php
                // Выводим список найденных файлов

                    if ($found_files != null){
                        if (!empty($search_text)) {
                                foreach ($found_files as $file)
                                    {
                                        echo '<div>
                                        <label>String "'.$search_text.'" found in '.$file[0].' at '.$file[1].' position</label>
                                        </div>';
                                    }
                        } else {
                            foreach ($found_files as $file) {
                                echo '<div>
                                        <label>' . $file . '</label>
                                        </div>';
                            }
                        }
                    }
            ?>
    </div>
</nav>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>

