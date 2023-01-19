<?php

function listarArquivos($diretorio) {
    $arquivos = scandir($diretorio);

    // percorre cada item no diretório
    foreach($arquivos as $arquivo) {
        // ignora os itens "." e ".."
        if($arquivo == "." || $arquivo == ".." ) {
            continue;
        }

        // constroi o caminho completo do item
        $caminho = $diretorio . "/" . $arquivo;

        // verifica se o item é um diretório
        if(is_dir($caminho)) {
            // se for um diretório, chama a função novamente
            listarArquivos($caminho);
        } else {

            //obtem o diretorio intermediorio 
            $dirS3 = dirname($caminho);            
            $dirS3 = str_replace('files/', '', $dirS3);

            //verifica se nao é o diretorio base
            if($dirS3 != "files"){

                if(!is_dir("destino/$dirS3/")){
                    //criar diretorio no destino se nao existir 
                    mkdir("destino/$dirS3/", 0777, true);
                    copy($caminho, "destino/$dirS3/$arquivo");
                    return true;
                }else{
                    //se o diretorio ja existir somente criar o arquivo no destino
                    copy($caminho, "destino/$dirS3/$arquivo");
                    return true;
                }
                
            }else {
                $arquivoS3 =  str_replace('files/', '', $caminho);
                copy($caminho, "destino/$arquivo");
                return true;
            }
            
        }
    }
}

if(listarArquivos("files")){
    echo "success";
}
?>
