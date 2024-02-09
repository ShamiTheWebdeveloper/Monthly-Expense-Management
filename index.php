<?php
$connection=mysqli_connect('localhost','root','','my_expense');
$limit=20000;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Expenses</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

</head>
<body>
<!-- Button trigger modal -->
<!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">-->
<!--    Launch demo modal-->
<!--</button>-->

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
                     <label>check if your expense is extra</label>
                     <label>
                         <input type="checkbox" name="extra" class="">
                     </label>
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
                        <label>check if your expense is extra</label>
                        <label>
                            <input type="checkbox" id="extra" name="extra" >
                        </label>
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
            <button class="btn btn-warning" onclick="all_mon()" style="float: right;">Months total expenses</button>
            <button type="button" class="btn btn-primary mx-2 " style="float: right;"  data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
        </div>
   <div class="my-2">

       <table id="" class="table table-hover myTable">
           <thead>
           <tr>
               <th>No.</th>
               <th>Name</th>
               <th>Price</th>
               <th>Date</th>
               <th>Actions</th>
           </tr>
           </thead>
           <tbody>
           <?php
            $sql=mysqli_query($connection,"select * from expenses WHERE date BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND LAST_DAY(NOW()) ORDER BY date");
            $num=1;
            $total=0;
            $total_extra=0;
            while ($row=mysqli_fetch_array($sql)){
           ?>
           <tr>
               <td><?= $num ?></td>
               <td><?= $row['name'] ?></td>
               <td class="<?= $row['extra']==1 ? 'text-danger':'' ?>"><?= $row['price'] ?></td>
               <td><?= date('D, d M Y',strtotime($row['date'])) ?></td>
               <td>
                   <button type="button" class="btn btn-info" onclick="editrecord(<?= $row['id'] ?>)" data-bs-toggle="modal"  data-bs-target="#editModal">Edit</button>
                   <a onclick=" confirm('Are you sure you want to delete?')? href='expense_action.php?action=delete&&id=<?= $row['id'] ?>':''" class="btn btn-danger">Delete</a>
               </td>

           </tr>
           <?php
                if ($row['extra']==1){
                    $total_extra +=$row['price'];
                }
                $total +=$row['price'];

                $num++;
            }
           ?>


           </tbody>
           <tr>
               <td colspan="2" class="text-center">
                   <b>Total Extra Expense:</b>
               </td>
               <td>
                   <b ><?= $total_extra ?>/-</b>
               </td>
           </tr>
           <tr>
               <td colspan="2" class="text-center">
                   <b >Total Expense: </b>
               </td>
               <td>
                   <b class="<?= $total>=$limit ? 'text-danger':'' ?>"> <?= $total ?>/-</b>
               </td>
           </tr>

       </table>

   </div>

    <div>

    </div>
        <div>

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
        SUM(CASE WHEN extra = 1 THEN price ELSE 0 END) AS extra_expense,
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
            echo "<table class='table table-hover myTable'><thead><tr><th>Month</th><th>Extra Expense</th><th>Total Expense</th></tr></thead><tbody>";

            $price = 0;
            $extra = 0;
            while ($row2 = $result2->fetch_assoc()) {
                $text_danger=$row2['total_expense']>=$limit? 'text-danger':'';
                echo "<tr><td>" . date('M Y',strtotime($row2["month"])) . "</td><td>" . $row2['extra_expense'] . "/-</td><td class='".$text_danger."'>" . $row2['total_expense'] . "/-</td></tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "0 results";
        }
        ?>

    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script>
         let expense_id;
        function editrecord(expense_id) {
            $.ajax({
                url:"expense_action.php?action=edit",
                type:"post",
                data:{expense_id:expense_id},
                success:function (data) {
                    let rowData = JSON.parse(data);
                    let extra=$('#extra');
                    $('#id').val(rowData.id);
                    $('#name').val(rowData.name);
                    $('#price').val(rowData.price);
                    $('#date').val(rowData.date);
                    rowData.extra == 1 ? extra.prop('checked', true) : extra.prop('checked', false);

                },
                error: function (error) {
                    alert('Error: '+error);
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
                 column:[0],
                 ignoreBOM:[0]
             });
         } );

</script>
</html>
