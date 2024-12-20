<?php global $connection, $limit;
include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>My Expenses</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <style>
        table {
            width: 100%;
            /*border-collapse: collapse;*/
        }

        /*th,*/
        /*td {*/
        /*    padding: 8px;*/
        /*    border: 1px solid #ddd;*/
        /*}*/
        @media screen and (max-width: 600px) {
            table{
                padding: 34px 0;
            }
            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                margin-bottom: 20px;
                border: 1px solid #ddd;
            }

            td {
                border: none;
                position: relative;
                padding-left: 50%;
            }

            td:before {
                margin-right: 50px;
                content: attr(data-label);
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add new record</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="expense_action.php?action=insert" method="post">
             <div class="modal-body">
                 <div class="my-3">
                     <label>Name:</label>
                     <input type="text" name="name" class="form-control">
                 </div>

                 <div class="my-3">
                     <label>Price:</label>
                     <input type="number" name="price" class="form-control">
                 </div>
                 <div class="my-3">
                     <label>Date:</label>
                     <input type="date" name="date" class="form-control">
                 </div>
                 <div class="my-3">
                     <label>Select Category:</label>
                     <?= make_select(get_categories(),'category','','class="form-control"') ?>
                 </div>
             </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit record</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="expense_action.php?action=update" method="post">
                <div class="modal-body">
                    <div class="my-3">
                        <label>Name:</label>
                        <input type="hidden" id="id" name="id">
                        <input type="text" id="name" name="name" class="form-control">
                    </div>

                    <div class="my-3">
                        <label>Price:</label>
                        <input type="number" id="price" name="price" class="form-control">
                    </div>
                    <div class="my-3">
                        <label>Date:</label>
                        <input type="date" id="date" name="date" class="form-control">
                    </div>
                    <div class="my-3">
                        <label>Select Category:</label>
                        <?= make_select(get_categories(),'category','','class="form-control" id="category-select"') ?>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit"  class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>




<div class="container">
    <div id="one-month">
   <div class="m-3">
       <h3 class="text-center">My Monthly Expenses</h3>
   </div>
        <div style="margin-bottom: 5rem">
            <button type="button" class="btn btn-primary mx-2 " style="float: right;"  data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
            <button class="btn btn-warning" onclick="all_mon()" style="float: right;">Months total expenses</button>
            <button type="button" class="btn btn-success mx-2 " style="float: right;"  data-bs-toggle="modal" data-bs-target="#show-total">Total</button>
        </div>
   <div class="my-2 ">

       <table id="myTable" class="table table-hover myTable">
           <thead>
           <tr>
               <th>No.</th>
               <th>Name</th>
               <th>Category</th>
               <th>Price</th>
               <th>Date</th>
               <th>Actions</th>
           </tr>
           </thead>
           <tbody>
           <?php
            $sql=mysqli_query($connection,"select e.id,e.category_id,e.price,e.date,e.name as e_name,c.name as c_name from expenses e left join categories c on e.category_id=c.id WHERE e.date BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND LAST_DAY(NOW()) ORDER BY e.date");
            $num=1;
            $total_by_category=[];
            $total=0;
            $total_extra=0;
            while ($row=mysqli_fetch_array($sql)):
                if ($row['c_name']=='' || $row['c_name']==null) $row['c_name']='Other';

           ?>
           <tr>
               <td data-label="No."><?= $num ?></td>
               <td data-label="Name:"><?= $row['e_name'] ?></td>
               <td data-label="Category:"><?= $row['c_name'] ?></td>
               <td data-label="Price:"><?= $row['price'] ?></td>
               <td data-label="Date:"><?= date('D, d M Y',strtotime($row['date'])) ?></td>
               <td data-label="Actions:">
                   <a href="expense_action.php?action=insert&name=<?= $row['e_name'] ?>&price=<?= $row['price'] ?>&category=<?= $row['category_id'] ?>" class="btn btn-warning">Copy</a>
                   <button type="button" class="btn btn-info" onclick="editRecord(<?= $row['id'] ?>,this)" >Edit</button>
                   <a onclick=" confirm('Are you sure that you want to delete the item <?= $row['e_name'] ?> ?')? href='expense_action.php?action=delete&&id=<?= $row['id'] ?>':''" class="btn btn-danger">Delete</a>
               </td>

           </tr>
           <?php
                $total +=$row['price'];
                $total_by_category[$row['c_name']]+=$row['price'];

                $num++;
            endwhile;
            $limit_class=array_sum($total_by_category)>$limit?'class="text-danger"':'';
            if(!empty($total_by_category)) $total_by_category['<b>Total</b>']='<b '.$limit_class.'>'.array_sum($total_by_category).'</b>';
           ?>


           </tbody>
       </table>

   </div>
    <div>

    </div>
        <div class="modal fade" id="show-total" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Total Expenses of <?= date('F Y') ?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <?php foreach ($total_by_category as $key=>$value): ?>
                            <div class="col-6 my-2"><?= $key ?>:</div>
                            <div class="col-6 my-2"><?= $value ?>/-</div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="all-months">
        <div class="m-3">
            <h3 class="text-center">All Monthly Expenses</h3>
        </div>
        <button class="btn btn-primary mb-2" onclick="one_mon()" style="float: right; ">Back</button>
        <?php
        $sql2 = "
    SELECT 
        DATE_FORMAT(date, '%Y-%m') AS month,
        SUM(price) AS total_expense
    FROM 
        expenses
    GROUP BY 
        month
    ORDER BY 
        month;
";


        $result2 = $connection->query($sql2);

        if ($result2->num_rows > 0) {
            echo "<table class='table table-hover myTable'><thead><tr><th>Month</th><th>Total Expense</th></tr></thead><tbody>";

            $price = 0;
            $extra = 0;
            while ($row2 = $result2->fetch_assoc()) {
                $text_danger=$row2['total_expense']>=$limit? 'text-danger':'';
                echo "<tr><td>" . date('M Y',strtotime($row2["month"])) . "</td><td class='".$text_danger."'>" . $row2['total_expense'] . "/-</td></tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "0 results";
        }
        ?>

    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script>

        function editRecord(expense_id,button) {
            $(button).html('<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span> </div>');
            $.ajax({
                url:"expense_action.php?action=edit",
                type:"post",
                data:{expense_id:expense_id},
                success:function (data) {
                    let editData = JSON.parse(data);
                    // let extra=$('#extra');
                    $('#id').val(editData.id);
                    $('#name').val(editData.name);
                    $('#price').val(editData.price);
                    $('#date').val(editData.date);
                    let category_id=editData.category_id;

                    let select = $('#category-select');
                    select.find('option').each(function () {
                        if ($(this).val() == category_id) {
                            $(this).prop('selected', true);
                            return false;
                        }
                    });
                    let myModal = new bootstrap.Modal(document.getElementById('editModal'), {
                        keyboard: false,
                        backdrop: 'static'
                    });
                    myModal.show();
                    $(button).html('Edit');
                },
                error: function (error) {
                    console.log('Error: '+error);
                }
            });
        }
        let all_month=$('#all-months');
        let one_month=$('#one-month');
         all_month.hide();
        function all_mon() {
            one_month.hide();
            all_month.show();
        }
        function one_mon() {
            one_month.show();
            all_month.hide();
        }
         $(document).ready( function () {
             $('.myTable').DataTable({
                 "order": [[0, "desc"]]

             });
         } );

</script>
</html>
