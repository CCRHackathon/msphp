    function Avaliar(estrela,categoria,index) {

        var url = window.location;
        url = url.toString()
        url = url.split("index.html");
        url = url[0];

        var s1 = document.getElementById("s1_" + categoria).src;
        var s2 = document.getElementById("s2_" + categoria).src;
        var s3 = document.getElementById("s3_" + categoria).src;
        var s4 = document.getElementById("s4_" + categoria).src;
        var s5 = document.getElementById("s5_" + categoria).src;
        var avaliacao = 0;

        if (estrela == 5){        
        if (s5 == url + "icones/star0.png") {
            document.getElementById("s1_" + categoria).src = "icones/star1.png";
            document.getElementById("s2_" + categoria).src = "icones/star1.png";
            document.getElementById("s3_" + categoria).src = "icones/star1.png";
            document.getElementById("s4_" + categoria).src = "icones/star1.png";
            document.getElementById("s5_" + categoria).src = "icones/star1.png";
            avaliacao = 5;
        } else {
            document.getElementById("s1" + categoria).src = "icones/star1.png";
            document.getElementById("s2" + categoria).src = "icones/star1.png";
            document.getElementById("s3" + categoria).src = "icones/star1.png";
            document.getElementById("s4" + categoria).src = "icones/star1.png";
            document.getElementById("s5" + categoria).src = "icones/star0.png";
            avaliacao = 4;
        }}
        
        //ESTRELA 4
        if (estrela == 4){  
        if (s4 == url + "icones/star0.png") {
            document.getElementById("s1_" + categoria).src = "icones/star1.png";
            document.getElementById("s2_" + categoria).src = "icones/star1.png";
            document.getElementById("s3_" + categoria).src = "icones/star1.png";
            document.getElementById("s4_" + categoria).src = "icones/star1.png";
            document.getElementById("s5_" + categoria).src = "icones/star0.png";
            avaliacao = 4;
        } else {
            document.getElementById("s1_" + categoria).src = "icones/star1.png";
            document.getElementById("s2_" + categoria).src = "icones/star1.png";
            document.getElementById("s3_" + categoria).src = "icones/star1.png";
            document.getElementById("s4_" + categoria).src = "icones/star0.png";
            document.getElementById("s5_" + categoria).src = "icones/star0.png";
            avaliacao = 3;
        }}

        //ESTRELA 3
        if (estrela == 3){  
        if (s3 == url + "icones/star0.png") {
            document.getElementById("s1_" + categoria).src = "icones/star1.png";
            document.getElementById("s2_" + categoria).src = "icones/star1.png";
            document.getElementById("s3_" + categoria).src = "icones/star1.png";
            document.getElementById("s4_" + categoria).src = "icones/star0.png";
            document.getElementById("s5_" + categoria).src = "icones/star0.png";
            avaliacao = 3;
        } else {
            document.getElementById("s1_" + categoria).src = "icones/star1.png";
            document.getElementById("s2_" + categoria).src = "icones/star1.png";
            document.getElementById("s3_" + categoria).src = "icones/star0.png";
            document.getElementById("s4_" + categoria).src = "icones/star0.png";
            document.getElementById("s5_" + categoria).src = "icones/star0.png";
            avaliacao = 2;
        }}
        
        //ESTRELA 2
        if (estrela == 2){  
        if (s2 == url + "icones/star0.png") {
            document.getElementById("s1_" + categoria).src = "icones/star1.png";
            document.getElementById("s2_" + categoria).src = "icones/star1.png";
            document.getElementById("s3_" + categoria).src = "icones/star0.png";
            document.getElementById("s4_" + categoria).src = "icones/star0.png";
            document.getElementById("s5_" + categoria).src = "icones/star0.png";
            avaliacao = 2;
        } else {
            document.getElementById("s1_" + categoria).src = "icones/star1.png";
            document.getElementById("s2_" + categoria).src = "icones/star0.png";
            document.getElementById("s3_" + categoria).src = "icones/star0.png";
            document.getElementById("s4_" + categoria).src = "icones/star0.png";
            document.getElementById("s5_" + categoria).src = "icones/star0.png";
            avaliacao = 1;
        }}
        
        //ESTRELA 1
        if (estrela == 1){  
        if (s1 == url + "icones/star0.png") {
            document.getElementById("s1_" + categoria).src = "icones/star1.png";
            document.getElementById("s2_" + categoria).src = "icones/star0.png";
            document.getElementById("s3_" + categoria).src = "icones/star0.png";
            document.getElementById("s4_" + categoria).src = "icones/star0.png";
            document.getElementById("s5_" + categoria).src = "icones/star0.png";
            avaliacao = 1;
        } else {
            document.getElementById("s1_" + categoria).src = "icones/star0.png";
            document.getElementById("s2_" + categoria).src = "icones/star0.png";
            document.getElementById("s3_" + categoria).src = "icones/star0.png";
            document.getElementById("s4_" + categoria).src = "icones/star0.png";
            document.getElementById("s5_" + categoria).src = "icones/star0.png";
            avaliacao = 0;
        }}
        
        avaliacao_array[index].estrelas = avaliacao;
        
    }
