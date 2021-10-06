window.onload = function setUpdateInterval()
{
    setInterval(getFreshData, 30000);
}

function getFreshData()
{
    $.getJSON("nodeStatus.php", updateData);
}

function updateData(data)
{
    if(!(data.getnetworkinfo === undefined))
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
        conEl.innerText = "Connections: " + newConnectionCount;
    }

    if(!(data.getwalletinfo === undefined))
    {
        let walletLock = document.getElementById('walletLock');
        if(data.getwalletinfo.staking)
        {
            walletLock.innerText = "Staking";
            walletLock.className = "content green";
        } else
        {
            walletLock.innerText = "Wallet locked";
            walletLock.className = "conetnt red";
        }
    }

    if(!(data.showblock === undefined))
    {
        document.getElementById('blockCount').innerText = data.showblock.height;
        document.getElementById('difficulty').innerText = data.showblock.difficulty;
        document.getElementById('hash').innerText = data.showblock.hash;
        document.getElementById('CPID').innerText = data.showblock.CPID;
        document.getElementById('clientVersion').innerText = data.showblock.ClientVersion;
    }
}
