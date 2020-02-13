<?php 
$root = $_SERVER["DOCUMENT_ROOT"] . substr($_SERVER["REDIRECT_URL"], 8);
function scan($dir)
{
    $redirect = $_SERVER["REDIRECT_URL"];
    if(is_dir($dir))//Si c'est un dossier
    {
        if($open = opendir($dir))//Ouvre un dossier
        {
            //Create two button backward and forward
            echo '<a href="javascript:history.forward();">---></a></br></br>';
            echo '<a href="javascript:history.back();"><---</a></br></br>';
            while($read = readdir($open))//Lis un dossier
            {
                // echo '<a href="' . $read .'">'.$read .'</a>' . "</br>";
                if($read != "." && $read != "..")//Enleve les dossiers parents & courants
                {
                    if(is_dir($dir."/".$read))//Verifie si il y a un dossier dans le dossier
                    {
                        $path = $_SERVER["DOCUMENT_ROOT"] . $_SERVER["REDIRECT_URL"];
                        echo '<img src="/my_h5ai/image/folder.png" alt="folder"/><a href="' . $redirect ."/". $read .'">' . $read .'</a>' . "<span> Last modifications at : " . date ('F d Y H:i:s.', filemtime($dir."/".$read)) . "</span></br></br>";
                    }
                    else
                    {
                        $ext = pathinfo($dir."/".$read, PATHINFO_EXTENSION);//Verifie si le chemin possède l'extension voulu, sinon affiche une icone par défault
                        if($ext === "png" OR $ext === "php" OR $ext === "js" OR $ext === "pdf" OR $ext === "json" OR $ext === "css" OR $ext === "docx" OR $ext === "html" OR $ext === "jpg" OR $ext === "sql")
                        {
                            echo '<img src="/my_h5ai/image/'.$ext.'.png" alt ="icone"/><a href="?file=' . $dir . '/' . $read .'">'.$read .'</a>' . "<span> Size : " . filesize($dir."/".$read) . " octets" ." | ". "Last modifications at : " . date ('F d Y H:i:s.', filemtime($dir."/".$read)) . "</span></br></br>";
                        }
                        else 
                        {
                            echo '<img src="/my_h5ai/image/other.png" alt ="other"/><a href="?file=' . $dir . '/' . $read .'">'.$read .'</a>' . "<span> Size : " . filesize($dir."/".$read) . " octets" ." | ". "Last modifications at : " . date ('F d Y H:i:s.', filemtime($dir."/".$read)) . "</span></br></br>";
                        }
                    } 
                }
            }
            closedir($open);
        }
    }
    else
    {
        echo "No folder found";
    }
}

if (isset($_GET['file']))
{
    echo '<pre>';
    $taille = filesize($_GET['file']);
    $filetim = filemtime($_GET['file']);
    if($openfile = fopen($_GET['file'], 'r'))
    {
        while($lire = fread($openfile, $taille))
        {
            $pathget = pathinfo($_GET['file'], PATHINFO_EXTENSION);//Recupère l'extention
            if($pathget === "png" OR $pathget === "jpg" OR $pathget === "jpeg" OR $pathget === "ico") 
            {
                $filecontent = base64_encode(file_get_contents($_GET['file']));//Encode une chaîne en MIME base64
                $src = 'data: ' .mime_content_type($_GET['file']) . ';base64,' .$filecontent;
                echo '<img src="' . $src . '">';
            }
            else
            {
                $content = htmlentities(file_get_contents($_GET['file']));
                echo $content;
            }
            echo '<pre>';
            echo "Size = " . $taille."octets";
            echo '</pre>';
            echo '<pre>';
            echo "Last modifications at : " . date ('F d Y H:i:s.', $filetim);
            echo '</pre>';
            echo '<a href="#null" onclick="javascript:history.back();"><---</a></br></br>';
        }   
    }
    echo '</pre>';
}
else
{
    scan($root);
}