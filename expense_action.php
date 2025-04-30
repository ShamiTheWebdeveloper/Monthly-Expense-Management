<?php
global $connection;
include 'config.php';

    if (isset($_GET['action']) && $_GET['action'] != '') {
        switch ($_GET['action']) {
            case 'insert':
                $name=$_REQUEST['name'];
                $price=$_REQUEST['price'];
                $category_id=$_REQUEST['category'];
                $date=$_REQUEST['date']==''?date('Y-m-d'):$_REQUEST['date'];
             $sql=mysqli_query($connection, 'INSERT INTO `expenses` (`name`, `price`, `date`,`category_id`) VALUES ( "'.$name.'","'.$price.'" ,"'.$date.'",'.$category_id.')');
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
                $category_id=$_POST['category'];
                $sql=mysqli_query($connection,'UPDATE expenses SET `name` = "'.$name.'", `price` = "'.$price.'", date="'.$date.'", category_id='.$category_id.'  WHERE id='.$id);
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

            case 'query':
                if(isset($_GET['sql']) && $_GET['sql'] != ''){
//                    echo $_GET['sql'];
                    $sql=mysqli_query($connection,$_GET['sql']);
                    if($sql){
                        echo 'Successfully running query';
                    }else{
                        echo 'Query failed';
                    }
                }else{
                    echo 'Please enter query';
                }
            break;
            default:
                echo 'No action selected';
                break;
        }
    }

