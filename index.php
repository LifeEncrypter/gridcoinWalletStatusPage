<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>grcTestNode.fulda.tech</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" href="Gridcoin_32x32.png">
  </head>
  <body>
    <header>
      <h1>Willkommen bei grcTestNode.fulda.tech</h1>
    </header>
    <main>
      <?php
        require_once('GridcoinRPC.php');
        $gridcoin = new GridcoinRPC();

        $info = $gridcoin->getnetworkinfo();
        echo "<div class='wrapper'>";
          echo "<div class='content'>";
            echo "Version: " . $info['version'];
          echo "</div>";
          echo "<div class='content' ";
            if($info['connections'] > 8) {
              echo "id='green'>";
            } else if($info['connections'] > 0) {
              echo "id='orange'>";
            } else {
              echo "id='red'>";
            }
            echo "Connections: " . $info['connections'];
          echo "</div>";
          echo "<div class='content'>";
            echo "<div class='contentList'>";
              $newestBlock = $gridcoin->getblockcount()-1;
              echo "<b>Newest Block:</b><br>" . $newestBlock . "<br><br>";
              $blockInfo = $gridcoin->showblock($newestBlock);
              echo "<b>Difficulty:</b><br>" . $blockInfo['difficulty'] . "<br><br>";
              echo "<b>Blockhash:</b><br>" . $blockInfo['hash'] . "<br><br>";
              echo "<b>Mined by:</b><br>" . $blockInfo['CPID'] . "<br>";
              echo "<b>With client version:</b><br>" . $blockInfo['ClientVersion'] . "<br>";
        echo "</div></div></div>";
      ?>
    </main>
    <footer>
      <img alt="Gridcoin Logo" src="https://raw.githubusercontent.com/gridcoin-community/Gridcoin-Marketing/master/Gridcoin%20Logos/PNG%20Format/Horizontal/GRCHorizontal_Purple_Transparent.png">
    </footer>
  </body>
</html>

