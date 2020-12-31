<?php

require '../includes/database.php';

#$document = new DOMDocument();
#$document->loadHTML();


if (isset($_REQUEST["pin"])) {
  if (preg_match("/-?[0-9]*(\.[0-9]+)?/", $_REQUEST["pin"])) {

    global $conn;
    $stm_check_active = $conn->prepare("SELECT pin_active, employee_number FROM users WHERE pin = :pin_given");
    $stm_check_active->execute(array(
            ':pin_given' => $_REQUEST["pin"]
    ));
    $output = $stm_check_active->fetchAll();
    var_dump($output);
    if (isset($output[0]["pin_active"]) && $output[0]["pin_active"] == 1) {
      #$pin_box = $document->getElementByID("pin_box");
      #$pin_box->removeChild();
      echo "<script>
        var parent = document.getElementById(\"content\");
        var child = document.getElementById(\"pin-box\");
        parent.removeChild(child);
      </script>";

      echo "valid";
    } else {
      echo "pin not active";
    }
  } else {
      exit();
  }
}

?>

<html>
<head>
  <title>Apeiro | Communications </title>
  <link rel="stylesheet" href="../lib/mdl/material.min.css">
  <script src="../lib/mdl/material.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="communications.css">
</head>
<body> 
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
    <div class="mdl-layout__drawer">
      <span class="mdl-layout-title">Dashboard</span>
      <nav class="mdl-navigation">
        <a class="mdl-navigation__link" href="/login">Login</a>
        <a class="mdl-navigation__link" href="/sales">Sales</a>
        <a class="mdl-navigation__link" href="/customer_management">Customer Management</a>
        <a class="mdl-navigation__link" href="/colleague_management">Colleague Management</a>
        <a class="mdl-navigation__link" href="/trade-in">Trade-In</a>
        <a class="mdl-navigation__link" href="/communications">Communications</a>
      </nav>
    </div>
    <main class="mdl-layout__content" id="content">

      <!-- Numeric Textfield -->
      <form action="<?php $_PHP_SELF ?>" method="POST" id="pin-box">
        <div class="mdl-textfield mdl-js-textfield">
          <input class="mdl-textfield__input" autofocus="true" maxlength="4" type="password" pattern="-?[0-9]*(\.[0-9]+)?" name="pin">
          <label class="mdl-textfield__label" for="">Enter PIN</label>
          <span class="mdl-textfield__error">Input is not a number!</span>
        </div>
      </form>
    </div> 
      <div class="page-content">
        <div id="posts">
          <!--This is the section where the posts go-->

        </div>
        <div id="channels">
          <!--This is where the channels go-->


          <div class="channel_selector">Questions and Answers</div>
          <div class="channel_selector"></div>
          <div class="channel_selector">Discussion</div>



        </div>
      </div>
    </main>
  </div>
</body>
</html>
