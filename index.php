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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        html, body {
            min-height: 100vh;
            font-family: 'Inter', Arial, sans-serif;
        }
        body {
            /* Animated vibrant gradient */
            background: linear-gradient(270deg, #f8fafc, #a1c4fd, #c2e9fb, #fbc2eb, #f8fafc);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            transition: background 0.5s;
        }
        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
        [data-bs-theme="dark"] body {
            /* Richer black/blue gradient for dark mode */
            background: linear-gradient(135deg, #18181f 0%, #232526 40%, #2c3e50 80%, #000000 100%);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        /* Glassmorphism card */
        .glass-card {
            background: rgba(255,255,255,0.25);
            border-radius: 1.2rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.18);
            transition: background 0.4s, box-shadow 0.4s;
        }
        [data-bs-theme="dark"] .glass-card {
            background: rgba(24,24,31,0.65);
            border: 1px solid rgba(44,62,80,0.28);
        }
        /* Glassmorphism modal */
        .glass-modal .modal-content {
            background: rgba(255,255,255,0.25);
            border-radius: 1.2rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.18);
        }
        [data-bs-theme="dark"] .glass-modal .modal-content {
            background: rgba(24,24,31,0.75);
            border: 1px solid rgba(44,62,80,0.28);
        }
        /* Glassy table */
        .glass-table {
            background: rgba(255,255,255,0.18);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 24px 0 rgba(31, 38, 135, 0.10);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,0.12);
        }
        [data-bs-theme="dark"] .glass-table {
            background: rgba(24,24,31,0.55);
            border: 1px solid rgba(44,62,80,0.18);
        }
        .glass-table th, .glass-table td {
            border: none !important;
        }
        .glass-table thead {
            background: rgba(255,255,255,0.35);
        }
        [data-bs-theme="dark"] .glass-table thead {
            background: rgba(44,62,80,0.35);
        }
        .glass-table tbody tr {
            transition: background 0.2s;
        }
        .glass-table tbody tr:hover {
            background: rgba(161,196,253,0.18) !important;
        }
        /* Modern buttons */
        .btn-gradient {
            background: linear-gradient(90deg, #a1c4fd 0%, #c2e9fb 100%);
            color: #222;
            border: none;
            border-radius: 2rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(161,196,253,0.18);
            transition: background 0.3s, color 0.3s, box-shadow 0.3s;
        }
        .btn-gradient:hover, .btn-gradient:focus {
            background: linear-gradient(90deg, #fbc2eb 0%, #a6c1ee 100%);
            color: #111;
            box-shadow: 0 4px 16px rgba(161,196,253,0.28);
        }
        .btn-glass {
            background: rgba(255,255,255,0.18);
            color: #222;
            border: 1px solid rgba(255,255,255,0.28);
            border-radius: 2rem;
            font-weight: 600;
            transition: background 0.3s, color 0.3s;
            box-shadow: 0 2px 8px rgba(44,62,80,0.10);
        }
        .btn-glass:hover, .btn-glass:focus {
            background: rgba(255,255,255,0.35);
            color: #111;
        }
        [data-bs-theme="dark"] .btn-glass {
            background: rgba(44,62,80,0.22);
            color: #eee;
            border: 1px solid rgba(44,62,80,0.28);
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        }
        [data-bs-theme="dark"] .btn-glass:hover, [data-bs-theme="dark"] .btn-glass:focus {
            background: rgba(44,62,80,0.35);
            color: #fff;
        }
        /* Navbar glassmorphism */
        .navbar {
            background: rgba(33, 37, 41, 0.7) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: 0 2px 16px 0 rgba(31, 38, 135, 0.10);
        }
        .navbar .navbar-brand {
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        .navbar .btn {
            border-radius: 2rem;
        }
        /* Theme toggle button fix */
        #theme-toggle {
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            border: 2px solid rgba(200,200,200,0.25);
            box-shadow: 0 2px 8px rgba(44,62,80,0.10);
        }
        #theme-toggle:focus {
            outline: 2px solid #a1c4fd;
        }
        #theme-toggle i {
            color: #222;
            transition: color 0.3s;
        }
        [data-bs-theme="dark"] #theme-toggle {
            border: 2px solid rgba(44,62,80,0.28);
            background: rgba(44,62,80,0.22);
        }
        [data-bs-theme="dark"] #theme-toggle i {
            color: #fff;
        }
        /* Section headers */
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        /* Smooth transitions */
        .glass-card, .glass-table, .btn, .navbar, body {
            transition: background 0.4s, box-shadow 0.4s, color 0.3s;
        }
        /* Responsive tweaks */
        @media (max-width: 600px) {
            .glass-card, .glass-table {
                border-radius: 0.7rem;
            }
            .section-title {
                font-size: 19px;
            }
            .navbar .navbar-brand {
                font-size: 16px;
            }
        }
        /* DataTable override for glassy look */
        table.dataTable.no-footer {
            border-bottom: none;
        }
        /* ...existing mobile table CSS... */
        @media screen and (max-width: 600px) {
            table.myTable{
                padding: 34px 0;
            }
            table.myTable,
           .myTable thead,
            .myTable tbody,
            .myTable th,
            .myTable td,
            .myTable tr {
                display: block;
            }

            .myTable thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .myTable tr {
                margin-bottom: 20px;
                border: 5px solid #ddd;
            }

            .myTable td {
                border: none;
                position: relative;
                padding-left: 50%;
            }

            .myTable td:before {
                margin-right: 45px;
                content: attr(data-label);
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
<!-- Navbar/Header -->
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/"><i class="bi bi-calendar2-week"></i>My Expenses of <?= GLOBAL_DATE ?>
    <button class="btn btn-glass ms-auto" id="theme-toggle" type="button">
      <i class="bi bi-moon-stars-fill" id="theme-icon"></i>
    </button>
  </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add new record</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= ACTION_FILE ?>?action=insert" method="post">
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
            <form action="<?= ACTION_FILE ?>?action=update" method="post">
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
   <div class="glass-card card shadow mb-4 border-0">
       <div class="card-body">
<!--           <div class="section-title"><i class="bi bi-calendar2-week"></i>My Expenses of --><?php //= GLOBAL_DATE ?><!--</div>-->
           <div class="row mb-3">
               <div class="col-md-8"></div>
               <div class="col-md-4 text-lg-end text-md-end text-center">
                   <button type="button" class="btn btn-gradient mx-1 mb-2" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-circle me-1"></i>New</button>
                   <button class="btn btn-glass mx-1 mb-2" onclick="all_mon()"><i class="bi bi-calendar3 me-1"></i>Past Months</button>
                   <button type="button" class="btn btn-gradient mx-1 mb-2" data-bs-toggle="modal" data-bs-target="#show-total"><i class="bi bi-cash-stack me-1"></i>Total</button>
               </div>
           </div>
           <div class="table-responsive">
               <table id="myTable" class="glass-table current-month-table table table-hover table-striped myTable align-middle">
                   <thead class="table-dark">
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
                    $search="e.date BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND LAST_DAY(NOW())";
                    if (isset($_GET['date_search'])) {
                        $search="e.date LIKE '%".$_GET['date_search']."%'";
                    }
                    $sql=mysqli_query($connection,"select e.id,e.category_id,e.price,e.date,e.name as e_name,c.name as c_name from expenses e left join categories c on e.category_id=c.id WHERE ".$search." ORDER BY e.date");
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
                           <a href="<?= ACTION_FILE ?>?action=insert&name=<?= $row['e_name'] ?>&price=<?= $row['price'] ?>&category=<?= $row['category_id'] ?>" class="btn btn-warning">Copy</a>
                           <button type="button" class="btn btn-info" onclick="editRecord(<?= $row['id'] ?>,this)" >Edit</button>
                           <a onclick=" confirm('Are you sure that you want to delete the item <?= $row['e_name'] ?>?')? href='<?= ACTION_FILE ?>?action=delete&&id=<?= $row['id'] ?>':''" class="btn btn-danger">Delete</a>
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
       </div>
   </div>
    <div>

    </div>
        <div class="modal fade glass-modal" id="show-total" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="bi bi-cash-coin me-2"></i>Total Expenses of <?= GLOBAL_DATE ?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <?php foreach ($total_by_category as $key=>$value): ?>
                            <div class="col-6 my-2 fw-bold text-muted"><?= $key ?>:</div>
                            <div class="col-6 my-2 fw-bold"><?= $value ?>/-</div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="all-months">
        <div class="glass-card card shadow mb-4 border-0">
            <div class="card-body">
                <div class="section-title"><i class="bi bi-bar-chart-steps"></i>All Monthly Expenses</div>
                <button class="btn btn-glass mb-3 float-end" onclick="one_mon()"><i class="bi bi-arrow-left me-1"></i>Back</button>
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
                    echo "<div class='table-responsive'><table class='glass-table table table-hover table-striped myTable align-middle'><thead class='table-dark'><tr><th>Month</th><th>Total Expense</th><th>Action</th></tr></thead><tbody>";

                    $price = 0;
                    $extra = 0;
                    while ($row2 = $result2->fetch_assoc()) {
                        $text_danger=$row2['total_expense']>=$limit? 'text-danger fw-bold':'fw-bold';
                        echo "<tr><td>" . date('M Y',strtotime($row2["month"])) . "</td><td class='".$text_danger."'>" . $row2['total_expense'] . "/-</td> <td class='".$text_danger."'><a href='?date_search=".date('Y-m',strtotime($row2["month"]))."' class='btn btn-light'>Full Report</a></td></tr>";
                    }

                    echo "</tbody></table></div>";
                } else {
                    echo "<div class='alert alert-info'>No results found.</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.js"></script>
<script>
    // Theme toggle with cookie
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
    function setTheme(mode) {
        if (mode === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            document.getElementById('theme-icon').className = 'bi bi-brightness-high-fill';
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light');
            document.getElementById('theme-icon').className = 'bi bi-moon-stars-fill';
        }
        setCookie('theme', mode, 365);
    }
    document.addEventListener('DOMContentLoaded', function() {
        var savedTheme = getCookie('theme') || 'light';
        setTheme(savedTheme);
        document.getElementById('theme-toggle').addEventListener('click', function() {
            var current = document.documentElement.getAttribute('data-bs-theme');
            setTheme(current === 'dark' ? 'light' : 'dark');
        });
    });

        function editRecord(expense_id,button) {
            $(button).html('<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span> </div>');
            $.ajax({
                url:"<?= ACTION_FILE ?>?action=edit",
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
