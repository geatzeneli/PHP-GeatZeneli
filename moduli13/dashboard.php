<?php include_once("header.php");?>


    <?php

    include_once("config.php");

    $sql="SELECT * FROM users";

    $getUsers=$conn->prepare($sql);

    $getUsers->execute();

    $users=$getUsers->fetchAll();

    ?>    
    <nav>
        <a href="logout.php" class="nav-link">Sign Out</a>
</nav>
<table>
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Update</th>
    </thead>
    <tbody>
        <?php
        foreach($users as $user){
        ?>
        <tr>
            <td><?= $user['id']?></td>
            <td><?= $user['name']?></td>
            <td><?= $user['username']?></td>
            <td><?= $user['email']?></td>
            <td><?= "<a href='delete.php?id=$user[id]'>Delete</a> | <a href='edit.php?id=$user[id]'>Update</a> "?> </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<a href="add.php">Add</a>

<?php include_once("footer.php");?>
