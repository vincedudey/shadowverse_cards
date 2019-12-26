<!DOCTYPE html>
<html>
<style>
<?php include 'sv.css'; ?>
</style>
<body>

<div class="topnav">
  <a class="active" href="index.php">Home</a>
  <a href="#Neutral">Neutral</a>
  <a href="bloodcraft.php">Blood</a>
  <a href="#Dragoncraft">Dragon</a>
  <a href="#Forestcraft">Forest</a>
  <a href="#Havencraft">Haven</a>
  <a href="#Portalcraft">Portal</a>
  <a href="#Runecraft">Rune</a>
  <a href="#Shadowcraft">Shadow</a>
  <a href="#Swordcraft">Sword</a>
</div>

<h1>Please make a selection</h1>

<div>
<form method="post" action="" name="form">
<select name="class">
  <option value="">Class</option>
  <option value="Neutral">Neutral</option>
  <option value="Bloodcraft">Bloodcraft</option>
  <option value="Dragoncraft">Dragoncraft</option>
  <option value="Forestcraft">Forestcraft</option>
  <option value="Havencraft">Havencraft</option>
  <option value="Portalcraft">Portalcraft</option>
  <option value="Runecraft">Runecraft</option>
  <option value="Shadowcraft">Shadowcraft</option>
  <option value="Swordcraft">Swordcraft</option>
</select>

<select name="card_pack">
  <option value="">Card Pack</option>
  <option value="Basic">Basic</option>
  <option value="Classic">Classic</option>
  <option value="Promo">Promo</option>
  <option value="Altersphere">Altersphere</option>
  <option value="Bahamut">Rise of Bahamut</option>
  <option value="Brigade">Brigade of the Sky</option>
  <option value="Chronogenesis">Chronogenesis</option>
  <option value="Colosseum">Ultimate Colosseum</option>
  <option value="Darkness">Darkness Evolved</option>
  <option value="Dawnbreak">Dawnbreak Nightedge</option>
  <option value="Glory">Rebirth of Glory</option>
  <option value="Omen">Omen of Ten</option>
  <option value="Rebellion">Steel Rebellion</option>
  <option value="Starforged">Starforged Legends</option>
  <option value="Tempest">Tempest of the Gods</option>
  <option value="Verdant">Verdant Conflict</option>
  <option value="Wonderland">Wonderland Dreams</option>
  <option value="-">Other</option>
</select>

<select name="trait">
  <option value="">Trait</option>
  <option value="Artifact">Artifact</option>
  <option value="Commander">Commander</option>
  <option value="Earth Sigil">Earth Sigil</option>
  <option value="Levin">Levin</option>
  <option value="Loot">Loot</option>
  <option value="Machina">Machina</option>
  <option value="Mysteria">Mysteria</option>
  <option value="Natura">Natura</option>
  <option value="Officer">Officer</option>
  <option value="-">Other</option>
</select>

<select name="rarity">
  <option value="">Rarity</option>
  <option value="Bronze">Bronze</option>
  <option value="Silver">Silver</option>
  <option value="Gold">Gold</option>
  <option value="Legendary">Legendary</option>
</select>

<select name="cost">
  <option value="">PP Cost</option>
  <option value="0">0</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
  <option value="6">6</option>
  <option value="7">7</option>
  <option value="8">8</option>
  <option value="9">9</option>
  <option value="10">10+</option>
</select>
<input style="width:200px" type="text" name="text" id="search" value="" placeholder="Contains...">
<input name="submit" type="submit">
</form>
</div>

<br>
<button onclick="averageCostOfCard()">Average PP cost of filtered results</button>
<p id="averageCostOfCard"></p>
<button onclick="averageHealthOfCard()">Average Unevolved Health of filtered results</button>
<p id="averageHealthOfCard"></p>
<button onclick="averageAttackOfCard()">Average Unevolved Attack of filtered results</button>
<p id="averageAttackOfCard"></p>

<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "mHv3y4Py5pN0CPUd";
$db = "shadowverse";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
if($conn->connect_error){
  die("Connection failed: ". $conn->connect_error);
}
else{
    echo "<br><div>Connected Successfully!</div><br>";
}

$class="";
$card_pack="";
$trait="";
$rarity="";
$cost="";
$text="";

$where=array();

if(isset($_POST['class'])){
  $class=$_POST['class'];
  if($class!='')$where[]="Class = '$class'";
}

if(isset($_POST['card_pack'])){
  $card_pack=$_POST['card_pack'];
  if($card_pack!='')$where[]="Card_Pack = '$card_pack'";
}

if(isset($_POST['trait'])){
  $trait=$_POST['trait'];
  if($trait!='')$where[]="Trait = '$trait'";
}

if(isset($_POST['rarity'])){
  $rarity=$_POST['rarity'];
  if($rarity!='')$where[]="Rarity = '$rarity'";
}

if(isset($_POST['cost'])){
  $cost=$_POST['cost'];
  if($cost!='')$where[]="Cost = '$cost'";
}

if(isset($_POST['text'])){
  $text=$_POST['text'];
  if($text!=''){
    preg_replace("/[^A-Za-z ]/", '', $text);
    $texts=explode(" ",$text);
    foreach($texts as $item){
        $where[]="(Unevolved_Skill LIKE '%$item%' OR Evolved_Skill LIKE '%$item%' OR Card_Name LIKE '%$item%')";
    }
  }
}

$where_string = implode(' AND ', $where);
$sql = "SELECT * FROM cards";// cards WHERE (Card_Pack='$card_pack' OR '$card_pack' IS NULL) AND Class='$class' AND Rarity='$rarity'";
if($where){
  $sql .=' WHERE '.$where_string;
}
echo "<div>".$sql."</div><br>";
$result = $conn->query($sql);

echo "<body style='background-color:black'>";
$return_result=array();
if($result->num_rows>0){
  while($row=$result->fetch_assoc()){
    $temp=array();
    array_push($temp,$row['ID'],$row['Card_Name'],$row['Trait'],$row['Class'],$row['Cost']);
    array_push($temp,$row['Type'],$row['Rarity'],$row['Create'],$row['Liquefy'],$row['Card_Pack']);
    array_push($temp,$row['Card_Pack'],$row['Unevolved_Atk'],$row['Unevolved_Life'],$row['Evolved_Atk'],$row['Evolved_Life']);
    array_push($temp,$row['Unevolved_Skill']);
    if($row['Rarity']=='Bronze')
      echo "<div class='bronze'>";
    elseif ($row['Rarity']=='Silver') {
      echo "<div class='silver'>";
    }
    elseif ($row['Rarity']=='Gold') {
      echo "<div class='gold'>";
    }
    else{
      echo "<div style='color:gold' class='legendary'>";
    }
    echo "<h1 align='center' style='width:100%; font-size:30px'><i><u>". $row['Card_Name']."</u></i></h1>";
    echo "<p style='margin-left:20px'>Trait: ". $row['Trait']."</p>";
    echo "<p style='margin-left:20px'>Class: ". $row['Class']."</p>";
    echo "<p style='margin-left:20px'>Rarity: ". $row['Rarity']."</p>";
    echo "<p style='margin-left:20px'>Create: ". $row['Create']."</p>";
    echo "<p style='margin-left:20px'>Liquefy: ". $row['Liquefy']."</p>";
    echo "<p style='margin-left:20px'>Card Pack: ". $row['Card_Pack']."</p>";
    echo "<p style='margin-left:20px'>Unevolved Skill: ". $row['Unevolved_Skill']. "<p>";
    echo "<p style='margin-left:20px'>Evolved Skill: ". $row['Evolved_Skill']. "</p>";
    echo "<div style='clear: both'>";
    if($row['Type']=="Followers"){
      echo "<h3 style='float:left; margin-left:140px'>Unevolved Form</h3>";
      echo "<h3 style='float:right; margin-right:150px'>Evolved Form</h3>";
      array_push($temp,$row['Evolved_Skill']);
    }

    echo "</div>";
    echo "<div style='clear: both'>";
    echo '<img style="float:left; margin-left:20px; margin-bottom:20px" src="'.$row['Unevolved_Image'].'" width="375" height="500"/>';
    if($row['Evolved_Image']!=''){
      echo '<img style="float:right; margin-right:20px; margin-bottom:20px" src="'.$row['Evolved_Image'].'" width="375" height="500"/>';
    }
    echo "</div>";
    echo "</div>";
    echo "<br>";
    array_push($return_result,$temp);
  }
}
else{
  echo "<br>0 results<br>";
}

echo sizeof($return_result);
echo "<br>Query successful<br>";
$conn->close();

?>

<script type="text/javascript">
  var js_result=<?php echo json_encode($return_result); ?>;
  console.log(js_result);
  function averageCostOfCard(){
      var avgCost=0;
      for(var i=0; i<js_result.length; ++i){
         avgCost+=parseInt(js_result[i][4]);
      }
      avgCost/=js_result.length;
      document.getElementById("averageCostOfCard").innerHTML="Average PP cost of filtered cards: "+avgCost.toFixed(2);
  }

  function averageHealthOfCard(){
      var avgHealth=0;
      var valid=0;
      for(var i=0; i<js_result.length; ++i){
         if(js_result[i][5]=="Followers"){
           avgHealth+=parseInt(js_result[i][12]);
           valid++;
         }
      }
      avgHealth/=valid;
      document.getElementById("averageHealthOfCard").innerHTML="Average unevolved health of filtered cards: "+avgHealth.toFixed(2);
  }

  function averageAttackOfCard(){
      var avgAttack=0;
      var valid=0;
      for(var i=0; i<js_result.length; ++i){
         if(js_result[i][5]=="Followers"){
           avgAttack+=parseInt(js_result[i][11]);
           valid++;
         }
      }
      avgAttack/=valid;
      document.getElementById("averageAttackOfCard").innerHTML="Average unevolved attack of filtered cards: "+avgAttack.toFixed(2);
  }

</script>

</body>
</html>
