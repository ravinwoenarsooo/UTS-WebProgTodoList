<?php 
require 'db_conn.php';
session_start();
if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated']) {
    header("Location: login_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link rel="stylesheet" href="css/stylus.css">
</head>
<body>
    <div class="home-button-container" id="home">
        <form action="user_page.php" method="get">
            <button class="home-button" type="submit">Kembali ke Home Page</button>
        </form>
    </div>
    <div class="main-section">
        <div class="add-section">
            <form action="app/add.php" method="POST" autocomplete="off">
                <?php if(isset($_GET['mess']) && $_GET['mess'] == 'error'){ ?>
                <input type="text" name="title" style="border-color: #ff6666"placeholder="Harus Isi To-do list" />
                <button type="submit">Add &nbsp; <span>&#43;</span></button>

                <?php }else{ ?>
                <input type="text" name="title" placeholder="Apa yang ingin kamu lakukan?" />
                <button type="submit">Add &nbsp; <span>&#43;</span></button>
                <?php } ?>
            </form>
        </div>

        <?php 
          $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
        ?>
        <div class="show-todo-section">
            <?php if($todos->rowCount() <= 0){ ?>
                <div class="todo-item">
                    <div class="empty">
                        <img src="img/TDL.jpg" width="90%" />
                        <img src="img/Ellipsis.gif" width="80px">
                    </div>
                </div>
            <?php } ?>

            
            <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <span id="<?php echo $todo['id']; ?>"
                        class="remove-to-do">x</span>
                    <?php if($todo['checked']){ ?> 
                        <input type="checkbox"
                            class="check-box"
                            data-todo-id ="<?php echo $todo['id']; ?>"
                            checked />
                        <h2 class="checked"><?php echo $todo['title'] ?></h2>
                    <?php }else { ?>
                        <input type="checkbox"
                            data-todo-id ="<?php echo $todo['id']; ?>"
                            class="check-box" />
                        <h2><?php echo $todo['title'] ?></h2>
                    <?php } ?>
                    <br><br>
                    <form action="app/edit.php" method="post">
                        <input type="hidden" name="edit_id" value="<?php echo $todo['id']; ?>" />
                        <input type="text" name="new_title" value="" />
                        <select name="new_status">
                            <option value="not_yet_started">Not Yet Started</option>
                            <option value="in_progress">In Progress</option>
                            <option value="finished">Finished</option>
                        </select>
                        <button type="submit">Update</button>

                    </form>
                    <br>
                    <small>created: <?php echo $todo['date_time'] ?></small> 
                </div>
            <?php } ?>
            <ul>
            <?php if (isset($_SESSION['tasks']) && !empty($_SESSION['tasks'])): ?>
                <?php foreach ($_SESSION['tasks'] as $task): ?>
                    <li><?= $task; ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');
                
                $.post("app/remove.php", 
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });
            
        $("form[action='app/edit.php']").submit(function(event) {
            event.preventDefault();

            const form = $(this);
            const editId = form.find("input[name='edit_id']").val();
            const newTitle = form.find("input[name='new_title']").val();

            $.post("app/edit.php", {
                edit_id: editId,
                new_title: newTitle,
                update_todo: true
            }, function(data) {
                if (data === 'success') {
                    window.location.reload();
                }
            });
        });

            $(".check-box").click(function(e){
                const id = $(this).attr('data-todo-id');
                
                $.post('app/check.php', 
                      {
                          id: id
                      },
                      (data) => {
                          if(data != 'error'){
                              const h2 = $(this).next();
                              if(data === '1'){
                                  h2.removeClass('checked');
                              }else {
                                  h2.addClass('checked');
                              }
                          }
                      }
                );
            });
        });

        function saveSelectedOption() {
            var selectElement = document.getElementsByName("new_status")[0];
            var selectedValue = selectElement.value;
            localStorage.setItem("selectedOption", selectedValue);
        }

        function loadSelectedOption() {
            var selectElement = document.getElementsByName("new_status")[0];
            var selectedValue = localStorage.getItem("selectedOption");
            if (selectedValue) {
                selectElement.value = selectedValue;
            }
        }

        document.getElementsByName("new_status")[0].addEventListener("change", saveSelectedOption);

        window.addEventListener("load", loadSelectedOption);
    </script>
</body>
</html>