<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Eike Fulda">
    <?php $config = require_once('../config.php');
    echo "<meta name=\"Description\" content=\"" . $config['siteDescription'] . "\">\n";
    echo "\t<title>" . $config['siteName'] . "</title>\n";?>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="icon" type="image/png" href="../images/Gridcoin_32x32.png">
  </head>
  <body>
    <header>
      <h1>Willkommen bei <?php echo $config['siteName']?></h1>
    </header>
    <main>
        <?php
        require_once('grcJSON.php');
        $block = block();

        echo "<div class='wrapper'>
          <div class='content'>
            <div id='blockHeight'>" . $block['height'] . "</div>
          </div>";
          echo "<div class='content'>
            <div class='contentList'>
              <b>Confirmations:</b><br><div id='confirmations'>" . $block['confirmations'] . "</div><br>
              <b>Difficulty:</b><br><div id='difficulty'>" . $block['difficulty'] . "</div><br>
              <b>Blockhash:</b><br><div id='hash'>" . $block['hash'] . "</div><br>
              <b>Mined by:</b><br><div id='CPID'>" . $block['claim']['mining_id'] . "</div>
              <b>With client version:</b><br><div id='client_version'>" . $block['claim']['client_version'] . "</div><br>
              </div></div></div>";
          ?>
    </main>
    <footer>
      <!--<img alt="Gridcoin Logo" src="https://raw.githubusercontent.com/gridcoin-community/Gridcoin-Marketing/master/Gridcoin%20Logos/PNG%20Format/Horizontal/GRCHorizontal_Purple_Transparent.png">-->
      <img alt="Gridcoin Horizontal Logo" src="../images/GRCHorizontal_Purple_Transparent.webp">
    </footer>
  </body>
</html>
