<?php
error_reporting(0);
$connection=mysqli_connect('localhost','root','','my_expense');
if ($connection) {
    if (isset($_GET['action'])) {

        $action = $_GET['action'];
        switch ($action) {
            case 'insert':
                $name=$_POST['name'];
                $price=$_POST['price'];
                $date=$_POST['date'];
                $extra=($_POST['extra']=='on')?1:0;
             $sql=mysqli_query($connection, 'INSERT INTO `expenses` (`name`, `price`, `date`,`extra`) VALUES ( "'.$name.'","'.$price.'" ,"'.$date.'","'.$extra.'")');
             if($sql){
                 echo '<script>window.location.href="index.php"</script>';
             }else{
                 echo 'Cannot inserted';
             }
             break;
            case 'edit':
              $ed_id=$_POST['expense_id'];
                $sql=mysqli_query($connection,'select * from expenses where id='.$ed_id);
                if ($sql){
                    $row=mysqli_fetch_array($sql);
                    echo json_encode($row);
                }else{
                    echo 'error';
                }
                break;
            case 'update':
                $id=$_POST['id'];
                $name=$_POST['name'];
                $price=$_POST['price'];
                $date=$_POST['date'];
                $extra=($_POST['extra']=='on')?1:0;
                $sql=mysqli_query($connection,'UPDATE expenses SET `name` = "'.$name.'", `price` = "'.$price.'", date="'.$date.'", extra="'.$extra.'"  WHERE id='.$id);
                if($sql){
                    echo '<script>window.location.href="index.php"</script>';
                }else{
                    echo 'Cannot Updated';
                }
                break;
            case 'delete':
            $id=$_GET['id'];
            $sql=mysqli_query($connection,'delete from expenses where id='.$id);
                if($sql){
                    echo '<script>window.location.href="index.php"</script>';
                }else{
                    echo 'Cannot Deleted';
                }
            break;
        }
    }
}else{
    echo 'Error:'.mysqli_connect_error();
}
