<html>

<body>
    <div id='selectedResult'></div>

    <select name='test' onChange='showSelected(this.value)'>
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


    <script type="text/javascript">
        function showSelected(val) {
            document.getElementById('selectedResult').innerHTML = "The selected number is: " + val;
        }
    </script>
</body>

</html>