<?php

require 'includes/database.php';  

function assign_colours($percentage) {
  if (($percentage / 10) == 100) {
    # On target
    # Green
    return "#2E7D32";
  } elseif (($percentage / 10) > 100 ) {
    # Above target
    # Blue
    return "#1565C0";
  } elseif (($percentage / 10) <= 80) {
    # Not on target
    # Red
    return "#E53935";
  } else {
    # Close
    # Maybe change this later (yellow)
    return "#FFEA00";
  }
};

function assign_note($percentage) {
  if (($percentage / 10) == 100) {
    # On target
    # Green
    return "On target :)";
  } elseif (($percentage / 10) > 100 ) {
    # Above target
    # Blue
    return "Great Job!";
  } elseif (($percentage / 10) <= 80) {
    # Not on target
    # Red
    return "Needs improvement.";
  } else {
    # Close
    # Maybe change this later (yellow)
    return "Getting there!";
  }
};



function pull_data() {

  global $conn;

  $store_num = 3345;
  $stm_pull_data = $conn->prepare("SELECT * FROM performance WHERE store_num = :store_num");

  $stm_pull_data->execute(array(
     ':store_num' => $store_num
  ));

  global $data;
  $data = $stm_pull_data->fetchAll();



};

pull_data();

#var_dump($data);

global $data;

//debugging
#var_dump($data[0]["target_volume"]);
#var_dump($data[0]["target_insurance"]);
#var_dump($data[0]["nps_target"]);
#var_dump($data[0]["acquisition_target"]);
#var_dump($data[0]["volume"]);
#var_dump($data[0]["nps"]);
#var_dump($data[0]["acquisition"]);
#var_dump($data[0]["store_num"]);


// key variables

//database column names

#target_volume
#target_insurance
#nps_target
#acquisition_target
#volume
#insurance
#nps
#acquisition
#store_num

$volume_percentage = round($data[0]["volume"] / $data[0]["target_volume"] * 1000);
$insurance_percentage = round($data[0]["insurance"] / $data[0]["target_insurance"] * 1000);
$nps_percentage =  round($data[0]["nps"] / $data[0]["nps_target"] * 1000);
$acquisition_percentage = round($data[0]["acquisition"] / $data[0]["acquisition_target"] * 1000); 


global $percentage_set;
global $colour_set;


$percentage_set = array();
array_push($percentage_set, $volume_percentage);
array_push($percentage_set, $insurance_percentage);
array_push($percentage_set, $nps_percentage);
array_push($percentage_set, $acquisition_percentage);
  
  // $colour_set = [x,y,z,w];

$colour_set = array();
  foreach ($percentage_set as $i) {
    array_push($colour_set, assign_colours($i));
  }

$note_set = array();
  foreach ($percentage_set as $i) {
    array_push($note_set, assign_note($i));
  }


#var_dump($colour_set);
#var_dump($percentage_set);



  /*/
    array(1) { [0]=> array(9) { 
      ["target_volume"]=> int(45),
      ["target_insurance"]=> int(18),
      ["nps_target"]=> int(95),
      ["acquisition_target"]=> int(9),
      ["volume"]=> int(15) ["insurance"]=> int(15),
      ["nps"]=> int(92),
      ["acquisition"]=> int(8),
      ["store_num"]=> int(3345) 
      } 
    }

  /*/

/*/
<div class="announcement">
  <div class="mdl-card__title mdl-card--expand">
    <h2 class="mdl-card__title-text announcement-header">Week 45 DOTM</h2>
  </div>
  <div class="description">
    <div class="tags">
      <span class="mdl-chip live">
        <span class="mdl-chip__text">Live</span>
      </span>
      <span class="mdl-chip">
        <span class="mdl-chip__text">DOTM</span>
      </span>
    </div>
    <div class="date">
      Monday 12:04pm
    </div>
  </div>
  <div class="mdl-card__actions mdl-card--border">
    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
      View Updates
    </a>
  </div>
</div>
/*/ 

function show_announcements() {

  global $conn;
  global $announcements;
  $stm_get_announcements = $conn->prepare("SELECT post_id, title, last_update, tag_live, tag_dotm, tag_confidential, tag_urgent, tag_pos FROM announcements ORDER BY last_update DESC");
  $stm_get_announcements->execute();
  $announcements = $stm_get_announcements->fetchAll();

  # Database columns:
  #post_id <-- y
  #title <-- 
  #author 
  #last_update <--
  #body
  #tag_live <---
  #tag_dotm <---
  #tag_confidential <---
  #tag_urgent <---
  #tag_pos  <---
};

function create_labels($i) {
  global $announcements;

  $label_set = array();

  if ($announcements[$i]["tag_live"]) {
      
      # Live tag

    array_push($label_set, 
      "<span class=\"mdl-chip live\">
        <span class=\"mdl-chip__text\">Live</span>
      </span>"
    );




  }
  if ($announcements[$i]["tag_dotm"]) {

      # DOTM tag

    array_push($label_set, 
      "<span class=\"mdl-chip\">
        <span class=\"mdl-chip__text\">DOTM</span>
      </span>"
    );



  }
  if ($announcements[$i]["tag_confidential"]) {

      # Confidential tag

    array_push($label_set, 
      "<span class=\"mdl-chip confidential\">
        <span class=\"mdl-chip__text\">Confidential</span>
      </span>"
    );




  }
  if ($announcements[$i]["tag_urgent"]) {

      # Urgent tag

    array_push($label_set, 
      "<span class=\"mdl-chip urgent\">
        <span class=\"mdl-chip__text\">Urgent</span>
      </span>"
    );



  }
  if ($announcements[$i]["tag_pos"]) {

      # POS tag

    array_push($label_set, 
      "<span class=\"mdl-chip\">
        <span class=\"mdl-chip__text\">POS</span>
      </span>"
    );


  }

  foreach ($label_set as $l) {
    echo $l;
    #use echo because using return breaks the call stack 
  }
}

show_announcements();

?>

<html>
<head>
  <title>Home | Apeiro</title>
  <link rel="stylesheet" href="../lib/mdl/material.min.css">
  <link rel="stylesheet" href="dashboard.css">
  <script src="../lib/mdl/material.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
    <main class="mdl-layout__content">
      <div class="page-content">
        <div id="main-body">
          <div id="stats" class="">
            <div class="wrapper">
                <div class="grid">
                  <section>
                   <!--
                      
                      "Stroke-dash-array is important" : This is responsible for the "percentage that the user sees"
                      "Stroke (IN THE SECOND CIRCLE CLASS) is also important" : This controls the colour of the chart.  
                        | Below : #E53935
                        | On : #2E7D32
                        | Above : #1565C0
                      
                    -->

                    <h2>Volume</h2>
                    <svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" width="125" height="125" xmlns="http://www.w3.org/2000/svg">
                      <circle class="circle-chart__background" stroke="#efefef" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
                      <circle class="circle-chart__circle circle-chart__circle--negative" stroke="<?=$colour_set[0]?>" stroke-width="2" stroke-dasharray="<?=$percentage_set[0] / 10?>" stroke-linecap="round" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
                      <g class="circle-chart__info">
                        <text class="circle-chart__percent" x="16.91549431" y="15.5" alignment-baseline="central" text-anchor="middle" font-size="8"><?=$percentage_set[0] / 10?>%</text>
                        <text class="circle-chart__subline" x="16.91549431" y="20.5" alignment-baseline="central" text-anchor="middle" font-size="2"><?=$note_set[0]?></text>
                      </g>
                    </svg>
                    <!--
                        Notable parameters:
                          width, height ::  Useful for changing the size of the rings.
                          ($color_set[0])stroke :: Colour of the shape in hex i.e. #2E7D32 = green. 
                          stroke-width :: Size of stroke width in pixels
                          fill :: Option to fill the circle or not 
                          ($percentage_set[0])stroke-dasharray :: How far the the stroke gets around the circle in thousands. i.e. 101,000 =  101% 
                    -->
                  </section>
                
                  <section>
                    <h2>Insurance</h2>
                    <svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" width="125" height="125" xmlns="http://www.w3.org/2000/svg">
                      <circle class="circle-chart__background" stroke="#efefef" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
                      <circle class="circle-chart__circle circle-chart__circle--negative" stroke="<?=$colour_set[1]?>" stroke-width="2" stroke-dasharray="<?=$percentage_set[1] / 10?>" stroke-linecap="round" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"                />
                      <g class="circle-chart__info">
                        <text class="circle-chart__percent" x="16.91549431" y="15.5" alignment-baseline="central" text-anchor="middle" font-size="8"><?=$percentage_set[1] / 10?>%</text>
                        <text class="circle-chart__subline" x="16.91549431" y="20.5" alignment-baseline="central" text-anchor="middle" font-size="2"><?=$note_set[1]?></text>
                      </g>
                    </svg>
                  </section>
                  <section>
                    <h2>NPS</h2>
                    <svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" width="125" height="125" xmlns="http://www.w3.org/2000/svg">
                      <circle class="circle-chart__background" stroke="#efefef" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
                      <circle class="circle-chart__circle circle-chart__circle--negative" stroke="<?=$colour_set[2]?>" stroke-width="2" stroke-dasharray="<?=$percentage_set[2] / 10?>" stroke-linecap="round" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"                />
                      <g class="circle-chart__info">
                        <text class="circle-chart__percent" x="16.91549431" y="15.5" alignment-baseline="central" text-anchor="middle" font-size="8"><?=$percentage_set[2] / 10?>%</text>
                        <text class="circle-chart__subline" x="16.91549431" y="20.5" alignment-baseline="central" text-anchor="middle" font-size="2"><?=$note_set[2]?></text>
                      </g>
                    </svg>
                  </section>
                  <section>
                    <h2>Acquisition</h2>
                    <svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" width="125" height="125" xmlns="http://www.w3.org/2000/svg">
                      <circle class="circle-chart__background" stroke="#efefef" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
                      <circle class="circle-chart__circle circle-chart__circle--negative" stroke="<?=$colour_set[3]?>" stroke-width="2" stroke-dasharray="<?=$percentage_set[3] / 10?>" stroke-linecap="round" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                      <g class="circle-chart__info">
                        <text class="circle-chart__percent" x="16.91549431" y="15.5" alignment-baseline="central" text-anchor="middle" font-size="8"><?=$percentage_set[3] / 10?>%</text>
                        <text class="circle-chart__subline" x="16.91549431" y="20.5" alignment-baseline="central" text-anchor="middle"  font-size="2"><?=$note_set[3]?></text>
                      </g>
                    </svg>
                  </section>
              </div>
            </div>
          </div>
          <!--Begin announcements section-->
          <div class="announcements">
            <div class="announcement">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text announcement-header"><?=$announcements[0]["title"]?></h2>
              </div>
              <div class="description">
                <div class="tags">
                <?=create_labels(0)?>
                </div>
                <div class="date">
                  <?=$announcements[0]["last_update"]?>
                </div>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="/communications#<?=$announcements[0]["post_id"]?>">
                  View Updates
                </a>
              </div>
            </div>
            <div class="announcement">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text announcement-header"><?=$announcements[1]["title"]?></h2>
              </div>
              <div class="description">
                <div class="tags">
                  <?=create_labels(1)?>
                </div>
                <div class="date">
                  <?=$announcements[1]["last_update"]?>
                </div>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="/communications#<?=$announcements[1]["post_id"]?>">
                  View Updates
                </a>
              </div>
            </div>
            <div class="announcement">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text announcement-header"><?=$announcements[2]["title"]?></h2>
              </div>
              <div class="description">
                <div class="tags">
                  <?=create_labels(2)?>
                </div>
                <div class="date">
                  <?=$announcements[2]["last_update"]?>
                </div>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="/communications#<?=$announcements[2]["post_id"]?>">
                  View Updates
                </a>
              </div>
            </div>
            <div class="announcement">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text announcement-header"><?=$announcements[3]["title"]?></h2>
              </div>
              <div class="description">​
                <div class="tags">
                  <?=create_labels(3)?>
                </div>
                <div class="date">
                  <?=$announcements[3]["last_update"]?>
                </div>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="/communications#<?=$announcements[3]["post_id"]?>">
                  View Updates
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>