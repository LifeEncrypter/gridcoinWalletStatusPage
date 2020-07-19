<!DOCTYPE html>
<html  lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="Description" content="Gridcoin Node grcnode.fulda.tech stats">
    <meta name="author" content="Eike Fulda">
    <title>grcnode.fulda.tech</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" href="images/Gridcoin_32x32.png">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  </head>
  <body>
    <header>
      <h1>Willkommen bei grcnode.fulda.tech</h1>
    </header>
    <main>
        <?php
        require_once('GridcoinRPC.php');
        $gridcoin = new GridcoinRPC();
        $newestblock = $gridcoin->multiCall(array(array("getblockcount", [])));
        $calls = array(
            array("getnetworkinfo", []),
            array("showblock", [$newestblock["getblockcount"]])
        );
        $nodeStatus = $gridcoin->multiCall($calls);
        ?>
        <div class='wrapper'>
          <div class='content'>
            <div id='nodeVersion'>Version: <?php echo $nodeStatus['getnetworkinfo']['version']?></div>
          </div>
          <?php
          echo "<div id='connectionCount' class='content ";
            if($nodeStatus['getnetworkinfo']['connections'] > 8) {
              echo "green'>";
            } else if($nodeStatus['getnetworkinfo']['connections'] > 0) {
              echo "orange'>";
            } else {
              echo "red'>";
            }
            echo "Connections: " . $nodeStatus['getnetworkinfo']['connections'];
            ?>
          </div>
          <!-- $getinfo = $gridcoin->getinfo();
          if(array_key_exists('unlocked_until', $getinfo)) {
              echo "<div class='content' ";
              if ($getinfo['unlocked_until'] != 0) {
                  echo "id='green'>";
                  echo "Staking";
              } else {
                  echo "id='red'>";
                  echo "Wallet locked";
              }
              echo "</div>";
          } -->
          <div class='content'>
            <div class='contentList'>
              <?php
              echo "<b>Newest Block:</b><br><div id='blockCount'>" . $nodeStatus['showblock']['height'] . "</div><br>";
              echo "<b>Difficulty:</b><br><div id='difficulty'>" . $nodeStatus['showblock']['difficulty'] . "</div><br>";
              echo "<b>Blockhash:</b><br><div id='hash'>" . $nodeStatus['showblock']['hash'] . "</div><br>";
              echo "<b>Mined by:</b><br><div id='CPID'>" . $nodeStatus['showblock']['CPID'] . "</div>";
              echo "<b>With client version:</b><br><div id='clientVersion'>" . $nodeStatus['showblock']['ClientVersion'] . "</div><br>";
              ?>
            </div></div></div>
        <script>
            setInterval(getFreshData, 30000);

            function getFreshData()
            {
                $.getJSON("nodeStatus.php", updateData);
            }
            
            function updateData(data)
            {
                document.getElementById('nodeVersion').innerText = "Version: " + data.getnetworkinfo.version;

                let newConnectionCount = data.getnetworkinfo.connections;
                let conEl = document.getElementById("connectionCount");
                if(newConnectionCount > 8)
                {
                    conEl.className = "content green";
                } else if( newConnectionCount > 0)
                {
                    conEl.className = "content orange";
                } else
                {
                    conEl.className = "content red";
                }
                conEl.innerText = newConnectionCount;

                document.getElementById('blockCount').innerText = data.showblock.height;
                document.getElementById('difficulty').innerText = data.showblock.difficulty;
                document.getElementById('hash').innerText = data.showblock.hash;
                document.getElementById('CPID').innerText = data.showblock.CPID;
                document.getElementById('clientVersion').innerText = data.showblock.ClientVersion;
            }
        </script>
    </main>
    <footer>
      <!--<img alt="Gridcoin Logo" src="https://raw.githubusercontent.com/gridcoin-community/Gridcoin-Marketing/master/Gridcoin%20Logos/PNG%20Format/Horizontal/GRCHorizontal_Purple_Transparent.png">-->
      <img alt="Gridcoin Horizontal Logo" src="images/GRCHorizontal_Purple_Transparent_1080.webp">
    </footer>
  </body>
</html>

