<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="styles.css">
  </head>

  <body>
    <form method="GET">
      <label for="api_key">API Key:</label>
      <input type="text" name="api_key" required>

      <label for="actions">Action:</label>
      <select id="actions" name="actions" onclick="hideShowFields()">
        <option value="create">Create</option>
        <option value="fetch">Fetch</option>
        <option value="update">Update</option>
        <option value="delete">Delete</option>
      </select>

      <label for="user_id" class="fetch update delete" style="display: none;">User ID:</label>
      <input type="text" name="user_id" class="fetch update delete" style="display: none;" required disabled="true">

      <label for="first_name" class="create">First Name:</label>
      <input type="text" name="first_name" class="create" required>

      <label for="surname" class="create">Surname:</label>
      <input type="text" name="surname" class="create" required>

      <label for="dob" class="create">Date Of Birth:</label>
      <input type="date" name="dob" class="create" required>

      <label for="phone_number" class="create">Phone Number:</label>
      <input type="tel" name="phone_number" class="create" title="UK Standard Number - 11 Digits" pattern="[0-9]{11}" required>

      <label for="email" class="create">Email:</label>
      <input type="email" name="email" class="create" required>

      <label for="update_column" class="update" style="display: none;">Field To Update:</label>
      <select id="update_column" name="update_column" class="update" onclick="updateColumnChoice()" style="display: none;">
        <option value="first_name">First Name</option>
        <option value="surname">Surname</option>
        <option value="dob">Date Of Birth</option>
        <option value="phone_number">Phone Number</option>
        <option value="email">Email</option>
      </select>

      <label for="updated_value" class="update" style="display: none;">Updated Information:</label>
      <input id="updated_value" type="text" name="updated_value" class="update" style="display: none;">

      <button type="submit" name="submit" value="true">Submit</button>
    </form>
    <script>
      function hideShowFields(){
        
        console.clear();

        var message = document.getElementById('message');
        if(typeof(message) != 'undefined' && message != null){
          message.style.display = "none";
        } 
        
        var selection = document.getElementById('actions').value;

        if(selection == "create"){
          var hide_fields = document.querySelectorAll('.fetch, .update, .delete');
          for(i = 0; i < hide_fields.length; i++){
            hide_fields[i].style.display = "none";
            hide_fields[i].disabled = true;
          }

          var create_fields = document.getElementsByClassName('create');
          for(i = 0; i < create_fields.length; i++){
            create_fields[i].style.display = "block";
            create_fields[i].disabled = false;
            create_fields[i].required = true;
          }
        }

        if(selection == "fetch"){
          var hide_fields = document.querySelectorAll('.create, .update, .delete');
          for(i = 0; i < hide_fields.length; i++){
            hide_fields[i].style.display = "none";
            hide_fields[i].disabled = true;
          }

          var fetch_fields = document.getElementsByClassName('fetch');
          for(i = 0; i < fetch_fields.length; i++){
            fetch_fields[i].style.display = "block";
            fetch_fields[i].disabled = false;
            fetch_fields[i]. required = true;
          }
        }

        if(selection == "update"){
          var hide_fields = document.querySelectorAll('.create, .fetch, .delete');
          for(i = 0; i < hide_fields.length; i++){
            hide_fields[i].style.display = "none";
            hide_fields[i].disabled = true;
          }

          var update_fields = document.getElementsByClassName('update');
          for(i = 0; i < update_fields.length; i++){
            update_fields[i].style.display = "block";
            update_fields[i].disabled = false;
            update_fields[i].required = false;
            if(i == 1 || i == 5){
              update_fields[i].required = true;
            }
          }
        }

        if(selection == "delete"){
      		var hide_fields = document.querySelectorAll('.create, .fetch, .update');
      		for(i = 0; i < hide_fields.length; i++){
      			hide_fields[i].style.display = "none";
            hide_fields[i].disabled = true;
      	   }

          var delete_fields = document.getElementsByClassName('delete');
          for(i = 0; i < delete_fields.length; i++){
            delete_fields[i].style.display = "block";
            delete_fields[i].disabled = false;
            delete_fields[i].required = true;
          }
        }
      }

      function updateColumnChoice(){
        var selection = document.getElementById('update_column').value;

        if(selection == "first_name"){
          document.getElementById('updated_value').type = "text";
          document.getElementById('updated_value').removeAttribute("pattern");
          document.getElementById('updated_value').removeAttribute("title");
        }

        if(selection == "surname"){
          document.getElementById('updated_value').type = "text";
          document.getElementById('updated_value').removeAttribute("pattern");
          document.getElementById('updated_value').removeAttribute("title");
        }

        if(selection == "dob"){
          document.getElementById('updated_value').type = "date";
          document.getElementById('updated_value').removeAttribute("pattern");
          document.getElementById('updated_value').removeAttribute("title");
        }

        if(selection == "phone_number"){
          document.getElementById('updated_value').type = "tel";
          document.getElementById('updated_value').pattern = "[0-9]{11}";
          document.getElementById('updated_value').title = "UK Standard Number - 11 Digits";
        }

        if(selection == "email"){
          document.getElementById('updated_value').type = "email";
          document.getElementById('updated_value').removeAttribute("pattern");
          document.getElementById('updated_value').removeAttribute("title");
        }
      }
    </script>
  </body>
</html>
<?php

$successful_message = '<p id="message" class="successful-message">Successful API Call - Check The Console For Output</p>';
$unsuccessful_message = '<p id="message" class="unsuccessful-message">Unsuccessful API Call - Check The Console For Error Output</p>';

if(isset($_GET['submit'])){
  if(empty($_GET['api_key'])){
    echo $unsuccessful_message;
    echo '<script> console.log(' . json_encode(
      array(
        'code'  => '1',
        'error' => 'API Key Is Required'
      )
    ) . ')</script>';

    die();
  }else{
    $api_key = filter_var($_GET['api_key'], FILTER_SANITIZE_STRING);
  }

  if(empty($_GET['actions'])){
    echo $unsuccessful_message;
    echo '<script> console.log(' . json_encode(
      array(
        'code'  => '2',
        'error' => 'An Action Is Required'
      )
    ) . ')</script>';

    die();
  }elseif($_GET['actions'] == "create" || $_GET['actions'] == "fetch" || $_GET['actions'] == "update" || $_GET['actions'] == "delete"){
    $action  = $_GET['actions'];
  }else{
    echo $unsuccessful_message;
    echo '<script> console.log(' . json_encode(
      array(
        'code'  => '2',
        'error' => 'Action Does\'nt exist'
      )
    ) . ')</script>';

    die();
  }

  include('config.php');

  $query = $conn->prepare("SELECT api_key FROM api_keys WHERE api_key = ?");
  $query->bind_param("s", $api_key);
  $query->execute();
  $query->store_result();

  if($query->num_rows === 0){
    echo $unsuccessful_message;
    echo '<script> console.log(' . json_encode(
      array(
        'code'  => '3',
        'error' => 'API Key Is Invalid'
      )
    ) . ')</script>';

    die();
  }

  if($action == "fetch" || $action == "update" || $action == "delete"){
    
    if(empty($_GET['user_id'])){
      echo $unsuccessful_message;
      echo '<script> console.log(' . json_encode(
        array(
          'code'  => '3.1',
          'error' => 'An ID Must Be Set For This Action'
        )
      ) . ')</script>';

      die();
    }else{
      $user_id = filter_var($_GET['user_id'], FILTER_SANITIZE_NUMBER_INT);
      if(!filter_var($user_id, FILTER_VALIDATE_INT)){
        echo $unsuccessful_message;
        echo '<script> console.log(' . json_encode(
          array(
            'code'  => '3.1.1',
            'error' => 'Invalid ID'
          )
        ) . ')</script>';
  
        die();
      }
    }
  }

  if($action == "create"){
    if(!empty($_GET['first_name']) && !empty($_GET['surname']) && !empty($_GET['dob']) && !empty($_GET['phone_number']) && !empty($_GET['email'])){

      $first_name   = filter_var($_GET['first_name'], FILTER_SANITIZE_STRING);
      $surname      = filter_var($_GET['surname'], FILTER_SANITIZE_STRING);
      $dob          = filter_var($_GET['dob'], FILTER_SANITIZE_STRING);
      $phone_number = filter_var($_GET['phone_number'], FILTER_SANITIZE_STRING);
      $email        = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
      
      if(!preg_match('/^[A-Za-z]+$/', $first_name )){
        echo $unsuccessful_message;
        echo '<script> console.log(' . json_encode(
          array(
            'code'  => '3.2',
            'error' => 'Invalid First Name'
          )
        ) . ')</script>';
  
        die();
      }

      if(!preg_match('/^[A-Za-z]+$/', $surname)){
        echo $unsuccessful_message;
        echo '<script> console.log(' . json_encode(
          array(
            'code'  => '3.3',
            'error' => 'Invalid Surname'
          )
        ) . ')</script>';
  
        die();
      }
      
      if(strpos($dob, "-")){
        $dob_exploded = explode("-", $dob);
        $year         = $dob_exploded[0];
        $day          = $dob_exploded[2];
        $month        = $dob_exploded[1];
        if(!checkdate($month, $day, $year)){
          echo $unsuccessful_message;
          echo '<script> console.log(' . json_encode(
            array(
              'code'  => '3.4',
              'error' => 'Invalid Date Of Birth'
            )
          ) . ')</script>';
  
          die();
        }
      }else{
        echo $unsuccessful_message;
        echo '<script> console.log(' . json_encode(
          array(
            'code'  => '3.4.1',
            'error' => 'Invalid Date Of Birth'
          )
        ) . ')</script>';

        die();
      }
        
      if(!preg_match("/^[0-9]{11}$/", $phone_number)){
        echo $unsuccessful_message;
        echo '<script> console.log(' . json_encode(
          array(
            'code'  => '3.5',
            'error' => 'Invalid UK Phone Number'
          )
        ) . ')</script>';

        die();
      }
      
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo $unsuccessful_message;
        echo '<script> console.log(' . json_encode(
          array(
            'code'  => '3.6',
            'error' => 'Invalid Email Address'
          )
        ) . ')</script>';
  
        die();
      }

      $query = $conn->prepare("INSERT INTO users (first_name, surname, dob, phone_number, email) VALUES(?, ?, ?, ?, ?)");
      $query->bind_param("sssss", $first_name, $surname, $dob, $phone_number, $email);
      $query->execute();
      $query->store_result();

      if($query->affected_rows == 1){
        echo $successful_message;
        echo '<script> console.log(' . json_encode(
          array(
            'message'  => 'User Successfully Created. ID = ' . $conn->insert_id . ''
          )
        ) . ')</script>';
        die();
      }else{
        echo $unsuccessful_message;
        echo '<script> console.log(' . json_encode(
          array(
            'code'  => '4',
            'error' => 'User Can\'t Be Created'
          )
        ) . ')</script>';
      die();
      }
    }else{
      echo $unsuccessful_message;
      echo '<script> console.log(' . json_encode(
        array(
          'code'  => '5',
          'error' => 'Please Ensure All Values Are Set To Create A User'
        )
      ) . ')</script>';
    die();
    }
  }

  if($action == "fetch"){
    
    $query = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();

    if($result->num_rows == 0){
      echo $unsuccessful_message;
      echo '<script> console.log( ' . json_encode(
        array(
          'code'  => '6',
          'error' => 'User ' . $user_id . ' Doesn\'t Exist'
        )
      ) . ')</script>';

      die();
    }else{
      echo $successful_message;
      echo '<script> console.log( ' . json_encode($result->fetch_assoc()) . ')</script>';
      die();
    }
  }

  if($action == "update"){
    if(!empty($_GET['update_column']) && !empty($_GET['updated_value'])){
      
      $updated_column = filter_var($_GET['update_column'], FILTER_SANITIZE_STRING);

      if($updated_column == "first_name" || $updated_column == "surname" || $updated_column == "dob" || $updated_column == "phone_number" || $updated_column == "email"){ 
        if($updated_column == "first_name"){
          
          $updated_value = filter_var($_GET['updated_value'], FILTER_SANITIZE_STRING);
          
          if(!preg_match('/^[A-Za-z]+$/', $updated_value)){
            echo $unsuccessful_message;
            echo '<script> console.log(' . json_encode(
              array(
                'code'  => '7.1',
                'error' => 'Invalid First Name'
              )
            ) . ')</script>';
            die();
          }

        }elseif($updated_column == "surname"){

          $updated_value = filter_var($_GET['updated_value'], FILTER_SANITIZE_STRING);
          
          if(!preg_match('/^[A-Za-z]+$/', $updated_value)){
            echo $unsuccessful_message;
            echo '<script> console.log(' . json_encode(
              array(
                'code'  => '7.2',
                'error' => 'Invalid Surname'
              )
            ) . ')</script>';
            die();
          }

        }elseif($updated_column == "dob"){

          $updated_value = filter_var($_GET['updated_value'], FILTER_SANITIZE_STRING);

          if(strpos($updated_value, "-")){
            
            $dob_exploded = explode("-", $updated_value); 
            $year         = $dob_exploded[0];
            $day          = $dob_exploded[2];
            $month        = $dob_exploded[1];
            
            if(!checkdate($month, $day, $year)){
              echo $unsuccessful_message;
              echo '<script> console.log(' . json_encode(
                array(
                  'code'  => '7.3.1',
                  'error' => 'Invalid Date Of Birth'
                )
              ) . ')</script>';
              die();
            }
          }else{
            echo $unsuccessful_message;
            echo '<script> console.log(' . json_encode(
              array(
                'code'  => '7.3.2',
                'error' => 'Invalid Date Of Birth'
              )
            ) . ')</script>';
            die();
          }
        
        }elseif($updated_column == "phone_number"){

          $updated_value = filter_var($_GET['updated_value'], FILTER_SANITIZE_STRING);

          if(!preg_match("/^[0-9]{11}$/", $updated_value)){
            echo $unsuccessful_message;
            echo '<script> console.log(' . json_encode(
              array(
                'code'  => '7.3.3',
                'error' => 'Invalid UK Phone Number'
              )
            ) . ')</script>';
            die();
          }

        }elseif($updated_column == "email"){

          $updated_value = filter_var($_GET['updated_value'], FILTER_SANITIZE_EMAIL);
          
          if(!filter_var($updated_value, FILTER_VALIDATE_EMAIL)){
            echo $unsuccessful_message;
            echo '<script> console.log(' . json_encode(
              array(
                'code'  => '7.3.4',
                'error' => 'Invalid Email Address'
              )
            ) . ')</script>';
            die();
          }

        }

        $query = $conn->prepare("UPDATE users SET $updated_column = ? WHERE id = ?");
        $query->bind_param("si", $updated_value, $user_id);
        $query->execute();
        $query->store_result();

        if($query->affected_rows == 1){
          echo $successful_message;
          echo '<script> console.log(' . json_encode(
            array(
              'message'  => 'User ' . $user_id . ' Successfully Updated ' . $updated_column . ' To ' . $updated_value . ''
            )
          ) . ')</script>';
          die();
        }else{
          echo $unsuccessful_message;
          echo '<script> console.log(' . json_encode(
            array(
              'code'  => '7',
              'error' => 'User ' . $user_id . ' Can\'t Be Updated'
            )
          ) . ')</script>';
          die();
        }

      }else{
        echo $unsuccessful_message;
        echo '<script> console.log(' . json_encode(
          array(
            'code'  => '7.2',
            'error' => 'Invalid Column Provided'
          )
        ) . ')</script>';
        die();
      }
    }else{
      echo $unsuccessful_message;
      echo '<script> console.log(' . json_encode(
        array(
          'code'  => '8',
          'error' => 'The Column And Value Must Be Set'
        )
      ) . ')</script>';
      die();
    }
  }

  if($action == "delete"){
    
    $query = $conn->prepare("DELETE FROM users WHERE id = ?");
    $query->bind_param("i",$user_id);
    $query->execute();
    $query->store_result();

    if($query->affected_rows == 1){
      echo $successful_message;
      echo '<script> console.log(' . json_encode(
        array(
          'message'  => 'User ' . $user_id . ' Successfully Deleted'
        )
      ) . ')</script>';
      die();
    }else{
      echo $unsuccessful_message;
      echo '<script> console.log(' . json_encode(
        array(
          'code'  => '9',
          'error' => 'User ' . $user_id . ' Can\'t Be Deleted - This User Doesn\'t Exist'
        )
      ) . ')</script>';
      die();
    }
  }

}else{
  echo '<script> console.log(' . json_encode(
    array(
      'code'  => '0',
      'error' => 'The Submit Attribute Must Be Set'
      )
    ) . ')</script>';
}
?>
