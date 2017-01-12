<?php

$conn = new mysqli('localhost', 'root', 'dataworks', 'login') 
or die ('Cannot connect to db');

    $result = $conn->query("select * from device_mgmt");
    
    echo "<html>";
    echo "<body>";
    echo "<form id='form' action = 'dropdown.php' method='post' onchange='this.form.submit()'>";
    echo "<select name='id' id = 'id' classs = 'select' method='post' onChange='click()'>";
    while ($row = $result->fetch_assoc()) {

                  unset($id, $name);
                  $id = $row['username'];
                  $name = $row['deviceID']; 
                  echo '<option value="'.$id.'">'.$name.'</option>';
                 
}
    
    echo "</select>";
    echo "<noscript><input type='submit' value='Submit'></noscript>";
    echo "</form>";
   
    $selectID = $_POST['id'];
    echo $selectID;
    echo "</body>";
    echo "</html>";
    function submit() {
        
        
    }
?>

<html>
<body>
<form id='form' method='post' onChange="click()">
<select id="id_select" name="name" method='post' onChange="click()">
<?php
    $conn = new mysqli('localhost', 'root', 'dataworks', 'login') 
or die ('Cannot connect to db');

    $result = $conn->query("select * from device_mgmt");
    while ($row = $result->fetch_assoc()) {

                  unset($id, $name);
                  $id = $row['username'];
                  $name = $row['deviceID']; 
                  echo '<option value="'.$id.'">'.$name.'</option>';
                 
}
   
    ?>
</select>
    </form>
    <script>
    function click() {
        console.log("sdlfkjsf");
        value_select = document.getElementById("id_select").value;
        console.log(value_select);
    }
</script>
    </body>
</html>
