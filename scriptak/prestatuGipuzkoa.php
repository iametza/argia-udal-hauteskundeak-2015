<?php

/*
 * 0: ORDUA-HORA
 * 1: ZENB-ESCRUT.
 * 2: ESPARRUARREN KOD.-COD.AMBITO
 * 3: ESPARRUAREN IZENA-NOMBRE AMBITO
 * 4: ZENTSUA-CENSO
 * 5: HAUTETSIAK-ELECTOS
 * 6: PARTE HARTZE %-% PARTICIPACION
 * 7: HAUTESLEAK-VOTANTES
 * 8: BALIOGABEAK-NULOS
 * 9: BALIOZKOAK-VALIDOS
 * 10: ZURIAK-BLANCOS
 * 11: AURKEZTUTAKO HAUTAGAIAK
 * 12: 1 HAUTAGAIA-CANDIDATURA 1
 * 13: BALIOZKO BOTOEKIKO %-% SOBRE VOTOS VALIDOS
 * 14: BOTOAK-VOTOS
 * 15: HAUTETSIAK-ELECTOS
 * 16: 2 HAUTAGAIA-CANDIDATURA 2
 * 17: BALIOZKO BOTOEKIKO %-% SOBRE VOTOS VALIDOS
 * 18: BOTOAK-VOTOS
 * 19: HAUTETSIAK-ELECTOS
 * ...
 * 15 HAUTAGAIA-CANDIDATURA 15|BALIOZKO BOTOEKIKO %-% SOBRE VOTOS VALIDOS|BOTOAK-VOTOS|HAUTETSIAK-ELECTOS|
 *
 * 72 ANTEULT.ESCAÑO
 * 73 ULTIMO ESCAÑO
 * 74 PARTIDO 1 OPTA ULT.ESCAÑO
 * 75 VOTOS PARTIDO 1
 * 76 PARTIDO 2 OPTA ULT.ESCAÑO
 * 77 VOTOS PARTIDO 2
 * 
 */
$fh = fopen('../datuak/2015_Munizipalak_Gipuzkoa.txt','r');

$lerro_kont = 0;

function lerrotikJSONera($lerroa) {
    
    $zatiak = explode("|", $lerroa);
    
    $katea = '{';
    
    $katea .= '"ordua":"' . $zatiak[0] . '",';
    $katea .= '"zenbatua":"' . $zatiak[1] . '",';
    $katea .= '"kodea":"' . $zatiak[2] . '",';
    $katea .= '"izena":"' . $zatiak[3] . '",';
    $katea .= '"zentsua":"' . $zatiak[4] . '",';
    $katea .= '"hautetsiak":"' . $zatiak[5] . '",';
    $katea .= '"partehartzea":"' . $zatiak[6] . '",';
    $katea .= '"hautesleak":"' . $zatiak[7] . '",';
    $katea .= '"baliogabeak":"' . $zatiak[8] . '",';
    $katea .= '"baliozkoak":"' . $zatiak[9] . '",';
    $katea .= '"zuriak":"' . $zatiak[10] . '",';
    $katea .= '"hautagai_kop":"' . $zatiak[11] . '",';
    
    // EGITEKO: Hautagaien emaitzen arraya boto kopuruaren arabera ordenatzea komeni da!
    $katea .= '"emaitzak":[';
    
    for ($i = 0; $i < 15; $i++) {
        
        $katea .= '{';
        $katea .= '"izena":"' . $zatiak[12 + $i * 4] . '",';
        $katea .= '"ehunekoa":"' . $zatiak[13 + $i * 4] . '",';
        $katea .= '"botoak":"' . $zatiak[14 + $i * 4] . '",';
        $katea .= '"hautetsiak":"' . $zatiak[15 + $i * 4] . '"';
        $katea .= '}';
        
        if ($i < 14) {
            $katea .= ',';
        }
    }
    
    $katea .= '],';
    
    $katea .= '"hautagaiak":{';
    
    for ($i = 0; $i < 15; $i++) {
        
        $katea .= '"' . $zatiak[12 + $i * 4] . '": {';
        $katea .= '"ehunekoa":"' . $zatiak[13 + $i * 4] . '",';
        $katea .= '"botoak":"' . $zatiak[14 + $i * 4] . '",';
        $katea .= '"hautetsiak":"' . $zatiak[15 + $i * 4] . '"';
        $katea .= '}';
        
        if ($i < 14) {
            $katea .= ',';
        }
    }
    
    $katea .= '},';
    
    $katea .= '"azkenaurrekoa":"' . $zatiak[72] . '",';
    $katea .= '"azkena":"' . $zatiak[73] . '",';
    
    $katea .= '"azkena_aukera1":"' . $zatiak[74] . '",';
    $katea .= '"azkena_aukera1_botoak":"' . $zatiak[75] . '",';
    $katea .= '"azkena_aukera2":"' . $zatiak[76] . '",';
    $katea .= '"azkena_aukera2_botoak":"' . $zatiak[77] . '"';
    
    $katea .= '}';
    
    return $katea;
    
}

while ($lerroa = fgets($fh)) {
    
    if ($lerro_kont == 1) {
        
        file_put_contents("udalerriak-gipuzkoa-osoa.json", lerrotikJSONera($lerroa));
        
        $katea = '{"udalerriak":[';
        
    } else if ($lerro_kont >= 2 && $lerro_kont < 90) {
        
        $katea .= lerrotikJSONera($lerroa) . ',';
        
    }  else if ($lerro_kont == 90) {
        
        $katea .= lerrotikJSONera($lerroa) . ']}';
        
        file_put_contents("udalerriak-gipuzkoa.json", $katea);
        
    }
    
    $lerro_kont++;
    
}

fclose($fh);

?>