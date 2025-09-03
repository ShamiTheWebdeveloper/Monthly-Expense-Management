<?php
include 'db.php';
$connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if ($connection) {

}else{
    echo 'Error:'.$connection->connect_error;
    exit();
}
$limit=50000;
define('ACTION_FILE','expense_action.php');
date_default_timezone_set('Asia/Karachi');
error_reporting(0);

function get_categories($id=0): array
{
    global $connection;
    $id_sql='';
    if ($id!=0) $id_sql=" and id=".$id;
    $query = "SELECT id,name FROM categories where status=1".$id_sql;
    $result=$connection->query($query);
    $output=[];
    foreach($result as $row) $output[$row['id']]=$row['name'];
    return $output;
}

function make_select($options,$name,$default='',$parameters='',$first_selected='Please select'): false|string
{
    ob_start();
    ?>
    <select name="<?= $name ?>" <?= $parameters ?>>
        <?php if ($first_selected!=''): ?>
        <option <?= $default==''?'selected':''; ?> ><?= $first_selected ?></option>
        <?php endif; ?>
        <?php foreach ($options as $key=>$option): ?>
        <option value="<?= $key ?>" <?php if ($default!='') echo $default===$key?"selected":""; ?>><?= $option ?></option>
        <?php endforeach; ?>
    </select>
    <?php
    $output=ob_get_contents();
    ob_end_clean();
    return $output;
}
