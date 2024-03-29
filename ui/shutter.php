<!doctype html>

<?php
  $URL_BASE = "http://13.53.174.25:5000/resource/";
  $URL_TYPE = "shutters";
  //echo $URL_BASE.$URL_TYPE;
  $contents = file_get_contents($URL_BASE.$URL_TYPE);
  $arr = json_decode($contents, true);
  $wind = file_get_contents($URL_BASE."windwatcher");
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css">
    <title>SmartHome - Jalousien</title>
	<?php include 'header.inc.php'; ?>

  </head>
  <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <center>

      <p class='ueberschrift1'><img src='sym/toggle-on.svg'> Jalousiesteuerung</p>
      <p class='ueberschrift2'>Einzelsteuerung</p>
      <table align='center' border='0' width='80%' class='control'>

            <?php
            foreach($arr as $key => $val) {
              echo "
              <tr>
          			<td align='center' border='1' width='5%'>
                ";

                if ($val == 0)
                {
                  echo "<img src='sym/shutter/caret-up-square.svg'>";
                }
                else
                {
                  echo "<img src='sym/shutter/caret-down-square-fill.svg'>";
                }

                echo "
                </td>
          			<td align='center' border='1' width='25%'>
                ";
                echo $key;
                echo "</td>
                <td align='center' border='1' width='5%'>
                ";
                echo strval($val);
                echo "%";
                echo "</td>
                <td id='$key' align='center' border='1' width='5%'><img src='sym/trash.svg'
                onClick='sendDELETE(\"$key\")'></td>
                <td align='center' border='1' width='5%'><img src='sym/shutter/chevron-up.svg'";
                if($val != 0)
                {
                  echo " onClick='sendPUT(\"$key\", ($val-10))";
                }
                echo "'></td>
                <td align='center' border='1' width='5%'><img src='sym/shutter/chevron-down.svg'";
                if($val < 100)
                {
                  echo " onClick='sendPUT(\"$key\", ($val+10))";
                }
                echo "'></td>
                <td align='center' border='1' width='5%'>
                ";

                if ($val <= 0)
                {
                  echo "<img src='sym/shutter/chevron-double-down.svg' onClick='sendPUT(\"$key\", 100)'>";
                }
                else
                {
                  echo "<img src='sym/shutter/chevron-double-up.svg' onClick='sendPUT(\"$key\", 0)'>";
                }

                echo "
                </td>
          		</tr>
              ";
            }
            ?>
            <script>

            function sendDELETE(name = "light1")
            {
              var url = "<?php
              echo $URL_BASE.$URL_TYPE."/";
              ?>" + name;

              var xhr = new XMLHttpRequest();
              xhr.open("DELETE", url, true);

              xhr.onreadystatechange = function () {
                 if (xhr.readyState === 4) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                 }};
              xhr.send();

              setTimeout(function()
              {
                location.reload();
              }, 100);

            }

            function sendPUT(name, state)
            {
              var url = "<?php
              echo $URL_BASE.$URL_TYPE."/";
              ?>" + name + "?state=" + state;

              var xhr = new XMLHttpRequest();
              xhr.open("PUT", url, true);

              xhr.onreadystatechange = function () {
                 if (xhr.readyState === 4) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                 }};
              xhr.send();
              setTimeout(function()
              {
                location.reload();
              }, 100);
            }

            function sendPUTmaster(command, state)
            {
              var url = "<?php
              echo $URL_BASE.$URL_TYPE;
              ?>" + "?" + command + "=" + state;

              var xhr = new XMLHttpRequest();
              xhr.open("PUT", url, true);

              xhr.onreadystatechange = function () {
                 if (xhr.readyState === 4) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                 }};
              xhr.send();
              setTimeout(function()
              {
                location.reload();
              }, 100);
            } //ce/lights?scene=lesen
        </script>

        <tr>
          <td align='center' border='1' colspan='7' class='button'>
            Neue Jalousie anlegen
            <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
            <form action="<?php
            echo $URL_BASE.$URL_TYPE;
            ?>" id="newLight" target="dummyframe" method='post'>
              <label for="name">Name: </label>
              <input type="text" name="name" id="name" maxlength="30">
              <button type="submit" onclick="location.reload()">Anlegen</button>
            </form>
          </td>
        </tr>
    	</table>

      <p class='ueberschrift2'>Mastersteuerung</p>
      <table align='center' border='0'>
    		<tr>
    			<td align='center' border='0' width='35%' class='button' onClick='sendPUTmaster("state", 0)'><img src='sym/shutter/chevron-double-up.svg'><br>Alle HOCH</td>
          <td align='center' border='0' width='10%'></td>
    			<td align='center' border='0' width='35%' class='button' onClick='sendPUTmaster("state", 100)'><img src='sym/shutter/chevron-double-down.svg'><br>Alle RUNTER</td>
    		</tr>
    	</table>

      <p class='ueberschrift1'><img src='sym/weather/air.svg'> Windw&auml;chter</p>
      <table align='center' border='0'>
        <tr>
          <td align='right' border='0' width='15%'>Windwächter aktiv:</td>
          <td align='center' border='0' width='2%'></td>
          <td align='left' border='0' width='15%'><?php
          if($wind == 0)
          {
            echo "<img src='sym/x-lg.svg'>";
          }
          else
          {
            echo "<img src='sym/check-lg.svg'>";
          }?></td>
        </tr>
      </table>

    </center>
  </body>
</html>
