<?php

  require ("../functions.php");
  require("../class/User.class.php");

 $User = new User ($mysqli);

require("../class/Helper.class.php");
$Helper = new Helper();
//kas kasutaja on sisse logitud

if  (isset ($_SESSION ["userId"])) {
  //suunab sisselogimise lehele
      header ("Location: data3.php");
      exit ();
}

    //var_dump ($_GET); //var_dump näitab kõike, mis on muutujaks määratud-
    // näitab andmetüüpi ja väärtust

    //echo "<br>"; echo trükib stringi sõnumina välja

    //var_dump ($_POST);



    // kontrollin, kas vajalikud andmed olid olemas:

    // MUUTUJAD:

// REGISTREEMISMUUTUJAD
$signUpEmail = ""; //alguses on ta tühi väärtus
$signUpName = "";
$signUpFamilyName = "";
$signUpEmailError = "";
$signUpPasswordError = "";
$signUpNameError = "";
$signUpFamilyNameError = "";
$gender = "";


// //SISSELOGIMISMUUTUJAD
// $loginEmail = "";
// $loginPassword = "";
// $loginEmailError = "";
// $loginPasswordError = "";


//
// if (isset ($_POST["loginEmail"]) ) {
//   //kas keegi vajutas submit nuppu nii, et e-mail oli olemas!
//     if (empty ($_POST["loginEmail"] ) ) {
//               // e-maili koht oli TÜHI!
//     $loginEmailError =  "See väli on kohustuslik";
//
//     } else {
//         //e-mail on õige, oli olemas!
//     $loginEmail = $_POST["loginEmail"];
//     }
//
//        }
// if (isset ($_POST["loginPassword"]) ) {
//     if (empty ($_POST["loginPassword"] ) ) {
//     $loginPasswordError =  "See väli on kohustuslik";
//     } else {
//             //tean, et parool oli ja see ei olnud tühi. vähemalt 8!
//             if (strlen($_POST["loginPassword"]) < 8 ) {
//               $loginPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk";
//         }
//
//             }
//                  }

    if (isset ($_POST["signUpEmail"]) ) {
//kas keegi vajutas submit nuppu nii, et e-mail oli olemas!
            if (empty ($_POST["signUpEmail"] ) ) {
            // e-maili koht oli TÜHI!
                $signUpEmailError =  "See väli on kohustuslik";

            } else {

              //e-mail on õige, oli olemas!
              $signUpEmail = $_POST["signUpEmail"];
            }

       }
      if (isset ($_POST["signUpPassword"]) ) {

                  if (empty ($_POST["signUpPassword"] ) ) {

                $signUpPasswordError =  "See väli on kohustuslik";

                } else {

          //tean, et parool oli ja see ei olnud tühi. vähemalt 8!

      if (strlen($_POST["signUpPassword"]) < 8 ) {

                    $signUpPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk";
                    }
                    }
                    }

      if (isset ($_POST["signUpName"]) ) {

            if (empty ($_POST["signUpName"] ) ) {
          // oli nimi, kuid see oli tühi
            $signUpNameError =  "See väli on kohustuslik";

          } else {
              $signUpName = $_POST["signUpName"];
          }
              }

        if (isset ($_POST["signUpFamilyName"]) ) {


                    if (empty ($_POST["signUpFamilyName"] ) ) {
          // oli e-mail, kuid see oli tühi
                $signUpFamilyNameError =  "See väli on kohustuslik";

              } else {
                  $signUpFamilyName = $_POST["signUpFamilyName"];
                  if (strlen($_POST["signUpFamilyName"]) < 1 ) {

                //  $signUpFamilyNameError = "Perekonnanimi peab olema vähemalt 1 tähemärk pikk!";
              }
          }
          //tean, et perekonnanimi oli ja see ei olnud tühi


          //if (isset ($_POST["btnsubmit"]) ) {
          if (isset ($_POST["gender"]) )
                  if (empty ($_POST["gender"] ) ) {
                //  $answer = $_POST['radioGender'];

                  $gender = $_POST["gender"];
              // } else {
                  //$gender = $_POST["gender"];
              //  $genderError =  "See väli on kohustuslik";
          }

    }


//Tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada:

if (  isset($_POST["signUpPassword"]) &&
      isset($_POST["signUpEmail"]) &&
      isset ($_POST["signUpName"]) &&
      isset ($_POST["signUpFamilyName"]) &&
      empty ($signUpEmailError) &&
      empty ($signUpNameError) &&
      empty ($signUpFamilyNameError) &&
      empty ($signUpPasswordError) )
      {

      echo "Salvestan... <br>";
      echo "email ".$signUpEmail."<br>";
      echo "password: ".$_POST["signUpPassword"]."<br>";

      $password = hash ("sha512", $_POST["signUpPassword"]);

      // echo "parool" .$_POST["signUpPassword"]. "<br>";
      // //echo "räsi ".$password."<br>";
      // echo "eesnimi" .$signUpName. "<br>";
      echo "password hashed: ".$password."<br>";

      // $signUpEmail = cleanInput($signUpEmail);
      // $signUpPassword = cleanInput ($password);
      // $signUpName = cleanInput ($signUpName);
      // $signUpFamilyName = cleanInput ($signUpFamilyName);
      // echo $serverUsername;  ECHO ON PRINTIMINE!!
      //signup ($signUpEmail, $password, $signUpName, $signUpFamilyName, $gender);

      //$signUpEmail = $Helper->cleanInput($signUpEmail);

      $signUpEmail = $Helper->cleanInput($signUpEmail);

		  $User->signUp($signUpEmail, $Helper->cleanInput($password));
}
$error = " ";
// kontrollin, et kasutaja täitis väljad ja võib sisse logida
if ( isset ($_POST ["loginEmail"]) &&
     isset ($_POST ["loginPassword"]) &&
     !empty ($_POST ["loginEmail"]) &&
     !empty ($_POST ["loginPassword"])
) {


// login sisse

          $error = $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"]));
}
?>


  <?php require("../header.php"); ?>

  <div class = "container">
  <div class = "row">
  <div class ="col-sm-3 col-sm-offset-3">

    <h1>Logi sisse</h1>
    <form method="POST">
      <p style="color:red;"><?=$error;?></p>
      <label>E-post</label>
      <br>
      <div class = "form-group">
      <input class = "form-control" name="loginEmail" type="text">

    </div>
      <input type="password" name="loginPassword" placeholder="Parool">
      <br><br>

      <input class = "btn btn-success btn-sm"  type="submit" value="Logi sisse">
      <input class="btn btn-success btn-sm btn-block visible-xs-block" type="submit" value="Logi sisse 2">
    </form>
  </div>

  <div class ="col-sm-4 col-md-3 col-sm-offset-4">


  <h1>Loo kasutaja</h1>

      <form method = "POST">
        <input name ="signUpEmail" type = "email" placeholder="e-posti aadress" value="<?=$signUpEmail;?>"> <?php echo $signUpEmailError; ?>
        <br><br>
        <!--type input ja value on atribuudid -->
        <!--type määrab tüübi, value on väärtus-->


        <input  name = "signUpPassword" type = "password" placeholder="parool"> <?php echo $signUpPasswordError; ?>

        <br><br>

        <input name = "signUpName" type = "name" placeholder ="Sinu nimi" value= "<?=$signUpName;?>"> <?php echo $signUpNameError; ?>

        <br><br>

        <input name = "signUpFamilyName" type = "familyname" placeholder = "Sinu perekonnanimi" value= "<?=$signUpFamilyName;?>"> <?php echo $signUpFamilyNameError; ?>
        <br><br>

        <?php if($gender == "male") { ?>
                <input type="radio" name="gender" value="male" checked> Mees<br>
               <?php } else { ?>
                <input type="radio" name="gender" value="male" > Mees<br>
               <?php } ?>

               <?php if($gender == "female") { ?>
                <input type="radio" name="gender" value="female" checked> Naine<br>
               <?php } else { ?>
                <input type="radio" name="gender" value="female" > Naine<br>
               <?php } ?>

               <?php if($gender == "other") { ?>
                <input type="radio" name="gender" value="other" checked> Muu<br>
               <?php } else { ?>
                <input type="radio" name="gender" value="other" > Muu<br>
               <?php } ?>
               <input type = "submit" value = "REGISTREERU">
          </form>
        </div>
      </div>
</div>


<p> Wanna go clubbing? Can't find a proper place? Me aitame Sind! ClubOGo aitab valida klubide seast <br>
  külastajate
  hinnangutele tuginedes selle õige. Et ükski pilet poleks "waste of money". Liitu kohe!</p>
<?php require("../footer.php"); ?>
